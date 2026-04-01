<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
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
            ->assertSee('No posts or listings table exists yet.')
            ->assertViewHas('userCount', 1)
            ->assertViewHas('totalPostsCount', 0)
            ->assertViewHas('rentPostsCount', 0)
            ->assertViewHas('salePostsCount', 0)
            ->assertViewHas('listingDataAvailable', false);
    }

    public function test_admin_users_page_can_read_post_metrics_from_posts_table(): void
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

        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('type');
            $table->timestamps();
        });

        DB::table('posts')->insert([
            ['user_id' => $rentUser->id, 'type' => 'rent'],
            ['user_id' => $rentUser->id, 'type' => 'rent'],
            ['user_id' => $saleUser->id, 'type' => 'sale'],
            ['user_id' => $saleUser->id, 'type' => 'sale'],
        ]);

        $response = $this->actingAs($admin, 'admin')->get('/admin/users');

        $response
            ->assertOk()
            ->assertViewHas('listingDataAvailable', true)
            ->assertViewHas('listingTable', 'posts')
            ->assertViewHas('listingTypeColumn', 'type')
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
