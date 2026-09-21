<div class="title-panel" style="background-color: {{ $scoreboard->color }}; color:white;">
    <div class="event-name"><h1>{{ $scoreboard->title }}</h1></div>
    @if($scoreboard->savegames >= 0)
        <div class="games-title"><h2>Games</h2></div>
    @endif
    <div class="score-title {{ $scoreboard->scoretype == 'single' ? 'span-2' : '' }}"><h2>Score</h2></div>
</div>
<div class="player1-panel">
    <div class="display-panel">
        <div class="player-name">
            @if($country1?->image_url)
                <div class="player-flag"><img src="{{ $country1->image_url }}" alt="{{ $country1->name }}"></div>
            @endif
            <h3>{{ $scoreboard->name1 }}</h3>
        </div>
        @if($scoreboard->scoretype == "combined")
            @if($scoreboard->ball_color == "primary")
                <div class="ball-b"><div class="ball"></div></div>
                <div class="ball-k"><div class="ball"></div></div>
            @elseif($scoreboard->ball_color == "secondary")
                <div class="ball-g"><div class="ball"></div></div>
                <div class="ball-n"><div class="ball"></div></div>
            @endif
        @endif
    </div>
    <div class="score-panel">
        @if($scoreboard->savegames == 1)
            @if(sizeof($data['games']) > 0)
                @foreach($data['games'] as $g)
                    <div class="player-games score-narrow"><h4>{{ $g->score1 }}</h4></div>
                @endforeach
            @else
                <div class="player-games score-narrow"><h4>0</h4></div>
            @endif
        @elseif($scoreboard->savegames == 0)
            <div class="player-games"><h4>{{ $scoreboard->games1 }}</h4></div>
        @endif
        @if($scoreboard->scoretype == "single")
            <div class="player-score {{ $scoreboard->ball_color == 'primary' ? 'square-b' : 'square-g' }}"><h4>{{ $scoreboard->score1 }}</h4></div>
            <div class="player-score {{ $scoreboard->ball_color == 'primary' ? 'square-k' : 'square-n' }}"><h4>{{ $scoreboard->score3 }}</h4></div>
        @else
            <div class="player-score"><h4>{{ $scoreboard->score1 }}</h4></div>
        @endif
    </div>
</div>
<div class="player2-panel">
    <div class="display-panel">
        <div class="player-name">
            @if($country2?->image_url)
                <div class="player-flag"><img src="{{ $country2->image_url }}" alt="{{ $country2->name }}"></div>
            @endif
            <h3>{{ $scoreboard->name2 }}</h3>
        </div>
        @if($scoreboard->scoretype == "combined")
            @if($scoreboard->ball_color == "primary")
                <div class="ball-r"><div class="ball"></div></div>
                <div class="ball-y"><div class="ball"></div></div>
            @elseif($scoreboard->ball_color == "secondary")
                <div class="ball-p"><div class="ball"></div></div>
                <div class="ball-w"><div class="ball"></div></div>
            @endif
        @endif
    </div>
    <div class="score-panel">
        @if($scoreboard->savegames == 1)
            @if(sizeof($data['games']) > 0)
                @foreach($data['games'] as $g)
                    <div class="player-games score-narrow"><h4>{{ $g->score2 }}</h4></div>
                @endforeach
            @else
                <div class="player-games score-narrow"><h4>0</h4></div>
            @endif
        @elseif($scoreboard->savegames == 0)
            <div class="player-games"><h4>{{ $scoreboard->games2 }}</h4></div>
        @endif
        @if($scoreboard->scoretype == "single")
            <div class="player-score {{ $scoreboard->ball_color == 'primary' ? 'square-r' : 'square-p' }}"><h4>{{ $scoreboard->score2 }}</h4></div>
            <div class="player-score {{ $scoreboard->ball_color == 'primary' ? 'square-y' : 'square-w' }}"><h4>{{ $scoreboard->score4 }}</h4></div>
        @else
            <div class="player-score"><h4>{{ $scoreboard->score2 }}</h4></div>
        @endif
    </div>
</div>