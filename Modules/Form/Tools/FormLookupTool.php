<?php

declare(strict_types=1);

namespace Modules\Form\Tools;

use Illuminate\Support\Collection;
use Modules\ContactForm\Models\Form as ContactForm;
use Modules\Form\Models\FormData;
use Modules\Form\Models\FormList;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

class FormLookupTool extends AbstractFormsTool
{
    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'form_lookup',
            'Search Microweber form definitions and summarize recent submission activity.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'form_id',
                type: PropertyType::INTEGER,
                description: 'Optional form metadata ID for a single-form lookup.',
                required: false,
            ),
            new ToolProperty(
                name: 'search_term',
                type: PropertyType::STRING,
                description: 'Optional search term for form name, slug, description, or module ID.',
                required: false,
            ),
            new ToolProperty(
                name: 'list_id',
                type: PropertyType::INTEGER,
                description: 'Optional form list ID filter.',
                required: false,
            ),
            new ToolProperty(
                name: 'is_active',
                type: PropertyType::STRING,
                description: 'Optional active-state filter: "yes" or "no".',
                required: false,
            ),
            new ToolProperty(
                name: 'limit',
                type: PropertyType::INTEGER,
                description: 'Maximum number of forms to return (1-50). Default is 20.',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $formId = isset($args['form_id']) ? (int) $args['form_id'] : null;
        $searchTerm = trim((string) ($args['search_term'] ?? ''));
        $listId = isset($args['list_id']) ? (int) $args['list_id'] : null;
        $activeFilter = strtolower(trim((string) ($args['is_active'] ?? '')));
        $limit = $this->safeLimit($args['limit'] ?? 20);

        if (! $this->authorize()) {
            return $this->handleError('You do not have permission to view forms.');
        }

        try {
            $query = ContactForm::query();

            if ($formId !== null && $formId > 0) {
                $query->where('id', $formId);
            }

            if ($searchTerm !== '') {
                $query->where(function ($builder) use ($searchTerm): void {
                    $builder->where('name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('slug', 'like', '%' . $searchTerm . '%')
                        ->orWhere('description', 'like', '%' . $searchTerm . '%')
                        ->orWhere('module_id', 'like', '%' . $searchTerm . '%');
                });
            }

            if ($listId !== null && $listId > 0) {
                $query->where('list_id', $listId);
            }

            if (in_array($activeFilter, ['yes', 'no'], true)) {
                $query->where('is_active', $activeFilter === 'yes' ? 1 : 0);
            }

            $forms = $query->orderByDesc('created_at')->limit($limit)->get();

            if ($forms->isEmpty()) {
                return $this->formatAsHtmlTable(
                    [],
                    [
                        'form' => 'Form',
                        'status' => 'Status',
                        'submissions' => 'Submissions',
                    ],
                    'No forms matched the current filters.',
                    'forms-lookup-empty'
                );
            }

            $listIds = $forms->pluck('list_id')->filter()->map(fn ($id): int => (int) $id)->unique()->all();
            $moduleIds = $forms->pluck('module_id')->filter(fn ($id): bool => (string) $id !== '')->map(fn ($id): string => (string) $id)->unique()->all();

            $lists = FormList::query()->whereIn('id', $listIds)->get()->keyBy('id');

            $countsByList = FormData::query()
                ->selectRaw('list_id, COUNT(*) as total_count, SUM(CASE WHEN is_read = 1 THEN 1 ELSE 0 END) as read_count, MAX(created_at) as last_submission_at')
                ->when($listIds !== [], fn ($builder) => $builder->whereIn('list_id', $listIds))
                ->groupBy('list_id')
                ->get()
                ->keyBy(fn (FormData $submission): int => (int) $submission->list_id);

            $countsByModule = FormData::query()
                ->selectRaw('rel_id, COUNT(*) as total_count, SUM(CASE WHEN is_read = 1 THEN 1 ELSE 0 END) as read_count, MAX(created_at) as last_submission_at')
                ->when($moduleIds !== [], fn ($builder) => $builder->whereIn('rel_id', $moduleIds))
                ->groupBy('rel_id')
                ->get()
                ->keyBy(fn (FormData $submission): string => (string) $submission->rel_id);

            $rows = $forms->map(function (ContactForm $form) use ($lists, $countsByList, $countsByModule): array {
                $listStats = $form->list_id ? $countsByList->get((int) $form->list_id) : null;
                $moduleStats = (string) $form->module_id !== '' ? $countsByModule->get((string) $form->module_id) : null;
                $stats = $listStats ?? $moduleStats;
                $submissionCount = (int) ($stats->total_count ?? 0);
                $readCount = (int) ($stats->read_count ?? 0);

                return [
                    'form' => '#' . $form->id . ' ' . ($form->name ?: 'Untitled form'),
                    'status' => (int) ($form->is_active ?? 0) === 1 ? 'Active' : 'Inactive',
                    'binding' => $form->list_id
                        ? 'list #' . $form->list_id . ' ' . (($lists->get((int) $form->list_id)?->title) ?: '')
                        : ('module ' . ((string) $form->module_id !== '' ? (string) $form->module_id : 'unbound')),
                    'submissions' => (string) $submissionCount,
                    'unread' => (string) max(0, $submissionCount - $readCount),
                    'last_submission' => (string) ($stats->last_submission_at ?? 'No submissions yet'),
                    'notifications' => (string) $this->countRecipients($form->emails_notifications) . ' recipient(s)',
                ];
            })->all();

            return $this->formatAsHtmlTable(
                $rows,
                [
                    'form' => 'Form',
                    'status' => 'Status',
                    'binding' => 'Binding',
                    'submissions' => 'Submissions',
                    'unread' => 'Unread',
                    'last_submission' => 'Last submission',
                    'notifications' => 'Notifications',
                ],
                '',
                'forms-lookup-results'
            );
        } catch (\Throwable $exception) {
            return $this->handleError('Error looking up forms: ' . $exception->getMessage());
        }
    }
}
