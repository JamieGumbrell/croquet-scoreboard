<?php

namespace App\Http\Controllers;

use App\Models\Scoreboard;
use App\Models\Country;
use Illuminate\Http\Request;

class ScoreboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $scoreboards = Scoreboard::latest()->paginate(10);
        return view('scoreboards.index', compact('scoreboards'));
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
        $validated['user_id'] = auth()->id();
        Scoreboard::create($validated);

        return redirect()->route('scoreboards.index')->with('success', 'Scoreboard created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Scoreboard $scoreboard)
    {
        $countries = Country::all();

        return response()
            ->view('scoreboards.show', compact('scoreboard', 'countries'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Scoreboard $scoreboard)
    {
        return view('scoreboards.edit', compact('scoreboard'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Scoreboard $scoreboard)
    {
        $validated = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'color' => ['sometimes', 'required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'ball_color' => ['sometimes', 'required', 'in:hid,primary,secondary'],
            'design' => ['sometimes', 'required', 'in:default,simple_light,simple_dark'],
            'width' => ['nullable', 'string', 'max:255'],
            'scoretype' => ['sometimes', 'required', 'in:single,combined,gateball'],
            'gametype' => ['sometimes', 'required', 'in:-1,0,1'],
            'name1' => ['sometimes', 'nullable', 'string', 'max:255'],
            'name2' => ['sometimes', 'nullable', 'string', 'max:255'],
            'country1' => ['sometimes', 'nullable', 'string', 'max:10'],
            'country2' => ['sometimes', 'nullable', 'string', 'max:10'],
            'games1' => ['sometimes', 'required', 'integer', 'min:0'],
            'games2' => ['sometimes', 'required', 'integer', 'min:0'],
            'score1' => ['sometimes', 'required', 'integer', 'min:0'],
            'score2' => ['sometimes', 'required', 'integer', 'min:0'],
            'score3' => ['sometimes', 'required', 'integer', 'min:0'],
            'score4' => ['sometimes', 'required', 'integer', 'min:0'],
            'score5' => ['sometimes', 'required', 'integer', 'min:0'],
            'score6' => ['sometimes', 'required', 'integer', 'min:0'],
            'score7' => ['sometimes', 'required', 'integer', 'min:0'],
            'score8' => ['sometimes', 'required', 'integer', 'min:0'],
            'score9' => ['sometimes', 'required', 'integer', 'min:0'],
            'score10' => ['sometimes', 'required', 'integer', 'min:0'],
        ]);

        $scoreboard->update($validated);

        if ($request->expectsJson()) {
            return response()->json($scoreboard->fresh());
        }

        return redirect()->route('scoreboards.show', $scoreboard)->with('success', 'Scoreboard updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Scoreboard $scoreboard)
    {
        $scoreboard->delete();

        return redirect()->route('scoreboards.index')->with('success', 'Scoreboard deleted successfully.');
    }
}
