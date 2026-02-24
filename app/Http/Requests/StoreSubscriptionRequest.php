<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $sourceBaseUrls = collect(config('subscriptions.parsers'))
            ->pluck('base_url')
            ->implode(',');

        return [
            'name' => ['required', 'string', 'max:255'],
            'url' => ['required', 'url', 'starts_with:'.$sourceBaseUrls, 'max:1024'],
            'email' => ['required', 'email', 'max:255'],
            'notes' => ['nullable', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if (isset($this->url)) {
            $this->merge(['url' => removeQueryFromUrl($this->url)]);
        }
    }
}
