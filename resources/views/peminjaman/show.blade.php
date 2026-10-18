
@extends('layouts/layoutMaster')

@section('title', 'Detail Berita Acara Peminjaman Alat')

@section('content')
<div class="row">
  <div class="col-lg-9 col-md-11 mx-auto">
    <div class="card mb-4">
      <div class="card-header border-bottom py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div>
          <h5 class="mb-1 text-primary"><i class="ri-file-text-line me-2"></i>Detail Berita Acara Peminjaman Alat</h5>
          <span class="font-monospace fw-bold text-dark">{{ $peminjaman->nomor_surat }}</span>
        </div>
        <div class="d-flex gap-2">
          @if($peminjaman->status !== 'dikembalikan')
            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalKembalikanDetail">
              <i class="ri-check-double-line me-1"></i> Proses Pengembalian
            </button>
          @endif
          <a href="{{ route('peminjaman.cetak-berita-acara', $peminjaman->id) }}" target="_blank" class="btn btn-primary btn-sm">
            <i class="ri-printer-line me-1"></i> Cetak Berita Acara (PDF)
          </a>
          <a href="{{ route('peminjaman.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="ri-arrow-left-line me-1"></i> Kembali
          </a>
        </div>
      </div>

      <div class="card-body pt-4">
        <!-- Status Banner -->
        <div class="mb-4">
          @if($peminjaman->status === 'dikembalikan')
            <div class="alert alert-success d-flex align-items-center mb-0" role="alert">
              <i class="ri-checkbox-circle-line ri-2x me-3"></i>
              <div>
                <strong>Alat Sudah Dikembalikan:</strong> Dikembalikan pada hari {{ $peminjaman->hari_kembali }}, tanggal {{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali_aktual)->translatedFormat('d F Y') }} dalam kondisi <strong>{{ $peminjaman->kondisi_kembali }}</strong>.
              </div>
            </div>
          @elseif($peminjaman->is_overdue)
            <div class="alert alert-danger d-flex align-items-center mb-0" role="alert">
              <i class="ri-error-warning-line ri-2x me-3"></i>
              <div>
                <strong>Status Terlambat:</strong> Waktu rencana pengembalian adalah {{ \Carbon\Carbon::parse($peminjaman->rencana_kembali)->translatedFormat('d F Y') }} dan telah melewati batas jatuh tempo.
              </div>
            </div>
          @else
            <div class="alert alert-warning d-flex align-items-center mb-0" role="alert">
              <i class="ri-time-line ri-2x me-3"></i>
              <div>
                <strong>Sedang Dipinjam:</strong> Batas rencana pengembalian adalah {{ \Carbon\Carbon::parse($peminjaman->rencana_kembali)->translatedFormat('d F Y') }}.
              </div>
            </div>
          @endif
        </div>

        <!-- Pihak Pertama & Kedua -->
        <div class="row g-4 mb-4">
          <div class="col-md-6">
            <div class="card bg-light border p-3 h-100">
              <h6 class="fw-bold text-primary mb-2"><i class="ri-government-line me-1"></i>Pihak Pertama (Penyerah)</h6>
              <div class="mb-1"><strong>Nama:</strong> {{ $peminjaman->nama_penyerah }}</div>
              <div class="mb-1 text-muted small"><strong>Jabatan:</strong> {{ $peminjaman->jabatan_penyerah }}</div>
              <div class="text-muted small"><strong>Instansi:</strong> {{ $peminjaman->instansi_penyerah }}</div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card bg-light border p-3 h-100">
              <h6 class="fw-bold text-success mb-2"><i class="ri-user-shared-line me-1"></i>Pihak Kedua (Peminjam)</h6>
              <div class="mb-1"><strong>Nama:</strong> {{ $peminjaman->nama_peminjam }}</div>
              <div class="mb-1 text-muted small"><strong>Jabatan:</strong> {{ $peminjaman->jabatan_peminjam }}</div>
              <div class="mb-1 text-muted small"><strong>Instansi:</strong> {{ $peminjaman->instansi_peminjam }}</div>
              @if($peminjaman->kontak_peminjam)
                <div class="text-muted small"><strong>Kontak:</strong> {{ $peminjaman->kontak_peminjam }}</div>
              @endif
            </div>
          </div>
        </div>

        <!-- Rincian Alat -->
        <div class="card border mb-4">
          <div class="card-header bg-light py-2">
            <strong><i class="ri-tools-line me-1 text-primary"></i>Daftar Alat yang Dipinjam</strong>
          </div>
          <div class="table-responsive">
            <table class="table table-bordered mb-0">
              <thead class="table-light">
                <tr>
                  <th style="width: 40px;">No</th>
                  <th>Kode & Nama Alat</th>
                  <th>Jumlah</th>
                  <th>Kondisi Saat Pinjam</th>
                  <th>Keterangan</th>
                </tr>
              </thead>
              <tbody>
                @foreach($peminjaman->details as $idx => $d)
                <tr>
                  <td>{{ $idx + 1 }}</td>
                  <td>
                    <strong>{{ $d->barang->nama_barang ?? '-' }}</strong>
                    @if($d->barang->merk_tipe)
                      <br><small class="text-muted">Merk/Model: {{ $d->barang->merk_tipe }}</small>
                    @endif
                    @if($d->barang->kode_barang)
                      <br><small class="text-muted font-monospace">Kode: {{ $d->barang->kode_barang }}</small>
                    @endif
                  </td>
                  <td>{{ $d->jumlah }} {{ $d->barang->satuan ?? 'Unit' }}</td>
                  <td><span class="badge bg-label-info">{{ $d->kondisi_pinjam }}</span></td>
                  <td>{{ $d->keterangan ?: '-' }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>

        <!-- Keperluan -->
        <div class="mb-4 p-3 border rounded bg-light">
          <h6 class="fw-bold text-dark mb-2"><i class="ri-file-list-line me-1 text-primary"></i>Keperluan / Tujuan Peminjaman:</h6>
          <p class="mb-0 text-muted fst-italic">"{{ $peminjaman->keperluan }}"</p>
        </div>

        <!-- Catatan Pengembalian (jika ada) -->
        @if($peminjaman->status === 'dikembalikan')
          <div class="card border-success border p-3 mb-4">
            <h6 class="fw-bold text-success mb-2"><i class="ri-check-double-line me-1"></i>Catatan Verifikasi Pengembalian</h6>
            <div class="row g-2 small">
              <div class="col-md-6">
                <strong>Tanggal Kembali Aktual:</strong> {{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali_aktual)->translatedFormat('l, d F Y') }}<br>
                <strong>Kondisi Diterima:</strong> {{ $peminjaman->kondisi_kembali }}
              </div>
              <div class="col-md-6">
                <strong>Diserahkan oleh:</strong> {{ $peminjaman->nama_penyerah_kembali }}<br>
                <strong>Diterima oleh:</strong> {{ $peminjaman->nama_penerima_kembali }}
              </div>
              @if($peminjaman->catatan_kembali)
                <div class="col-12 mt-2 pt-2 border-top">
                  <strong>Catatan Petugas:</strong> {{ $peminjaman->catatan_kembali }}
                </div>
              @endif
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

@if($peminjaman->status !== 'dikembalikan')
  <div class="modal fade" id="modalKembalikanDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content text-start">
        <form method="POST" action="{{ route('peminjaman.kembalikan', $peminjaman->id) }}">
          @csrf
          <div class="modal-header border-bottom">
            <h5 class="modal-title"><i class="ri-check-double-line me-2 text-success"></i>Verifikasi Pengembalian Alat</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="alert alert-info py-2 small mb-3">
              Nomor Surat: <strong>{{ $peminjaman->nomor_surat }}</strong><br>
              Peminjam: <strong>{{ $peminjaman->nama_peminjam }}</strong> ({{ $peminjaman->instansi_peminjam }})<br>
              Rencana Kembali: <strong>{{ \Carbon\Carbon::parse($peminjaman->rencana_kembali)->translatedFormat('d F Y') }}</strong>
            </div>

            <div class="mb-3">
              <label class="form-label" for="detail_tanggal_kembali_aktual">Tanggal Pengembalian Aktual <span class="text-danger">*</span></label>
              <input type="date" class="form-control" id="detail_tanggal_kembali_aktual" name="tanggal_kembali_aktual" value="{{ date('Y-m-d') }}" required />
            </div>

            <div class="mb-3">
              <label class="form-label" for="detail_kondisi_kembali">Kondisi Fisik Saat Dikembalikan <span class="text-danger">*</span></label>
              <select class="form-select" id="detail_kondisi_kembali" name="kondisi_kembali" required>
                <option value="Baik" selected>Baik (Lengkap & Berfungsi Normal)</option>
                <option value="Rusak Ringan">Rusak Ringan</option>
                <option value="Rusak Berat">Rusak Berat</option>
              </select>
            </div>

            <div class="row g-2 mb-3">
              <div class="col-6">
                <label class="form-label small" for="detail_nama_penyerah_kembali">Yang Menyerahkan Kembali <span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-sm" id="detail_nama_penyerah_kembali" name="nama_penyerah_kembali" value="{{ $peminjaman->nama_peminjam }}" required />
              </div>
              <div class="col-6">
                <label class="form-label small" for="detail_nama_penerima_kembali">Yang Menerima di BBWS <span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-sm" id="detail_nama_penerima_kembali" name="nama_penerima_kembali" value="{{ $peminjaman->nama_penyerah }}" required />
              </div>
            </div>

            <div class="mb-0">
              <label class="form-label small" for="detail_catatan_kembali">Catatan Tambahan Pengembalian</label>
              <textarea class="form-control form-control-sm" id="detail_catatan_kembali" name="catatan_kembali" rows="2" placeholder="Contoh: Alat dikembalikan lengkap dengan kabel konektor dan sensor probe."></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-success">
              <i class="ri-check-line me-1"></i> Konfirmasi Terima & Pulihkan Stok
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endif
@endsection
