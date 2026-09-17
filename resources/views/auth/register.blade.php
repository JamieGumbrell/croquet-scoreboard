@extends('layouts.main')
@vite(['resources/css/form.css'])

@section('body')
    <div class="sm-container">
        <div class="form">
            <h1>Register</h1>
            <form id="form" action="{{route('register')}}" method="post">
                <div class="input-container">
                    <input type="text" name="username" placeholder="Username" class="input-field" value="{{old('username')}}">
                </div>
                <div class="input-container">
                    <input type="text" name="email" placeholder="Email" class="input-field" value="{{old('email')}}">
                </div>
                <div class="input-container">
                    <input type="password" name="password" placeholder="Password" class="input-field" value="">
                </div>
                <div class="input-container">
                    <input type="password" name="password_confirmation" placeholder="Confirm Password" class="input-field" value="">
                </div>

                <div class="text-center">
                    <button class="w-50 btn default-primary-color text-primary-color" type='submit'>Register</button>
                </div>
            </form>
            <p class="text-center link"><a href="{{route('login')}}">Login</a></p>
            @if ($errors->any())
                <div style="color: red; margin-bottom: 15px;">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
@endsection