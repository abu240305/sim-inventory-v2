@extends('layouts/layoutMaster')

@section('title', 'Transaksi Barang & Alat Keluar')

@section('content')
<div class="row mb-4">
  <div class="col-12">
    <div class="card">
      <div class="card-header border-bottom py-3 d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
          <h5 class="mb-1 text-primary"><i class="ri-file-upload-line me-2"></i>Transaksi Pengeluaran Barang & Alat</h5>
          <p class="text-muted mb-0 small">Pencatatan sirkulasi barang keluar dan cetak <strong>Form Keluar Alat / Barang</strong> resmi</p>
        </div>
        <div>
          <a href="{{ route('transaksi-keluar.create') }}" class="btn btn-primary">
            <i class="ri-add-line me-1"></i> Catat Pengeluaran Baru
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
                <th style="width: 14%;">Kode Transaksi</th>
                <th style="width: 10%;">Tanggal</th>
                <th style="width: 22%;">Barang / Alat</th>
                <th style="width: 11%;">Jumlah Keluar</th>
                <th style="width: 18%;">Penerima & Bagian</th>
                <th style="width: 15%;">Keperluan / Notes</th>
                <th class="text-center" style="width: 10%;">Aksi</th>
              </tr>
            </thead>
            <tbody class="table-border-bottom-0">
              @forelse($transaksis as $index => $transaksi)
              @php
                $sisaBisaRetur = $transaksi->jumlah - ($transaksi->jumlah_retur ?? 0);
              @endphp
              <tr>
                <td class="text-center text-muted small">{{ $index + 1 }}</td>
                <td>
                  <span class="fw-semibold text-primary font-monospace" style="font-size: 0.78rem;">
                    {{ $transaksi->kode_transaksi ?: ('TRK-' . str_pad($transaksi->id, 5, '0', STR_PAD_LEFT)) }}
                  </span>
                </td>
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
                  <div class="d-flex flex-column">
                    <span class="fw-bold text-danger" style="font-size: 0.82rem;">-{{ $transaksi->jumlah }} {{ $transaksi->barang->satuan ?? 'Unit' }}</span>
                    @if(($transaksi->jumlah_retur ?? 0) > 0)
                      <span class="badge bg-label-info mt-1" style="font-size: 0.68rem;" title="{{ $transaksi->catatan_retur }}">
                        <i class="ri-arrow-go-back-line me-1"></i>Retur: {{ $transaksi->jumlah_retur }}
                      </span>
                    @endif
                  </div>
                </td>
                <td>
                  <div class="d-flex flex-column">
                    <span class="fw-medium text-dark" style="font-size: 0.815rem;">{{ $transaksi->penerima }}</span>
                    <small class="text-muted" style="font-size: 0.725rem;"><i class="ri-building-line me-1"></i>{{ $transaksi->bagian }}</small>
                  </div>
                </td>
                <td>
                  <div style="font-size: 0.76rem; line-height: 1.3;" class="text-muted">
                    {{ $transaksi->keperluan ?: ($transaksi->keterangan ?: '-') }}
                  </div>
                </td>
                <td class="text-center">
                  <div class="d-flex justify-content-center align-items-center gap-1 text-nowrap">
                    <!-- Cetak Form PDF (Dokumen 1) -->
                    <a href="{{ route('transaksi-keluar.cetak-form', $transaksi->id) }}" target="_blank" class="btn btn-xs btn-outline-primary px-2" title="Cetak Form Keluar (PDF)">
                      <i class="ri-printer-line me-1"></i> Form
                    </a>

                    <!-- Tombol Retur Barang -->
                    @if($sisaBisaRetur > 0)
                      <button type="button" class="btn btn-xs btn-icon btn-outline-warning" title="Retur / Kembalikan ke Stok Gudang" data-bs-toggle="modal" data-bs-target="#modalRetur{{ $transaksi->id }}">
                        <i class="ri-arrow-go-back-line"></i>
                      </button>
                    @endif

                    <!-- Edit Transaksi -->
                    <a href="{{ route('transaksi-keluar.edit', $transaksi->id) }}" class="btn btn-xs btn-icon btn-outline-info" title="Edit Transaksi">
                      <i class="ri-edit-line"></i>
                    </a>

                    <!-- Hapus Transaksi -->
                    <form action="{{ route('transaksi-keluar.destroy', $transaksi->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan & menghapus transaksi ini? Stok barang akan otomatis dipulihkan kembali ke gudang.');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-xs btn-icon btn-outline-danger" title="Hapus & Pulihkan Stok">
                        <i class="ri-delete-bin-line"></i>
                      </button>
                    </form>
                  </div>

                  <!-- Modal Retur Barang -->
                  @if($sisaBisaRetur > 0)
                  <div class="modal fade" id="modalRetur{{ $transaksi->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content text-start">
                        <form method="POST" action="{{ route('transaksi-keluar.retur', $transaksi->id) }}">
                          @csrf
                          <div class="modal-header border-bottom">
                            <h5 class="modal-title"><i class="ri-arrow-go-back-line me-2 text-warning"></i>Retur Barang Keluar</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            <div class="alert alert-info py-2 small">
                              Barang: <strong>{{ $transaksi->barang->nama_barang ?? '-' }}</strong><br>
                              Kuantitas Keluar: <strong>{{ $transaksi->jumlah }} {{ $transaksi->barang->satuan ?? 'Unit' }}</strong><br>
                              Maksimal yang dapat diretur: <strong>{{ $sisaBisaRetur }} {{ $transaksi->barang->satuan ?? 'Unit' }}</strong>
                            </div>

                            <div class="mb-3">
                              <label class="form-label" for="jumlah_retur_{{ $transaksi->id }}">Jumlah yang Diretur / Dikembalikan ke Gudang <span class="text-danger">*</span></label>
                              <input type="number" class="form-control" id="jumlah_retur_{{ $transaksi->id }}" name="jumlah_retur" min="1" max="{{ $sisaBisaRetur }}" value="{{ $sisaBisaRetur }}" required />
                            </div>

                            <div class="mb-3">
                              <label class="form-label" for="catatan_retur_{{ $transaksi->id }}">Alasan / Catatan Pengembalian</label>
                              <textarea class="form-control" id="catatan_retur_{{ $transaksi->id }}" name="catatan_retur" rows="3" placeholder="Contoh: Sisa material lapangan tidak terpakai, dikembalikan ke gudang"></textarea>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-warning">
                              <i class="ri-check-line me-1"></i> Proses Pengembalian Stok
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
                <td colspan="8" class="text-center py-5">
                  <div class="d-flex flex-column align-items-center justify-content-center text-muted">
                    <i class="ri-file-list-3-line ri-4x mb-2 text-secondary opacity-50"></i>
                    <h6>Belum Ada Transaksi Pengeluaran Barang / Alat</h6>
                    <p class="small mb-3">Catat pengeluaran barang dinas lapangan untuk menghasilkan Form Keluar resmi.</p>
                    <a href="{{ route('transaksi-keluar.create') }}" class="btn btn-sm btn-primary">
                      <i class="ri-add-line me-1"></i> Catat Pengeluaran Sekarang
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
