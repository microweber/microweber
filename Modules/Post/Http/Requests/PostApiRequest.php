<?php

declare(strict_types=1);

namespace Modules\Post\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostApiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $postId = $this->route('post');

        return [
            'title' => ['required', 'string', 'max:500'],
            'url' => [
                'nullable',
                'string',
                'max:500',
                Rule::unique('content', 'url')->ignore($postId),
            ],
            'content' => ['nullable', 'string'],
            'content_body' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'content_meta_title' => ['nullable', 'string', 'max:500'],
            'content_meta_keywords' => ['nullable', 'string', 'max:500'],
            'content_meta_description' => ['nullable', 'string', 'max:1000'],
            'og_title' => ['nullable', 'string', 'max:500'],
            'og_description' => ['nullable', 'string', 'max:1000'],
            'og_image' => ['nullable', 'string', 'max:500'],
            'og_type' => ['nullable', 'string', 'max:50'],
            'twitter_title' => ['nullable', 'string', 'max:500'],
            'twitter_description' => ['nullable', 'string', 'max:1000'],
            'twitter_image' => ['nullable', 'string', 'max:500'],
            'twitter_card' => ['nullable', 'string', 'max:50'],
            'canonical_url' => ['nullable', 'string', 'max:500'],
            'robots_meta' => ['nullable', 'string', 'max:100'],
            'sitemap_priority' => ['nullable', 'numeric', 'min:0', 'max:1'],
            'sitemap_changefreq' => ['nullable', 'string', 'in:always,hourly,daily,weekly,monthly,yearly,never'],
            'exclude_from_sitemap' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'is_deleted' => ['nullable', 'boolean'],
            'require_login' => ['nullable', 'boolean'],
            'parent' => ['nullable', 'integer'],
            'layout_file' => ['nullable', 'string', 'max:500'],
            'active_site_template' => ['nullable', 'string', 'max:500'],
            'original_link' => ['nullable', 'string', 'max:500'],
            'position' => ['nullable', 'integer'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The post title is required.',
            'title.max' => 'The post title may not be greater than 500 characters.',
            'url.unique' => 'This post URL is already in use.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator): void
    {
        throw new \Illuminate\Validation\ValidationException($validator, response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $validator->errors(),
        ], 422));
    }
}
