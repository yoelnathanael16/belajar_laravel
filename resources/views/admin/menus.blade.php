@extends('admin.layout')

@section('title', 'Kelola Menu')

@section('content')
    <div class="head">
        <div>
            <h1>Kelola Menu</h1>
            <p class="small">Atur harga dan ketersediaan menu yang tampil di halaman Menu dan Booking.</p>
        </div>
        <div>
            <button class="btn ghost" type="button" onclick="document.getElementById('tambah').showModal()">+ Tambah Menu Baru</button>
            <button class="btn" type="submit" form="semuaMenu">Simpan Perubahan</button>
        </div>
    </div>

    <form id="semuaMenu" class="card" method="POST" action="{{ route('admin.menus.update') }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <table>
            <thead>
                <tr><th>Foto</th><th>Nama menu</th><th>Kategori</th><th>Keterangan</th><th>Harga (Rp)</th><th>Tersedia</th></tr>
            </thead>
            <tbody>
                @foreach ($menus as $m)
                    <tr>
                        <td>
                            @if ($m->foto_url)
                                <img class="thumb" src="{{ $m->foto_url }}" alt="">
                            @else
                                <span class="thumb"></span>
                            @endif
                            <label class="small">{{ $m->foto ? 'Ganti' : 'Unggah' }}
                                <input type="file" name="foto[{{ $m->id }}]" accept="image/jpeg,image/png,image/webp" style="width:120px">
                            </label>
                        </td>
                        <td><b>{{ $m->nama_menu }}</b></td>
                        <td>{{ $m->kategori_label }}</td>
                        <td>
                            <input name="menus[{{ $m->id }}][keterangan]" value="{{ old("menus.{$m->id}.keterangan", $m->keterangan) }}" aria-label="Keterangan {{ $m->nama_menu }}">
                        </td>
                        <td>
                            <input class="money" type="number" min="0" step="500" name="menus[{{ $m->id }}][harga]"
                                   value="{{ old("menus.{$m->id}.harga", $m->harga) }}" aria-label="Harga {{ $m->nama_menu }}">
                        </td>
                        <td>
                            {{-- hidden 0 dikirim saat checkbox tidak dicentang --}}
                            <input type="hidden" name="menus[{{ $m->id }}][tersedia]" value="0">
                            <input type="checkbox" name="menus[{{ $m->id }}][tersedia]" value="1" style="width:auto"
                                   @checked(old("menus.{$m->id}.tersedia", $m->tersedia)) aria-label="{{ $m->nama_menu }} tersedia">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </form>

    <dialog id="tambah" @if ($errors->hasAny(['nama_menu', 'kategori', 'harga'])) open @endif>
        <form method="POST" action="{{ route('admin.menus.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="head">
                <h2>Tambah Menu Baru</h2>
                <button class="btn ghost" type="button" onclick="this.closest('dialog').close()" aria-label="Tutup">✕</button>
            </div>
            <label>Nama menu <input name="nama_menu" value="{{ old('nama_menu') }}" required placeholder="Contoh: Sayur Lodeh"></label>
            <label>Keterangan <input name="keterangan" value="{{ old('keterangan') }}" placeholder="Tulis keterangan"></label>
            <label>Kategori
                <select name="kategori" required>
                    <option value="">Pilih kategori</option>
                    @foreach ($kategori as $nilai => $label)
                        <option value="{{ $nilai }}" @selected(old('kategori') === $nilai)>{{ $label }}</option>
                    @endforeach
                </select>
            </label>
            <label>Harga (Rp) <input type="number" name="harga" min="0" step="500" value="{{ old('harga') }}" required placeholder="10000"></label>
            <label>Foto <input type="file" name="foto" accept="image/jpeg,image/png,image/webp"><span class="small">JPG, PNG, atau WebP. Maksimal 2 MB.</span></label>
            <label><input type="checkbox" name="tersedia" value="1" checked style="width:auto"> Langsung tampil di halaman Menu dan Booking</label>
            <div class="head" style="margin:0">
                <span></span>
                <span>
                    <button class="btn ghost" type="button" onclick="this.closest('dialog').close()">Batal</button>
                    <button class="btn" type="submit">Simpan Menu</button>
                </span>
            </div>
        </form>
    </dialog>
@endsection
