<?php

namespace Tests\Feature;

use App\Models\Country;
use App\Models\Player;
use App\Models\PlayerList;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class PlayerListCsvImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_csv_preview_shows_import_rows_without_creating_records(): void
    {
        $user = User::create([
            'username' => 'list-owner',
            'email' => 'list-owner@example.test',
            'password' => 'password',
        ]);
        Country::create([
            'name' => 'Australia',
            'link' => 'countries/aus.jpg',
        ]);

        $response = $this->actingAs($user)->post('/player_lists/import/preview', [
            'name' => 'Summer Players',
            'csv' => UploadedFile::fake()->createWithContent(
                'players.csv',
                "name,country\nJamie Gumbrell,Australia\nMollymook Player,\n",
            ),
        ]);

        $response->assertOk()
            ->assertViewIs('players.preview')
            ->assertSee('Summer Players')
            ->assertSee('Jamie Gumbrell')
            ->assertSee('Australia')
            ->assertViewHas('importToken');

        $this->assertDatabaseCount('player_lists', 0);
        $this->assertDatabaseCount('players', 0);
    }

    public function test_confirmation_creates_the_reviewed_list_and_players(): void
    {
        $user = User::create([
            'username' => 'list-owner',
            'email' => 'list-owner@example.test',
            'password' => 'password',
        ]);
        $country = Country::create([
            'name' => 'Australia',
            'link' => 'countries/aus.jpg',
        ]);
        $otherList = PlayerList::create([
            'user_id' => $user->id,
            'name' => 'Other Players',
        ]);
        Player::create([
            'player_list_id' => $otherList->id,
            'name' => 'Outside Player',
            'country' => null,
        ]);

        $preview = $this->actingAs($user)->post('/player_lists/import/preview', [
            'name' => 'Summer Players',
            'csv' => UploadedFile::fake()->createWithContent(
                'players.csv',
                "name,country\nJamie Gumbrell,Australia\nMollymook Player,\n",
            ),
        ]);
        $importToken = $preview->viewData('importToken');

        $response = $this->post('/player_lists/import/confirm', [
            'import_token' => $importToken,
        ]);

        $playerList = PlayerList::query()->where('name', 'Summer Players')->firstOrFail();
        $response->assertRedirectToRoute('player_lists.show', $playerList);
        $this->get(route('player_lists.show', $playerList))
            ->assertSeeText('Jamie Gumbrell')
            ->assertDontSeeText('Outside Player');
        $this->assertDatabaseHas('player_lists', [
            'id' => $playerList->id,
            'user_id' => $user->id,
            'name' => 'Summer Players',
        ]);
        $this->assertDatabaseHas('players', [
            'player_list_id' => $playerList->id,
            'name' => 'Jamie Gumbrell',
            'country' => $country->id,
        ]);
        $this->assertDatabaseHas('players', [
            'player_list_id' => $playerList->id,
            'name' => 'Mollymook Player',
            'country' => null,
        ]);
    }

    public function test_rows_with_unknown_countries_cannot_be_confirmed(): void
    {
        $user = User::create([
            'username' => 'list-owner',
            'email' => 'list-owner@example.test',
            'password' => 'password',
        ]);

        $preview = $this->actingAs($user)->post('/player_lists/import/preview', [
            'name' => 'Summer Players',
            'csv' => UploadedFile::fake()->createWithContent(
                'players.csv',
                "name,country\nJamie Gumbrell,Narnia\n",
            ),
        ]);
        $preview->assertOk()->assertSeeText("Country 'Narnia' was not found.");

        $response = $this->post('/player_lists/import/confirm', [
            'import_token' => $preview->viewData('importToken'),
        ]);

        $response->assertRedirectToRoute('player_lists.create')
            ->assertSessionHasErrors('csv');
        $this->assertDatabaseCount('player_lists', 0);
        $this->assertDatabaseCount('players', 0);
    }

    public function test_import_override_uses_defaults_for_missing_and_invalid_values(): void
    {
        $user = User::create([
            'username' => 'list-owner',
            'email' => 'list-owner@example.test',
            'password' => 'password',
        ]);

        $preview = $this->actingAs($user)->post('/player_lists/import/preview', [
            'name' => 'Summer Players',
            'csv' => UploadedFile::fake()->createWithContent(
                'players.csv',
                "name,country\n,Narnia\n\nA Valid Name,\n".str_repeat('x', 256).",Australia\n",
            ),
        ]);
        $preview->assertOk()
            ->assertSeeText('Player 1')
            ->assertSeeText('Player name is required.')
            ->assertSeeText('Country \'Narnia\' was not found.')
            ->assertSeeText('Player 3')
            ->assertSeeText('Player name must be 255 characters or fewer.')
            ->assertSeeText('Hidden');

        $response = $this->post('/player_lists/import/confirm', [
            'import_token' => $preview->viewData('importToken'),
            'override' => '1',
        ]);

        $playerList = PlayerList::query()->where('name', 'Summer Players')->firstOrFail();
        $response->assertRedirectToRoute('player_lists.show', $playerList);
        $this->assertDatabaseHas('players', [
            'player_list_id' => $playerList->id,
            'name' => 'Player 1',
            'country' => null,
        ]);
        $this->assertDatabaseHas('players', [
            'player_list_id' => $playerList->id,
            'name' => 'A Valid Name',
            'country' => null,
        ]);
        $this->assertDatabaseHas('players', [
            'player_list_id' => $playerList->id,
            'name' => 'Player 3',
            'country' => Country::query()->where('name', 'Australia')->value('id'),
        ]);
        $this->assertDatabaseCount('players', 3);
    }

    public function test_csv_without_a_name_header_is_rejected(): void
    {
        $user = User::create([
            'username' => 'list-owner',
            'email' => 'list-owner@example.test',
            'password' => 'password',
        ]);

        $response = $this->actingAs($user)->post('/player_lists/import/preview', [
            'name' => 'Summer Players',
            'csv' => UploadedFile::fake()->createWithContent(
                'players.csv',
                "player,country\nJamie Gumbrell,Australia\n",
            ),
        ]);

        $response->assertRedirectBackWithErrors(['csv']);
        $this->assertDatabaseCount('player_lists', 0);
        $this->assertDatabaseCount('players', 0);
    }

    public function test_player_list_can_be_enabled_using_its_route_bound_id(): void
    {
        $user = User::create([
            'username' => 'list-owner',
            'email' => 'list-owner@example.test',
            'password' => 'password',
        ]);
        $playerList = PlayerList::create([
            'user_id' => $user->id,
            'name' => 'Summer Players',
            'enabled' => false,
        ]);

        $response = $this->actingAs($user)->put(route('player_lists.enable', $playerList));

        $response->assertRedirectToRoute('player_lists.index');
        $this->assertDatabaseHas('player_lists', [
            'id' => $playerList->id,
            'enabled' => true,
        ]);
    }

    public function test_player_list_can_be_disabled_using_its_route_bound_id(): void
    {
        $user = User::create([
            'username' => 'list-owner',
            'email' => 'list-owner@example.test',
            'password' => 'password',
        ]);
        $playerList = PlayerList::create([
            'user_id' => $user->id,
            'name' => 'Summer Players',
            'enabled' => true,
        ]);

        $response = $this->actingAs($user)->put(route('player_lists.disable', $playerList));

        $response->assertRedirectToRoute('player_lists.index');
        $this->assertDatabaseHas('player_lists', [
            'id' => $playerList->id,
            'enabled' => false,
        ]);
    }
}
