<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\BlogPage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BlogPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_blog_page_can_be_rendered(): void
    {
        $admin = Admin::factory()->create();

        $this->actingAs($admin, 'admin')
            ->get(route('admin.blog-page.edit'))
            ->assertOk()
            ->assertSee('Blog Page Content')
            ->assertSee('Page Header')
            ->assertSee('Template Labels');
    }

    public function test_admin_can_update_blog_page_header(): void
    {
        Storage::fake('public');

        $admin = Admin::factory()->create();

        $this->actingAs($admin, 'admin')
            ->put(route('admin.blog-page.update'), [
                'section' => 'page-header',
                'hero_title' => 'Bangladesh property insights and owner advice',
                'hero_background' => UploadedFile::fake()->image('blog-header.jpg', 1600, 900),
            ])
            ->assertRedirect(route('admin.blog-page.edit').'#page-header');

        $blogPage = BlogPage::query()->first();

        $this->assertNotNull($blogPage);
        $this->assertSame('Bangladesh property insights and owner advice', $blogPage->hero_title);
        $this->assertSame('upload', $blogPage->hero_background_source);
        Storage::disk('public')->assertExists($blogPage->hero_background_path);
    }
}
