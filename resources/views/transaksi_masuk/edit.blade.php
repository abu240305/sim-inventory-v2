@extends('layouts/layoutMaster')

@section('title', 'Koreksi Transaksi Barang Masuk')

@section('content')
<div class="row">
  <div class="col-lg-8 col-md-10 mx-auto">
    <div class="card mb-4">
      <div class="card-header border-bottom py-3 d-flex justify-content-between align-items-center">
        <div>
          <h5 class="mb-1 text-primary"><i class="ri-edit-line me-2"></i>Koreksi Transaksi Barang Masuk</h5>
          <p class="text-muted mb-0 small">Perubahan kuantitas penerimaan akan otomatis menyesuaikan stok master gudang</p>
        </div>
        <a href="{{ route('transaksi-masuk.index') }}" class="btn btn-sm btn-outline-secondary">
          <i class="ri-arrow-left-line me-1"></i> Kembali
        </a>
      </div>
      <div class="card-body pt-4">
        <form method="POST" action="{{ route('transaksi-masuk.update', $transaksi->id) }}">
          @csrf
          @method('PUT')
          
          <div class="mb-3">
            <label class="form-label" for="tanggal">Tanggal Penerimaan Masuk <span class="text-danger">*</span></label>
            <input type="date" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal" name="tanggal" value="{{ old('tanggal', $transaksi->tanggal) }}" required />
            @error('tanggal')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label" for="barang_id">Pilih Barang / Alat <span class="text-danger">*</span></label>
            <select class="form-select @error('barang_id') is-invalid @enderror" id="barang_id" name="barang_id" required>
                <option value="">-- Pilih Barang --</option>
                @foreach($barangs as $barang)
                    <option value="{{ $barang->id }}" {{ (old('barang_id', $transaksi->barang_id) == $barang->id) ? 'selected' : '' }}>
                        {{ $barang->kode_barang }} - {{ $barang->nama_barang }} [{{ $barang->jenis == 'alat' ? 'Alat' : 'Bahan' }}] (Stok Sekarang: {{ $barang->jumlah }} {{ $barang->satuan ?? 'Unit' }})
                    </option>
                @endforeach
            </select>
            @error('barang_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="row g-3">
            <div class="col-md-6 mb-3">
              <label class="form-label" for="jumlah">Jumlah Masuk <span class="text-danger">*</span></label>
              <input type="number" class="form-control @error('jumlah') is-invalid @enderror" id="jumlah" name="jumlah" value="{{ old('jumlah', $transaksi->jumlah) }}" min="1" required />
              <small class="text-muted">Jumlah saat ini: {{ $transaksi->jumlah }}</small>
              @error('jumlah')
                  <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label" for="sumber">Sumber Pengadaan / Rekanan</label>
              <input type="text" class="form-control" id="sumber" name="sumber" value="{{ old('sumber', $transaksi->sumber) }}" placeholder="Contoh: APBN 2026, Hibah, Supplier" />
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label" for="keterangan">Keterangan / Alasan Koreksi</label>
            <textarea class="form-control" id="keterangan" name="keterangan" rows="3">{{ old('keterangan', $transaksi->keterangan) }}</textarea>
          </div>

          <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('transaksi-masuk.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">
              <i class="ri-save-line me-1"></i> Simpan & Koreksi Stok
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
