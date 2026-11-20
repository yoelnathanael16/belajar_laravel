@extends('pawon.layouts.app')

@section('title', 'Tentang Kami')
@section('page', 'tentang-kami.html')
@section('script', 'about')

@section('content')
    <section class="wrap">
        <div class="about">
            <div>
                <img src="{{ Vite::asset('resources/images/pawon/about.jpg') }}" alt="Sajian prasmanan">
                <div class="stats">
                    <div><b>4.7</b>Penilaian Pelanggan</div>
                    <div><b>40+</b>Menu</div>
                </div>
            </div>
            <p class="lead">Pawon berarti dapur dalam bahasa Jawa, dan di sinilah rasa Jawa yang hangat kami jaga. Pawon Ratu Roso menyajikan sayur, lauk, dan jajanan rumahan dengan bumbu yang mengingatkan Anda pada masakan nenek. Rasakan kenikmatan sederhana yang membuat rindu untuk kembali.</p>
        </div>
    </section>
@endsection
