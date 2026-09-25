@extends('layouts.main')
@vite(['resources/css/players.css'])

@section('body')
    <div class="md-container">
        <h2>Players</h2>
        <button class="btn default-primary-color text-primary-color"onclick="window.location.href='{{ route('players.create') }}';">Add Player</button>
        @if (session('success'))
            <p class="success-message">{{ session('success') }}</p>
        @endif
        
        @forelse ($players as $player)
            <div class="player-container">
                <div class="player-content" onclick="window.location.href='{{ route('players.show', $player) }}';">
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
            <form id="delete-player-{{$player>id}}" method="POST" action="{{ route('player.destroy', $player) }}">
                @csrf
                @method('DELETE')
            </form>
        @empty
            <tr>
                <td colspan="4">No players have been added.</td>
            </tr>
        @endforelse
    </div>
@endsection