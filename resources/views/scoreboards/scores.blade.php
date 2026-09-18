<div class="md-container">
    <form method="POST" action="{{ route('scoreboards.update', $scoreboard) }}">
        @csrf
        @method('PUT')
        <label class="type-label">Player 1</label>
        <input class="player-input input-field" type="text" name="name1" value=" old('name1', $scoreboard->name1">
        
        <select class="country-select input-field" id="country1" name="country1">
            <option value="hid" @selected(old('country1', $scoreboard->country1) === 'hid')>--Hidden--</option>
            @foreach ($countries->all() as $country)
                <option value="{{$country->code}}" @selected(old('country1', $scoreboard->country1) === '{{$country->code}}')>{{@country->name}}</option>
            @endforeach
        </select>

        <label class="type-label">Player 2</label>
        <input class="player-input input-field" type="text" name="name2" value=" old('name2', $scoreboard->name2">
        
        <select class="country-select input-field" id="country1" name="country2">
            <option value="hid" @selected(old('country2', $scoreboard->country2) === 'hid')>--Hidden--</option>
            @foreach ($countries->all() as $country)
                <option value="{{$country->code}}" @selected(old('country2', $scoreboard->country2) === '{{$country->code}}')>{{@country->name}}</option>
            @endforeach
        </select>
    </form>
</div>