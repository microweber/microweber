# Export

Data export engine. Export content, products, orders, and other site data in multiple formats with batch processing support.

## Key Features

- Multiple export formats: CSV, JSON, XML, XLSX
- Zip batch export for large datasets
- Configurable table selection for export
- Progress logging during export
- Trait-based architecture for extending exportable models

## Export Formats

| Format | Class |
|---|---|
| CSV | `Formats\CsvExport` |
| JSON | `Formats\JsonExport` |
| XML | `Formats\XmlExport` |
| XLSX | `Formats\XlsxExport` |
| ZIP (batch) | `Formats\ZipBatchExport` |
| Default | `Formats\DefaultExport` |

## Key Classes

| Class | Purpose |
|---|---|
| `Services\Export` | Main export orchestrator |
| `Services\ExportTables` | Table selection and data gathering |
| `Loggers\*` | Progress logging during exports |
| `Traits\*` | Exportable model traits |

## Usage

```php
$exporter = new \Modules\Export\Services\Export();
// Configure tables and format, then run export
```

Formats implement a common interface defined in `Formats\Interfaces\`.
