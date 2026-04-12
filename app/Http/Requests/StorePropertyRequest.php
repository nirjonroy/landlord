<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePropertyRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<int, mixed>|string>
     */
    public function rules(): array
    {
        return [
            'property_form' => ['nullable', 'string'],
            'title' => ['required', 'string', 'max:255'],
            'purpose' => ['required', Rule::in(['sale', 'rent'])],
            'property_type' => [
                'required',
                'string',
                'max:255',
                Rule::exists('property_types', 'filter_value')->where(fn ($query) => $query->where('is_active', true)),
            ],
            'price' => ['required', 'numeric', 'min:0'],
            'area_size' => ['nullable', 'numeric', 'min:0'],
            'bedrooms' => ['nullable', 'integer', 'min:0', 'max:99'],
            'bathrooms' => ['nullable', 'integer', 'min:0', 'max:99'],
            'garages' => ['nullable', 'integer', 'min:0', 'max:20'],
            'location' => ['required', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:255'],
            'division' => ['nullable', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:1000'],
            'description' => ['nullable', 'string', 'max:4000'],
            'contact_phone' => ['nullable', 'string', 'max:30'],
            'thumbnail_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'gallery_images' => ['nullable', 'array', 'max:6'],
            'gallery_images.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ];
    }
}
