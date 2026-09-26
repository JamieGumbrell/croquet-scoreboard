@extends('layouts.main')

@section('body')
    <div class="md-container">
        <div class="page-header">
            <h2>Countries</h2>
            <a class="btn default-primary-color text-primary-color" href="{{ route('countries.create') }}">Add country</a>
        </div>

        @if (session('success'))
            <p class="success-message">{{ session('success') }}</p>
        @endif

        <table class="countries-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Link</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($countries as $country)
                    <tr>
                        <td>{{ $country->name }}</td>
                        <td>
                            @if ($country->link)
                                <a href="{{ str_starts_with($country->link, 'countries/') ? \Illuminate\Support\Facades\Storage::disk('public')->url($country->link) : $country->link }}" target="_blank" rel="noreferrer">View image</a>
                            @endif
                        </td>
                        <td>
                            @if ($country->link)
                                <img src="{{ str_starts_with($country->link, 'countries/') ? \Illuminate\Support\Facades\Storage::disk('public')->url($country->link) : $country->link }}" alt="{{ $country->name }}" style="max-width: 80px; max-height: 50px;">
                            @endif
                        </td>
                        <td class="table-actions">
                            <a href="{{ route('countries.edit', $country) }}">Edit</a>
                            <form method="POST" action="{{ route('countries.destroy', $country) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No countries have been added.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $countries->links() }}
    </div>
@endsection