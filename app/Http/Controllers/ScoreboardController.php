<?php

namespace App\Http\Controllers;

use App\Models\Scoreboard;
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
        return view('scoreboards.create');
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
        return view('scoreboards.show', compact('scoreboard'));
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
            'title' => ['required', 'string', 'max:255'],
            'color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'ball_color' => ['required', 'in:hid,primary,secondary'],
            'design' => ['required', 'in:default,simple_light,simple_dark'],
            'width' => ['nullable', 'string', 'max:255'],
            'scoretype' => ['required', 'in:single,combined,gateball'],
            'gametype' => ['required', 'in:-1,0,1'],
        ]);

        $scoreboard->update($validated);

        return redirect()->route('scoreboards.index')->with('success', 'Scoreboard updated successfully.');
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
