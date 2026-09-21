@vite('resources/css/preview.css')
@if($scoreboard->design == "simple_light")
    @vite('resources/css/simple_lightLayout.css')
@elseif($scoreboard->design == "simple_dark")
    @vite('resources/css/simple_darkLayout.css')
@endif

@if(false)
<style>
    .bg-scoreboard {
        width: {{ $scoreboard->width }}px;
    }
</style>
@endif

@php
    $country1 = $countries->find($scoreboard->country1);
    $country2 = $countries->find($scoreboard->country2);
@endphp

<div class="md-container">
    <div id="scoreboard-content" class="bg-scoreboard">
        @if($scoreboard->scoretype == "gateball")
            @include('scoreboards.gateball_preview')
        @else
            @include('scoreboards.croquet_preview')
        @endif
    </div>
</div>