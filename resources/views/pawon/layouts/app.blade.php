@php
    // Peta nama berkas lama -> URL rute, dibaca oleh resources/js/pawon/app.js (Pawon.url)
    $pawonRoutes = [
        'index.html' => route('home'),
        'tentang-kami.html' => route('about'),
        'menu.html' => route('menu'),
        'booking.html' => route('booking'),
        'hubungi-kami.html' => route('contact'),
        'masuk.html' => route('login'),
        'daftar.html' => route('register'),
        'profil.html' => route('profile'),
    ];
@endphp
<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title') | Pawon Ratu Roso</title>
        <link rel="icon" href="{{ Vite::asset('resources/images/pawon/logo.png') }}">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
        @vite(['resources/css/pawon.css', 'resources/js/pawon/pages/' . trim($__env->yieldContent('script')) . '.js'])
    </head>
    {{-- data-user dibaca Pawon.me() di app.js (kosong untuk tamu) --}}
    <body class="@yield('body_class')" data-page="@yield('page')" data-routes="{{ json_encode($pawonRoutes) }}"
        data-user="{{ auth()->check() ? json_encode(['name' => auth()->user()->nama, 'email' => auth()->user()->email]) : '' }}">
        @include('pawon.partials.header')
        <main>
            @yield('content')
        </main>
        @include('pawon.partials.footer')
        @isset($menu)
            <script type="application/json" id="pawon-menu">@json($menu, JSON_HEX_TAG | JSON_UNESCAPED_UNICODE)</script>
        @endisset
    </body>
</html>
