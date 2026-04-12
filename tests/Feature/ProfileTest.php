<?php

namespace Tests\Feature;

use App\Models\Property;
use App\Models\PropertyType;
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
            ->assertSee($user->name)
            ->assertSee('My Property')
            ->assertSee('Add New Property');
    }

    public function test_dashboard_redirects_to_profile_dashboard_tab(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertRedirect('/profile?tab=dashboard#dashboard');
    }

    public function test_profile_page_can_show_logged_in_user_property_status(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        Property::factory()->create([
            'user_id' => $user->id,
            'title' => 'Beach Plot',
            'purpose' => 'sale',
            'status' => 'pending',
            'price' => 3500000,
        ]);

        Property::factory()->create([
            'user_id' => $user->id,
            'title' => 'City Apartment',
            'purpose' => 'rent',
            'status' => 'approved',
            'price' => 45000,
        ]);

        Property::factory()->create([
            'user_id' => $otherUser->id,
            'title' => 'Other User Property',
            'purpose' => 'sale',
            'status' => 'approved',
            'price' => 5000000,
        ]);

        $response = $this->actingAs($user)->get('/profile?tab=my_property');

        $response
            ->assertOk()
            ->assertSee('Beach Plot')
            ->assertSee('City Apartment')
            ->assertDontSee('Other User Property')
            ->assertSee('For Sale')
            ->assertSee('For Rent')
            ->assertSee('Pending')
            ->assertSee('Approved');

        $propertyAnalytics = $response->viewData('propertyAnalytics');

        $this->assertTrue($propertyAnalytics['available']);
        $this->assertSame('properties', $propertyAnalytics['table']);
        $this->assertSame(2, $propertyAnalytics['total_posts']);
        $this->assertSame(1, $propertyAnalytics['sale_posts']);
        $this->assertSame(1, $propertyAnalytics['rent_posts']);
    }

    public function test_user_can_add_property_from_profile(): void
    {
        Storage::fake('public');

        PropertyType::query()->create([
            'name' => 'Apartment',
            'filter_value' => 'Apartment',
            'icon_path' => 'frontend-assets/img/icons/apartment_icon.svg',
            'icon_source' => 'asset',
            'display_order' => 1,
            'is_active' => true,
            'show_on_home' => true,
        ]);

        $user = User::factory()->create([
            'phone' => '01712345678',
            'district' => 'Dhaka',
            'division' => 'Dhaka',
            'present_address' => 'House 12, Road 7, Dhanmondi, Dhaka',
        ]);

        $response = $this
            ->actingAs($user)
            ->post('/properties', [
                'property_form' => 'create',
                'title' => 'Dhanmondi Family Apartment',
                'purpose' => 'rent',
                'property_type' => 'Apartment',
                'price' => 52000,
                'area_size' => 1450,
                'bedrooms' => 3,
                'bathrooms' => 2,
                'garages' => 1,
                'location' => 'Dhanmondi, Dhaka',
                'district' => 'Dhaka',
                'division' => 'Dhaka',
                'address' => 'House 12, Road 7, Dhanmondi, Dhaka',
                'description' => 'South-facing flat near main road.',
                'thumbnail_image' => UploadedFile::fake()->image('cover.jpg'),
                'gallery_images' => [
                    UploadedFile::fake()->image('room.jpg'),
                    UploadedFile::fake()->image('front.jpg'),
                ],
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile?tab=my_property#my_property');

        $property = Property::query()->first();

        $this->assertNotNull($property);
        $this->assertSame($user->id, $property->user_id);
        $this->assertSame('Dhanmondi Family Apartment', $property->title);
        $this->assertSame('rent', $property->purpose);
        $this->assertSame('pending', $property->status);
        $this->assertSame('01712345678', $property->contact_phone);
        $this->assertNotNull($property->thumbnail_path);
        $this->assertCount(2, $property->gallery_paths ?? []);

        Storage::disk('public')->assertExists($property->thumbnail_path);

        foreach ($property->gallery_paths as $path) {
            Storage::disk('public')->assertExists($path);
        }
    }

    public function test_user_can_delete_their_property(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $property = Property::factory()->create([
            'user_id' => $user->id,
            'thumbnail_path' => 'users/'.$user->id.'/properties/thumbnails/cover.jpg',
            'gallery_paths' => [
                'users/'.$user->id.'/properties/gallery/one.jpg',
                'users/'.$user->id.'/properties/gallery/two.jpg',
            ],
        ]);

        Storage::disk('public')->put($property->thumbnail_path, 'cover');
        Storage::disk('public')->put($property->gallery_paths[0], 'one');
        Storage::disk('public')->put($property->gallery_paths[1], 'two');

        $this->actingAs($user)
            ->delete(route('properties.destroy', $property))
            ->assertRedirect('/profile?tab=my_property#my_property');

        $this->assertNull($property->fresh());
        Storage::disk('public')->assertMissing($property->thumbnail_path);
        Storage::disk('public')->assertMissing($property->gallery_paths[0]);
        Storage::disk('public')->assertMissing($property->gallery_paths[1]);
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

    public function test_home_info_section_can_be_updated_without_profile_details_fields(): void
    {
        $user = User::factory()->create([
            'name' => 'Existing User',
            'email' => 'existing@example.com',
        ]);

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'profile_section' => 'home_info',
                'home_name' => 'Prantik',
                'home_type' => 'Apartment',
                'present_address' => 'Dhanmondi, Dhaka',
                'permanent_address' => 'Sylhet Sadar, Sylhet',
                'district' => 'Sylhet',
                'division' => 'Sylhet',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile?tab=home_info#home_info');

        $user->refresh();

        $this->assertSame('Existing User', $user->name);
        $this->assertSame('existing@example.com', $user->email);
        $this->assertSame('Prantik', $user->home_name);
        $this->assertSame('Apartment', $user->home_type);
        $this->assertSame('Sylhet', $user->district);
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
