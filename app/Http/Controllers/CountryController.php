<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('countries.index', [
            'countries' => Country::query()
                ->where(function ($query) {
                    $query->Where('user_id', auth()->id());
                })
                ->orderBy('name')
                ->paginate(20),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('countries.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request, true);
        $data['user_id'] = auth()->id();
        if ($request->hasFile('image')) {
            $data['link'] = $request->file('image')->store('countries', 'public');
        }

        Country::create($data);

        return redirect()->route('countries.index')->with('success', 'Country created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Country $country): View
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Country $country): View
    {
        return view('countries.edit', compact('country'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Country $country): RedirectResponse
    {
        $data = $this->validatedData($request);

        if ($request->hasFile('image')) {
            $this->deleteStoredImage($country->link);
            $data['link'] = $request->file('image')->store('countries', 'public');
        } else {
            unset($data['link']);
        }

        $country->update($data);

        return redirect()->route('countries.index')->with('success', 'Country updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Country $country): RedirectResponse
    {
        $this->deleteStoredImage($country->link);
        $country->delete();

        return redirect()->route('countries.index')->with('success', 'Country deleted successfully.');
    }

    private function validatedData(Request $request, bool $imageRequired = false): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'image' => [$imageRequired ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:4096'],
        ]);
    }

    private function deleteStoredImage(?string $path): void
    {
        if ($path !== null && str_starts_with($path, 'countries/')) {
            Storage::disk('public')->delete($path);
        }
    }
}
