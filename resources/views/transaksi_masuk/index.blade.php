@extends('layouts/layoutMaster')

@section('title', 'Transaksi Barang & Alat Masuk')

@section('content')
<div class="row mb-4">
  <div class="col-12">
    <div class="card">
      <div class="card-header border-bottom py-3 d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
          <h5 class="mb-1 text-primary"><i class="ri-file-download-line me-2"></i>Transaksi Penerimaan Barang & Alat Masuk</h5>
          <p class="text-muted mb-0 small">Pencatatan pengadaan, penerimaan material, dan instrumen baru ke stok gudang</p>
        </div>
        <div>
          <a href="{{ route('transaksi-masuk.create') }}" class="btn btn-primary">
            <i class="ri-add-line me-1"></i> Catat Barang Masuk
          </a>
        </div>
      </div>

      <div class="card-body pt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ri-checkbox-circle-line me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="ri-error-warning-line me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th style="width: 35px;" class="text-center">No</th>
                <th style="width: 11%;">Tanggal</th>
                <th style="width: 25%;">Barang / Alat</th>
                <th style="width: 12%;">Jumlah Masuk</th>
                <th style="width: 18%;">Sumber / Rekanan</th>
                <th style="width: 18%;">Keterangan</th>
                <th style="width: 10%;">Petugas Input</th>
                <th class="text-center" style="width: 70px;">Aksi</th>
              </tr>
            </thead>
            <tbody class="table-border-bottom-0">
              @forelse($transaksis as $index => $transaksi)
              <tr>
                <td class="text-center text-muted small">{{ $index + 1 }}</td>
                <td class="text-muted small">{{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d/m/Y') }}</td>
                <td>
                  <div class="d-flex flex-column">
                    <span class="fw-medium text-dark" style="font-size: 0.815rem;">{{ $transaksi->barang->nama_barang ?? '-' }}</span>
                    <div class="d-flex align-items-center gap-1 mt-0.5">
                      @if(($transaksi->barang->jenis ?? '') == 'alat')
                        <span class="badge bg-label-primary px-1 py-0" style="font-size: 0.68rem;"><i class="ri-tools-line me-1"></i>Alat</span>
                      @else
                        <span class="badge bg-label-warning px-1 py-0" style="font-size: 0.68rem;"><i class="ri-drop-line me-1"></i>Bahan</span>
                      @endif
                      <small class="text-muted font-monospace" style="font-size: 0.7rem;">{{ $transaksi->barang->kode_barang ?? '-' }}</small>
                    </div>
                  </div>
                </td>
                <td>
                  <span class="fw-bold text-success" style="font-size: 0.82rem;">+{{ $transaksi->jumlah }} {{ $transaksi->barang->satuan ?? 'Unit' }}</span>
                </td>
                <td>
                  <span class="text-dark" style="font-size: 0.815rem;">{{ $transaksi->sumber ?: '-' }}</span>
                </td>
                <td>
                  <span class="small text-muted">{{ $transaksi->keterangan ?: '-' }}</span>
                </td>
                <td>
                  <span class="small text-muted"><i class="ri-user-line me-1"></i>{{ $transaksi->user->name ?? '-' }}</span>
                </td>
                <td class="text-center">
                  <div class="d-flex justify-content-center align-items-center gap-1 text-nowrap">
                    <a href="{{ route('transaksi-masuk.edit', $transaksi->id) }}" class="btn btn-xs btn-icon btn-outline-info" title="Edit Transaksi">
                      <i class="ri-edit-line"></i>
                    </a>
                    <form action="{{ route('transaksi-masuk.destroy', $transaksi->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan transaksi penerimaan ini? Stok di gudang akan otomatis dikurangi.');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-xs btn-icon btn-outline-danger" title="Hapus Transaksi">
                        <i class="ri-delete-bin-line"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="8" class="text-center py-5">
                  <div class="d-flex flex-column align-items-center justify-content-center text-muted">
                    <i class="ri-inbox-archive-line ri-4x mb-2 text-secondary opacity-50"></i>
                    <h6>Belum Ada Transaksi Penerimaan Barang Masuk</h6>
                    <p class="small mb-3">Catat barang atau material yang baru diterima untuk menambah stok gudang.</p>
                    <a href="{{ route('transaksi-masuk.create') }}" class="btn btn-sm btn-primary">
                      <i class="ri-add-line me-1"></i> Catat Barang Masuk Sekarang
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
  </div>
</div>
@endsection
