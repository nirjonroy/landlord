<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BlogPostTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_blog_posts_pages_can_be_rendered(): void
    {
        $admin = Admin::factory()->create();

        $this->actingAs($admin, 'admin')
            ->get(route('admin.blog-posts.index'))
            ->assertOk()
            ->assertSee('Blog Posts')
            ->assertSee('Create Post');

        $this->actingAs($admin, 'admin')
            ->get(route('admin.blog-posts.create'))
            ->assertOk()
            ->assertSee('Create Blog Post')
            ->assertSee('Post Title');
    }

    public function test_admin_can_create_blog_post(): void
    {
        Storage::fake('public');

        $admin = Admin::factory()->create();
        $category = BlogCategory::query()->create([
            'name' => 'Property Tips',
            'slug' => 'property-tips',
            'display_order' => 1,
            'is_active' => true,
        ]);

        $this->actingAs($admin, 'admin')
            ->post(route('admin.blog-posts.store'), [
                'blog_category_id' => $category->id,
                'title' => 'How to write a better rent listing',
                'slug' => 'how-to-write-a-better-rent-listing',
                'author_name' => 'Land Site Team',
                'excerpt' => 'Short summary for the card.',
                'content' => 'Full article content for the blog post.',
                'quote' => 'A useful quote from the article.',
                'meta_description' => 'Meta description for the post.',
                'featured_image' => UploadedFile::fake()->image('blog-post.jpg', 1200, 800),
                'published_at' => now()->toDateTimeString(),
                'tags_input' => 'rent, landlord tips',
                'is_published' => '1',
                'show_on_home' => '1',
            ])
            ->assertRedirect();

        $post = BlogPost::query()->first();

        $this->assertNotNull($post);
        $this->assertSame('How to write a better rent listing', $post->title);
        $this->assertSame('upload', $post->featured_image_source);
        $this->assertNotNull($post->featured_image_path);
        $this->assertTrue($post->is_published);
        $this->assertTrue($post->show_on_home);
    }
}
