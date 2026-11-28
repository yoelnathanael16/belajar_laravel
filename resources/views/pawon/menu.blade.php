@extends('pawon.layouts.app')

@section('title', 'Menu')
@section('page', 'menu.html')
@section('script', 'menu')

@section('content')
    <section class="wrap wide">
        <div class="menutools">
            <input id="q" type="search" placeholder="Cari menu, mis. ayam, kopi, pepes" aria-label="Cari menu">
            <span id="count"></span>
        </div>
        <div id="menuRoot"></div>
    </section>
@endsection
