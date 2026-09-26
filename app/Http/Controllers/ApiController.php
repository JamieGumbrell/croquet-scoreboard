<?php

namespace App\Http\Controllers;

use App\Models\Scoreboard;
use App\Models\Country;
use App\Models\Player;
use App\Models\PlayerList;

class ApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Scoreboard $scoreboard)
    {
        $countries = Country::query()
                ->where(function ($query) {
                    $query->whereNull('user_id')
                    ->orWhere('user_id', auth()->id());
                })
                ->orderBy('name')
                ->get();
        $players = Player::query()
            ->whereIn('player_list_id', PlayerList::query()
                ->where('user_id', auth()->id())
                ->where('enabled', true)
                ->select('id'))
            ->get();

        return response()
            ->view('api.preview', compact('scoreboard', 'countries', 'players'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }
}
