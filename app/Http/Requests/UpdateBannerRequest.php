<?php

namespace App\Http\Requests;

use App\Enums\BannerStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBannerRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:5120'], // 5MB max
            'target_url' => ['required', 'url', 'max:2048'],
            'link_text' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::enum(BannerStatus::class)],
        ];
    }
}
