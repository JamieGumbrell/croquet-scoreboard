@extends('layouts.main')
@vite(['resources/css/form.css'])

@section('body')
    <div class="sm-container">
        <div class="form">
            <h1>Login</h1>
            <form id="form" action="{{route('login')}}" method="post">
                @csrf    
                <div class="input-container">
                    <input type="text" name="username" placeholder="Username" class="input-field" required value="{{ old('username') }}"">
                </div>
                <div class="input-container">
                    <input type="password" name="password" placeholder="Password" class="input-field" required value=""">
                </div>
                <div class="text-center">
                    <button class="w-50 btn default-primary-color text-primary-color" type='submit'>Login</button>
                </div>
            </form>
            <p class="text-center link"><a href="{{route('register.view')}}">Register an Account</a></p>
            <p class="text-center link"><a href="{{route('forgot.view')}}">Forgot my Password</a></p>
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