<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Player;
use App\Models\PlayerList;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PlayerListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $playerLists = PlayerList::latest()->paginate(10);

        return view('players.list', compact('playerLists'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('players.import');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $data['user_id'] = $request->user()->getAuthIdentifier();
        $data['name'] = 'New Player List';
        PlayerList::create($data);

        return redirect()->route('player_lists.index')->with('success', 'Player list created successfully.');

    }

    public function preview(Request $request): View
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'csv' => ['required', 'file', 'mimes:csv,txt', 'max:2048'],
        ]);

        $rows = $this->parseCsv($request->file('csv')->getRealPath(), (int) $request->user()->getAuthIdentifier());
        $importToken = Str::uuid()->toString();
        $canImport = collect($rows)->every(fn (array $row): bool => $row['errors'] === []);

        $request->session()->put("player_list_imports.{$importToken}", [
            'user_id' => (int) $request->user()->getAuthIdentifier(),
            'name' => $validated['name'],
            'rows' => $rows,
        ]);

        return view('players.preview', compact('canImport', 'importToken', 'rows') + [
            'playerListName' => $validated['name'],
        ]);
    }

    public function confirm(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'import_token' => ['required', 'uuid'],
            'override' => ['sometimes', 'accepted'],
        ]);
        $sessionKey = "player_list_imports.{$validated['import_token']}";
        $import = $request->session()->get($sessionKey);

        if (! is_array($import)
            || ($import['user_id'] ?? null) !== (int) $request->user()->getAuthIdentifier()) {
            return redirect()->route('player_lists.create')
                ->withErrors(['csv' => 'This CSV preview has expired. Please upload the file again.']);
        }

        $hasErrors = collect($import['rows'] ?? [])->contains(fn (array $row): bool => $row['errors'] !== []);

        if (empty($import['rows']) || ($hasErrors && ! ($validated['override'] ?? false))) {
            return redirect()->route('player_lists.create')
                ->withErrors(['csv' => 'Fix the invalid CSV rows and upload the file again.']);
        }

        $playerList = DB::transaction(function () use ($import): PlayerList {
            $playerList = PlayerList::create([
                'user_id' => $import['user_id'],
                'name' => $import['name'],
            ]);
            $timestamp = now();

            foreach (array_chunk($import['rows'], 200) as $rows) {
                $players = array_map(fn (array $row): array => [
                    'player_list_id' => $playerList->id,
                    'name' => $row['name'],
                    'country' => $row['country_id'],
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ], $rows);

                DB::table('players')->insert($players);
            }

            return $playerList;
        });

        $request->session()->forget($sessionKey);

        return redirect()->route('player_lists.show', $playerList)
            ->with('success', 'Player list imported successfully.');
    }

    /**
     * @return array<int, array{line: int, name: string, country_id: int|null, country_name: string, errors: array<int, string>, defaults: array<int, string>}>
     */
    private function parseCsv(string|false $path, int $userId): array
    {
        $handle = $path === false ? false : fopen($path, 'rb');

        if ($handle === false) {
            throw ValidationException::withMessages(['csv' => 'The uploaded CSV could not be read.']);
        }

        try {
            $headers = fgetcsv($handle, null, ',', '"', '');

            if ($headers === false || $headers === []) {
                throw ValidationException::withMessages(['csv' => 'The CSV file is empty.']);
            }

            $headers[0] = preg_replace('/^\xEF\xBB\xBF/', '', (string) $headers[0]) ?? '';
            $headers = array_map(fn (mixed $header): string => Str::lower(trim((string) $header)), $headers);

            if (count($headers) !== count(array_unique($headers)) || ! in_array('name', $headers, true)) {
                throw ValidationException::withMessages(['csv' => 'The CSV must have a unique "name" header and may include a "country" header.']);
            }

            $nameColumn = array_search('name', $headers, true);
            $countryColumn = array_search('country', $headers, true);
            $countries = Country::query()
                ->where(function (Builder $query) use ($userId): void {
                    $query->whereNull('user_id')->orWhere('user_id', $userId);
                })
                ->get(['id', 'name'])
                ->keyBy(fn (Country $country): string => Str::lower($country->name));

            $rows = [];
            $line = 1;

            while (($record = fgetcsv($handle, null, ',', '"', '')) !== false) {
                $line++;

                if (! array_filter($record, fn ($value): bool => trim((string) $value) !== '')) {
                    continue;
                }

                if (count($rows) >= 5000) {
                    throw ValidationException::withMessages(['csv' => 'A player list can contain at most 5,000 players.']);
                }

                $sourceName = trim((string) ($record[$nameColumn] ?? ''));
                $countryName = $countryColumn === false ? '' : trim((string) ($record[$countryColumn] ?? ''));
                $country = $countryName === '' ? null : $countries->get(Str::lower($countryName));
                $errors = [];
                $name = $sourceName;

                if ($sourceName === '') {
                    $name = 'Player '.(count($rows) + 1);
                    $errors[] = 'Player name is required.';
                } elseif (Str::length($sourceName) > 255) {
                    $name = 'Player '.(count($rows) + 1);
                    $errors[] = 'Player name must be 255 characters or fewer.';
                }

                if ($countryName !== '' && $country === null) {
                    $errors[] = "Country '{$countryName}' was not found.";
                } 

                $rows[] = [
                    'line' => $line,
                    'name' => $name,
                    'country_id' => $country?->id,
                    'country_name' => $country?->name ?? '',
                    'errors' => $errors,
                ];
            }

            if ($rows === []) {
                throw ValidationException::withMessages(['csv' => 'The CSV must contain at least one player row.']);
            }

            return $rows;
        } finally {
            fclose($handle);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PlayerList $playerList): View
    {
        $players = Player::with('countryRecord')
            ->where('player_list_id', $playerList->id)
            ->get();

        return view('players.show', compact('playerList', 'players'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PlayerList $playerList): View
    {
        return view('players.editList', compact('playerList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PlayerList $playerList): RedirectResponse
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
    public function destroy(PlayerList $playerList): RedirectResponse
    {
        $playerList->delete();

        return redirect()->route('player_lists.index')->with('success', 'PlayerList deleted successfully.');

    }

    /**
     * Enable a Player List
     */
    public function enable(PlayerList $playerList): RedirectResponse
    {
        $playerList->update(['enabled' => true]);

        return redirect()->route('player_lists.index')->with('success', 'PlayerList enabled.');
    }

    /**
     * Disable a Player List
     */
    public function disable(PlayerList $playerList): RedirectResponse
    {
        $playerList->update(['enabled' => false]);

        return redirect()->route('player_lists.index')->with('success', 'PlayerList disabled.');
    }
}
