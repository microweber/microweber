<?php

namespace MicroweberPackages\Monitoring\Console\Commands;

use Illuminate\Console\Command;
use MicroweberPackages\Monitoring\Models\ErrorTracking;
use Illuminate\Support\Facades\File;

class ExportErrorReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitoring:export-errors
                            {--format=json : Export format (json, csv)}
                            {--output= : Output file path}
                            {--unresolved-only : Only export unresolved errors}
                            {--days=7 : Export errors from last N days}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export error tracking data to a file';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $format = $this->option('format');
        $output = $this->option('output') ?? storage_path("error-report-{$format}-" . now()->format('Y-m-d') . ".{$format}");
        $days = $this->option('days');

        // Build query
        $query = ErrorTracking::where('created_at', '>=', now()->subDays($days));

        if ($this->option('unresolved-only')) {
            $query->where('is_resolved', false);
        }

        $errors = $query->orderBy('created_at', 'desc')->get();

        $this->info("Exporting {$errors->count()} error records...");

        switch ($format) {
            case 'json':
                return $this->exportJson($errors, $output);
            case 'csv':
                return $this->exportCsv($errors, $output);
            default:
                $this->error("Unsupported format: {$format}");
                return self::FAILURE;
        }
    }

    /**
     * Export errors to JSON format.
     */
    protected function exportJson($errors, string $output): int
    {
        $data = [
            'generated_at' => now()->toIso8601String(),
            'count' => $errors->count(),
            'errors' => $errors->map(function ($error) {
                return [
                    'id' => $error->id,
                    'level' => $error->level,
                    'message' => $error->message,
                    'exception_class' => $error->exception_class,
                    'file' => $error->file,
                    'line' => $error->line,
                    'url' => $error->url,
                    'method' => $error->method,
                    'user_id' => $error->user_id,
                    'user_ip' => $error->user_ip,
                    'is_resolved' => $error->is_resolved,
                    'occurrence_count' => $error->occurrence_count,
                    'created_at' => $error->created_at->toIso8601String(),
                    'last_occurred_at' => $error->last_occurred_at?->toIso8601String(),
                ];
            }),
        ];

        File::put($output, json_encode($data, JSON_PRETTY_PRINT));

        $this->info("Successfully exported to: {$output}");

        return self::SUCCESS;
    }

    /**
     * Export errors to CSV format.
     */
    protected function exportCsv($errors, string $output): int
    {
        $handle = fopen($output, 'w');

        // Write headers
        fputcsv($handle, [
            'ID',
            'Level',
            'Message',
            'Exception Class',
            'File',
            'Line',
            'URL',
            'Method',
            'User ID',
            'User IP',
            'Resolved',
            'Occurrence Count',
            'Created At',
            'Last Occurred At',
        ]);

        // Write data
        foreach ($errors as $error) {
            fputcsv($handle, [
                $error->id,
                $error->level,
                $error->message,
                $error->exception_class,
                $error->file,
                $error->line,
                $error->url,
                $error->method,
                $error->user_id,
                $error->user_ip,
                $error->is_resolved ? 'Yes' : 'No',
                $error->occurrence_count,
                $error->created_at->format('Y-m-d H:i:s'),
                $error->last_occurred_at?->format('Y-m-d H:i:s'),
            ]);
        }

        fclose($handle);

        $this->info("Successfully exported to: {$output}");

        return self::SUCCESS;
    }
}
