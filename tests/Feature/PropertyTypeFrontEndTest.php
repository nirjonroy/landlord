<?php

namespace Tests\Feature;

use App\Models\PropertyType;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertyTypeFrontEndTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_renders_property_types_from_database(): void
    {
        $this->seed(DatabaseSeeder::class);

        PropertyType::query()->updateOrCreate(
            ['filter_value' => 'Warehouse'],
            [
                'name' => 'Warehouse',
                'icon_path' => 'frontend-assets/img/icons/more_icon.svg',
                'icon_source' => 'asset',
                'display_order' => 99,
                'is_active' => true,
                'show_on_home' => true,
            ]
        );

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Discover the Property Types')
            ->assertSee('Warehouse');
    }

    public function test_profile_add_property_form_uses_active_property_types(): void
    {
        $this->seed(DatabaseSeeder::class);

        $user = User::query()->where('email', 'user@landsite.test')->firstOrFail();

        $this->actingAs($user)
            ->get(route('profile.edit', ['tab' => 'add_property']))
            ->assertOk()
            ->assertSee('Apartment')
            ->assertSee('Villa')
            ->assertSee('Commercial Space');
    }
}
