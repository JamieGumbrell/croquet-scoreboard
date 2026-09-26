@vite(['resources/css/form.css'])

<div>
    <button class="btn default-primary-color text-primary-color" onclick="window.location.href='{{ route('player_lists.show', $playerList) }}'">Cancel</button>
</div>
<form class="player-form" method="POST" action="{{ $formAction }}" enctype="multipart/form-data">
    @csrf
    @if ($formMethod !== 'POST')
        @method($formMethod)
    @endif

    @if ($errors->any())
        <div class="error-text">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif
    <input name="player_list_id" type="text" hidden readonly value="{{ $playerList->id }}">
    <div class="input-container">
        <h2>Name</h2>
        <input id="player-name" class="input-field" type="text" name="name" value="{{ old('name', $player?->name) }}" required>
    </div>
    <div class="input-container">
        <h2>Country</h2>
        <select id="player-country" class="input-field" name="country">
            <option value="" @selected(old('country', $player?->country) == '')>--Hidden--</option>
            @foreach ($countries as $country)
                <option value="{{ $country->id }}" @selected(old('country', $player?->country) == $country->id)>{{ $country->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="text-center">
        <button class="w-50 btn success-color text-primary-color" type="submit">Save</button>
    </div>
</form>
