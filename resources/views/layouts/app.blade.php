<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Inventory') · Pickleball Inventory</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="shell">

    {{-- Sidebar: navigation + signed-in user --}}
    <aside class="sidebar">
        <a class="brand" href="{{ route('equipment.index') }}">
            @include('layouts._ball')
            <span>Pickleball<small>Equipment inventory</small></span>
        </a>

        <nav class="nav" aria-label="Main">
            <a href="{{ route('equipment.index') }}" class="{{ request()->routeIs('equipment.*') ? 'is-active' : '' }}">
                <x-icon name="box" /> Equipment
            </a>
            <a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.*') ? 'is-active' : '' }}">
                <x-icon name="report" /> Reports
            </a>
        </nav>

        <div class="account">
            <strong>{{ auth()->user()->name }}</strong>
            <span>&#64;{{ auth()->user()->username }}</span>
            {{-- Logout is a POST with a CSRF token, never a plain link. --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-quiet"><x-icon name="logout" /> Log out</button>
            </form>
        </div>
    </aside>

    <main class="main">
        <header class="page-head">
            <div>
                <h1>@yield('heading')</h1>
                @hasSection('subheading')
                    <p>@yield('subheading')</p>
                @endif
            </div>
            @yield('actions')
        </header>

        @if (session('status'))
            <div class="flash" role="status">
                <x-icon name="check" /> <span>{{ session('status') }}</span>
            </div>
        @endif

        @yield('content')
    </main>
</div>

{{-- One shared confirmation dialog for every delete button on the page. --}}
<dialog id="delete-dialog" class="dialog" aria-labelledby="delete-title">
    <form method="POST" id="delete-form">
        @csrf
        @method('DELETE')
        <h2 id="delete-title">Delete this equipment?</h2>
        <p><strong id="delete-name"></strong> will be removed from the inventory and from all reports. This can't be undone.</p>
        <div class="dialog-actions">
            <button type="button" class="btn" id="delete-cancel">Cancel</button>
            <button type="submit" class="btn btn-danger">Delete</button>
        </div>
    </form>
</dialog>

<script>
    // Any element with [data-delete] opens the dialog and points its form at that record's URL.
    (function () {
        var dialog = document.getElementById('delete-dialog');
        var form = document.getElementById('delete-form');
        var name = document.getElementById('delete-name');

        document.addEventListener('click', function (e) {
            var trigger = e.target.closest('[data-delete]');
            if (!trigger) return;
            form.action = trigger.getAttribute('data-action');
            name.textContent = trigger.getAttribute('data-name'); // textContent: no HTML injection
            dialog.showModal();
        });

        document.getElementById('delete-cancel').addEventListener('click', function () { dialog.close(); });
    })();
</script>
</body>
</html>
