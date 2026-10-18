@extends('layouts/layoutMaster')

@section('title', 'Form Pengeluaran Barang & Alat')

@section('content')
<div class="row">
  <div class="col-lg-9 col-md-11 mx-auto">
    <div class="card mb-4">
      <div class="card-header border-bottom py-3 d-flex justify-content-between align-items-center">
        <div>
          <h5 class="mb-1 text-primary"><i class="ri-file-upload-line me-2"></i>Pencatatan Barang / Alat Keluar</h5>
          <p class="text-muted mb-0 small">Sesuai format resmi <strong>Form Keluar Alat / Barang</strong> Unit Hidrologi & Kualitas Air</p>
        </div>
        <a href="{{ route('transaksi-keluar.index') }}" class="btn btn-sm btn-outline-secondary">
          <i class="ri-arrow-left-line me-1"></i> Kembali
        </a>
      </div>
      <div class="card-body pt-4">
        <form method="POST" action="{{ route('transaksi-keluar.store') }}">
          @csrf
          
          <div class="row g-3">
            <div class="col-md-6 mb-3">
              <label class="form-label" for="tanggal">Tanggal Pengeluaran <span class="text-danger">*</span></label>
              <input type="date" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required />
              @error('tanggal')
                  <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label" for="barang_id">Pilih Barang / Alat <span class="text-danger">*</span></label>
              <select class="form-select @error('barang_id') is-invalid @enderror" id="barang_id" name="barang_id" required>
                  <option value="">-- Pilih Barang / Alat dari Gudang --</option>
                  @foreach($barangs as $barang)
                      <option value="{{ $barang->id }}" {{ old('barang_id') == $barang->id ? 'selected' : '' }}>
                          {{ $barang->kode_barang }} - {{ $barang->nama_barang }} [{{ $barang->jenis == 'alat' ? 'Alat' : 'Bahan' }}] (Sisa Stok: {{ $barang->jumlah }} {{ $barang->satuan ?? 'Unit' }})
                      </option>
                  @endforeach
              </select>
              @error('barang_id')
                  <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="row g-3">
            <div class="col-md-4 mb-3">
              <label class="form-label" for="jumlah">Jumlah Dikeluarkan <span class="text-danger">*</span></label>
              <input type="number" class="form-control @error('jumlah') is-invalid @enderror" id="jumlah" name="jumlah" value="{{ old('jumlah', 1) }}" min="1" required />
              @error('jumlah')
                  <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-4 mb-3">
              <label class="form-label" for="penerima">Nama Penerima <span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('penerima') is-invalid @enderror" id="penerima" name="penerima" value="{{ old('penerima') }}" placeholder="Nama personil penerima" required />
              @error('penerima')
                  <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            
            <div class="col-md-4 mb-3">
              <label class="form-label" for="bagian">Bagian / Bidang / Tim Lapangan <span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('bagian') is-invalid @enderror" id="bagian" name="bagian" value="{{ old('bagian', 'Unit Hidrologi & Kualitas Air') }}" required />
              @error('bagian')
                  <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label" for="keperluan">Notes : (Kepentingan / Keperluan Lapangan)</label>
            <textarea class="form-control @error('keperluan') is-invalid @enderror" id="keperluan" name="keperluan" rows="3" placeholder="Contoh: Operasional sampling kualitas air dan debit sungai pos hidrologi">{{ old('keperluan') }}</textarea>
            <div class="form-text">Catatan ini akan tercetak pada baris <em>Notes (kepentingan / keperluan)</em> di Form Cetak PDF.</div>
            @error('keperluan')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label" for="keterangan">Keterangan Tambahan (Opsional)</label>
            <input type="text" class="form-control" id="keterangan" name="keterangan" value="{{ old('keterangan') }}" placeholder="Contoh: Kelengkapan kabel probe lengkap, kondisi baik" />
          </div>

          <!-- Otorisasi 3 Pihak (Sesuai Form Dokumen 1) -->
          <div class="card bg-light border p-3 mb-4">
            <h6 class="fw-bold mb-3 text-dark"><i class="ri-quill-pen-line me-1"></i>Otorisasi Form Cetak (3 Kolom Tanda Tangan)</h6>
            <div class="row g-3">
              <div class="col-md-4">
                <label class="form-label small" for="dibawa_oleh">1. Dibawa oleh (Petugas Lapangan)</label>
                <input type="text" class="form-control form-control-sm" id="dibawa_oleh" name="dibawa_oleh" value="{{ old('dibawa_oleh') }}" placeholder="Otomatis mengikuti Nama Penerima jika kosong" />
              </div>
              <div class="col-md-4">
                <label class="form-label small" for="diperiksa_oleh">2. Diperiksa oleh (Admin / Pengelola)</label>
                <input type="text" class="form-control form-control-sm" id="diperiksa_oleh" name="diperiksa_oleh" value="{{ old('diperiksa_oleh', $defaultPemeriksa ?? '') }}" />
              </div>
              <div class="col-md-4">
                <label class="form-label small" for="disetujui_oleh">3. Disetujui oleh (Kepala / Ketua Satuan)</label>
                <input type="text" class="form-control form-control-sm" id="disetujui_oleh" name="disetujui_oleh" value="{{ old('disetujui_oleh', $defaultPenyetuju ?? 'A. Zafriyadi, S.T.') }}" />
              </div>
            </div>
          </div>

          <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('transaksi-keluar.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">
              <i class="ri-save-line me-1"></i> Simpan & Buat Form Keluar
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
