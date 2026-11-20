<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log in · Pickleball Inventory</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="auth">

    <section class="auth-aside">
        <a class="brand" href="{{ route('login') }}">
            @include('layouts._ball')
            <span>Pickleball<small>Equipment inventory</small></span>
        </a>

        <div>
            <h1>Know what's on the shelf.</h1>
            <p>Paddles, balls, nets and accessories, with prices and availability in one place.</p>
        </div>

        {{-- Court markings: baseline, kitchen lines, centre service lines and the net. --}}
        <svg class="court" viewBox="0 0 400 200" fill="none" stroke="rgba(255,255,255,.4)" stroke-width="1.5" aria-hidden="true">
            <rect x="20" y="20" width="360" height="160"/>
            <line x1="143" y1="20" x2="143" y2="180"/>
            <line x1="257" y1="20" x2="257" y2="180"/>
            <line x1="20" y1="100" x2="143" y2="100"/>
            <line x1="257" y1="100" x2="380" y2="100"/>
            <line x1="200" y1="12" x2="200" y2="188" stroke="#d6f03c" stroke-width="2"/>
        </svg>
    </section>

    <section class="auth-main">
        <form class="auth-form" method="POST" action="{{ route('login.store') }}" novalidate>
            @csrf

            <h2>Log in</h2>

            <div class="field">
                <label for="login">Email or username</label>
                <input type="text" id="login" name="login" value="{{ old('login') }}"
                       autocomplete="username" autofocus
                       class="{{ $errors->has('login') ? 'is-invalid' : '' }}">
                @error('login') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" autocomplete="current-password"
                       class="{{ $errors->has('password') ? 'is-invalid' : '' }}">
                @error('password') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <label class="check">
                <input type="checkbox" name="remember" value="1" @checked(old('remember'))>
                Keep me signed in
            </label>

            <button type="submit" class="btn btn-primary btn-block">Log in</button>
        </form>
    </section>

</div>
</body>
</html>
