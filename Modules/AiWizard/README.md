# AI Wizard Module

The AI Wizard module provides AI-powered page generation capabilities for Microweber using OpenAI's GPT models. It allows you to quickly create website pages by describing what you want, and the AI will generate appropriate content.

## Features

- AI-powered page content generation
- Multiple section support (header, content, features, testimonials, contact)
- Configurable AI models and content tones
- Integration with existing Microweber pages
- Easy content regeneration
- Driver-based architecture for multiple AI providers

## Installation

1. Install the module:
```bash
php artisan module:install AiWizard
```

2. Publish the module configuration:
```bash
php artisan module:publish AiWizard
```

3. Add your OpenAI API key to your `.env` file:
```env
OPENAI_API_KEY=your-api-key-here
```

## Configuration

The module's configuration file is located at `config/aiwizard.php`. Here you can configure:

- Default AI driver (OpenAI by default)
- AI model settings (model, max tokens, temperature)
- Default page sections
- System prompts for content generation

### Available Configuration Options

```php
return [
    'default' => env('AI_DRIVER', 'openai'),
    
    'drivers' => [
        'openai' => [
            'model' => env('OPENAI_MODEL', 'gpt-3.5-turbo'),
            'max_tokens' => env('OPENAI_MAX_TOKENS', 1000),
            'temperature' => env('OPENAI_TEMPERATURE', 0.7),
        ],
    ],
    
    'page_generation' => [
        'system_prompt' => 'You are a professional web content creator...',
        'default_sections' => [
            'header',
            'content',
            'features',
            'testimonials',
            'contact',
        ],
    ],
];
```

## Usage

### Creating a New Page with AI

1. Navigate to the AI Page Wizard in your admin panel
2. Click "Create Page with AI"
3. Fill in the page details:
   - Title: The page title
   - Description: Describe what kind of page you want
   - Sections: Select which sections to include
   - AI Settings: Choose the model and content tone
4. Click "Create" to generate the page

### Regenerating Content

1. Open an existing AI-generated page
2. Click the "Regenerate with AI" button
3. The content will be regenerated while maintaining the same structure

### Customizing Content

- You can edit the generated content manually
- The original AI settings are preserved for future regeneration
- All standard Microweber page features are available

## Extending

### Adding New AI Providers

1. Create a new driver class implementing `AiServiceInterface`
2. Add the driver configuration to `config/aiwizard.php`
3. Register the driver in `AiService.php`

Example driver implementation:

```php
class CustomDriver extends BaseDriver
{
    public function generateContent(string $prompt, array $options = []): string
    {
        // Implement your AI provider's content generation logic
    }

    public function getDriverName(): string
    {
        return 'custom';
    }
}
```

## Support

For support with this module, please contact the Microweber team at info@microweber.com.

## License

This module is open-sourced software licensed under the MIT license.
