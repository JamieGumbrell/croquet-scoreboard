@extends('layouts.main')
@vite(['resources/css/form.css'])

@section('body')
    <div class="md-container">
        <h2>Edit PlayerList</h2>

        <div>
            <button class="btn default-primary-color text-primary-color" onclick="window.location.href='{{ route('player_lists.index') }}'">Cancel</button>
        </div>
        <form class="country-form" method="POST" action="{{route('player_lists.update', $playerList)}}" enctype="multipart/form-data">
            @csrf
            @method("PUT")

            @if ($errors->any())
                <div class="error-text">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif
            <div class="input-container">
                <h2>Name</h2>
                <input id="player-list-name" class="input-field" type="text" name="name" value="{{ old('name', $playerList?->name) }}" required>
            </div>

            <div class="text-center">
                <button class="w-50 btn success-color text-primary-color" type="submit">Save</button>
            </div>
        </form>

    </div>
@endsection
