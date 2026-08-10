# microweber-packages/ai-tools

Standalone Laravel package providing an AI **tool-calling framework**:

- `ToolInterface` / `ToolRegistryInterface` contracts
- `BaseTool` (NeuronAI-compatible) with error markers, HTML helpers, audit hooks
- `ToolRegistry` + `AiTools` facade for registration/lookup/`make()`
- Built-in external tools: Amazon scraper, Google Trends, Supadata

Domain-specific CMS tools (content, shop, media, …) live in their modules and
register into the shared registry via `RegistersAiTools`.

## Install (standalone Laravel app)

```bash
composer require microweber-packages/ai-tools
```

Register the provider (or load via `microweber-packages/core`):

```php
// bootstrap/providers.php or config/app.php
MicroweberPackages\AiTools\Providers\AiToolsServiceProvider::class,
```

Publish config:

```bash
php artisan vendor:publish --tag=ai-tools-config
```

## Usage

```php
use MicroweberPackages\AiTools\Facades\AiTools;

AiTools::register(MyCustomTool::class);

$tool = AiTools::make('my_custom_tool');
echo $tool(['input' => 'hello']);
```

## Tests

```bash
# from monorepo root
./vendor/bin/phpunit --testsuite=MicroweberAiTools
composer analyse -- packages/microweber-ai-tools/src
```

## License

MIT
