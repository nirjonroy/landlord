<?php

namespace App\Http\Requests;

use App\Models\PropertyType;
use Illuminate\Foundation\Http\FormRequest;

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
        $currentPropertyType = $this->route('property')?->property_type;

        return [
            'property_form' => ['nullable', 'string'],
            'title' => ['required', 'string', 'max:255'],
            'purpose' => ['required', 'in:sale,rent'],
            'property_type' => [
                'required',
                'string',
                'max:255',
                function (string $attribute, mixed $value, \Closure $fail) use ($currentPropertyType): void {
                    $normalizedValue = trim((string) $value);

                    if ($normalizedValue === '') {
                        $fail('Please select a valid property type.');

                        return;
                    }

                    $isValid = PropertyType::query()
                        ->where('filter_value', $normalizedValue)
                        ->where(function ($query) use ($currentPropertyType) {
                            $query->where('is_active', true);

                            if ($currentPropertyType !== null) {
                                $query->orWhere('filter_value', $currentPropertyType);
                            }
                        })
                        ->exists();

                    if (! $isValid) {
                        $fail('Please select a valid property type.');
                    }
                },
            ],
            'price' => ['required', 'numeric', 'min:0'],
            'area_size' => ['nullable', 'numeric', 'min:0'],
            'bedrooms' => ['nullable', 'integer', 'min:0', 'max:99'],
            'bathrooms' => ['nullable', 'integer', 'min:0', 'max:99'],
            'garages' => ['nullable', 'integer', 'min:0', 'max:20'],
            'location' => ['required', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:255'],
            'division' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:1000'],
            'description' => ['nullable', 'string', 'max:4000'],
            'contact_phone' => ['nullable', 'string', 'max:30'],
            'thumbnail_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'remove_thumbnail_image' => ['nullable', 'boolean'],
            'gallery_images' => ['nullable', 'array', 'max:6'],
            'gallery_images.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'reset_gallery_images' => ['nullable', 'boolean'],
        ];
    }
}
