@extends('pawon.layouts.app')

@section('title', 'Hubungi Kami')
@section('page', 'hubungi-kami.html')
@section('script', 'contact')

@section('content')
    <section class="wrap">
        <div class="contact">
            <div>
                <h1>Ada yang Bisa Kami Bantu?</h1>
                <p>Tanya apa saja seputar Pawon Ratu Roso, mulai dari menu sampai cara pesan meja. Kami siap menjawab.</p>
                <div class="infos">
                    <div><b>Lokasi</b>Kanigoro, Tirtomoyo, Kec. Pakis, Kabupaten Malang, Jawa Timur 65154</div>
                    <div><b>Email Kami</b>forresttirtomoyo@gmail.com</div>
                    <div><b>Telepon</b>+62 812 1386 5343</div>
                    <div><b>Buka Setiap Hari</b>08.00 - 17.00 WIB</div>
                </div>
            </div>
            <form id="contactForm" class="card">
                <label>Nama
                    <input name="n" placeholder="Nama Anda" required>
                </label>
                <label>Email
                    <input name="e" type="email" placeholder="Email Anda" required>
                </label>
                <label>Pertanyaan
                    <textarea name="m" rows="4" placeholder="Tulis Pertanyaan Anda di sini" required></textarea>
                </label>
                <button class="btn" type="submit">Kirim</button>
                <p class="ok" id="ok" role="status" hidden>Pertanyaan terkirim. Kami akan membalas lewat email Anda.</p>
                <p class="small c">Atau, chat langsung:</p>
                <a class="btn wa" href="https://wa.me/6281213865343">Tanya Via WhatsApp</a>
            </form>
        </div>
    </section>
@endsection
