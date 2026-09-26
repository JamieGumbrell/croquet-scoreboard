<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\PlayerList;
use App\Models\Country;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $playerList = PlayerList::findOrFail($request->query('player_list'));
        $countries = Country::query()
                ->where(function ($query) {
                    $query->whereNull('user_id')
                    ->orWhere('user_id', auth()->id());
                })
                ->orderBy('name')
                ->get();
        return view('players.create', compact('playerList', 'countries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validateData($request);

        Player::create($validated);

        return redirect()->route('player_lists.show', $validated['player_list_id'])->with('success', 'Player created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Player $player)
    {
        return view('players.show', compact('player'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Player $player)
    {
        $playerList = PlayerList::findOrFail($player->player_list_id);
        $countries = Country::query()
                ->where(function ($query) {
                    $query->whereNull('user_id')
                    ->orWhere('user_id', auth()->id());
                })
                ->orderBy('name')
                ->get();

        return view('players.edit', compact('player', 'playerList', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Player $player)
    {
        $validated = $this->validateData($request);

        $player->update($validated);

        return redirect()->route('player_lists.show', $player->player_list_id)->with('success', 'Player updated successfully.');
    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Player $player)
    {
        $player_list_id = $player->player_list_id;
        $player->delete();

        return redirect()->route('player_lists.show', $player_list_id)->with('success', 'Country deleted successfully.');
    }

    private function validateData(Request $request)
    {
        return $request->validate([
            'player_list_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'country' => ['nullable', 'integer', 'exists:countries,id'],
        ]);
    }
}
