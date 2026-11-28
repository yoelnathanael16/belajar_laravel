<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>@yield('title') | Admin Pawon Ratu Roso</title>
        <link rel="icon" href="{{ Vite::asset('resources/images/pawon/logo.png') }}">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=DM+Serif+Display&display=swap" rel="stylesheet">
        @vite('resources/css/admin.css')
    </head>
    <body>
        <div class="adm">
            <aside class="side">
                <div class="brand"><b>Pawon Ratu Roso</b><span>Panel Admin</span></div>
                <nav>
                    <a href="{{ route('admin.dashboard') }}" @class(['on' => request()->routeIs('admin.dashboard')])>
                        <span class="ic" aria-hidden="true">📊</span>Ringkasan
                    </a>
                    <a href="{{ route('admin.bookings.index') }}" @class(['on' => request()->routeIs('admin.bookings.*')])>
                        <span class="ic" aria-hidden="true">📋</span>Pesanan
                        @if ($jumlahMenunggu)
                            <span class="badge" title="{{ $jumlahMenunggu }} pesanan menunggu konfirmasi">{{ $jumlahMenunggu }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.menus.index') }}" @class(['on' => request()->routeIs('admin.menus.*')])>
                        <span class="ic" aria-hidden="true">🍽️</span>Kelola Menu
                    </a>
                </nav>
                <div class="me">
                    <b>{{ auth()->user()->nama }}</b>
                    {{ auth()->user()->email }}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn" type="submit">Keluar</button>
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
