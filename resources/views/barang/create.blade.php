@extends('layouts/layoutMaster')

@section('title', 'Tambah Barang & Alat')

@section('content')
<div class="row">
  <div class="col-lg-8 col-md-10 mx-auto">
    <div class="card mb-4">
      <div class="card-header border-bottom py-3 d-flex justify-content-between align-items-center">
        <div>
          <h5 class="mb-1 text-primary"><i class="ri-add-circle-line me-2"></i>Tambah Inventaris Baru</h5>
          <p class="text-muted mb-0 small">Masukkan informasi detail barang atau alat kerja Unit Hidrologi & Kualitas Air</p>
        </div>
        <a href="{{ route('barang.index') }}" class="btn btn-sm btn-outline-secondary">
          <i class="ri-arrow-left-line me-1"></i> Kembali
        </a>
      </div>
      <div class="card-body pt-4">
        <form method="POST" action="{{ route('barang.store') }}">
          @csrf
          
          <!-- Pilihan Jenis Barang (Alat vs Bahan) -->
          <div class="mb-4 p-3 border rounded bg-light">
            <label class="form-label fw-bold d-block mb-2">Klasifikasi Jenis Inventaris <span class="text-danger">*</span></label>
            <div class="row g-3">
              <div class="col-md-6">
                <div class="form-check custom-option custom-option-basic border p-3 rounded bg-white">
                  <input class="form-check-input" type="radio" name="jenis" id="jenis_alat" value="alat" {{ old('jenis', 'alat') == 'alat' ? 'checked' : '' }} required>
                  <label class="form-check-label d-flex align-items-center" for="jenis_alat">
                    <i class="ri-tools-line ri-2x text-primary me-2"></i>
                    <div>
                      <span class="fw-bold d-block text-dark">Alat / Instrumen Kerja</span>
                      <small class="text-muted">Peralatan lab/lapangan yang dapat dipinjamkan (misal: Water Quality Meter, GPS, Mesin, Bor)</small>
                    </div>
                  </label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-check custom-option custom-option-basic border p-3 rounded bg-white">
                  <input class="form-check-input" type="radio" name="jenis" id="jenis_bahan" value="bahan" {{ old('jenis') == 'bahan' ? 'checked' : '' }} required>
                  <label class="form-check-label d-flex align-items-center" for="jenis_bahan">
                    <i class="ri-drop-line ri-2x text-warning me-2"></i>
                    <div>
                      <span class="fw-bold d-block text-dark">Bahan / Material Habis Pakai</span>
                      <small class="text-muted">Material/bahan sekali pakai keluar gudang (misal: Reagen Kimia, Semen, Cat, Pipa, Filter)</small>
                    </div>
                  </label>
                </div>
              </div>
            </div>
            @error('jenis')
              <div class="text-danger mt-1 small">{{ $message }}</div>
            @enderror
          </div>

          <div class="row g-3">
            <div class="col-md-6 mb-3">
              <label class="form-label" for="kode_barang">Kode Barang / Register <span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('kode_barang') is-invalid @enderror" id="kode_barang" name="kode_barang" value="{{ old('kode_barang') }}" placeholder="Contoh: ALT-001 atau BHN-001" required />
              @error('kode_barang')
                  <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label" for="kategori_id">Kategori Inventaris <span class="text-danger">*</span></label>
              <select class="form-select @error('kategori_id') is-invalid @enderror" id="kategori_id" name="kategori_id" required>
                  <option value="">-- Pilih Kategori --</option>
                  @foreach($kategoris as $kategori)
                      <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>{{ $kategori->nama_kategori }}</option>
                  @endforeach
              </select>
              @error('kategori_id')
                  <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="row g-3">
            <div class="col-md-8 mb-3">
              <label class="form-label" for="nama_barang">Nama Barang / Instrumen <span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('nama_barang') is-invalid @enderror" id="nama_barang" name="nama_barang" value="{{ old('nama_barang') }}" placeholder="Contoh: Combo Water Quality Meter dan Turbidity" required />
              @error('nama_barang')
                  <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-4 mb-3">
              <label class="form-label" for="merk_tipe">Merk / Model / Seri</label>
              <input type="text" class="form-control @error('merk_tipe') is-invalid @enderror" id="merk_tipe" name="merk_tipe" value="{{ old('merk_tipe') }}" placeholder="Contoh: Horiba U-50, Hanna HI9829" />
              @error('merk_tipe')
                  <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="row g-3">
            <div class="col-md-4 mb-3">
              <label class="form-label" for="satuan">Satuan Unit <span class="text-danger">*</span></label>
              <input class="form-control @error('satuan') is-invalid @enderror" list="satuanList" id="satuan" name="satuan" value="{{ old('satuan', 'Unit') }}" placeholder="Pilih / ketik satuan" required>
              <datalist id="satuanList">
                <option value="Unit">
                <option value="Set">
                <option value="Buah">
                <option value="Botol">
                <option value="Box">
                <option value="Sak">
                <option value="Batang">
                <option value="Roll">
                <option value="Liter">
                <option value="Kg">
                <option value="Meter">
                <option value="Pcs">
              </datalist>
              @error('satuan')
                  <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-4 mb-3">
              <label class="form-label" for="kondisi">Kondisi Fisik</label>
              <select class="form-select" id="kondisi" name="kondisi">
                <option value="Baik" {{ old('kondisi', 'Baik') == 'Baik' ? 'selected' : '' }}>Baik</option>
                <option value="Rusak Ringan" {{ old('kondisi') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                <option value="Rusak Berat" {{ old('kondisi') == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
              </select>
            </div>

            <div class="col-md-4 mb-3">
              <label class="form-label" for="lokasi">Lokasi Penyimpanan</label>
              <input type="text" class="form-control" id="lokasi" name="lokasi" value="{{ old('lokasi') }}" placeholder="Contoh: Lab Kualitas Air, Lemari B" />
            </div>
          </div>

          <div class="alert alert-info py-2 d-flex align-items-center">
            <i class="ri-information-line me-2 ri-lg"></i>
            <small>Stok awal akan bernilai <strong>0</strong>. Untuk menambah stok fisik gudang, silakan catat melalui menu <strong>Transaksi Barang Masuk</strong>.</small>
          </div>

          <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('barang.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">
              <i class="ri-save-line me-1"></i> Simpan Inventaris
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
