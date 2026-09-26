@extends('layouts.main')
@vite('resources/css/scoreboard.css')

@section('body')
    <div
        id="scoreboard-app"
        data-scoreboard='@json($scoreboard)'
        data-preview-url="{{ route('scoreboards.preview', $scoreboard) }}"
    ></div>

    <section id="scoreboard-details-panel">
        @include('scoreboards.details', ['scoreboard' => $scoreboard])
    </section>

    <section id="scoreboard-scores-panel">
        @include('scoreboards.scores', ['scoreboard' => $scoreboard])
    </section>

    <section id="scoreboard-preview-panel">
        <div class="md-container">
            <div class="flex">
                <div class="flex-right">
                    <button
                        type="button"
                        class="btn success-color text-primary-color"
                        data-copy-preview-link="{{ route('api.preview', ['scoreboard' => $scoreboard->uid]) }}"
                    >Copy Link</button>
                </div>
            </div>
        </div>
        @include('scoreboards.preview', ['scoreboard' => $scoreboard])
    </section>

    @vite('resources/js/scoreboard-show.ts')
@endsection