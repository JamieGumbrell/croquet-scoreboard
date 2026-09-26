<?php

namespace Tests\Feature;

use App\Models\Player;
use App\Models\PlayerList;
use App\Models\Scoreboard;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScoreboardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_receives_players_from_enabled_lists_owned_by_the_current_user(): void
    {
        $user = User::create([
            'username' => 'scoreboard-owner',
            'email' => 'scoreboard-owner@example.test',
            'password' => 'password',
        ]);
        $otherUser = User::create([
            'username' => 'other-owner',
            'email' => 'other-owner@example.test',
            'password' => 'password',
        ]);
        $enabledList = PlayerList::create([
            'user_id' => $user->id,
            'name' => 'Enabled Players',
            'enabled' => true,
        ]);
        $disabledList = PlayerList::create([
            'user_id' => $user->id,
            'name' => 'Disabled Players',
            'enabled' => false,
        ]);
        $otherUsersList = PlayerList::create([
            'user_id' => $otherUser->id,
            'name' => 'Other User Players',
            'enabled' => true,
        ]);
        $includedPlayer = Player::create([
            'player_list_id' => $enabledList->id,
            'name' => 'Included Player',
        ]);
        Player::create([
            'player_list_id' => $disabledList->id,
            'name' => 'Disabled Player',
        ]);
        Player::create([
            'player_list_id' => $otherUsersList->id,
            'name' => 'Other User Player',
        ]);
        $scoreboard = Scoreboard::create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('scoreboards.show', $scoreboard));

        $response->assertViewIs('scoreboards.show')
            ->assertViewHas('players', function ($players) use ($includedPlayer): bool {
                return $players->modelKeys() === [$includedPlayer->id];
            });
    }
}
