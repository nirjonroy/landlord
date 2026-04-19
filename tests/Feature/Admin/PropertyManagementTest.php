<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertyManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_properties_page_can_be_rendered(): void
    {
        $admin = Admin::factory()->create();
        $user = User::factory()->create([
            'name' => 'Property Owner',
            'email' => 'owner@landsite.test',
        ]);

        Property::factory()->create([
            'user_id' => $user->id,
            'title' => 'Bashundhara Review Flat',
            'status' => 'pending',
        ]);

        $this->actingAs($admin, 'admin')
            ->get('/admin/properties')
            ->assertOk()
            ->assertSee('Property Reviews')
            ->assertSee('Pending Review Queue')
            ->assertSee('Bashundhara Review Flat')
            ->assertSee('Property Owner');
    }

    public function test_admin_can_approve_a_property(): void
    {
        $admin = Admin::factory()->create();
        $property = Property::factory()->create([
            'status' => 'pending',
            'review_note' => null,
            'reviewed_at' => null,
            'reviewed_by_admin_id' => null,
        ]);

        $this->actingAs($admin, 'admin')
            ->put(route('admin.properties.review.update', $property), [
                'status' => 'approved',
                'review_note' => 'All information looks valid.',
            ])
            ->assertRedirect(route('admin.properties.index'));

        $property->refresh();

        $this->assertSame('approved', $property->status);
        $this->assertSame('All information looks valid.', $property->review_note);
        $this->assertNotNull($property->reviewed_at);
        $this->assertSame($admin->id, $property->reviewed_by_admin_id);
    }

    public function test_admin_property_detail_page_can_be_rendered(): void
    {
        $admin = Admin::factory()->create();
        $property = Property::factory()->create([
            'status' => 'approved',
            'availability_status' => 'available',
            'postal_code' => '1209',
        ]);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.properties.show', $property))
            ->assertOk()
            ->assertSee('Property Details')
            ->assertSee($property->title)
            ->assertSee('ZIP Code');
    }

    public function test_admin_can_reject_a_property_with_note(): void
    {
        $admin = Admin::factory()->create();
        $property = Property::factory()->create([
            'status' => 'pending',
        ]);

        $this->actingAs($admin, 'admin')
            ->put(route('admin.properties.review.update', $property), [
                'status' => 'rejected',
                'review_note' => 'Please add a clearer address and valid pricing details.',
            ])
            ->assertRedirect(route('admin.properties.index'));

        $property->refresh();

        $this->assertSame('rejected', $property->status);
        $this->assertSame('Please add a clearer address and valid pricing details.', $property->review_note);
        $this->assertNotNull($property->reviewed_at);
        $this->assertSame($admin->id, $property->reviewed_by_admin_id);
    }

    public function test_rejection_requires_a_review_note(): void
    {
        $admin = Admin::factory()->create();
        $property = Property::factory()->create([
            'status' => 'pending',
        ]);

        $this->actingAs($admin, 'admin')
            ->from(route('admin.properties.index'))
            ->put(route('admin.properties.review.update', $property), [
                'status' => 'rejected',
                'review_note' => '',
            ])
            ->assertSessionHasErrors('review_note')
            ->assertRedirect(route('admin.properties.index'));

        $this->assertSame('pending', $property->fresh()->status);
    }

    public function test_edited_property_returns_to_admin_pending_queue(): void
    {
        PropertyType::query()->create([
            'name' => 'Apartment',
            'filter_value' => 'Apartment',
            'icon_path' => 'frontend-assets/img/icons/apartment_icon.svg',
            'icon_source' => 'asset',
            'display_order' => 1,
            'is_active' => true,
            'show_on_home' => true,
        ]);

        $admin = Admin::factory()->create();
        $user = User::factory()->create();
        $property = Property::factory()->create([
            'user_id' => $user->id,
            'title' => 'Editable Review Flat',
            'property_type' => 'Apartment',
            'status' => 'approved',
            'review_note' => 'Approved previously.',
            'reviewed_at' => now(),
            'reviewed_by_admin_id' => $admin->id,
        ]);

        $this->actingAs($user)
            ->put(route('properties.update', $property), [
                'property_form' => 'edit',
                'title' => 'Editable Review Flat',
                'purpose' => $property->purpose,
                'property_type' => 'Apartment',
                'price' => $property->price,
                'area_size' => $property->area_size,
                'bedrooms' => $property->bedrooms,
                'bathrooms' => $property->bathrooms,
                'garages' => $property->garages,
                'location' => $property->location,
                'district' => $property->district,
                'division' => $property->division,
                'postal_code' => $property->postal_code,
                'address' => $property->address,
                'description' => 'Updated details for another review pass.',
                'contact_phone' => $property->contact_phone,
            ])
            ->assertRedirect(route('properties.show', $property).'#management-panel');

        $property->refresh();

        $this->assertSame('pending', $property->status);
        $this->assertNull($property->review_note);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.properties.index'))
            ->assertOk()
            ->assertSee('Pending Review Queue')
            ->assertSee('Editable Review Flat');
    }
}
