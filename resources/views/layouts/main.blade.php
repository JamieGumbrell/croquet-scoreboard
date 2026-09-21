<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
	@vite(['resources/css/palette.css', 'resources/css/default.css'])
    <title>Croquet Scoreboard</title>
</head>
<body>
    <nav>
	<a href="/">Home</a>
	<div class="nav-right">
		@auth
			<a href="{{route('player_lists')}}">Player Lists</a>
			<a href="{{route('countries.index')}}">Countries</a>
			<a href="{{route('scoreboards.index')}}">Scoreboards</a>
			<a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
		@endauth
		
		@guest
			<a href="{{route('login.view')}}">Login</a>
		@endguest
	</div>
</nav>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
	@yield('body')
</body>
</html>