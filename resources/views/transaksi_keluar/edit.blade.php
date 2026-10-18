@extends('layouts/layoutMaster')

@section('title', 'Koreksi Transaksi Barang Keluar')

@section('content')
<div class="row">
  <div class="col-lg-9 col-md-11 mx-auto">
    <div class="card mb-4">
      <div class="card-header border-bottom py-3 d-flex justify-content-between align-items-center">
        <div>
          <h5 class="mb-1 text-primary"><i class="ri-edit-line me-2"></i>Koreksi / Edit Transaksi Barang Keluar</h5>
          <p class="text-muted mb-0 small">Kode Transaksi: <strong class="text-dark">{{ $transaksi->kode_transaksi ?? ('TRK-' . $transaksi->id) }}</strong> (Perubahan kuantitas akan otomatis merekonsiliasi stok master)</p>
        </div>
        <a href="{{ route('transaksi-keluar.index') }}" class="btn btn-sm btn-outline-secondary">
          <i class="ri-arrow-left-line me-1"></i> Kembali
        </a>
      </div>
      <div class="card-body pt-4">
        <form method="POST" action="{{ route('transaksi-keluar.update', $transaksi->id) }}">
          @csrf
          @method('PUT')
          
          <div class="row g-3">
            <div class="col-md-6 mb-3">
              <label class="form-label" for="tanggal">Tanggal Pengeluaran <span class="text-danger">*</span></label>
              <input type="date" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal" name="tanggal" value="{{ old('tanggal', $transaksi->tanggal) }}" required />
              @error('tanggal')
                  <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label" for="barang_id">Pilih Barang / Alat <span class="text-danger">*</span></label>
              <select class="form-select @error('barang_id') is-invalid @enderror" id="barang_id" name="barang_id" required>
                  <option value="">-- Pilih Barang / Alat --</option>
                  @foreach($barangs as $barang)
                      <option value="{{ $barang->id }}" {{ (old('barang_id', $transaksi->barang_id) == $barang->id) ? 'selected' : '' }}>
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
              <input type="number" class="form-control @error('jumlah') is-invalid @enderror" id="jumlah" name="jumlah" value="{{ old('jumlah', $transaksi->jumlah) }}" min="1" required />
              <small class="text-muted">Jumlah sebelumnya: {{ $transaksi->jumlah }}</small>
              @error('jumlah')
                  <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-4 mb-3">
              <label class="form-label" for="penerima">Nama Penerima <span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('penerima') is-invalid @enderror" id="penerima" name="penerima" value="{{ old('penerima', $transaksi->penerima) }}" required />
              @error('penerima')
                  <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            
            <div class="col-md-4 mb-3">
              <label class="form-label" for="bagian">Bagian / Bidang / Tim Lapangan <span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('bagian') is-invalid @enderror" id="bagian" name="bagian" value="{{ old('bagian', $transaksi->bagian) }}" required />
              @error('bagian')
                  <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label" for="keperluan">Notes : (Kepentingan / Keperluan Lapangan)</label>
            <textarea class="form-control @error('keperluan') is-invalid @enderror" id="keperluan" name="keperluan" rows="3">{{ old('keperluan', $transaksi->keperluan) }}</textarea>
            @error('keperluan')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label" for="keterangan">Keterangan Tambahan</label>
            <input type="text" class="form-control" id="keterangan" name="keterangan" value="{{ old('keterangan', $transaksi->keterangan) }}" />
          </div>

          <!-- Otorisasi 3 Pihak (Sesuai Form Dokumen 1) -->
          <div class="card bg-light border p-3 mb-4">
            <h6 class="fw-bold mb-3 text-dark"><i class="ri-quill-pen-line me-1"></i>Otorisasi Form Cetak (3 Kolom Tanda Tangan)</h6>
            <div class="row g-3">
              <div class="col-md-4">
                <label class="form-label small" for="dibawa_oleh">1. Dibawa oleh (Petugas Lapangan)</label>
                <input type="text" class="form-control form-control-sm" id="dibawa_oleh" name="dibawa_oleh" value="{{ old('dibawa_oleh', $transaksi->dibawa_oleh) }}" />
              </div>
              <div class="col-md-4">
                <label class="form-label small" for="diperiksa_oleh">2. Diperiksa oleh (Admin / Pengelola)</label>
                <input type="text" class="form-control form-control-sm" id="diperiksa_oleh" name="diperiksa_oleh" value="{{ old('diperiksa_oleh', $transaksi->diperiksa_oleh) }}" />
              </div>
              <div class="col-md-4">
                <label class="form-label small" for="disetujui_oleh">3. Disetujui oleh (Kepala / Ketua Satuan)</label>
                <input type="text" class="form-control form-control-sm" id="disetujui_oleh" name="disetujui_oleh" value="{{ old('disetujui_oleh', $transaksi->disetujui_oleh) }}" />
              </div>
            </div>
          </div>

          <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('transaksi-keluar.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">
              <i class="ri-save-line me-1"></i> Simpan Perubahan & Koreksi Stok
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
