<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response
            ->assertOk()
            ->assertSee('Landlord Profile')
            ->assertSee($user->name);
    }

    public function test_profile_information_can_be_updated(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Mostofa Mahmud',
                'account_type' => 'owner',
                'email' => 'mostofa@example.com',
                'phone' => '01700000000',
                'alternative_phone' => '01800000000',
                'date_of_birth' => '1990-01-01',
                'gender' => 'male',
                'profession' => 'Business',
                'home_name' => 'Mahmud Villa',
                'home_type' => 'Apartment',
                'present_address' => 'House 10, Road 12, Dhanmondi, Dhaka',
                'permanent_address' => 'Village Home, Cumilla',
                'area_name' => 'Dhanmondi',
                'post_office' => 'Dhanmondi',
                'postal_code' => '1209',
                'upazila' => 'Dhanmondi',
                'district' => 'Dhaka',
                'division' => 'Dhaka',
                'country' => 'Bangladesh',
                'bio' => 'Verified landlord and property owner in Dhaka.',
                'nid_number' => '1234567890',
                'passport_number' => 'A12345678',
                'ownership_document_type' => 'mutation-certificate',
                'emergency_contact_name' => 'Rahim Uddin',
                'emergency_contact_phone' => '01900000000',
                'profile_photo' => UploadedFile::fake()->image('profile.jpg'),
                'nid_front_image' => UploadedFile::fake()->image('nid-front.jpg'),
                'nid_back_image' => UploadedFile::fake()->image('nid-back.jpg'),
                'passport_image' => UploadedFile::fake()->image('passport.jpg'),
                'ownership_proof' => UploadedFile::fake()->create('ownership.pdf', 500, 'application/pdf'),
                'home_elevation_images' => [
                    UploadedFile::fake()->image('front.jpg'),
                    UploadedFile::fake()->image('side.jpg'),
                ],
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        $this->assertSame('Mostofa Mahmud', $user->name);
        $this->assertSame('owner', $user->account_type);
        $this->assertSame('mostofa@example.com', $user->email);
        $this->assertSame('01700000000', $user->phone);
        $this->assertSame('Dhaka', $user->district);
        $this->assertSame('mutation-certificate', $user->ownership_document_type);
        $this->assertCount(2, $user->home_elevation_image_paths ?? []);
        $this->assertNotNull($user->profile_photo_path);
        $this->assertNotNull($user->ownership_proof_path);
        $this->assertNull($user->email_verified_at);

        Storage::disk('public')->assertExists($user->profile_photo_path);
        Storage::disk('public')->assertExists($user->nid_front_image_path);
        Storage::disk('public')->assertExists($user->nid_back_image_path);
        Storage::disk('public')->assertExists($user->passport_image_path);
        Storage::disk('public')->assertExists($user->ownership_proof_path);

        foreach ($user->home_elevation_image_paths as $path) {
            Storage::disk('public')->assertExists($path);
        }

        $this->actingAs($user)
            ->get(route('profile.files.show', ['type' => 'profile-photo']))
            ->assertOk();
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => $user->email,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_user_can_delete_their_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($user->fresh());
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->delete('/profile', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect('/profile');

        $this->assertNotNull($user->fresh());
    }
}
