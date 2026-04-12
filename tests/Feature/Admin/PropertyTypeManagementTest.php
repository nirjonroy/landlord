<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\PropertyType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PropertyTypeManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_property_types_page_can_be_rendered(): void
    {
        $admin = Admin::factory()->create();

        $this->actingAs($admin, 'admin')
            ->get(route('admin.property-types.index'))
            ->assertOk()
            ->assertSee('Property Types')
            ->assertSee('Add Property Type')
            ->assertSee('Existing Property Types');
    }

    public function test_admin_can_create_a_property_type(): void
    {
        Storage::fake('public');
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->post(route('admin.property-types.store'), [
            'name' => 'Warehouse',
            'filter_value' => 'Warehouse',
            'display_order' => 13,
            'is_active' => '1',
            'show_on_home' => '1',
            'icon_image' => UploadedFile::fake()->image('warehouse.png', 120, 120),
        ]);

        $response->assertRedirect(route('admin.property-types.index'));

        $this->assertDatabaseHas('property_types', [
            'name' => 'Warehouse',
            'filter_value' => 'Warehouse',
            'display_order' => 13,
            'is_active' => true,
            'show_on_home' => true,
        ]);
    }

    public function test_admin_can_update_a_property_type(): void
    {
        $admin = Admin::factory()->create();
        $propertyType = PropertyType::query()->create([
            'name' => 'Warehouse',
            'filter_value' => 'Warehouse',
            'icon_path' => 'frontend-assets/img/icons/more_icon.svg',
            'icon_source' => 'asset',
            'display_order' => 0,
            'is_active' => true,
            'show_on_home' => true,
        ]);

        $response = $this->actingAs($admin, 'admin')->put(route('admin.property-types.update', $propertyType), [
            'name' => 'Storage Unit',
            'filter_value' => 'Storage Unit',
            'display_order' => 4,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.property-types.index'));

        $this->assertDatabaseHas('property_types', [
            'id' => $propertyType->id,
            'name' => 'Storage Unit',
            'filter_value' => 'Storage Unit',
            'display_order' => 4,
            'is_active' => true,
            'show_on_home' => false,
        ]);
    }
}
