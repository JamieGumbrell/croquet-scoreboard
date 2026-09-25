@extends('layouts.main')
@vite(['resources/css/players.css'])

@section('body')
    <div class="md-container">
            <h2>Player Lists</h2>
            <form action="{{ route('player_lists.store') }}" method="POST">
                @csrf
                <button class="btn default-primary-color text-primary-color" type='submit'>Add Player List</button>    
            </form>
        @if (session('success'))
            <p class="success-message">{{ session('success') }}</p>
        @endif
        
        @forelse ($playerLists as $playerList)
            <div class="player-list-container">
                <div class="player-list-content" onclick="window.location.href='{{ route('player_lists.show', $playerList) }}';">
                    <h3>{{ $playerList->name }}</h3>
                </div>
                <div class="player-list-settings">
                    <div class="edit" onclick="window.location.href='{{ route('player_lists.edit', $playerList) }}';">
                        <x-heroicon-s-pencil-square />
                    </div>
                    <div class="delete" onclick="
                        event.preventDefault();
                        if(confirm('Are you sure you want to delete this player list?')) { 
                            document.getElementById('delete-player-list-{{$playerList->id}}').submit(); 
                        }">
                        <x-heroicon-s-trash />
                    </div>
                </div>
            </div>
            <form id="delete-player-list-{{$playerList->id}}" method="POST" action="{{ route('player_lists.destroy', $playerList) }}">
                @csrf
                @method('DELETE')
            </form>
        @empty
            <tr>
                <td colspan="4">No player lists have been added.</td>
            </tr>
        @endforelse
    </div>
@endsection