@extends('layouts/layoutMaster')

@section('title', 'Edit Barang')

@section('content')
<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Edit Barang</h5>
  </div>
  <div class="card-body">
    <form method="POST" action="{{ route('barang.update', $barang->id) }}">
      @csrf
      @method('PUT')
      
      <div class="mb-3">
        <label class="form-label" for="kode_barang">Kode Barang</label>
        <input type="text" class="form-control" id="kode_barang" name="kode_barang" value="{{ old('kode_barang', $barang->kode_barang) }}" required />
        @error('kode_barang')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label class="form-label" for="nama_barang">Nama Barang</label>
        <input type="text" class="form-control" id="nama_barang" name="nama_barang" value="{{ old('nama_barang', $barang->nama_barang) }}" required />
        @error('nama_barang')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label class="form-label" for="kategori_id">Kategori</label>
        <select class="form-select" id="kategori_id" name="kategori_id" required>
            <option value="">-- Pilih Kategori --</option>
            @foreach($kategoris as $kategori)
                <option value="{{ $kategori->id }}" {{ (old('kategori_id', $barang->kategori_id) == $kategori->id) ? 'selected' : '' }}>{{ $kategori->nama_kategori }}</option>
            @endforeach
        </select>
        @error('kategori_id')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label class="form-label" for="kondisi">Kondisi</label>
        <input type="text" class="form-control" id="kondisi" name="kondisi" value="{{ old('kondisi', $barang->kondisi) }}" placeholder="Contoh: Baik, Rusak Ringan" />
      </div>

      <div class="mb-3">
        <label class="form-label" for="lokasi">Lokasi</label>
        <input type="text" class="form-control" id="lokasi" name="lokasi" value="{{ old('lokasi', $barang->lokasi) }}" placeholder="Contoh: Gudang Utama, Lemari A" />
      </div>

      <button type="submit" class="btn btn-primary">Update</button>
      <a href="{{ route('barang.index') }}" class="btn btn-secondary">Batal</a>
    </form>
  </div>
</div>
@endsection
