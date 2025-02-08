# Ai

## Run module migrations

```sh
php artisan module:migrate Ai
```

## Publish module assets

```sh
php artisan module:publish Ai
```

### module config values

```php
config('modules.ai.name')
```

### Module views

Extend master layout

```php
@extends('modules.ai::layouts.master')
```

Use Module view

```php
view('modules.ai::example')
```

## Chat Completion with Functions Support

The AI module provides a flexible chat completion interface with support for OpenAI functions. Here's how to use it:

```php
$messages = [
    ['role' => 'system', 'content' => 'You are a helpful assistant that can schedule tasks.'],
    ['role' => 'user', 'content' => 'I need help planning my week.']
];

$response = $aiService->sendToChat($messages, [
    'functions' => [
        [
            'name' => 'llm_functions_scheduling',
            'description' => 'Helper for scheduling tasks in personal calendar',
            'parameters' => [
                'type' => 'object',
                'properties' => [
                    'tasks' => [
                        'type' => 'array',
                        'description' => 'List of tasks to schedule',
                        'items' => [
                            'date' => 'string',
                            'description' => 'string'
                        ]
                    }
                ],
                'required' => ['tasks']
            ]
        ]
    ],
    'function_call' => 'auto', // Optional: force specific function
    'model' => 'gpt-3.5-turbo-0613', // Optional
    'temperature' => 0, // Optional
    'max_tokens' => 1000 // Optional
]);

// Handle function call response
if (is_array($response) && isset($response['function_call'])) {
    $functionName = $response['function_call']->name;
    $arguments = json_decode($response['function_call']->arguments, true);
    
    // Execute the function and get result
    $result = call_user_func($functionName, $arguments);
    
    // Continue the conversation with the function result
    $messages[] = [
        'role' => 'assistant',
        'content' => null,
        'function_call' => [
            'name' => $functionName,
            'arguments' => json_encode($arguments)
        ]
    ];
    $messages[] = [
        'role' => 'function',
        'name' => $functionName,
        'content' => json_encode($result)
    ];
    
    // Get final response
    $finalResponse = $aiService->sendToChat($messages);
}
```

### Message Format

Messages should be provided as an array of message objects, each with:
- `role`: One of 'system', 'user', 'assistant', or 'function'
- `content`: The message content
- `name`: Required for function messages, the name of the function that was called
- `function_call`: Optional for assistant messages, contains function call details

### Available Options

- `functions`: Array of function definitions that the AI can call
- `function_call`: Optional parameter to force a specific function call
- `model`: OpenAI model to use (default: gpt-3.5-turbo)
- `temperature`: Sampling temperature (default: 0.7)
- `max_tokens`: Maximum tokens in response (default: 1000)

### Function Definition Format

Each function in the `functions` array should follow this structure:
```php
[
    'name' => 'function_name',
    'description' => 'Description of what the function does',
    'parameters' => [
        'type' => 'object',
        'properties' => [
            // Function parameters defined in JSON Schema format
        ],
        'required' => ['required_param1', 'required_param2']
    ]
]
```

### Response Handling

The response will be either:
- A string containing the assistant's message content
- An array containing function call details: `['function_call' => object, 'content' => null]`

When handling function calls, you should:
1. Execute the requested function
2. Add the function call and result to the message history
3. Continue the conversation by sending the updated messages back to the AI
