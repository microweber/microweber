<?php

namespace Goodby\CSV\Export\Protocol;

use Traversable;
use Goodby\CSV\Export\Protocol\Exception\IOException;

/**
 * Interface of the Exporter
 */
interface ExporterInterface
{
    /**
     * Export data as CSV file
     *
     * @param string $filename
     * @param array|Traversable $rows
     * @throws IOException
     * @return void
     */
    public function export($filename, $rows);
}
