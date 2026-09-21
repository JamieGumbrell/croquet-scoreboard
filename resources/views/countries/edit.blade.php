@extends('layouts.main')

@section('body')
    <div class="md-container">
        <h2>Edit Country</h2>
        @include('countries.form', ['country' => $country, 'formAction' => route('countries.update', $country), 'formMethod' => 'PUT'])
    </div>
@endsection
