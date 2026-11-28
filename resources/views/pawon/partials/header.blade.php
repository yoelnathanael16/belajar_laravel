<header class="nav">
    <a class="brand" href="{{ route('home') }}">
        <img src="{{ Vite::asset('resources/images/pawon/logo.png') }}" alt="Logo Pawon Ratu Roso">
        <span>Pawon Ratu Roso</span>
    </a>
    <button class="burger" aria-label="Buka menu" aria-expanded="false">&#9776;</button>
    <nav id="mainnav">
        <a href="{{ route('home') }}" @class(['on' => request()->routeIs('home')])>Beranda</a>
        <a href="{{ route('about') }}" @class(['on' => request()->routeIs('about')])>Tentang Kami</a>
        <a href="{{ route('menu') }}" @class(['on' => request()->routeIs('menu')])>Menu</a>
        <a href="{{ route('booking') }}" @class(['on' => request()->routeIs('booking')])>Booking</a>
        <a href="{{ route('contact') }}" @class(['on' => request()->routeIs('contact')])>Hubungi Kami</a>
        <span id="authSlot">
            @auth
                <a href="{{ route('profile') }}" @class(['prof-l', 'on' => request()->routeIs('profile')])><i></i>Profil</a>
            @elseif (request()->routeIs('login'))
                <a class="btn-nav out" href="{{ route('login') }}">Masuk</a>
            @else
                <a @class(['btn-nav', 'out' => request()->routeIs('register')]) href="{{ route('register') }}">Daftar</a>
            @endauth
        </span>
    </nav>
</header>
