<?php
namespace Modules\Export\Formats;

use Modules\Export\Formats\Interfaces\ExportInterface;
use Modules\Export\Traits\ExportFileNameGetSet;


class DefaultExport implements ExportInterface
{
    use ExportFileNameGetSet;
	public $type = 'json';
	public $data;
	public $overwrite = false;

	public function __construct($data = array())
	{
		$this->data = $data;
	}

	public function setType($type)
	{
		$this->type = $type;
	}

    public function setOverwrite($overwrite)
	{
		$this->overwrite = $overwrite;
	}

	public function start()
	{
		// start exporting
	}

	protected function _generateFilename($name = false)
	{
		if ($name) {
			$exportFilename = $name . '.' . $this->type;
		} else {
			$exportFilename = 'backup_' . date("Y-m-d-his") . '.' . $this->type;
		}

        if (isset($this->exportFileName) && !empty($this->exportFileName)) {
            $exportFilename = $this->exportFileName . '.' . $this->type;
        }

        $exportFilename = normalize_path($exportFilename, false);

		return array(
			'download' => route('admin.backup.download').'?file=' . $exportFilename,
			'filepath' => backup_location() . $exportFilename,
			'filename' => $exportFilename
		);
	}
}
