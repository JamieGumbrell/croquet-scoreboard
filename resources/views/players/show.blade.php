@extends('layouts.main')
@vite(['resources/css/players.css'])

@section('body')
    <div class="md-container">
        <h2>Players</h2>

        <div class="flex">
            <button class="btn default-primary-color text-primary-color"onclick="window.location.href='{{ route('player_lists.index') }}';">Back to Lists</button>
            <div class="flex flex-right">
                <button class="btn success-color text-primary-color"onclick="window.location.href='{{ route('players.create', ['player_list' => $playerList]) }}';">Add Player</button>
            </div>
        </div>
        @if (session('success'))
            <p class="success-message">{{ session('success') }}</p>
        @endif
        
        @forelse ($players as $player)
            <div class="player-container">
                <div class="player-content">
                    @if ($player->countryRecord?->link)
                        <div class="player-flag">
                            <img src="{{ str_starts_with($player->countryRecord->link, 'countries/') ? \Illuminate\Support\Facades\Storage::disk('public')->url($player->countryRecord->link) : $player->countryRecord->link }}" alt="{{ $player->countryRecord->name }}">
                        </div>    
                    @endif
                    <h3>{{ $player->name }}</h3>
                </div>
                <div class="player-settings">
                    <div class="edit" onclick="window.location.href='{{ route('players.edit', $player) }}';">
                        <x-heroicon-s-pencil-square />
                    </div>
                    <div class="delete" onclick="
                        event.preventDefault();
                        if(confirm('Are you sure you want to delete this player?')) { 
                            document.getElementById('delete-player-{{$player->id}}').submit(); 
                        }">
                        <x-heroicon-s-trash />
                    </div>
                </div>
            </div>
            <form id="delete-player-{{$player->id}}" method="POST" action="{{ route('players.destroy', $player) }}">
                @csrf
                @method('DELETE')
            </form>
        @empty
            <div>No players have been added.</div>
        @endforelse
    </div>
@endsection