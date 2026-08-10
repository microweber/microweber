<?php

declare(strict_types=1);

namespace Modules\Form\Tools;

use Illuminate\Support\Str;
use Modules\ContactForm\Models\Form as ContactForm;
use Modules\Form\Models\FormData;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

class FormSubmissionSearchTool extends AbstractFormsTool
{
    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'form_submission_search',
            'Search Microweber form submissions by form, status, sender, or date range with masked personal data.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'form_id',
                type: PropertyType::INTEGER,
                description: 'Optional form metadata ID filter.',
                required: false,
            ),
            new ToolProperty(
                name: 'search_term',
                type: PropertyType::STRING,
                description: 'Optional search term for sender fields, message text, URL, or submission ID.',
                required: false,
            ),
            new ToolProperty(
                name: 'read_status',
                type: PropertyType::STRING,
                description: 'Optional read-state filter: "all", "read", or "unread". Default is "all".',
                required: false,
            ),
            new ToolProperty(
                name: 'date_from',
                type: PropertyType::STRING,
                description: 'Optional start date filter in YYYY-MM-DD format.',
                required: false,
            ),
            new ToolProperty(
                name: 'date_to',
                type: PropertyType::STRING,
                description: 'Optional end date filter in YYYY-MM-DD format.',
                required: false,
            ),
            new ToolProperty(
                name: 'limit',
                type: PropertyType::INTEGER,
                description: 'Maximum number of submissions to return (1-50). Default is 20.',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $formId = isset($args['form_id']) ? (int) $args['form_id'] : null;
        $searchTerm = trim((string) ($args['search_term'] ?? ''));
        $readStatus = $this->normalizeReadStatus($args['read_status'] ?? 'all');
        $dateFrom = trim((string) ($args['date_from'] ?? ''));
        $dateTo = trim((string) ($args['date_to'] ?? ''));
        $limit = $this->safeLimit($args['limit'] ?? 20);

        if (! $this->authorize()) {
            return $this->handleError('You do not have permission to view form submissions.');
        }

        try {
            $query = FormData::query()->with(['formDataValues', 'formList']);

            if ($formId !== null && $formId > 0) {
                $form = ContactForm::query()->find($formId);

                if ($form === null) {
                    return $this->formatAsHtmlTable(
                        [],
                        [
                            'submission' => 'Submission',
                            'form' => 'Form',
                        ],
                        'The requested form metadata record was not found.',
                        'forms-submission-search-empty'
                    );
                }

                $query->where(function ($builder) use ($form): void {
                    if ($form->list_id) {
                        $builder->orWhere('list_id', (int) $form->list_id);
                    }

                    if ((string) $form->module_id !== '') {
                        $builder->orWhere('rel_id', (string) $form->module_id);
                    }
                });
            }

            if ($searchTerm !== '') {
                $query->where(function ($builder) use ($searchTerm): void {
                    $builder->where('id', 'like', '%' . $searchTerm . '%')
                        ->orWhere('rel_id', 'like', '%' . $searchTerm . '%')
                        ->orWhere('module_name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('url', 'like', '%' . $searchTerm . '%')
                        ->orWhere('form_values', 'like', '%' . $searchTerm . '%')
                        ->orWhereHas('formDataValues', function ($fieldQuery) use ($searchTerm): void {
                            $fieldQuery->where('field_key', 'like', '%' . $searchTerm . '%')
                                ->orWhere('field_name', 'like', '%' . $searchTerm . '%')
                                ->orWhere('field_value', 'like', '%' . $searchTerm . '%')
                                ->orWhere('field_value_json', 'like', '%' . $searchTerm . '%');
                        });
                });
            }

            if ($readStatus === 'read') {
                $query->where('is_read', 1);
            } elseif ($readStatus === 'unread') {
                $query->where(function ($builder): void {
                    $builder->where('is_read', 0)
                        ->orWhereNull('is_read');
                });
            }

            if ($dateFrom !== '') {
                $query->whereDate('created_at', '>=', $dateFrom);
            }

            if ($dateTo !== '') {
                $query->whereDate('created_at', '<=', $dateTo);
            }

            $submissions = $query->orderByDesc('created_at')->limit($limit)->get();

            if ($submissions->isEmpty()) {
                return $this->formatAsHtmlTable(
                    [],
                    [
                        'submission' => 'Submission',
                        'form' => 'Form',
                        'status' => 'Status',
                    ],
                    'No form submissions matched the current filters.',
                    'forms-submission-search-empty'
                );
            }

            $formsByListId = ContactForm::query()
                ->whereIn('list_id', $submissions->pluck('list_id')->filter()->map(fn ($id): int => (int) $id)->unique()->all())
                ->get()
                ->keyBy(fn (ContactForm $form): int => (int) $form->list_id);

            $formsByModuleId = ContactForm::query()
                ->whereIn('module_id', $submissions->pluck('rel_id')->filter()->map(fn ($id): string => (string) $id)->unique()->all())
                ->get()
                ->keyBy(fn (ContactForm $form): string => (string) $form->module_id);

            $rows = $submissions->map(function (FormData $submission) use ($formsByListId, $formsByModuleId): array {
                $fields = $this->extractSubmissionFields($submission);
                $nameField = $this->firstFieldValue($fields, fn (array $field): bool => $this->isNameField($field['field_key'], $field['field_type']));
                $emailField = $this->firstFieldValue($fields, fn (array $field): bool => $this->isEmailField($field['field_key'], $field['field_type']));
                $messageField = $this->firstFieldValue($fields, fn (array $field): bool => $this->isMessageField($field['field_key'], $field['field_type']));

                return [
                    'submission' => '#' . $submission->id,
                    'form' => $this->formDescriptorForSubmission($submission, $formsByListId, $formsByModuleId),
                    'status' => (int) ($submission->is_read ?? 0) === 1 ? 'Read' : 'Unread',
                    'sender' => ($nameField ? $this->maskFieldValue($nameField['value'], $nameField['field_key'], $nameField['field_type']) : 'Unknown')
                        . ($emailField ? ' / ' . $this->maskFieldValue($emailField['value'], $emailField['field_key'], $emailField['field_type']) : ''),
                    'summary' => $messageField
                        ? $this->maskFieldValue($messageField['value'], $messageField['field_key'], $messageField['field_type'])
                        : 'No message field',
                    'source' => Str::limit((string) ($submission->url ?: $submission->module_name ?: $submission->rel_id), 60),
                    'submitted_at' => (string) $submission->created_at,
                ];
            })->all();

            return $this->formatAsHtmlTable(
                $rows,
                [
                    'submission' => 'Submission',
                    'form' => 'Form',
                    'status' => 'Status',
                    'sender' => 'Sender',
                    'summary' => 'Summary',
                    'source' => 'Source',
                    'submitted_at' => 'Submitted at',
                ],
                '',
                'forms-submission-search-results'
            );
        } catch (\Throwable $exception) {
            return $this->handleError('Error searching form submissions: ' . $exception->getMessage());
        }
    }
}
