# Microweber AI Tools

A standalone Laravel **AI tool-calling framework**: a tool registry, NeuronAI-compatible base tools, and a few built-in external-service tools. Domain-specific CMS tools (content, shop, media, …) live in their own modules and register into the shared registry.

## Features

- `ToolInterface` / `ToolRegistryInterface` contracts.
- `BaseTool` — a NeuronAI v4-compatible base (`extends Neuron\Tool`) with permission checks, HTML/table/card-grid formatting helpers, error/success markers, money formatting, and audit hooks.
- `ToolRegistry` + `AiTools` facade — register by class / instance / factory, resolve and `make()` with DI, filter by domain / permission / authorization, alias support.
- Built-in external service tools: **Amazon scraper**, **Google Trends** (+ Supadata / YouTube config slots).
- `RegistersAiTools` trait so other packages/modules can register their tools into the shared registry.

## Installation

```bash
composer require microweber-packages/microweber-ai-tools
```

Register the provider (or let `microweber-packages/microweber-core` load it):

```php
// bootstrap/providers.php or config/app.php
MicroweberPackages\AiTools\Providers\AiToolsServiceProvider::class,
```

Publish the config:

```bash
php artisan vendor:publish --tag=ai-tools-config
```

The provider binds one `ToolRegistry` singleton, aliased as `ToolRegistryInterface::class` and `'aitools.registry'`, and auto-registers any tools listed in `config('ai-tools.tools')`.

## Usage

```php
use MicroweberPackages\AiTools\Facades\AiTools;

// Register tools
AiTools::register(MyCustomTool::class);
AiTools::registerMany([ToolA::class, ToolB::class]);
AiTools::registerFactory('report', fn () => new ReportTool($deps));

// Resolve / invoke
$tool = AiTools::make('my_custom_tool', ['content' => $repo]); // make() injects deps
echo $tool(['input' => 'hello']);

// Introspect the registry
AiTools::has('my_custom_tool');     // bool
AiTools::names();                   // list<string>
AiTools::getByDomain('shop');       // array<string, ToolInterface>
AiTools::getAuthorized();           // only tools the current user may run
AiTools::count();
```

Registering tools from another package via the trait:

```php
use MicroweberPackages\AiTools\Support\RegistersAiTools;

class MyModuleServiceProvider extends ServiceProvider
{
    use RegistersAiTools;

    public function boot(): void
    {
        $this->registerAiTools([MyFirstTool::class, MySecondTool::class]);
    }
}
```

## Configuration

`config/ai-tools.php` (publish tag `ai-tools-config`):

| Key | Env | Default | Description |
|-----|-----|---------|-------------|
| `enabled` | `AI_TOOLS_ENABLED` | `true` | Master toggle; off = no configured tools register |
| `tools` | — | `[]` | Tool classes to auto-register on boot |
| `defaults.max_tries` | — | `500` | Default tool `max_tries` |
| `defaults.timeout` | — | `30` | Default request timeout (s) |
| `services.amazon.enabled` | `AI_TOOLS_AMAZON_ENABLED` | `true` | Amazon scraper |
| `services.google_trends.enabled` | `AI_TOOLS_GOOGLE_TRENDS_ENABLED` | `true` | Google Trends |
| `services.supadata.*` | `AI_TOOLS_SUPADATA_ENABLED`, `SUPADATA_API_KEY` | off | Supadata |
| `services.youtube.*` | `AI_TOOLS_YOUTUBE_ENABLED`, `YOUTUBE_API_KEY` | on | YouTube |
| `permissions.check_permissions` | — | `true` | Enforce per-tool permissions |
| `permissions.default_permissions` | — | `['view content']` | Default required permissions |

## Key classes

- `MicroweberPackages\AiTools\Facades\AiTools` — registry facade.
- `MicroweberPackages\AiTools\Registry\ToolRegistry` — the registry (bound as `ToolRegistryInterface` / `aitools.registry`).
- `MicroweberPackages\AiTools\Base\BaseTool` / `AbstractContentTool` — base classes for new tools.
- `MicroweberPackages\AiTools\Contracts\{ToolInterface, ToolRegistryInterface, ContentRepositoryInterface}` — contracts.
- `MicroweberPackages\AiTools\Support\RegistersAiTools` — registration trait.
- `MicroweberPackages\AiTools\Providers\AiToolsServiceProvider` — bindings, config, auto-registration.

## Tests

```bash
# from monorepo root
./vendor/bin/phpunit --testsuite=MicroweberAiTools
composer analyse -- packages/microweber-ai-tools/src
```

## License

MIT

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
