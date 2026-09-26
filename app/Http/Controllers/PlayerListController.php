<?php

namespace App\Http\Controllers;

use App\Models\PlayerList;
use App\Models\Player;
use Illuminate\Http\Request;

class PlayerListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $playerLists = PlayerList::latest()->paginate(10);
        return view('players.list', compact('playerLists'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data['user_id'] = auth()->id();
        $data['name'] = "New Player List";
        PlayerList::create($data);

        return redirect()->route('player_lists.index')->with('success', 'Player list created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PlayerList $playerList)
    {
        $players = Player::with('countryRecord')->get();
        return view('players.show', compact('playerList', 'players'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PlayerList $playerList)
    {
        return view('players.editList', compact('playerList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PlayerList $playerList)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $playerList->update($validated);

        return redirect()->route('player_lists.index')->with('success', 'Player List updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PlayerList $playerList)
    {
        $playerList->delete();

        return redirect()->route('player_lists.index')->with('success', 'PlayerList deleted successfully.');

    }
}
