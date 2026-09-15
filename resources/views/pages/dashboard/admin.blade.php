@php
   $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Admin Dashboard')

@section('vendor-style')
   @vite(['resources/assets/vendor/libs/apex-charts/apex-charts.scss', 'resources/assets/vendor/libs/swiper/swiper.scss'])
@endsection

@section('page-style')
   @vite(['resources/assets/vendor/scss/pages/cards-statistics.scss', 'resources/assets/vendor/scss/pages/cards-analytics.scss'])
@endsection

@section('vendor-script')
   @vite(['resources/assets/vendor/libs/apex-charts/apexcharts.js', 'resources/assets/vendor/libs/swiper/swiper.js'])
@endsection

@section('page-script')
   <script>
      window.chartData = {
         traffic: {
            masuk: {!! json_encode($trafficMasuk) !!},
            keluar: {!! json_encode($trafficKeluar) !!}
         },
         health: {
            months: {!! json_encode($healthMonths) !!},
            masuk: {!! json_encode($healthMasuk) !!},
            keluar: {!! json_encode($healthKeluar) !!}
         }
      };
   </script>
   @vite(['resources/assets/js/dashboards-analytics.js'])
@endsection

@section('content')
   <div class="row g-6">
      <!-- Welcome Card -->
      <div class="col-md-12 col-xxl-8">
         <div class="card h-100">
            <div class="d-flex align-items-end row h-100">
               <div class="col-md-6 order-2 order-md-1">
                  <div class="card-body">
                     <h4 class="card-title mb-4">Selamat Datang, <span class="fw-bold">{{ auth()->user()->name }}!</span> 👑
                     </h4>
                     <p class="mb-5">Sistem siap digunakan. Anda memiliki akses penuh untuk mengelola master data,
                        user, dan permission di portal ini.</p>
                     <div class="d-flex gap-2">
                        <a href="{{ route('user.index') }}" class="btn btn-primary">Kelola User</a>
                        <a href="{{ route('permission.index') }}" class="btn btn-label-secondary">Cek Akses</a>
                     </div>
                  </div>
               </div>
               <div class="col-md-6 text-center text-md-end order-1 order-md-2">
                  <div class="card-body pb-0 px-0 pt-2 h-100 d-flex align-items-end justify-content-center">
                     <img src="{{ asset('assets/img/illustrations/illustration-john-' . $configData['style'] . '.png') }}"
                        height="186" class="scaleX-n1-rtl" alt="View Profile"
                        data-app-light-img="illustrations/illustration-john-light.png"
                        data-app-dark-img="illustrations/illustration-john-dark.png">
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!--/ Welcome Card -->

      <!-- Quick Stats Total Barang -->
      <div class="col-xxl-2 col-sm-6">
         <div class="card h-100">
            <div class="card-body">
               <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                  <div class="avatar">
                     <div class="avatar-initial bg-label-info rounded-3">
                        <i class="ri-archive-line ri-24px"></i>
                     </div>
                  </div>
               </div>
               <div class="card-info mt-5">
                  <h5 class="mb-1">{{ $totalBarang ?? 0 }}</h5>
                  <p>Total Barang</p>
                  <div class="badge bg-label-secondary rounded-pill">Master Data</div>
               </div>
            </div>
         </div>
      </div>

      <!-- Quick Stats Stok Menipis -->
      <div class="col-xxl-2 col-sm-6">
         <div class="card h-100">
            <div class="card-body">
               <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                  <div class="avatar">
                     <div class="avatar-initial bg-label-danger rounded-3">
                        <i class="ri-error-warning-line ri-24px"></i>
                     </div>
                  </div>
               </div>
               <div class="card-info mt-5">
                  <h5 class="mb-1">{{ $stokMenipis ? $stokMenipis->count() : 0 }}</h5>
                  <p>Stok Menipis</p>
                  <div class="badge bg-label-danger rounded-pill">< 5 Unit</div>
               </div>
            </div>
         </div>
      </div>

      <!-- Quick Stats Masuk Bulan Ini -->
      <div class="col-xxl-2 col-sm-6">
         <div class="card h-100">
            <div class="card-body">
               <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                  <div class="avatar">
                     <div class="avatar-initial bg-label-success rounded-3">
                        <i class="ri-arrow-right-down-line ri-24px"></i>
                     </div>
                  </div>
               </div>
               <div class="card-info mt-5">
                  <h5 class="mb-1">{{ $masukBulanIni ?? 0 }}</h5>
                  <p>Barang Masuk</p>
                  <div class="badge bg-label-success rounded-pill">Bulan Ini</div>
               </div>
            </div>
         </div>
      </div>

      <!-- Quick Stats Keluar Bulan Ini -->
      <div class="col-xxl-2 col-sm-6">
         <div class="card h-100">
            <div class="card-body">
               <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                  <div class="avatar">
                     <div class="avatar-initial bg-label-warning rounded-3">
                        <i class="ri-arrow-right-up-line ri-24px"></i>
                     </div>
                  </div>
               </div>
               <div class="card-info mt-5">
                  <h5 class="mb-1">{{ $keluarBulanIni ?? 0 }}</h5>
                  <p>Barang Keluar</p>
                  <div class="badge bg-label-warning rounded-pill">Bulan Ini</div>
               </div>
            </div>
         </div>
      </div>

      <!-- Performance & Projects (Placeholder of common admin view) -->
      <div class="col-12 col-xxl-8">
         <div class="card h-100">
            <div class="row row-bordered g-0 h-100">
               <div class="col-md-7 col-12 order-2 order-md-0">
                  <div class="card-header d-flex align-items-center justify-content-between">
                     <h5 class="mb-0">Transaksi 7 Hari Terakhir</h5>
                     <small class="text-muted">Keluar & Masuk Barang</small>
                  </div>
                  <div class="card-body">
                     <div id="totalTransactionChart"></div>
                  </div>
               </div>
               <div class="col-md-5 col-12">
                  <div class="card-header">
                     <h5 class="mb-1">Quick Actions</h5>
                     <p class="mb-0 card-subtitle">Shortcut to management</p>
                  </div>
                  <div class="card-body pt-6">
                     <ul class="list-unstyled mb-0">
                        <li class="d-flex align-items-center mb-4">
                           <div class="avatar avatar-sm me-3">
                              <span class="avatar-initial rounded bg-label-success"><i
                                    class="ri-add-line ri-18px"></i></span>
                           </div>
                           <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                              <div class="me-2">
                                 <h6 class="mb-0">Tambah Barang Baru</h6>
                                 <small class="text-muted">Master Data</small>
                              </div>
                              <a href="{{ route('barang.create') }}"
                                 class="btn btn-sm btn-icon btn-text-secondary rounded-pill"><i
                                    class="ri-arrow-right-s-line"></i></a>
                           </div>
                        </li>
                        <li class="d-flex align-items-center mb-4">
                           <div class="avatar avatar-sm me-3" style="cursor: pointer;"
                              onclick="location.href='{{ route('menu.index') }}'">
                              <span class="avatar-initial rounded bg-label-warning"><i
                                    class="ri-menu-search-line ri-18px"></i></span>
                           </div>
                           <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                              <div class="me-2">
                                 <h6 class="mb-0">Atur Menu Sidebar</h6>
                                 <small class="text-muted">Navigasi Dinamis</small>
                              </div>
                              <a href="{{ route('menu.index') }}"
                                 class="btn btn-sm btn-icon btn-text-secondary rounded-pill"><i
                                    class="ri-arrow-right-s-line"></i></a>
                           </div>
                        </li>
                        <li class="d-flex align-items-center">
                           <div class="avatar avatar-sm me-3">
                              <span class="avatar-initial rounded bg-label-info"><i
                                    class="ri-history-line ri-18px"></i></span>
                           </div>
                           <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                              <div class="me-2">
                                 <h6 class="mb-0">Log Aktivitas</h6>
                                 <small class="text-muted">Audit System</small>
                              </div>
                              <a href="{{ route('activity-log.index') }}"
                                 class="btn btn-sm btn-icon btn-text-secondary rounded-pill"><i
                                    class="ri-arrow-right-s-line"></i></a>
                           </div>
                        </li>
                     </ul>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <!-- Performance Chart -->
      <div class="col-12 col-xxl-4 col-md-6">
         <div class="card h-100">
            <div class="card-header">
               <div class="d-flex justify-content-between">
                  <h5 class="mb-1">Tren Bulanan</h5>
               </div>
            </div>
            <div class="card-body">
               <div id="performanceChart"></div>
            </div>
         </div>
      </div>
   </div>
@endsection
