<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_users_page_can_be_rendered_without_listing_data(): void
    {
        $admin = Admin::factory()->create();
        $user = User::factory()->create([
            'name' => 'App User',
            'email' => 'app.user@landsite.test',
        ]);

        $response = $this->actingAs($admin, 'admin')->get('/admin/users');

        $response
            ->assertOk()
            ->assertSee('App Users')
            ->assertSee('User Directory')
            ->assertSee($user->email)
            ->assertSee('The properties table is ready, but no user has posted a property yet.')
            ->assertViewHas('userCount', 1)
            ->assertViewHas('totalPostsCount', 0)
            ->assertViewHas('rentPostsCount', 0)
            ->assertViewHas('salePostsCount', 0)
            ->assertViewHas('listingDataAvailable', true)
            ->assertViewHas('listingTable', 'properties');
    }

    public function test_admin_users_page_can_read_post_metrics_from_properties_table(): void
    {
        $admin = Admin::factory()->create();
        $rentUser = User::factory()->create([
            'name' => 'Rent User',
            'email' => 'rent.user@landsite.test',
        ]);
        $saleUser = User::factory()->create([
            'name' => 'Sale User',
            'email' => 'sale.user@landsite.test',
        ]);

        Property::factory()->create(['user_id' => $rentUser->id, 'purpose' => 'rent']);
        Property::factory()->create(['user_id' => $rentUser->id, 'purpose' => 'rent']);
        Property::factory()->create(['user_id' => $saleUser->id, 'purpose' => 'sale']);
        Property::factory()->create(['user_id' => $saleUser->id, 'purpose' => 'sale']);

        $response = $this->actingAs($admin, 'admin')->get('/admin/users');

        $response
            ->assertOk()
            ->assertViewHas('listingDataAvailable', true)
            ->assertViewHas('listingTable', 'properties')
            ->assertViewHas('listingTypeColumn', 'purpose')
            ->assertViewHas('totalPostsCount', 4)
            ->assertViewHas('usersWithPostsCount', 2)
            ->assertViewHas('rentPostsCount', 2)
            ->assertViewHas('salePostsCount', 2)
            ->assertSee('rent.user@landsite.test')
            ->assertSee('sale.user@landsite.test');

        $users = $response->viewData('users');

        $this->assertSame(2, $users->firstWhere('id', $rentUser->id)->posts_count);
        $this->assertSame(2, $users->firstWhere('id', $rentUser->id)->rent_posts_count);
        $this->assertSame(0, $users->firstWhere('id', $rentUser->id)->sale_posts_count);
        $this->assertSame(2, $users->firstWhere('id', $saleUser->id)->sale_posts_count);
    }
}
