@extends('layouts/layoutMaster')

@section('title', 'Master Barang & Alat')

@section('content')
<div class="row mb-4">
  <div class="col-12">
    <div class="card">
      <div class="card-header border-bottom py-3 d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
          <h5 class="mb-1 text-primary"><i class="ri-archive-line me-2"></i>Master Inventaris Barang & Alat</h5>
          <p class="text-muted mb-0 small">Pengelolaan dan pengelompokan material/bahan dan peralatan kerja Unit Hidrologi & Kualitas Air BBWS-PJ</p>
        </div>
        <div class="d-flex gap-2">
          <a href="{{ route('barang.create') }}" class="btn btn-primary">
            <i class="ri-add-line me-1"></i> Tambah Barang / Alat Baru
          </a>
        </div>
      </div>

      <!-- Nav Filter Tabs -->
      <div class="card-body pb-0 pt-3">
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

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
          <ul class="nav nav-pills flex-column flex-sm-row">
            <li class="nav-item">
              <a class="nav-link {{ empty($selectedJenis) ? 'active' : '' }}" href="{{ route('barang.index') }}">
                <i class="ri-apps-2-line me-1"></i> Semua Barang
                <span class="badge rounded-pill bg-white text-primary ms-1">{{ $totalSemua }}</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ $selectedJenis == 'alat' ? 'active' : '' }}" href="{{ route('barang.index', ['jenis' => 'alat']) }}">
                <i class="ri-tools-line me-1"></i> Alat / Instrumen
                <span class="badge rounded-pill bg-label-info ms-1 {{ $selectedJenis == 'alat' ? 'bg-white text-primary' : '' }}">{{ $totalAlat }}</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ $selectedJenis == 'bahan' ? 'active' : '' }}" href="{{ route('barang.index', ['jenis' => 'bahan']) }}">
                <i class="ri-drop-line me-1"></i> Bahan / Material
                <span class="badge rounded-pill bg-label-warning ms-1 {{ $selectedJenis == 'bahan' ? 'bg-white text-primary' : '' }}">{{ $totalBahan }}</span>
              </a>
            </li>
          </ul>

          <!-- Form Search -->
          <form method="GET" action="{{ route('barang.index') }}" class="d-flex align-items-center gap-2">
            @if(!empty($selectedJenis))
              <input type="hidden" name="jenis" value="{{ $selectedJenis }}">
            @endif
            <div class="input-group input-group-merge">
              <span class="input-group-text"><i class="ri-search-line"></i></span>
              <input type="text" name="search" class="form-control" placeholder="Cari kode, nama, merk..." value="{{ request('search') }}">
            </div>
            <button type="submit" class="btn btn-outline-secondary">Cari</button>
            @if(request('search'))
              <a href="{{ route('barang.index', !empty($selectedJenis) ? ['jenis' => $selectedJenis] : []) }}" class="btn btn-outline-danger" title="Reset filter">
                <i class="ri-close-line"></i>
              </a>
            @endif
          </form>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th style="width: 35px;" class="text-center">No</th>
              <th style="width: 11%;">Kode</th>
              <th style="width: 24%;">Nama Barang / Alat</th>
              <th style="width: 12%;">Jenis</th>
              <th style="width: 12%;">Kategori</th>
              <th style="width: 11%;">Jumlah Stok</th>
              <th style="width: 11%;">Kondisi</th>
              <th style="width: 11%;">Lokasi</th>
              <th class="text-center" style="width: 80px;">Aksi</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            @forelse($barangs as $index => $barang)
            <tr>
              <td class="text-center text-muted small">{{ $index + 1 }}</td>
              <td><span class="fw-semibold text-primary font-monospace" style="font-size: 0.78rem;">{{ $barang->kode_barang }}</span></td>
              <td>
                <div class="d-flex flex-column">
                  <span class="fw-medium text-dark" style="font-size: 0.815rem;">{{ $barang->nama_barang }}</span>
                  @if($barang->merk_tipe)
                    <small class="text-muted" style="font-size: 0.725rem;"><i class="ri-price-tag-line me-1"></i>{{ $barang->merk_tipe }}</small>
                  @endif
                </div>
              </td>
              <td>
                @if($barang->jenis == 'alat')
                  <span class="badge bg-label-primary text-nowrap"><i class="ri-tools-line me-1"></i>Alat</span>
                @else
                  <span class="badge bg-label-warning text-nowrap"><i class="ri-drop-line me-1"></i>Bahan</span>
                @endif
              </td>
              <td>
                <span class="badge bg-label-secondary text-nowrap">{{ $barang->kategori->nama_kategori ?? '-' }}</span>
              </td>
              <td>
                <div class="d-flex align-items-center gap-1">
                  @if($barang->jumlah <= 0)
                    <span class="badge bg-label-danger">0</span>
                  @elseif($barang->jumlah < 5)
                    <span class="badge bg-label-warning">{{ $barang->jumlah }}</span>
                  @else
                    <span class="badge bg-label-success">{{ $barang->jumlah }}</span>
                  @endif
                  <span class="text-muted small" style="font-size: 0.75rem;">{{ $barang->satuan ?? 'Unit' }}</span>
                </div>
              </td>
              <td>
                @if(strtolower($barang->kondisi) == 'baik')
                  <span class="badge bg-label-success text-nowrap">{{ $barang->kondisi }}</span>
                @elseif(str_contains(strtolower($barang->kondisi), 'rusak'))
                  <span class="badge bg-label-danger text-nowrap">{{ $barang->kondisi }}</span>
                @else
                  <span class="text-muted small">{{ $barang->kondisi ?? '-' }}</span>
                @endif
              </td>
              <td>
                <span class="text-muted small d-inline-block text-truncate" style="max-width: 110px;" title="{{ $barang->lokasi }}">
                  <i class="ri-map-pin-line me-1"></i>{{ $barang->lokasi ?? '-' }}
                </span>
              </td>
              <td class="text-center">
                <div class="d-flex justify-content-center gap-1 text-nowrap">
                  <a href="{{ route('barang.edit', $barang->id) }}" class="btn btn-xs btn-icon btn-outline-info" title="Edit">
                    <i class="ri-edit-line"></i>
                  </a>
                  <form action="{{ route('barang.destroy', $barang->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-xs btn-icon btn-outline-danger" title="Hapus">
                      <i class="ri-delete-bin-line"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="9" class="text-center py-5">
                <div class="d-flex flex-column align-items-center justify-content-center text-muted">
                  <i class="ri-inbox-line ri-4x mb-2 text-secondary opacity-50"></i>
                  <h6>Belum Ada Data Barang / Alat</h6>
                  <p class="small mb-3">Silakan tambah barang baru atau ubah kata kunci filter Anda.</p>
                  <a href="{{ route('barang.create') }}" class="btn btn-sm btn-primary">
                    <i class="ri-add-line me-1"></i> Tambah Barang Sekarang
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
@endsection
