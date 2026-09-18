@extends('layouts.main')
@vite(['resources/css/scoreboards.css'])

@section('body')
    <div class="md-container">
        <h2>Scoreboards</h2>
        <form action="{{ route('scoreboards.store') }}" method="POST">
            @csrf
            <button class="btn default-primary-color" type='submit'>Add Scoreboard</button>    
        </form>
        @foreach($scoreboards as $scoreboard)
            <div class="scoreboard-container">
                <div onclick="window.location.href='{{ route('scoreboards.show', $scoreboard->id) }}'" class="scoreboard-content" style="background-color:{{ $scoreboard->color }};">
                    <h3 style="color: white ?>;">{{ $scoreboard->title }}</h3>
                    
                </div>
                <div class="scoreboard-settings">
                    <div onclick="
                        event.preventDefault();
                        if(confirm('Are you sure you want to delete this scoreboard?')) { 
                            document.getElementById('delete-scoreboard-{{$scoreboard->id}}').submit(); 
                        }">
                        <x-heroicon-s-trash class="w-4 h-4" />
                    </div>
                </div>
            </div>
            <form id="delete-scoreboard-{{$scoreboard->id}}" action="{{ route('scoreboards.destroy', $scoreboard->id) }}" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        @endforeach
    </div>
@endsection