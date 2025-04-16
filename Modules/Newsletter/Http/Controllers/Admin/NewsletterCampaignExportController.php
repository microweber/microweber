<?php

namespace Modules\Newsletter\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Newsletter\Filament\Exports\NewsletterCampaignExporter;
use Modules\Newsletter\Models\NewsletterCampaign;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Modules\Newsletter\Traits\Exportable;

class NewsletterCampaignExportController extends \MicroweberPackages\Admin\Http\Controllers\AdminController
{
    use Exportable;

    public function export(Request $request): StreamedResponse
    {
        $selectedColumns = $request->input('columns', []);
        $selectedIds = $request->input('selected_ids', ''); // For bulk export

        // Prepare the query
        $query = NewsletterCampaign::query();
        if (!empty($selectedIds)) {
            $ids = explode(',', $selectedIds);
            $query->whereIn('id', $ids);
        }

        $campaigns = $query->get();

        $headers = [];
        $exportData = [];

        // Prepare headers and data based on selected columns
        if (!empty($selectedColumns)) {
            foreach ($selectedColumns as $column) {
                $headers[] = ucfirst($column);
            }

            foreach ($campaigns as $campaign) {
                $rowData = [];
                foreach ($selectedColumns as $column) {
                    $rowData[] = data_get($campaign, $column);
                }
                $exportData[] = $rowData;
            }
        } else {
            // If no columns are selected, export all columns
            $headers = array_keys((new NewsletterCampaign())->toArray());
            foreach ($campaigns as $campaign) {
                $exportData[] = array_values($campaign->toArray());
            }
        }

        $filename = 'newsletter_campaigns_' . date('YmdHis') . '.csv';
        return $this->_streamCsv($exportData, $headers, $filename);
    }
}
