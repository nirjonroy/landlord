<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AboutPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_about_page_can_be_rendered_from_seeded_content(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->get('/about')
            ->assertOk()
            ->assertSee('Built for Bangladesh landlords, owners, and property seekers.')
            ->assertSee('Our Mission')
            ->assertSee('Meet Our Team')
            ->assertSee('What Bangladesh Clients Say')
            ->assertSee('Frequently Asked Questions');
    }
}
