<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertySeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_creates_ten_demo_properties_for_seeded_user(): void
    {
        $this->seed(DatabaseSeeder::class);

        $user = User::query()->where('email', 'user@landsite.test')->firstOrFail();

        $this->assertSame(10, $user->properties()->count());
        $this->assertSame(5, $user->properties()->where('purpose', 'rent')->count());
        $this->assertSame(5, $user->properties()->where('purpose', 'sale')->count());
        $this->assertSame(10, $user->properties()->where('status', 'pending')->count());
        $this->assertSame(0, $user->properties()->whereNotNull('reviewed_at')->count());
    }
}
