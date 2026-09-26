@extends('layouts.main')
@vite(['resources/css/players.css', 'resources/css/form.css'])

@section('body')
    <div class="md-container">
        <h2>Import Player List</h2>

        @if ($errors->any())
            <div class="error-text">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <p class="import-hint">Use a header row with a required <code>name</code> column and an optional <code>country</code> column.</p>

        <form class="player-import-form" action="{{ route('player_lists.import.preview') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="input-container">
                <h2>Player list name</h2>
                <input id="list-name" class="input-field" type="text" name="name" value="{{ old('name') }}" required maxlength="255">
            </div>

            <div class="input-container">
                <h2>CSV file</h2>
                <input id="players-csv" class="input-field" type="file" name="csv" accept=".csv,text/csv" required>
            </div>
            
            <div class="import-actions">
                <button class="btn success-color text-primary-color" type="submit">Preview import</button>
                <button class="btn default-primary-color text-primary-color" onclick="event.preventDefault(); window.location.href='{{ route('player_lists.index') }}'">Cancel</button>
            </div>
        </form>
    </div>
@endsection
<div>
    <!-- Simplicity is an acquired taste. - Katharine Gerould -->
</div>
