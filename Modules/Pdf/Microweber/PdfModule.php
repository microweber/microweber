<?php

namespace Modules\Pdf\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Pdf\Filament\PdfModuleSettings;

class PdfModule extends BaseModule
{
    public static string $name = 'PDF Module';
    public static string $icon = 'heroicon-o-document';
    public static string $categories = 'documents, pdf';
    public static int $position = 3;
    public static string $settingsComponent = PdfModuleSettings::class;

    public function render()
    {
        $viewData = $this->getViewData();

        $id = "mwpdf-" . $this->params['id'];

        $pdf = false;
        if (isset($this->params['data-pdf-url'])) {
            $pdf = $this->params['data-pdf-url'];
        }
        $pdfSource = get_module_option('data-pdf-source', $this->params['id']);
        $pdfUpload = get_module_option('data-pdf-upload', $this->params['id']);
        $pdfUrl = get_module_option('data-pdf-url', $this->params['id']);
        if ($pdfSource == 'url') {
            $pdf = $pdfUrl;
        } else {
            $pdf = $pdfUpload;
        }

        $viewData['pdf'] = $pdf;
        $viewData['id'] = $id;

        return view('modules.pdf::templates.default', $viewData);
    }
}
