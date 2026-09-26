@extends('layouts.main')
@vite(['resources/css/players.css'])

@section('body')
    <div class="md-container">
            <h2>Player Lists</h2>
            <div class="flex">
                <div class="flex flex-right">
                    <form action="{{ route('player_lists.store') }}" method="POST">
                        @csrf
                        <button class="gap btn success-color text-primary-color" type='submit'>Add Player List</button>    
                    </form>
                    <button class="btn success-color text-primary-color" onclick="window.location.href='{{ route('player_lists.create') }}'">Import Player List</button>
                </div>
            </div>
        @if (session('success'))
            <p class="success-message">{{ session('success') }}</p>
        @endif
        
        @forelse ($playerLists as $playerList)
            <div class="player-list-container">
                <div class="player-list-content" onclick="window.location.href='{{ route('player_lists.show', $playerList) }}';">
                    <h3>{{ $playerList->name }} @if ($playerList->enabled)(Enabled)@endif</h3>
                </div>
                <div class="player-list-settings">
                    @if ($playerList->enabled)
                        <div class="disable" onclick="
                            event.preventDefault();
                                document.getElementById('disable-player-list-{{$playerList->id}}').submit();">
                            <x-heroicon-s-x-mark />
                        </div> 
                    @else
                        <div class="enable" onclick="
                            event.preventDefault();
                                document.getElementById('enable-player-list-{{$playerList->id}}').submit();">
                            <x-heroicon-s-check />
                        </div> 
                    @endif  
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
            <form id="enable-player-list-{{$playerList->id}}" method="POST" action="{{ route('player_lists.enable', $playerList) }}">
                @csrf
                @method('PUT')
            </form>
            <form id="disable-player-list-{{$playerList->id}}" method="POST" action="{{ route('player_lists.disable', $playerList) }}">
                @csrf
                @method('PUT')
            </form>
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