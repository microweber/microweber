# Microweber AI Tools

A comprehensive AI-powered tool ecosystem for Microweber CMS, providing tool calling capabilities for content management, e-commerce operations, and external service integrations.

## Features

- **Content Management**: Create, edit, search, and list content/pages
- **E-commerce**: Product management, order search, customer lookup
- **Media**: Search and manage media files
- **External Services**: Amazon scraping, Google Trends, YouTube transcription, AI image generation
- **RAG Search**: Retrieval-Augmented Generation for semantic content search

## Installation

```bash
composer require microweber-packages/ai-tools
```

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag=ai-tools-config
```

Configure your tools in `config/ai-tools.php`:

```php
'tools' => [
    \MicroweberPackages\AiTools\Tools\Content\CreateContentTool::class,
    \MicroweberPackages\AiTools\Tools\Commerce\CreateProductTool::class,
    // ... more tools
],
```

## Usage

### Registering Tools

```php
use MicroweberPackages\AiTools\Facades\AiTools;

// Register a single tool
AiTools::register(\MicroweberPackages\AiTools\Tools\Content\CreateContentTool::class);

// Register multiple tools
AiTools::registerMany([
    \MicroweberPackages\AiTools\Tools\Content\CreateContentTool::class,
    \MicroweberPackages\AiTools\Tools\Commerce\CreateProductTool::class,
]);
```

### Using Tools

```php
use MicroweberPackages\AiTools\Facades\AiTools;

// Get a tool
$tool = AiTools::get('create_content');

// Execute the tool
$result = $tool->execute([
    'title' => 'My New Page',
    'content_type' => 'page',
]);
```

### Creating Custom Tools

```php
use MicroweberPackages\AiTools\Base\BaseTool;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

class MyCustomTool extends BaseTool
{
    protected string $domain = 'custom';
    protected array $requiredPermissions = ['manage custom'];

    public function __construct()
    {
        parent::__construct(
            name: 'my_custom_tool',
            description: 'Does something custom'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'input',
                type: PropertyType::STRING,
                description: 'Input parameter',
                required: true,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $params = is_array($args[0] ?? null) ? $args[0] : $args;
        $input = $params['input'] ?? '';

        // Tool logic here

        return $this->handleSuccess("Processed: {$input}");
    }
}
```

## Tool Categories

### Content Tools
- `create_content` - Create new content/pages
- `edit_content` - Edit existing content
- `get_content` - Retrieve content by ID
- `list_content` - List content with filters
- `search_content` - Search across content

### Commerce Tools
- `create_product` - Create new products
- `edit_product` - Edit existing products
- `search_product` - Search products
- `search_orders` - Search orders
- `lookup_customer` - Lookup customer information

### External Tools
- `amazon_scraper` - Scrape Amazon product data
- `google_trends` - Fetch Google Trends data
- `youtube_transcription` - Transcribe YouTube videos
- `generate_image` - Generate images via AI
- `supadata_search` - Supadata API integration

### Search Tools
- `rag_search` - Semantic search with RAG

## Architecture

The package follows a clean architecture pattern:

- **Contracts**: Interfaces for tool contracts
- **Base Classes**: Abstract implementations
- **Registry**: Centralized tool management
- **Facades**: Convenient access via Laravel Facades

## Testing

```bash
composer test
```

## License

MIT License. See LICENSE file for details.
