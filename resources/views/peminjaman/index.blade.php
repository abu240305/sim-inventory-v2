@extends('layouts/layoutMaster')

@section('title', 'Sirkulasi Peminjaman Alat')

@section('content')
<!-- Kartu Statistik Compact & Modern -->
<div class="row g-2 mb-3">
  <div class="col-6 col-md-3">
    <div class="card shadow-none border stat-card h-100">
      <div class="card-body p-3">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <span class="text-muted small d-block mb-1">Total Peminjaman</span>
            <h4 class="mb-0 fw-bold">{{ $totalSemua }}</h4>
          </div>
          <div class="avatar avatar-sm bg-label-primary rounded p-1 d-flex align-items-center justify-content-center">
            <i class="ri-file-paper-2-line ri-20px"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card shadow-none border stat-card h-100">
      <div class="card-body p-3">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <span class="text-muted small d-block mb-1">Sedang Dipinjam</span>
            <h4 class="mb-0 fw-bold text-warning">{{ $totalDipinjam }}</h4>
          </div>
          <div class="avatar avatar-sm bg-label-warning rounded p-1 d-flex align-items-center justify-content-center">
            <i class="ri-time-line ri-20px"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card shadow-none border stat-card h-100">
      <div class="card-body p-3">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <span class="text-muted small d-block mb-1">Sudah Dikembalikan</span>
            <h4 class="mb-0 fw-bold text-success">{{ $totalDikembalikan }}</h4>
          </div>
          <div class="avatar avatar-sm bg-label-success rounded p-1 d-flex align-items-center justify-content-center">
            <i class="ri-checkbox-circle-line ri-20px"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card shadow-none border stat-card h-100">
      <div class="card-body p-3">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <span class="text-muted small d-block mb-1">Terlambat Kembali</span>
            <h4 class="mb-0 fw-bold text-danger">{{ $totalTerlambat }}</h4>
          </div>
          <div class="avatar avatar-sm bg-label-danger rounded p-1 d-flex align-items-center justify-content-center">
            <i class="ri-alarm-warning-line ri-20px"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Card Utama -->
<div class="card shadow-sm border">
  <div class="card-header border-bottom py-2 px-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
    <div>
      <h6 class="mb-0 text-primary fw-bold"><i class="ri-hand-coin-line me-1"></i>Daftar Peminjaman & Pengembalian Alat</h6>
      <span class="text-muted small">Sirkulasi instrumen lapangan Unit Hidrologi & Kualitas Air</span>
    </div>
    <div>
      <a href="{{ route('peminjaman.create') }}" class="btn btn-sm btn-primary">
        <i class="ri-add-line me-1"></i> Terbitkan Berita Acara
      </a>
    </div>
  </div>

  <div class="card-body p-3">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show py-2 mb-2" role="alert">
            <i class="ri-checkbox-circle-line me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show py-2 mb-2" role="alert">
            <i class="ri-error-warning-line me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Filter Tab & Search -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
      <ul class="nav nav-pills gap-1">
        <li class="nav-item">
          <a class="nav-link py-1 px-2.5 small {{ empty(request('status')) ? 'active' : '' }}" href="{{ route('peminjaman.index') }}">
            Semua ({{ $totalSemua }})
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link py-1 px-2.5 small {{ request('status') == 'dipinjam' ? 'active' : '' }}" href="{{ route('peminjaman.index', ['status' => 'dipinjam']) }}">
            <i class="ri-time-line me-1"></i> Dipinjam ({{ $totalDipinjam }})
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link py-1 px-2.5 small {{ request('status') == 'dikembalikan' ? 'active' : '' }}" href="{{ route('peminjaman.index', ['status' => 'dikembalikan']) }}">
            <i class="ri-check-line me-1"></i> Dikembalikan ({{ $totalDikembalikan }})
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link py-1 px-2.5 small {{ request('status') == 'terlambat' ? 'active' : '' }}" href="{{ route('peminjaman.index', ['status' => 'terlambat']) }}">
            <i class="ri-error-warning-line me-1"></i> Terlambat ({{ $totalTerlambat }})
          </a>
        </li>
      </ul>

      <form method="GET" action="{{ route('peminjaman.index') }}" class="d-flex align-items-center gap-1">
        @if(request('status'))
          <input type="hidden" name="status" value="{{ request('status') }}">
        @endif
        <div class="input-group input-group-sm input-group-merge" style="width: 220px;">
          <span class="input-group-text py-1"><i class="ri-search-line"></i></span>
          <input type="text" name="search" class="form-control py-1 form-control-sm" placeholder="Cari surat / peminjam..." value="{{ request('search') }}">
        </div>
        <button type="submit" class="btn btn-sm btn-outline-secondary py-1">Cari</button>
        @if(request('search'))
          <a href="{{ route('peminjaman.index', request('status') ? ['status' => request('status')] : []) }}" class="btn btn-sm btn-outline-danger py-1" title="Reset">
            <i class="ri-close-line"></i>
          </a>
        @endif
      </form>
    </div>

    <!-- Tabel Peminjaman Responsive (Tanpa Horizontal Scrollbar) -->
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th style="width: 35px;" class="text-center">No</th>
            <th style="width: 17%;">Nomor Surat</th>
            <th style="width: 21%;">Pihak Kedua (Peminjam)</th>
            <th style="width: 25%;">Alat yang Dipinjam</th>
            <th style="width: 17%;">Periode Pinjam</th>
            <th style="width: 9%;" class="text-center">Status</th>
            <th style="width: 11%;" class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          @forelse($peminjamans as $index => $pinjam)
          <tr>
            <td class="text-center text-muted small">{{ $index + 1 }}</td>
            <td>
              <span class="fw-bold font-monospace text-primary d-block text-break" style="font-size: 0.8rem;">
                {{ $pinjam->nomor_surat }}
              </span>
              <span class="text-muted" style="font-size: 0.725rem;">
                {{ $pinjam->hari_surat }}, {{ \Carbon\Carbon::parse($pinjam->tanggal_pinjam)->format('d/m/Y') }}
              </span>
            </td>
            <td>
              <div class="fw-semibold text-dark" style="font-size: 0.825rem;">{{ $pinjam->nama_peminjam }}</div>
              <small class="text-muted d-block text-truncate" title="{{ $pinjam->instansi_peminjam }}" style="max-width: 200px;">
                <i class="ri-building-line me-1"></i>{{ $pinjam->instansi_peminjam }}
              </small>
              @if($pinjam->kontak_peminjam)
                <small class="text-muted d-block" style="font-size: 0.725rem;">
                  <i class="ri-phone-line me-1"></i>{{ $pinjam->kontak_peminjam }}
                </small>
              @endif
            </td>
            <td>
              <div class="d-flex flex-column gap-1">
                @foreach($pinjam->details as $d)
                  <div class="d-flex flex-wrap align-items-center gap-1" style="line-height: 1.25;">
                    <span class="badge bg-label-secondary font-monospace px-1 py-0" style="font-size: 0.68rem;">{{ $d->barang->kode_barang ?? '-' }}</span>
                    <span class="text-dark fw-medium" style="font-size: 0.785rem;">{{ $d->barang->nama_barang ?? '-' }}</span>
                    <span class="badge bg-label-info px-1 py-0" style="font-size: 0.68rem;">{{ $d->jumlah }} {{ $d->barang->satuan ?? 'Unit' }}</span>
                  </div>
                @endforeach
              </div>
            </td>
            <td>
              <div class="d-flex flex-column" style="font-size: 0.76rem; line-height: 1.3;">
                <span>Pinjam: <strong>{{ \Carbon\Carbon::parse($pinjam->tanggal_pinjam)->format('d/m/Y') }}</strong></span>
                <span>Kembali: <strong class="{{ $pinjam->is_overdue ? 'text-danger' : 'text-primary' }}">{{ \Carbon\Carbon::parse($pinjam->rencana_kembali)->format('d/m/Y') }}</strong></span>
                @if($pinjam->tanggal_kembali_aktual)
                  <span class="text-success">Aktual: <strong>{{ \Carbon\Carbon::parse($pinjam->tanggal_kembali_aktual)->format('d/m/Y') }}</strong></span>
                @endif
              </div>
            </td>
            <td class="text-center">
              @if($pinjam->status === 'dikembalikan')
                <span class="badge bg-label-success text-nowrap"><i class="ri-checkbox-circle-line me-1"></i>Kembali</span>
              @elseif($pinjam->status === 'terlambat')
                <span class="badge bg-label-danger text-nowrap"><i class="ri-alarm-warning-line me-1"></i>Terlambat</span>
              @else
                <span class="badge bg-label-warning text-nowrap"><i class="ri-time-line me-1"></i>Dipinjam</span>
              @endif
            </td>
            <td class="text-center">
              <div class="d-flex justify-content-center align-items-center gap-1 text-nowrap">
                <!-- Cetak Berita Acara PDF -->
                <a href="{{ route('peminjaman.cetak-berita-acara', $pinjam->id) }}" target="_blank" class="btn btn-xs btn-outline-primary px-2" title="Cetak Berita Acara Resmi (PDF)">
                  <i class="ri-printer-line me-1"></i> Cetak
                </a>

                <!-- Tombol Proses Pengembalian -->
                @if($pinjam->status !== 'dikembalikan')
                  <button type="button" class="btn btn-xs btn-outline-success px-2" title="Proses Pengembalian Alat" data-bs-toggle="modal" data-bs-target="#modalKembali{{ $pinjam->id }}">
                    <i class="ri-check-line me-1"></i> Kembali
                  </button>
                @endif

                <!-- Hapus Peminjaman -->
                <form action="{{ route('peminjaman.destroy', $pinjam->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan/menghapus berita acara peminjaman ini? Jika alat belum dikembalikan, stok alat akan dipulihkan otomatis ke gudang.');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-xs btn-icon btn-outline-danger" title="Hapus">
                    <i class="ri-delete-bin-line"></i>
                  </button>
                </form>
              </div>

              <!-- Modal Pengembalian Alat -->
              @if($pinjam->status !== 'dikembalikan')
              <div class="modal fade" id="modalKembali{{ $pinjam->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content text-start">
                    <form method="POST" action="{{ route('peminjaman.kembalikan', $pinjam->id) }}">
                      @csrf
                      <div class="modal-header border-bottom">
                        <h5 class="modal-title"><i class="ri-check-double-line me-2 text-success"></i>Verifikasi Pengembalian Alat</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <div class="alert alert-info py-2 small mb-3">
                          Nomor Surat: <strong>{{ $pinjam->nomor_surat }}</strong><br>
                          Peminjam: <strong>{{ $pinjam->nama_peminjam }}</strong> ({{ $pinjam->instansi_peminjam }})<br>
                          Rencana Kembali: <strong>{{ \Carbon\Carbon::parse($pinjam->rencana_kembali)->translatedFormat('d F Y') }}</strong>
                        </div>

                        <div class="mb-3">
                          <label class="form-label" for="tanggal_kembali_aktual_{{ $pinjam->id }}">Tanggal Pengembalian Aktual <span class="text-danger">*</span></label>
                          <input type="date" class="form-control" id="tanggal_kembali_aktual_{{ $pinjam->id }}" name="tanggal_kembali_aktual" value="{{ date('Y-m-d') }}" required />
                        </div>

                        <div class="mb-3">
                          <label class="form-label" for="kondisi_kembali_{{ $pinjam->id }}">Kondisi Fisik Saat Dikembalikan <span class="text-danger">*</span></label>
                          <select class="form-select" id="kondisi_kembali_{{ $pinjam->id }}" name="kondisi_kembali" required>
                            <option value="Baik" selected>Baik (Lengkap & Berfungsi Normal)</option>
                            <option value="Rusak Ringan">Rusak Ringan</option>
                            <option value="Rusak Berat">Rusak Berat</option>
                          </select>
                        </div>

                        <div class="row g-2 mb-3">
                          <div class="col-6">
                            <label class="form-label small" for="nama_penyerah_kembali_{{ $pinjam->id }}">Yang Menyerahkan Kembali <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="nama_penyerah_kembali_{{ $pinjam->id }}" name="nama_penyerah_kembali" value="{{ $pinjam->nama_peminjam }}" required />
                          </div>
                          <div class="col-6">
                            <label class="form-label small" for="nama_penerima_kembali_{{ $pinjam->id }}">Yang Menerima di BBWS <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="nama_penerima_kembali_{{ $pinjam->id }}" name="nama_penerima_kembali" value="{{ $pinjam->nama_penyerah }}" required />
                          </div>
                        </div>

                        <div class="mb-0">
                          <label class="form-label small" for="catatan_kembali_{{ $pinjam->id }}">Catatan Tambahan Pengembalian</label>
                          <textarea class="form-control form-control-sm" id="catatan_kembali_{{ $pinjam->id }}" name="catatan_kembali" rows="2" placeholder="Contoh: Alat dikembalikan lengkap dengan kabel konektor dan sensor probe."></textarea>
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
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" class="text-center py-5">
              <div class="d-flex flex-column align-items-center justify-content-center text-muted">
                <i class="ri-file-shield-line ri-4x mb-2 text-secondary opacity-50"></i>
                <h6>Belum Ada Berita Acara Peminjaman Alat</h6>
                <p class="small mb-3">Terbitkan berita acara peminjaman alat resmi untuk personil dinas maupun pihak eksternal.</p>
                <a href="{{ route('peminjaman.create') }}" class="btn btn-sm btn-primary">
                  <i class="ri-add-line me-1"></i> Terbitkan Berita Acara Sekarang
                </a>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
