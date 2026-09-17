@php
   $configData = Helper::appClasses();
   $stokMenipis = $stokMenipis ?? collect();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Dashboard')

@section('vendor-style')
   @vite(['resources/assets/vendor/libs/apex-charts/apex-charts.scss', 'resources/assets/vendor/libs/swiper/swiper.scss'])
@endsection

@section('page-style')
   @vite(['resources/assets/vendor/scss/pages/cards-statistics.scss', 'resources/assets/vendor/scss/pages/cards-analytics.scss'])

   @include("pages.dashboard._hero_style")
@endsection

@section('vendor-script')
   @vite(['resources/assets/vendor/libs/apex-charts/apexcharts.js', 'resources/assets/vendor/libs/swiper/swiper.js'])
@endsection

@section('page-script')
   <script>
      window.chartData = {
         daily: {
            labels: {!! json_encode($trafficDays) !!},
            masuk: {!! json_encode($trafficMasuk) !!},
            keluar: {!! json_encode($trafficKeluar) !!}
         },
         weekly: {
            labels: {!! json_encode($weeklyLabels) !!},
            masuk: {!! json_encode($weeklyMasuk) !!},
            keluar: {!! json_encode($weeklyKeluar) !!}
         },
         monthly: {
            labels: {!! json_encode($monthlyLabels) !!},
            masuk: {!! json_encode($monthlyMasuk) !!},
            keluar: {!! json_encode($monthlyKeluar) !!}
         }
      };
   </script>
   @vite(['resources/assets/js/dashboards-analytics.js'])
@endsection

@section('content')
@php
   $user = auth()->user();
   $isSuperAdmin = $user && $user->role && $user->role->slug === 'super-admin';
   $canManageBarang = $isSuperAdmin || ($user && $user->hasPermission('barang.index', 'read'));
   $canCreateBarang = $isSuperAdmin || ($user && $user->hasPermission('barang.index', 'create'));
   $canManageTransaksiMasuk = $isSuperAdmin || ($user && $user->hasPermission('transaksi-masuk.index', 'read'));
   $canManageTransaksiKeluar = $isSuperAdmin || ($user && $user->hasPermission('transaksi-keluar.index', 'read'));
   $canManageMenu = $isSuperAdmin || ($user && $user->hasPermission('menu.index', 'read'));
   $canManageActivityLog = $isSuperAdmin || ($user && $user->hasPermission('activity-log.index', 'read'));
   
   $hasAnyStatPermission = $canManageBarang || $canManageTransaksiMasuk || $canManageTransaksiKeluar;
   $hasAnyChartPermission = $canManageTransaksiMasuk || $canManageTransaksiKeluar;
   $hasAnyQuickAction = $canCreateBarang || $canManageMenu || $canManageActivityLog;
@endphp
   <div class="row g-4">

      {{-- ============ HERO BANNER ============ --}}
      <div class="{{ $hasAnyStatPermission ? 'col-lg-8' : 'col-lg-12' }} col-12">
         @include("pages.dashboard._hero_banner")
      </div>

      @if($hasAnyStatPermission)
      {{-- ============ STATISTIK RINGKAS ============ --}}
      <div class="col-lg-4 col-12">
         <div class="row g-4 h-100">
            @if($canManageBarang)
            <div class="col-6">
               <div class="card stat-card h-100 shadow-sm">
                  <div class="card-body">
                     <div class="avatar">
                        <div class="avatar-initial bg-label-info rounded-3">
                           <i class="ri-price-tag-3-line ri-24px"></i>
                        </div>
                     </div>
                     <div class="mt-3">
                        <h5 class="stat-card__value mb-0">{{ $totalBarang ?? 0 }}</h5>
                        <p class="text-muted mb-2 small">Jenis barang terdaftar</p>
                        <span class="badge bg-label-secondary rounded-pill" style="font-size: 0.65rem;">Master data</span>
                     </div>
                  </div>
               </div>
            </div>

            <div class="col-6">
               <div class="card stat-card h-100 shadow-sm">
                  <div class="card-body">
                     <div class="avatar">
                        <div class="avatar-initial bg-label-primary rounded-3">
                           <i class="ri-archive-stack-line ri-24px"></i>
                        </div>
                     </div>
                     <div class="mt-3">
                        <h5 class="stat-card__value mb-0">{{ $totalStokFisik ?? 0 }}</h5>
                        <p class="text-muted mb-2 small">Sisa stok gudang</p>
                        @if ($stokMenipis->count() > 0)
                           <button type="button" class="badge bg-label-danger rounded-pill border-0" data-bs-toggle="modal"
                              data-bs-target="#stokMenipisModal" style="font-size: 0.65rem;">
                              {{ $stokMenipis->count() }} barang menipis
                           </button>
                        @else
                           <span class="badge bg-label-success rounded-pill" style="font-size: 0.65rem;">Stok aman</span>
                        @endif
                     </div>
                  </div>
               </div>
            </div>
            @endif

            @if($canManageTransaksiMasuk)
            <div class="col-6">
               <div class="card stat-card h-100 shadow-sm">
                  <div class="card-body">
                     <div class="avatar">
                        <div class="avatar-initial bg-label-success rounded-3">
                           <i class="ri-arrow-right-down-line ri-24px"></i>
                        </div>
                     </div>
                     <div class="mt-3">
                        <h5 class="stat-card__value mb-0">{{ $masukBulanIni ?? 0 }}</h5>
                        <p class="text-muted mb-2 small">Barang masuk</p>
                        <span class="badge bg-label-success rounded-pill" style="font-size: 0.65rem;">Bulan ini</span>
                     </div>
                  </div>
               </div>
            </div>
            @endif

            @if($canManageTransaksiKeluar)
            <div class="col-6">
               <div class="card stat-card h-100 shadow-sm">
                  <div class="card-body">
                     <div class="avatar">
                        <div class="avatar-initial bg-label-warning rounded-3">
                           <i class="ri-arrow-right-up-line ri-24px"></i>
                        </div>
                     </div>
                     <div class="mt-3">
                        <h5 class="stat-card__value mb-0">{{ $keluarBulanIni ?? 0 }}</h5>
                        <p class="text-muted mb-2 small">Barang keluar</p>
                        <span class="badge bg-label-warning rounded-pill" style="font-size: 0.65rem;">Bulan ini</span>
                     </div>
                  </div>
               </div>
            </div>
            @endif
         </div>
      </div>
      @endif

      @if($hasAnyChartPermission || $hasAnyQuickAction)
      {{-- ============ GRAFIK + AKSI CEPAT ============ --}}
      <div class="col-12">
         <div class="card">
            <div class="row row-bordered g-0">

               @if($hasAnyChartPermission)
               <div class="col-md-7 order-2 order-md-1">
                  <div class="card-header d-flex align-items-start justify-content-between">
                     <div>
                        <h5 class="mb-0" id="chartTitle">Grafik transaksi (harian)</h5>
                        <small class="text-muted">Barang masuk dan keluar</small>
                     </div>
                     <div class="dropdown">
                        <button class="btn btn-sm btn-icon btn-text-secondary rounded-pill" type="button"
                           id="transactionFilter" data-bs-toggle="dropdown" aria-expanded="false"
                           aria-label="Ubah rentang waktu grafik">
                           <i class="ri-more-2-line ri-20px"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="transactionFilter">
                           <a class="dropdown-item" href="javascript:void(0);"
                              onclick="window.updateTransactionChart('daily')">Harian</a>
                           <a class="dropdown-item" href="javascript:void(0);"
                              onclick="window.updateTransactionChart('weekly')">Mingguan</a>
                           <a class="dropdown-item" href="javascript:void(0);"
                              onclick="window.updateTransactionChart('monthly')">Bulanan</a>
                        </div>
                     </div>
                  </div>
                  <div class="card-body">
                     <div id="totalTransactionChart"></div>
                  </div>
               </div>
               @endif

               @if($hasAnyQuickAction)
               <div class="{{ $hasAnyChartPermission ? 'col-md-5 order-1 order-md-2' : 'col-12' }}">
                  <div class="card-header">
                     <h5 class="mb-1">Aksi cepat</h5>
                     <p class="mb-0 card-subtitle">Pintasan ke halaman pengelolaan</p>
                  </div>
                  <div class="card-body pt-4">
                     <ul class="list-unstyled mb-0">
                        @if($canCreateBarang)
                        <li class="d-flex align-items-center mb-4">
                           <div class="avatar avatar-sm me-3">
                              <span class="avatar-initial rounded bg-label-success"><i class="ri-add-line ri-18px"></i></span>
                           </div>
                           <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                              <div class="me-2">
                                 <h6 class="mb-0">Tambah barang baru</h6>
                                 <small class="text-muted">Master data</small>
                              </div>
                              <a href="{{ route('barang.create') }}"
                                 class="btn btn-sm btn-icon btn-text-secondary rounded-pill"
                                 aria-label="Buka form tambah barang"><i class="ri-arrow-right-s-line"></i></a>
                           </div>
                        </li>
                        @endif

                        @if($canManageMenu)
                        <li class="d-flex align-items-center mb-4">
                           <div class="avatar avatar-sm me-3">
                              <span class="avatar-initial rounded bg-label-warning"><i class="ri-menu-search-line ri-18px"></i></span>
                           </div>
                           <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                              <div class="me-2">
                                 <h6 class="mb-0">Atur menu sidebar</h6>
                                 <small class="text-muted">Navigasi dinamis</small>
                              </div>
                              <a href="{{ route('menu.index') }}"
                                 class="btn btn-sm btn-icon btn-text-secondary rounded-pill"
                                 aria-label="Buka pengaturan menu"><i class="ri-arrow-right-s-line"></i></a>
                           </div>
                        </li>
                        @endif

                        @if($canManageActivityLog)
                        <li class="d-flex align-items-center">
                           <div class="avatar avatar-sm me-3">
                              <span class="avatar-initial rounded bg-label-info"><i class="ri-history-line ri-18px"></i></span>
                           </div>
                           <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                              <div class="me-2">
                                 <h6 class="mb-0">Log aktivitas</h6>
                                 <small class="text-muted">Audit sistem</small>
                              </div>
                              <a href="{{ route('activity-log.index') }}"
                                 class="btn btn-sm btn-icon btn-text-secondary rounded-pill"
                                 aria-label="Buka log aktivitas"><i class="ri-arrow-right-s-line"></i></a>
                           </div>
                        </li>
                        @endif
                     </ul>
                  </div>
               </div>
               @endif

            </div>
         </div>
      </div>
      @endif

   </div>

   @if($canManageBarang)
   {{-- ============ MODAL STOK MENIPIS ============ --}}
   <div class="modal fade" id="stokMenipisModal" tabindex="-1" aria-labelledby="stokMenipisModalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title" id="stokMenipisModalLabel">Barang dengan stok di bawah 5 unit</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body p-0">
               <ul class="list-group list-group-flush">
                  @forelse ($stokMenipis as $barang)
                     <li class="list-group-item d-flex justify-content-between align-items-center gap-3">
                        <span>{{ $barang->nama_barang }}</span>
                        <span class="badge bg-danger rounded-pill">{{ $barang->jumlah }} unit</span>
                     </li>
                  @empty
                     <li class="list-group-item text-center text-muted py-4">
                        Semua stok masih aman. Tidak ada barang yang perlu ditambah.
                     </li>
                  @endforelse
               </ul>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
               <a href="{{ route('barang.index') }}" class="btn btn-primary">Buka master barang</a>
            </div>
         </div>
      </div>
   </div>
   @endif
@endsection