@extends('layouts.main')

@section('body')
    <div class="md-container">
        <h2>Edit Player</h2>
        @include('players.form', ['player' => $player, 'formAction' => route('players.update', $player), 'formMethod' => 'PUT'])
    </div>
@endsection
