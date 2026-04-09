<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_blog_index_can_be_rendered_from_seeded_content(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->get(route('blog.index'))
            ->assertOk()
            ->assertSee('News from Land Site')
            ->assertSee('5 Things Dhaka Renters Check Before Calling a Landlord')
            ->assertSee('Property Tips');
    }

    public function test_blog_detail_page_can_be_rendered_for_published_post(): void
    {
        $this->seed(DatabaseSeeder::class);

        $post = BlogPost::query()->where('slug', '5-things-dhaka-renters-check-before-calling-a-landlord')->firstOrFail();

        $this->get(route('blog.show', $post))
            ->assertOk()
            ->assertSee($post->title)
            ->assertSee('Sharmeen Akter')
            ->assertSee('Article Tags');
    }
}
