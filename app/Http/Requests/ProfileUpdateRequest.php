<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $sectionRules = match ($this->input('profile_section')) {
            'profile_details' => $this->profileDetailsRules(),
            'verification' => $this->verificationRules(),
            'home_info' => $this->homeInfoRules(),
            default => array_merge(
                $this->profileDetailsRules(),
                $this->verificationRules(),
                $this->homeInfoRules()
            ),
        };

        return array_merge([
            'profile_section' => ['nullable', Rule::in(['profile_details', 'verification', 'home_info'])],
        ], $sectionRules);
    }

    private function profileDetailsRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'account_type' => ['nullable', Rule::in(['landlord', 'owner', 'landlord-owner'])],
            'email' => ['required', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'alternative_phone' => ['nullable', 'string', 'max:20'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'profession' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:2000'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:20'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_profile_photo' => ['nullable', 'boolean'],
        ];
    }

    private function verificationRules(): array
    {
        return [
            'nid_number' => ['nullable', 'string', 'max:30'],
            'passport_number' => ['nullable', 'string', 'max:30'],
            'ownership_document_type' => ['nullable', Rule::in(['title-deed', 'mutation-certificate', 'land-development-tax', 'utility-bill', 'other'])],
            'nid_front_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'nid_back_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'passport_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:4096'],
            'ownership_proof' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:5120'],
            'remove_nid_front_image' => ['nullable', 'boolean'],
            'remove_nid_back_image' => ['nullable', 'boolean'],
            'remove_passport_image' => ['nullable', 'boolean'],
            'remove_ownership_proof' => ['nullable', 'boolean'],
        ];
    }

    private function homeInfoRules(): array
    {
        return [
            'home_name' => ['nullable', 'string', 'max:255'],
            'home_type' => ['nullable', 'string', 'max:255'],
            'present_address' => ['nullable', 'string', 'max:1000'],
            'permanent_address' => ['nullable', 'string', 'max:1000'],
            'area_name' => ['nullable', 'string', 'max:255'],
            'post_office' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'upazila' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:255'],
            'division' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'home_elevation_images' => ['nullable', 'array', 'max:6'],
            'home_elevation_images.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'reset_home_elevation_images' => ['nullable', 'boolean'],
        ];
    }
}
