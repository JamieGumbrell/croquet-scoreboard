@extends('layouts.main')
@vite(['resources/css/players.css'])

@section('body')
    <div class="md-container">
        <h2>Review Player List</h2>
        <h3>{{ $playerListName }}</h3>
        <p>{{ count($rows) }} players will be added.</p>

        @if (! $canImport)
            <p class="error-text">Some values are invalid. If you proceed, the listed defaults will be used.</p>
        @endif

        <div class="import-table-wrap">
            <table class="import-table">
                <thead>
                    <tr>
                        <th>CSV row</th>
                        <th>Player</th>
                        <th>Country</th>
                        <th>Review</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rows as $row)
                        <tr>
                            <td>{{ $row['line'] }}</td>
                            <td>{{ $row['name'] }}</td>
                            <td>{{ $row['country_name'] ?: 'Hidden' }}</td>
                            <td>{{ implode(' ', $row['errors']) ?: 'Ready' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="import-actions">
            <form action="{{ route('player_lists.import.confirm') }}" method="POST">
                @csrf
                <input type="hidden" name="import_token" value="{{ $importToken }}">
                @if ($canImport)
                    <button class="btn success-color text-primary-color" type="submit">Confirm and import</button>
                @else
                    <button class="btn error-color text-primary-color" type="submit" name="override" value="1">Import Anyway</button>
                @endif
            </form>
            <button class="btn default-primary-color text-primary-color" onclick="event.preventDefault(); window.location.href='{{ route('player_lists.index') }}'">Cancel</button>
        </div>
    </div>
@endsection
