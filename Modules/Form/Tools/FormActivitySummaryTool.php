<?php

declare(strict_types=1);

namespace Modules\Form\Tools;

use Modules\ContactForm\Models\Form as ContactForm;
use Modules\Form\Models\FormData;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

class FormActivitySummaryTool extends AbstractFormsTool
{
    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'form_activity_summary',
            'Summarize Microweber form submission volume, unread backlog, and most active forms over a recent period.'
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
                name: 'period',
                type: PropertyType::STRING,
                description: 'Summary window: recent_7d, recent_30d, or all. Default is recent_30d.',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $formId = isset($args['form_id']) ? (int) $args['form_id'] : null;
        $period = $this->normalizePeriod($args['period'] ?? 'recent_30d');

        if (! $this->authorize()) {
            return $this->handleError('You do not have permission to view form activity.');
        }

        try {
            $query = FormData::query()->with(['formDataValues', 'formList']);

            if ($formId !== null && $formId > 0) {
                $form = ContactForm::query()->find($formId);

                if ($form === null) {
                    return $this->formatAsHtmlTable(
                        [],
                        [
                            'metric' => 'Metric',
                            'value' => 'Value',
                        ],
                        'The requested form metadata record was not found.',
                        'forms-activity-summary-empty'
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

            $this->applyPeriod($query, $period);

            $submissions = $query->orderByDesc('created_at')->get();

            if ($submissions->isEmpty()) {
                return $this->formatAsHtmlTable(
                    [],
                    [
                        'metric' => 'Metric',
                        'value' => 'Value',
                    ],
                    'No form activity was found for the selected period.',
                    'forms-activity-summary-empty'
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

            $summaryRows = [[
                'period' => $period,
                'total_submissions' => (string) $submissions->count(),
                'unread_submissions' => (string) $submissions->filter(fn (FormData $submission): bool => (int) ($submission->is_read ?? 0) !== 1)->count(),
                'unique_forms' => (string) $submissions->map(fn (FormData $submission): string => $this->formDescriptorForSubmission($submission, $formsByListId, $formsByModuleId))->unique()->count(),
                'latest_submission' => (string) ($submissions->first()?->created_at ?? 'Unknown'),
            ]];

            $activityRows = $submissions
                ->groupBy(fn (FormData $submission): string => $this->formDescriptorForSubmission($submission, $formsByListId, $formsByModuleId))
                ->map(function ($group, string $formLabel): array {
                    /** @var \Illuminate\Support\Collection<int, FormData> $group */
                    return [
                        'form' => $formLabel,
                        'submissions' => (string) $group->count(),
                        'unread' => (string) $group->filter(fn (FormData $submission): bool => (int) ($submission->is_read ?? 0) !== 1)->count(),
                        'latest' => (string) ($group->sortByDesc('created_at')->first()?->created_at ?? 'Unknown'),
                    ];
                })
                ->sortByDesc(fn (array $row): int => (int) $row['submissions'])
                ->take(10)
                ->values()
                ->all();

            return '<h4>Forms activity summary</h4>'
                . $this->formatAsHtmlTable(
                    $summaryRows,
                    [
                        'period' => 'Period',
                        'total_submissions' => 'Total submissions',
                        'unread_submissions' => 'Unread submissions',
                        'unique_forms' => 'Unique forms',
                        'latest_submission' => 'Latest submission',
                    ],
                    '',
                    'forms-activity-summary-results'
                )
                . '<h4>Most active forms</h4>'
                . $this->formatAsHtmlTable(
                    $activityRows,
                    [
                        'form' => 'Form',
                        'submissions' => 'Submissions',
                        'unread' => 'Unread',
                        'latest' => 'Latest',
                    ],
                    '',
                    'forms-activity-summary-forms'
                );
        } catch (\Throwable $exception) {
            return $this->handleError('Error summarizing form activity: ' . $exception->getMessage());
        }
    }
}
