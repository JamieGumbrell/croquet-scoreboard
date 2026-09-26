@vite(['resources/css/form.css'])
<div class="md-container">
    <form class="grid" method="POST" action="{{ route('scoreboards.update', $scoreboard) }}">
        @csrf
        @method('PUT')

        <h2 class="grid-1">Event Name</h2>
        <input class="input-field grid-2" type="text" name="title" value="{{ old('title', $scoreboard->title) }}">

        <h2 class="grid-17">Colour</h2>
        <input class="input-field grid-4" type="color" name="color" value="{{ old('color', $scoreboard->color) }}">

        <h2 class="grid-5">Ball Colour</h2>
        <select class="input-field grid-6" id="ball-color" name="ball_color">
            <option value="0" @selected(old('ball_color', $scoreboard->ball_color) === 0)>--Hidden--</option>
            <option value="primary" @selected(old('ball_color', $scoreboard->ball_color) === 'primary')>Primary</option>
            <option value="secondary" @selected(old('ball_color', $scoreboard->ball_color) === 'secondary')>Secondary</option>
        </select>

        <h2 class="grid-18">Scoreboard Design</h2>
        <select class="input-field grid-19" id="design" name="design">
            <option value="default" @selected(old('design', $scoreboard->design) === 'default')>Default</option>
            <option value="simple_light" @selected(old('design', $scoreboard->design) === 'simple_light')>Simple Light</option>
            <option value="simple_dark" @selected(old('design', $scoreboard->design) === 'simple_dark')>Simple Dark</option>
        </select>
        
        <h2 class="grid-20">Width</h2>
        <input class="input-field grid-21" type="text" name="width" value="{{ old('width', $scoreboard->width) }}">
        
        <h2 class="grid-10">Scoreboard Type</h2>
        <select class="input-field grid-7" id="scoretype" name="scoretype">
            <option value="single" @selected(old('scoretype', $scoreboard->scoretype) === 'single')>AC - Scores per ball</option>
            <option value="combined" @selected(old('scoretype', $scoreboard->scoretype) === 'combined')>GC - Combined Score</option>
            <option value="gateball" @selected(old('scoretype', $scoreboard->scoretype) === 'gateball')>Gateball</option>
        </select>

        <h2 class="grid-9">Game Score Type</h2>
        <select class="input-field grid-8" id="gametype" name="gametype">
            <option value="-1" @selected(old('gametype', $scoreboard->gametype) === '-1')>--Hidden--</option>
            <option value="0" @selected(old('gametype', $scoreboard->gametype) === '0')>Display Game Wins</option>
            <option value="1" @selected(old('gametype', $scoreboard->gametype) === '1')>Display Game Scores</option>
        </select>


        <div id="btn-update" class="grid-13">
            <button class="btn success-color text-primary-color" type="submit">Save</button>
        </div>
    </form>
</div>