@extends('layouts.main')

@section('body')
    <div class="md-container">
        <h2>Create Player</h2>
        @include('players.form', ['player' => null, 'formAction' => route('players.store'), 'formMethod' => 'POST'])
        </div>
@endsection