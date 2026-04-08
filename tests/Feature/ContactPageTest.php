<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_page_can_be_rendered_from_seeded_content(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->get('/contact')
            ->assertOk()
            ->assertSee('We are eager to hear from you.')
            ->assertSee('Any Question? Catch Us Up!')
            ->assertSee('What Bangladesh Clients Say');
    }

    public function test_contact_form_submission_is_saved(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->post(route('contact.store'), [
            'name' => 'Nirjon Roy',
            'email' => 'nirjon@example.com',
            'message' => 'I want to know how to list a property for rent in Dhaka.',
        ])->assertRedirect(route('contact'));

        $this->assertDatabaseHas('contact_messages', [
            'name' => 'Nirjon Roy',
            'email' => 'nirjon@example.com',
        ]);
    }
}
