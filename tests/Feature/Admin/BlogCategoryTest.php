<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_blog_categories_page_can_be_rendered(): void
    {
        $admin = Admin::factory()->create();

        $this->actingAs($admin, 'admin')
            ->get(route('admin.blog-categories.index'))
            ->assertOk()
            ->assertSee('Blog Categories')
            ->assertSee('Create Category');
    }

    public function test_admin_can_create_blog_category(): void
    {
        $admin = Admin::factory()->create();

        $this->actingAs($admin, 'admin')
            ->post(route('admin.blog-categories.store'), [
                'name' => 'Legal Guidance',
                'slug' => 'legal-guidance',
                'description' => 'Bangladesh property paperwork and compliance notes.',
                'display_order' => 8,
                'is_active' => '1',
            ])
            ->assertRedirect(route('admin.blog-categories.index'));

        $this->assertDatabaseHas('blog_categories', [
            'name' => 'Legal Guidance',
            'slug' => 'legal-guidance',
        ]);
    }
}
