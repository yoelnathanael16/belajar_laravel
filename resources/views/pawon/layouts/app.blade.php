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
        <title>@yield('title') | Pawon Ratu Roso</title>
        <link rel="icon" href="{{ Vite::asset('resources/images/pawon/logo.png') }}">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
        @vite(['resources/css/pawon.css', 'resources/js/pawon/pages/' . trim($__env->yieldContent('script')) . '.js'])
    </head>
    <body class="@yield('body_class')" data-page="@yield('page')" data-routes="{{ json_encode($pawonRoutes) }}">
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
