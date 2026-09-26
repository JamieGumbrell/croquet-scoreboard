@vite(['resources/css/form.css'])

@if($scoreboard->scoretype == "gateball")
    @vite(['resources/css/gateball.css'])
@elseif($scoreboard->ball_color == "primary")
    @vite(['resources/css/primary.css'])
@elseif($scoreboard->ball_color == "secondary")
    @vite(['resources/css/secondary.css'])
@endif

@include('scoreboards.preview')

<hr/>
<div class="md-container">
    <form id="scoreboard-players-form" method="POST" action="{{ route('scoreboards.update', $scoreboard) }}">
        @csrf
        @method('PUT')
        <div class="player-select">
            <label class="type-label">Player 1</label>
            @if (!empty($players) && sizeof($players) > 0)
                <select class="player-select input-field" id="player_select_1" name="name1" data-country-target="country1">
                    <option value="" @selected(blank(old('name1', $scoreboard->name1)))>--Select a Player--</option>
                    @foreach ($players as $player)
                        <option value="{{ $player->name }}" data-country="{{ $player->country }}" @selected((string) old('name1', $scoreboard->name1) === $player->name)>{{ $player->name }}</option>
                    @endforeach
                </select>
                <input type="hidden" id="country1" name="country1" value="{{ old('country1', $scoreboard->country1) }}">
            @else
                <input class="player-input input-field" type="text" name="name1" value="{{ old('name1', $scoreboard->name1) }}">
                <select class="country-select input-field" id="country1" name="country1">
                    <option value="" @selected(blank(old('country1', $scoreboard->country1)))>--Hidden--</option>
                    @foreach ($countries->all() as $country)
                        <option value="{{ $country->id }}" @selected((string) old('country1', $scoreboard->country1) === (string) $country->id)>{{ $country->name }}</option>
                    @endforeach
                </select>
            @endif
        </div>

        <div class="player-select">
            <label class="type-label">Player 2</label>
            @if (!empty($players) && sizeof($players) > 0)
                <select class="player-select input-field" id="player_select_2" name="name2" data-country-target="country2">
                    <option value="" @selected(blank(old('name2', $scoreboard->name2)))>--Select a Player--</option>
                    @foreach ($players as $player)
                        <option value="{{ $player->name }}" data-country="{{ $player->country }}" @selected((string) old('name2', $scoreboard->name2) === $player->name)>{{ $player->name }}</option>
                    @endforeach
                </select>
                <input type="hidden" id="country2" name="country2" value="{{ old('country2', $scoreboard->country2) }}">
            @else
                <input class="player-input input-field" type="text" name="name2" value="{{ old('name2', $scoreboard->name2) }}">
                <select class="country-select input-field" id="country2" name="country2">
                    <option value="" @selected(blank(old('country2', $scoreboard->country2)))>--Hidden--</option>
                    @foreach ($countries->all() as $country)
                        <option value="{{ $country->id }}" @selected((string) old('country2', $scoreboard->country2) === (string) $country->id)>{{ $country->name }}</option>
                    @endforeach
                </select>
            @endif
        </div>

        <div id="btn-switch"><button id="swap-players" type="button" class="btn default-primary-color text-primary-color"><x-heroicon-s-arrows-right-left class="swap-icon" /> Swap players</button></div>
    </form>

    <form id="scoreboard-scores-form" method="POST" action="{{ route('scoreboards.update', $scoreboard) }}">
        @csrf
        @method('PUT')
        <div class="control-panel" @if($scoreboard->savegames == 0) style="flex-direction: column-reverse;" @endif>
            @if($scoreboard->savegames == 0 && $scoreboard->scoretype != "gateball")
                <div class="games">
                    <h2>Games</h2>
                    <div class="games-1">
                        <button type="button" class="btn default-primary-color text-primary-color" data-score-action="decrement">-</button>
                        <p class="label" id="games-1">{{ $scoreboard->games1 }}</p>
                        <button type="button" class="btn default-primary-color text-primary-color" data-score-action="increment">+</button>
                    </div>
                    <div class="games-2">
                        <button type="button" class="btn default-primary-color text-primary-color" data-score-action="decrement">-</button>
                        <p class="label" id="games-2">{{ $scoreboard->games2 }}</p>
                        <button type="button" class="btn default-primary-color text-primary-color" data-score-action="increment">+</button>
                    </div>
                    <div class="reset">
                        <button type="button" class="btn warning-color primary-text-color" data-score-action="reset">Reset</button>
                    </div>
                </div> 
            @endif 
            <div class="score">
                <h2>Score</h2>
                <div class="score-1">
                            <button type="button" class="btn default-primary-color text-primary-color" data-score-action="decrement">-</button>
                    <p class="label" id="score-1">{{ $scoreboard->score1 }}</p>
                            <button type="button" class="btn default-primary-color text-primary-color" data-score-action="increment">+</button>
                </div>
                <div class="score-2">
                            <button type="button" class="btn default-primary-color text-primary-color" data-score-action="decrement">-</button>
                    <p class="label" id="score-2">{{ $scoreboard->score2 }}</p>
                            <button type="button" class="btn default-primary-color text-primary-color" data-score-action="increment">+</button>
                </div>
                @if($scoreboard->scoretype=="single" || $scoreboard->scoretype=="gateball")
                    <div class="score-3">
                        <button type="button" class="btn default-primary-color text-primary-color" data-score-action="decrement">-</button>
                        <p class="label" id="score-3">{{ $scoreboard->score3 }}</p>
                        <button type="button" class="btn default-primary-color text-primary-color" data-score-action="increment">+</button>
                    </div>
                    <div class="score-4">
                        <button type="button" class="btn default-primary-color text-primary-color" data-score-action="decrement">-</button>
                        <p class="label" id="score-4">{{ $scoreboard->score4 }}</p>
                        <button type="button" class="btn default-primary-color text-primary-color" data-score-action="increment">+</button>
                    </div>
                @endif
                @if($scoreboard->scoretype=="gateball")
                    @for($i = 5; $i <= 10; $i++)
                        <div class="score-{{$i}}">
                            <button type="button" class="btn default-primary-color text-primary-color" data-score-action="decrement">-</button>
                            <p class="label" id="score-{{$i}}">{{ $scoreboard->{"score$i"} }}</p>
                            <button type="button" class="btn default-primary-color text-primary-color" data-score-action="increment">+</button>
                        </div>
                    @endfor
                @endif
                <div class="reset">
                    <button type="button" class="btn warning-color primary-text-color" data-score-action="reset">Reset</button>
                </div>
            </div>
        </div>
    </form>
</div>