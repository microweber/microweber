<?php

declare(strict_types=1);

namespace Modules\Form\Tools;

use Illuminate\Support\Str;
use Modules\ContactForm\Models\Form as ContactForm;
use Modules\Form\Models\FormData;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

class FormSubmissionDetailTool extends AbstractFormsTool
{
    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'form_submission_detail',
            'Retrieve a detailed Microweber form submission view with masked personal data and normalized field values.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'submission_id',
                type: PropertyType::INTEGER,
                description: 'The form submission ID to inspect.',
                required: true,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $submissionId = isset($args['submission_id']) ? (int) $args['submission_id'] : 0;

        if (! $this->authorize()) {
            return $this->handleError('You do not have permission to view form submission details.');
        }

        if ($submissionId <= 0) {
            return $this->handleError('A valid submission_id is required.');
        }

        try {
            $submission = FormData::query()
                ->with(['formDataValues', 'formList'])
                ->find($submissionId);

            if ($submission === null) {
                return $this->formatAsHtmlTable(
                    [],
                    [
                        'submission' => 'Submission',
                    ],
                    'The requested form submission was not found.',
                    'forms-submission-detail-empty'
                );
            }

            $formsByListId = ContactForm::query()
                ->whereIn('list_id', array_filter([(int) $submission->list_id]))
                ->get()
                ->keyBy(fn (ContactForm $form): int => (int) $form->list_id);

            $formsByModuleId = ContactForm::query()
                ->whereIn('module_id', array_filter([(string) $submission->rel_id]))
                ->get()
                ->keyBy(fn (ContactForm $form): string => (string) $form->module_id);

            $fields = $this->extractSubmissionFields($submission);

            $summaryTable = $this->formatAsHtmlTable(
                [[
                    'submission' => '#' . $submission->id,
                    'form' => $this->formDescriptorForSubmission($submission, $formsByListId, $formsByModuleId),
                    'status' => (int) ($submission->is_read ?? 0) === 1 ? 'Read' : 'Unread',
                    'submitted_at' => (string) $submission->created_at,
                    'source_url' => Str::limit((string) ($submission->url ?: 'Unknown'), 80),
                    'source_ip' => $this->maskIp($submission->user_ip),
                ]],
                [
                    'submission' => 'Submission',
                    'form' => 'Form',
                    'status' => 'Status',
                    'submitted_at' => 'Submitted at',
                    'source_url' => 'Source URL',
                    'source_ip' => 'Source IP',
                ],
                '',
                'forms-submission-detail-summary'
            );

            $fieldRows = $fields->map(function (array $field): array {
                return [
                    'field' => $field['field_name'],
                    'type' => $field['field_type'] ?: 'text',
                    'value' => $this->maskFieldValue($field['value'], $field['field_key'], $field['field_type'], true),
                ];
            })->all();

            $fieldsTable = $this->formatAsHtmlTable(
                $fieldRows,
                [
                    'field' => 'Field',
                    'type' => 'Type',
                    'value' => 'Value',
                ],
                'This submission does not contain any stored field values.',
                'forms-submission-detail-fields'
            );

            return '<h4>Submission summary</h4>' . $summaryTable . '<h4>Submission fields</h4>' . $fieldsTable;
        } catch (\Throwable $exception) {
            return $this->handleError('Error loading form submission details: ' . $exception->getMessage());
        }
    }
}
