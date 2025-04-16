<?php

namespace Modules\Newsletter\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Newsletter\Filament\Exports\NewsletterSubscriberExporter;
use Modules\Newsletter\Models\NewsletterSubscriber;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Modules\Newsletter\Traits\Exportable;

class NewsletterSubscriberExportController extends \MicroweberPackages\Admin\Http\Controllers\AdminController
{
    use Exportable;

    public function export(Request $request): StreamedResponse
    {
        $selectedColumns = $request->input('columns', []);
        $selectedIds = $request->input('selected_ids', ''); // For bulk export

        // Prepare the query
        $query = NewsletterSubscriber::query();
        if (!empty($selectedIds)) {
            $ids = explode(',', $selectedIds);
            $query->whereIn('id', $ids);
        }

        $subscribers = $query->get();

        $headers = [];
        $exportData = [];

        // Prepare headers and data based on selected columns
        if (!empty($selectedColumns)) {
            foreach ($selectedColumns as $column) {
                $headers[] = ucfirst($column);
            }

            foreach ($subscribers as $subscriber) {
                $rowData = [];
                foreach ($selectedColumns as $column) {
                    $rowData[] = data_get($subscriber, $column);
                }
                $exportData[] = $rowData;
            }
        } else {
            // If no columns are selected, export all columns
            $headers = array_keys((new NewsletterSubscriber())->toArray());
            foreach ($subscribers as $subscriber) {
                $exportData[] = array_values($subscriber->toArray());
            }
        }

        $filename = 'newsletter_subscribers_' . date('YmdHis') . '.csv';
        return $this->_streamCsv($exportData, $headers, $filename);
    }
}
