<?php

declare(strict_types=1);

namespace Modules\Ai\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Ai\Services\Mcp\McpRequestContext;

class McpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->attributes->has(McpRequestContext::class);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'jsonrpc' => ['required', 'string', 'in:2.0'],
            'id' => ['nullable'],
            'method' => ['required', 'string', 'max:255'],
            'params' => ['sometimes', 'array'],
            'params.name' => ['required_if:method,tools/call', 'string', 'max:255'],
        ];
    }
}
