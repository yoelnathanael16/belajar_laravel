@extends('admin.layout')

@section('title', 'Kelola Menu')

@php
    $ikonFoto = '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="9" cy="10" r="2"/><path d="M21 17l-5-5-9 8"/></svg>';
@endphp

@section('content')
    <div class="head">
        <div>
            <h1>Kelola Menu</h1>
            <p class="sub">Atur harga dan ketersediaan menu yang tampil di halaman Menu dan Booking.</p>
        </div>
        <div class="acts">
            <button class="btn ghost" type="button" onclick="document.getElementById('tambah').showModal()">+ Tambah Menu Baru</button>
            <button class="btn" type="submit" form="semuaMenu">Simpan Perubahan</button>
        </div>
    </div>

    <form id="semuaMenu" class="card tablecard menucard" method="POST" action="{{ route('admin.menus.update') }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="scroll">
            <table>
                <thead>
                    <tr><th>Foto</th><th>Nama Menu</th><th>Kategori</th><th>Keterangan</th><th>Harga (Rp)</th><th>Tersedia</th></tr>
                </thead>
                <tbody>
                    @foreach ($menus as $m)
                        <tr>
                            <td>
                                <div class="foto">
                                    @if ($m->foto_url)
                                        <img class="thumb" src="{{ $m->foto_url }}" alt="">
                                    @else
                                        <span class="thumb empty">{!! $ikonFoto !!}</span>
                                    @endif
                                    <label class="uplink">
                                        {{-- nama file yang dipilih tampil di sini sampai disimpan --}}
                                        <span>{{ $m->foto ? 'Edit' : 'Unggah' }}</span>
                                        <input type="file" name="foto[{{ $m->id }}]" accept="image/jpeg,image/png,image/webp"
                                               onchange="this.previousElementSibling.textContent=this.files[0]?.name||'{{ $m->foto ? 'Edit' : 'Unggah' }}'">
                                    </label>
                                </div>
                            </td>
                            <td><b>{{ $m->nama_menu }}</b></td>
                            <td>{{ $m->kategori_label }}</td>
                            <td>
                                <input class="cell" name="menus[{{ $m->id }}][keterangan]" value="{{ old("menus.{$m->id}.keterangan", $m->keterangan) }}" aria-label="Keterangan {{ $m->nama_menu }}">
                            </td>
                            <td>
                                <input class="cell money" type="number" min="0" step="500" name="menus[{{ $m->id }}][harga]"
                                       value="{{ old("menus.{$m->id}.harga", $m->harga) }}" aria-label="Harga {{ $m->nama_menu }}">
                            </td>
                            <td>
                                {{-- hidden 0 dikirim saat tombol tersedia dimatikan --}}
                                <input type="hidden" name="menus[{{ $m->id }}][tersedia]" value="0">
                                <input class="switch" type="checkbox" role="switch" name="menus[{{ $m->id }}][tersedia]" value="1"
                                       @checked(old("menus.{$m->id}.tersedia", $m->tersedia)) aria-label="{{ $m->nama_menu }} tersedia">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </form>

    <dialog id="tambah" aria-labelledby="judulTambah" @if ($errors->hasAny(['nama_menu', 'kategori', 'harga', 'foto'])) open @endif>
        <form method="POST" action="{{ route('admin.menus.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="head" style="align-items:center;margin-bottom:18px">
                <h2 id="judulTambah">Tambah Menu Baru</h2>
                <button class="xbtn" type="button" onclick="this.closest('dialog').close()" aria-label="Tutup">✕</button>
            </div>
            <label>Nama menu <input class="field" name="nama_menu" value="{{ old('nama_menu') }}" required placeholder="Contoh: Sayur Lodeh"></label>
            <label>Keterangan <input class="field" name="keterangan" value="{{ old('keterangan') }}" placeholder="Tulis keterangan"></label>
            <div class="row2">
                <label>Kategori
                    <select class="field" name="kategori" required>
                        <option value="">Pilih kategori</option>
                        @foreach ($kategori as $nilai => $label)
                            <option value="{{ $nilai }}" @selected(old('kategori') === $nilai)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <label>Harga (Rp) <input class="field" type="number" name="harga" min="0" step="500" value="{{ old('harga') }}" required placeholder="10000"></label>
            </div>
            <label>Foto menu
                <input class="field" type="file" name="foto" accept="image/jpeg,image/png,image/webp">
                <span class="muted" style="font-weight:400">Format JPG, PNG, atau WebP. Maksimal 2 MB.</span>
            </label>
            <label class="check"><input class="switch" type="checkbox" role="switch" name="tersedia" value="1" checked> Tampilkan di halaman Menu dan Booking</label>
            <div class="end">
                <button class="btn ghost" type="button" onclick="this.closest('dialog').close()">Batal</button>
                <button class="btn" type="submit">Simpan Menu</button>
            </div>
        </form>
    </dialog>
@endsection
