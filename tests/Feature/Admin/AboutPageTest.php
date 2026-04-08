<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\AboutPage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AboutPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_about_page_can_be_rendered(): void
    {
        $admin = Admin::factory()->create();

        $this->actingAs($admin, 'admin')
            ->get(route('admin.about-page.edit'))
            ->assertOk()
            ->assertSee('About Page Content')
            ->assertSee('Page Header')
            ->assertSee('Mission Section');
    }

    public function test_admin_can_update_about_page_header(): void
    {
        Storage::fake('public');

        $admin = Admin::factory()->create();

        $this->actingAs($admin, 'admin')
            ->put(route('admin.about-page.update'), [
                'section' => 'page-header',
                'hero_title' => 'Bangladesh property guidance starts here',
                'hero_background' => UploadedFile::fake()->image('about-header.jpg', 1600, 900),
            ])
            ->assertRedirect(route('admin.about-page.edit').'#page-header');

        $aboutPage = AboutPage::query()->first();

        $this->assertNotNull($aboutPage);
        $this->assertSame('Bangladesh property guidance starts here', $aboutPage->hero_title);
        $this->assertSame('upload', $aboutPage->hero_background_source);
        $this->assertNotNull($aboutPage->hero_background_path);
        Storage::disk('public')->assertExists($aboutPage->hero_background_path);
    }

    public function test_admin_can_update_team_section(): void
    {
        Storage::fake('public');

        $admin = Admin::factory()->create();
        $aboutPage = AboutPage::query()->create(AboutPage::defaultAttributes());
        $teamMembers = $aboutPage->team_members;
        $teamMembers[0]['name'] = 'Updated Team Lead';
        $teamMembers[0]['role'] = 'Regional Director';
        $teamMembers[0]['image'] = UploadedFile::fake()->image('team-member.jpg', 800, 800);

        $this->actingAs($admin, 'admin')
            ->put(route('admin.about-page.update'), [
                'section' => 'team',
                'team_section_title' => 'Meet Our Bangladesh Team',
                'team_members' => $teamMembers,
            ])
            ->assertRedirect(route('admin.about-page.edit').'#team');

        $aboutPage->refresh();

        $this->assertSame('Meet Our Bangladesh Team', $aboutPage->team_section_title);
        $this->assertSame('Updated Team Lead', $aboutPage->team_members[0]['name']);
        $this->assertSame('Regional Director', $aboutPage->team_members[0]['role']);
        $this->assertSame('upload', $aboutPage->team_members[0]['image_source']);
        Storage::disk('public')->assertExists($aboutPage->team_members[0]['image_path']);
    }
}
