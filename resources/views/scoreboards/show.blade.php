@extends('layouts.main')
@vite('resources/css/scoreboard.css')

@section('body')
    <div
        id="scoreboard-app"
        data-scoreboard='@json($scoreboard)'
    ></div>

    <section id="scoreboard-details-panel">
        @include('scoreboards.details', ['scoreboard' => $scoreboard])
    </section>

    <section id="scoreboard-scores-panel">
        @include('scoreboards.scores', ['scoreboard' => $scoreboard])
    </section>

    <section id="scoreboard-preview-panel">
        @include('scoreboards.preview', ['scoreboard' => $scoreboard])
    </section>

    @vite('resources/js/scoreboard-show.ts')
@endsection