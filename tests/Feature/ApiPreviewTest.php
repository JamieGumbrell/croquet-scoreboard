<?php

namespace Tests\Feature;

use App\Models\Scoreboard;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiPreviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_preview_resolves_scoreboard_by_uid_instead_of_id(): void
    {
        $user = User::create([
            'username' => 'preview-owner',
            'email' => 'preview-owner@example.test',
            'password' => 'password',
        ]);
        $scoreboard = Scoreboard::create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->get('/api/preview/'.$scoreboard->uid)
            ->assertOk()
            ->assertViewIs('api.preview')
            ->assertSee(route('api.preview', ['scoreboard' => $scoreboard->uid]), false)
            ->assertViewHas('scoreboard', function (Scoreboard $resolvedScoreboard) use ($scoreboard): bool {
                return $resolvedScoreboard->is($scoreboard);
            });

        $this->actingAs($user)
            ->get('/api/preview/'.$scoreboard->id)
            ->assertNotFound();
    }
}
