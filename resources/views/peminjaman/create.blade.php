@extends('layouts/layoutMaster')

@section('title', 'Penerbitan Berita Acara Peminjaman Alat')

@section('content')
<div class="row">
  <div class="col-lg-10 mx-auto">
    <div class="card mb-4">
      <div class="card-header border-bottom py-3 d-flex justify-content-between align-items-center">
        <div>
          <h5 class="mb-1 text-primary"><i class="ri-file-paper-2-line me-2"></i>Penerbitan Berita Acara Peminjaman Alat</h5>
          <p class="text-muted mb-0 small">Sesuai format resmi surat dinas Ditjen SDA Balai Besar Wilayah Sungai Pompengan Jeneberang</p>
        </div>
        <a href="{{ route('peminjaman.index') }}" class="btn btn-sm btn-outline-secondary">
          <i class="ri-arrow-left-line me-1"></i> Kembali ke Daftar
        </a>
      </div>
      <div class="card-body pt-4">
        @if($alats->isEmpty())
          <div class="alert alert-warning d-flex align-items-center" role="alert">
            <i class="ri-alert-line ri-2x me-3"></i>
            <div>
              <strong>Perhatian:</strong> Tidak ada inventaris bertipe <strong>Alat</strong> dengan stok tersedia di gudang saat ini. Pastikan Anda telah menginput barang bertipe <em>Alat / Instrumen</em> dengan stok lebih dari 0 pada master barang.
            </div>
          </div>
        @endif

        <form method="POST" action="{{ route('peminjaman.store') }}" id="formPeminjaman">
          @csrf

          <!-- Informasi Surat & Tanggal -->
          <div class="card bg-light border p-3 mb-4">
            <h6 class="fw-bold mb-3 text-dark"><i class="ri-calendar-event-line me-1 text-primary"></i>Informasi Registrasi Surat Dinas</h6>
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label" for="nomor_surat">Nomor Surat Dinas <span class="text-danger">*</span></label>
                <input type="text" class="form-control font-monospace @error('nomor_surat') is-invalid @enderror" id="nomor_surat" name="nomor_surat" value="{{ old('nomor_surat', $nomorSuratRekomendasi) }}" required />
                <div class="form-text">Format standar BBWS: <code>UM0102/B/BBWS11.6.3/{Tahun}/{No}</code></div>
                @error('nomor_surat')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-3">
                <label class="form-label" for="tanggal_pinjam">Tanggal Peminjaman <span class="text-danger">*</span></label>
                <input type="date" class="form-control @error('tanggal_pinjam') is-invalid @enderror" id="tanggal_pinjam" name="tanggal_pinjam" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required />
                @error('tanggal_pinjam')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-3">
                <label class="form-label" for="rencana_kembali">Rencana Pengembalian <span class="text-danger">*</span></label>
                <input type="date" class="form-control @error('rencana_kembali') is-invalid @enderror" id="rencana_kembali" name="rencana_kembali" value="{{ old('rencana_kembali', date('Y-m-d', strtotime('+1 day'))) }}" required />
                @error('rencana_kembali')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>

          <!-- Pihak Pertama & Pihak Kedua -->
          <div class="row g-4 mb-4">
            <!-- Pihak Pertama (Penyerah) -->
            <div class="col-md-6">
              <div class="border rounded p-3 h-100 bg-white">
                <h6 class="fw-bold text-primary mb-3"><i class="ri-government-line me-1"></i>1. Pihak Pertama (Yang Menyerahkan Alat)</h6>
                <div class="mb-3">
                  <label class="form-label small" for="nama_penyerah">Nama Pejabat Penyerah <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="nama_penyerah" name="nama_penyerah" value="{{ old('nama_penyerah', $defaultPenyerah) }}" required />
                </div>
                <div class="mb-3">
                  <label class="form-label small" for="jabatan_penyerah">Jabatan Penyerah <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="jabatan_penyerah" name="jabatan_penyerah" value="{{ old('jabatan_penyerah', $defaultJabatanPenyerah) }}" required />
                </div>
                <div class="mb-0">
                  <label class="form-label small" for="instansi_penyerah">Instansi <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="instansi_penyerah" name="instansi_penyerah" value="{{ old('instansi_penyerah', $defaultInstansiPenyerah) }}" required />
                </div>
              </div>
            </div>

            <!-- Pihak Kedua (Peminjam) -->
            <div class="col-md-6">
              <div class="border rounded p-3 h-100 bg-white">
                <h6 class="fw-bold text-success mb-3"><i class="ri-user-shared-line me-1"></i>2. Pihak Kedua (Peminjam Alat)</h6>
                <div class="mb-3">
                  <label class="form-label small" for="nama_peminjam">Nama Lengkap Peminjam <span class="text-danger">*</span></label>
                  <input type="text" class="form-control @error('nama_peminjam') is-invalid @enderror" id="nama_peminjam" name="nama_peminjam" value="{{ old('nama_peminjam') }}" placeholder="Contoh: Dr. Eng. Sulfikar, S.Si., M.T." required />
                  @error('nama_peminjam')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
                <div class="row g-2 mb-3">
                  <div class="col-6">
                    <label class="form-label small" for="jabatan_peminjam">Jabatan <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('jabatan_peminjam') is-invalid @enderror" id="jabatan_peminjam" name="jabatan_peminjam" value="{{ old('jabatan_peminjam', 'Dosen') }}" placeholder="Dosen / Peneliti / Mahasiswa" required />
                  </div>
                  <div class="col-6">
                    <label class="form-label small" for="kontak_peminjam">No. HP / Kontak</label>
                    <input type="text" class="form-control" id="kontak_peminjam" name="kontak_peminjam" value="{{ old('kontak_peminjam') }}" placeholder="08xxxxxxxxxx" />
                  </div>
                </div>
                <div class="mb-0">
                  <label class="form-label small" for="instansi_peminjam">Instansi / Asal Lembaga <span class="text-danger">*</span></label>
                  <input type="text" class="form-control @error('instansi_peminjam') is-invalid @enderror" id="instansi_peminjam" name="instansi_peminjam" value="{{ old('instansi_peminjam') }}" placeholder="Contoh: Jurusan Kimia, FMIPA Universitas Negeri Makassar" required />
                  @error('instansi_peminjam')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div>
          </div>

          <!-- Rincian Alat yang Dipinjam (Bisa Multi Item) -->
          <div class="card border mb-4">
            <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
              <span class="fw-bold text-dark"><i class="ri-tools-line me-1 text-primary"></i>Rincian Instrumen / Alat yang Dipinjam</span>
              <button type="button" class="btn btn-sm btn-outline-primary" id="btnTambahAlat">
                <i class="ri-add-line me-1"></i> Tambah Baris Alat
              </button>
            </div>
            <div class="card-body p-3">
              <div class="table-responsive">
                <table class="table table-bordered mb-0" id="tabelAlatPinjam">
                  <thead class="table-light">
                    <tr>
                      <th>Pilih Alat / Instrumen <span class="text-danger">*</span></th>
                      <th style="width: 120px;">Jumlah <span class="text-danger">*</span></th>
                      <th style="width: 160px;">Kondisi Awal</th>
                      <th>Keterangan</th>
                      <th style="width: 50px;">Aksi</th>
                    </tr>
                  </thead>
                  <tbody id="containerBarisAlat">
                    <tr>
                      <td>
                        <select class="form-select select-alat" name="barang_id[]" required>
                          <option value="">-- Pilih Alat --</option>
                          @foreach($alats as $alat)
                            <option value="{{ $alat->id }}" data-stok="{{ $alat->jumlah }}" data-satuan="{{ $alat->satuan ?? 'Unit' }}">
                              {{ $alat->nama_barang }} {{ $alat->merk_tipe ? '(' . $alat->merk_tipe . ')' : '' }} - [Tersedia: {{ $alat->jumlah }} {{ $alat->satuan ?? 'Unit' }}]
                            </option>
                          @endforeach
                        </select>
                      </td>
                      <td>
                        <input type="number" class="form-control input-qty" name="jumlah[]" value="1" min="1" max="99" required />
                      </td>
                      <td>
                        <select class="form-select" name="kondisi_pinjam[]">
                          <option value="Baik" selected>Baik</option>
                          <option value="Rusak Ringan">Rusak Ringan</option>
                        </select>
                      </td>
                      <td>
                        <input type="text" class="form-control" name="keterangan_alat[]" placeholder="Catatan probe/kelengkapan" />
                      </td>
                      <td class="text-center">
                        <button type="button" class="btn btn-sm btn-icon btn-outline-danger btnHapusBaris" disabled>
                          <i class="ri-delete-bin-line"></i>
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Keperluan Peminjaman -->
          <div class="mb-4">
            <label class="form-label fw-bold" for="keperluan">Keperluan / Tujuan Peminjaman Alat <span class="text-danger">*</span></label>
            <textarea class="form-control @error('keperluan') is-invalid @enderror" id="keperluan" name="keperluan" rows="4" placeholder="Contoh: Sampling kualitas air pada penelitian 'Pemanfaatan Siklus Nutrien Ekosistem Nipah Fruticans Wurmb (Nipah) untuk bioremediasi lahan basah perkotaan'." required>{{ old('keperluan') }}</textarea>
            <div class="form-text">Teks ini akan tercantum lengkap pada klausul resmi Berita Acara Peminjaman Alat.</div>
            @error('keperluan')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="alert alert-info py-2 small d-flex align-items-center">
            <i class="ri-information-line me-2 ri-lg"></i>
            <div>
              Setelah disimpan, ketersediaan unit alat di gudang akan otomatis dikurangi dan surat Berita Acara siap langsung dicetak atau diunduh PDF.
            </div>
          </div>

          <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary" {{ $alats->isEmpty() ? 'disabled' : '' }}>
              <i class="ri-printer-line me-1"></i> Terbitkan Berita Acara & Simpan
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const container = document.getElementById('containerBarisAlat');
  const btnTambah = document.getElementById('btnTambahAlat');

  btnTambah.addEventListener('click', function () {
    const barisPertama = container.querySelector('tr');
    const clone = barisPertama.cloneNode(true);

    // Reset value
    clone.querySelector('select.select-alat').value = '';
    clone.querySelector('input.input-qty').value = '1';
    clone.querySelector('input[name="keterangan_alat[]"]').value = '';

    const btnHapus = clone.querySelector('.btnHapusBaris');
    btnHapus.disabled = false;
    btnHapus.addEventListener('click', function () {
      clone.remove();
    });

    container.appendChild(clone);
  });
});
</script>
@endsection
