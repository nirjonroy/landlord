<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Property;
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
}
