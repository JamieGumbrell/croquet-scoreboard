@vite(['resources/css/form.css'])

<div>
    <button class="btn default-primary-color text-primary-color" onclick="window.location.href='{{ route('countries.index') }}'">Cancel</button>
</div>
<form class="country-form" method="POST" action="{{ $formAction }}" enctype="multipart/form-data">
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
 <div class="input-container">
    <h2>Name</h2>
    <input id="country-name" class="input-field" type="text" name="name" value="{{ old('name', $country?->name) }}" required>
</div>
 <div class="input-container">
    <h2>Image</h2>
    <input id="country-image" class="input-field" type="file" name="image" accept="image/*">
</div>
    @if ($country?->link)
        <div class="input-container">
            <h2>Current image</h2>
            <div class="input-field"><img src="{{ str_starts_with($country->link, 'countries/') ? \Illuminate\Support\Facades\Storage::disk('public')->url($country->link) : $country->link }}" alt="{{ $country->name }}" style="max-width: 160px; max-height: 100px;"></div>
        </div>
    @endif

    <div class="text-center">
        <button class="w-50 btn success-color text-primary-color" type="submit">Save</button>
    </div>
</form>
