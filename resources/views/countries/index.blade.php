@extends('layouts.main')
@vite(['resources/css/countries.css'])

@section('body')
    <div class="md-container">
            <h2>Countries</h2>
            <div class="flex">
                <div class="flex-right">
                    <button class="btn success-color text-primary-color" onclick="window.location.href='{{ route('countries.create') }}'">Add country</button>
                </div>
            </div>

        @if (session('success'))
            <p class="success-message">{{ session('success') }}</p>
        @endif
        
        @forelse ($countries as $country)
            <div class="countries-container">
                <div class="countries-content">
                    <img src="{{ str_starts_with($country->link, 'countries/') ? \Illuminate\Support\Facades\Storage::disk('public')->url($country->link) : $country->link }}" alt="{{ $country->name }}" style="max-width: 160px; max-height: 100px;">
                    <h3>{{ $country->name }}</h3>
                </div>
                <div class="countries-settings">
                    <div class="edit" onclick="window.location.href='{{ route('countries.edit', $country) }}';">
                        <x-heroicon-s-pencil-square />
                    </div>
                    <div class="delete" onclick="
                        event.preventDefault();
                        if(confirm('Are you sure you want to delete this country?')) { 
                            document.getElementById('delete-country-{{$country->id}}').submit(); 
                        }">
                        <x-heroicon-s-trash />
                    </div>
                </div>
            </div>
            <form id="delete-country-{{$country->id}}" method="POST" action="{{ route('countries.destroy', $country) }}">
                @csrf
                @method('DELETE')
            </form>
        @empty
            <tr>
                <td colspan="4">No countries have been added.</td>
            </tr>
        @endforelse
    </div>
@endsection