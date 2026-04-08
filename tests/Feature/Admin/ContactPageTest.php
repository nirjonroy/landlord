<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\ContactPage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ContactPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_contact_page_can_be_rendered(): void
    {
        $admin = Admin::factory()->create();

        $this->actingAs($admin, 'admin')
            ->get(route('admin.contact-page.edit'))
            ->assertOk()
            ->assertSee('Contact Page Content')
            ->assertSee('Contact Form Copy')
            ->assertSee('Recent Inquiries');
    }

    public function test_admin_can_update_contact_page_header(): void
    {
        Storage::fake('public');

        $admin = Admin::factory()->create();

        $this->actingAs($admin, 'admin')
            ->put(route('admin.contact-page.update'), [
                'section' => 'page-header',
                'hero_title' => 'Talk to our Bangladesh support team today',
                'hero_background' => UploadedFile::fake()->image('contact-header.jpg', 1600, 900),
            ])
            ->assertRedirect(route('admin.contact-page.edit').'#page-header');

        $contactPage = ContactPage::query()->first();

        $this->assertNotNull($contactPage);
        $this->assertSame('Talk to our Bangladesh support team today', $contactPage->hero_title);
        $this->assertSame('upload', $contactPage->hero_background_source);
        Storage::disk('public')->assertExists($contactPage->hero_background_path);
    }

    public function test_admin_can_update_contact_form_copy(): void
    {
        $admin = Admin::factory()->create();

        $this->actingAs($admin, 'admin')
            ->put(route('admin.contact-page.update'), [
                'section' => 'contact-form',
                'form_title' => 'Need listing support?',
                'form_intro' => 'Send your question and our team will guide you.',
                'submit_button_text' => 'Send Inquiry',
                'success_message' => 'Thanks. We received your inquiry.',
            ])
            ->assertRedirect(route('admin.contact-page.edit').'#contact-form');

        $contactPage = ContactPage::query()->first();

        $this->assertNotNull($contactPage);
        $this->assertSame('Need listing support?', $contactPage->form_title);
        $this->assertSame('Send Inquiry', $contactPage->submit_button_text);
        $this->assertSame('Thanks. We received your inquiry.', $contactPage->success_message);
    }
}
