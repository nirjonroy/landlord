<?php

namespace Tests\Feature;

use App\Models\Property;
use App\Models\SiteInfo;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertyListingPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_listing_page_shows_seeded_demo_listings_when_no_property_is_approved(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->get(route('properties.index'))
            ->assertOk()
            ->assertSee('Seeded Bangladesh Demo')
            ->assertSee('Dhanmondi Family Flat')
            ->assertDontSee('Prantik, 70/1');
    }

    public function test_public_listing_page_shows_approved_live_properties_and_filters_them(): void
    {
        SiteInfo::query()->create([
            'site_name' => 'Land Site',
            'site_url' => 'http://127.0.0.1:8000',
            'contact_email' => 'admin@landsite.test',
            'short_description' => 'Find land for rent and sale from one place.',
        ]);

        $user = User::factory()->create(['name' => 'Approved Owner']);

        Property::query()->create([
            'user_id' => $user->id,
            'title' => 'Banani Approved Flat',
            'purpose' => 'rent',
            'property_type' => 'Apartment',
            'status' => 'approved',
            'availability_status' => 'available',
            'price' => 65000,
            'area_size' => 1650,
            'bedrooms' => 3,
            'bathrooms' => 3,
            'garages' => 1,
            'location' => 'Banani, Dhaka',
            'district' => 'Dhaka',
            'division' => 'Dhaka',
            'postal_code' => '1213',
            'address' => 'Road 11, Banani, Dhaka',
            'description' => 'Approved live listing.',
            'contact_phone' => '01700000000',
        ]);

        Property::query()->create([
            'user_id' => $user->id,
            'title' => 'Pending Gulshan Plot',
            'purpose' => 'sale',
            'property_type' => 'Land',
            'status' => 'pending',
            'availability_status' => 'available',
            'price' => 35000000,
            'area_size' => 3600,
            'location' => 'Gulshan, Dhaka',
            'district' => 'Dhaka',
            'division' => 'Dhaka',
            'postal_code' => '1212',
            'address' => 'Gulshan, Dhaka',
            'description' => 'Should stay hidden from the public page.',
            'contact_phone' => '01700000000',
        ]);

        Property::query()->create([
            'user_id' => $user->id,
            'title' => 'Sold Niketan Flat',
            'purpose' => 'sale',
            'property_type' => 'Apartment',
            'status' => 'approved',
            'availability_status' => 'sold',
            'price' => 15000000,
            'location' => 'Niketan, Dhaka',
            'district' => 'Dhaka',
            'division' => 'Dhaka',
            'postal_code' => '1212',
            'address' => 'Niketan, Dhaka',
            'description' => 'Should stay hidden from the public page because it is sold.',
            'contact_phone' => '01700000000',
        ]);

        $this->get(route('properties.index', ['purpose' => 'rent', 'search' => 'Banani']))
            ->assertOk()
            ->assertSee('Approved Live Listings')
            ->assertSee('Banani Approved Flat')
            ->assertDontSee('Pending Gulshan Plot');

        $this->get(route('properties.index', ['location' => 'Banani']))
            ->assertOk()
            ->assertSee('Banani Approved Flat')
            ->assertDontSee('Sold Niketan Flat')
            ->assertDontSee('Pending Gulshan Plot');

        $this->get(route('properties.index', ['postal_code' => '1213']))
            ->assertOk()
            ->assertSee('Banani Approved Flat')
            ->assertDontSee('Pending Gulshan Plot');
    }

    public function test_property_detail_page_can_be_viewed_for_approved_listing(): void
    {
        $user = User::factory()->create(['name' => 'Detail Owner']);

        $property = Property::factory()->create([
            'user_id' => $user->id,
            'title' => 'Dhanmondi Detail Flat',
            'status' => 'approved',
            'availability_status' => 'available',
            'postal_code' => '1209',
        ]);

        $this->get(route('properties.show', $property))
            ->assertOk()
            ->assertSee('Dhanmondi Detail Flat')
            ->assertSee('Still Available')
            ->assertSee('ZIP Code');
    }
}
