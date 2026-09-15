@extends('layouts/layoutMaster')

@section('title', 'Tambah Barang Keluar')

@section('content')
<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Form Pencatatan Barang Keluar</h5>
  </div>
  <div class="card-body">
    <form method="POST" action="{{ route('transaksi-keluar.store') }}">
      @csrf
      
      <div class="mb-3">
        <label class="form-label" for="tanggal">Tanggal Keluar</label>
        <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required />
        @error('tanggal')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label class="form-label" for="barang_id">Barang (Sisa Stok)</label>
        <select class="form-select" id="barang_id" name="barang_id" required>
            <option value="">-- Pilih Barang --</option>
            @foreach($barangs as $barang)
                <option value="{{ $barang->id }}" {{ old('barang_id') == $barang->id ? 'selected' : '' }}>
                    {{ $barang->kode_barang }} - {{ $barang->nama_barang }} (Stok: {{ $barang->jumlah }})
                </option>
            @endforeach
        </select>
        @error('barang_id')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label class="form-label" for="jumlah">Jumlah Keluar</label>
        <input type="number" class="form-control" id="jumlah" name="jumlah" value="{{ old('jumlah') }}" min="1" required />
        @error('jumlah')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label class="form-label" for="penerima">Nama Penerima</label>
        <input type="text" class="form-control" id="penerima" name="penerima" value="{{ old('penerima') }}" required />
      </div>
      
      <div class="mb-3">
        <label class="form-label" for="bagian">Bagian / Departemen</label>
        <input type="text" class="form-control" id="bagian" name="bagian" value="{{ old('bagian') }}" required />
      </div>

      <div class="mb-3">
        <label class="form-label" for="keterangan">Keterangan / Keperluan</label>
        <textarea class="form-control" id="keterangan" name="keterangan">{{ old('keterangan') }}</textarea>
      </div>

      <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
      <a href="{{ route('transaksi-keluar.index') }}" class="btn btn-secondary">Batal</a>
    </form>
  </div>
</div>
@endsection
