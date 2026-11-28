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
                    <div><span class="ic" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 2a6 6 0 0 0-6 6c0 4.5 6 12 6 12s6-7.5 6-12a6 6 0 0 0-6-6zm0 8.2a2.2 2.2 0 1 1 0-4.4 2.2 2.2 0 0 1 0 4.4z"/></svg></span><b>Lokasi</b>Kanigoro, Tirtomoyo, Kec. Pakis, Kabupaten Malang, Jawa Timur 65154</div>
                    <div><span class="ic" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M3 5h18v14H3zm2 2v.5l7 5 7-5V7l-7 5z"/></svg></span><b>Email Kami</b>forresttirtomoyo@gmail.com</div>
                    <div><span class="ic" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M6.6 10.8a15.1 15.1 0 0 0 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1A17 17 0 0 1 3 4c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1z"/></svg></span><b>Telepon</b>+62 812 1386 5343</div>
                    <div><span class="ic" aria-hidden="true"><svg viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2a10 10 0 1 1 0 20 10 10 0 0 1 0-20zm1 4.5h-2v6.6l4.8 2.9 1-1.7-3.8-2.3z"/></svg></span><b>Buka Setiap Hari</b>08.00 - 17.00 WIB</div>
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
                <a class="btn wa" href="https://wa.me/6281213865343"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.7 14.9L2 22l5.3-1.3A10 10 0 1 0 12 2zm4.6 13.6c-.2.6-1.2 1.1-1.7 1.2-.4.1-1 .1-1.6-.1-3-1-4.9-3.9-5.1-4.1-.1-.2-1.2-1.6-1.2-3s.7-2.1 1-2.4c.3-.3.6-.3.8-.3h.6c.2 0 .4 0 .6.5l.8 2c.1.2.1.4 0 .5l-.4.6c-.1.2-.3.3-.1.6.1.2.6 1 1.3 1.6.9.8 1.6 1 1.9 1.1.2.1.4.1.5-.1l.7-.9c.2-.2.4-.2.6-.1l1.8.9c.3.1.4.2.5.3 0 .2 0 .8-.2 1.3z"/></svg>Tanya Via WhatsApp</a>
            </form>
        </div>
    </section>
@endsection
