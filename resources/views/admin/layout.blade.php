<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>@yield('title') | Admin Pawon Ratu Roso</title>
        <link rel="icon" href="{{ Vite::asset('resources/images/pawon/logo.png') }}">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
        @vite('resources/css/admin.css')
    </head>
    <body>
        <div class="adm">
            <aside class="side">
                <div class="brand">Pawon Ratu Roso<small>Panel Admin</small></div>
                <nav>
                    <a href="{{ route('admin.dashboard') }}" @class(['on' => request()->routeIs('admin.dashboard')])>Ringkasan</a>
                    <a href="{{ route('admin.bookings.index') }}" @class(['on' => request()->routeIs('admin.bookings.*')])>
                        Pesanan
                        @if ($jumlahMenunggu)
                            <span class="badge">{{ $jumlahMenunggu }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.menus.index') }}" @class(['on' => request()->routeIs('admin.menus.*')])>Kelola Menu</a>
                </nav>
                <div class="me">
                    <b>{{ auth()->user()->nama }}</b>
                    {{ auth()->user()->email }}
                    <form method="POST" action="{{ route('logout') }}" class="stack">
                        @csrf
                        <button class="btn ghost full" type="submit" style="color:inherit;border-color:currentColor">Keluar</button>
                    </form>
                </div>
            </aside>
            <main class="main">
                @if (session('status'))
                    <p class="flash" role="status">{{ session('status') }}</p>
                @endif
                @if ($errors->any())
                    <p class="flash err" role="alert">{{ $errors->first() }}</p>
                @endif
                @yield('content')
            </main>
        </div>
    </body>
</html>
