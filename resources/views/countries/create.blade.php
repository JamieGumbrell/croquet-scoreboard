@extends('layouts.main')

@section('body')
    <div class="md-container">
        <h2>Add Country</h2>
        @include('countries.form', ['country' => null, 'formAction' => route('countries.store'), 'formMethod' => 'POST'])
    </div>
@endsection
