<div class="card hero shadow-lg h-100">

   <div class="hero__photo">
      <img src="{{ asset('assets/img/backgrounds/bg-bbws.png') }}" alt="" aria-hidden="true">
   </div>
   <div class="hero__scrim"></div>

   <div class="hero__ribbon">
      <svg viewBox="0 0 1440 78" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none"
         aria-hidden="true">
         <path d="M0 30C420 44 940 22 1440 0V10C940 32 420 54 0 40V30Z" fill="#FFC72C" />
         <path d="M0 40C420 54 940 32 1440 10V78H0V40Z" fill="#FFFFFF" />
      </svg>
   </div>

   <div class="hero__band"></div>

   <img src="{{ asset('assets/img/illustrations/characters-pu.png') }}" alt="" class="hero__character d-none d-lg-block"
      aria-hidden="true">

   <div class="hero__body">

      {{-- Baris atas: identitas instansi + tagline --}}
      <div class="d-flex flex-wrap justify-content-between align-items-start gap-4">
         <div>
            <div class="d-flex align-items-center gap-3">
               <div class="hero__logo">
                  <img src="{{ asset('assets/img/lo-pu.jpeg') }}" alt="Logo Kementerian Pekerjaan Umum">
               </div>
               <h6 class="hero__ministry">
                  Kementerian<br>Pekerjaan Umum
                  <small>Republik Indonesia</small>
               </h6>
            </div>

            <p class="hero__unit">
               Balai Besar Wilayah Sungai<br>Pompengan Jeneberang
               <small>Makassar</small>
            </p>

            <div class="hero__rule"></div>
         </div>

         <div class="d-none d-xl-flex align-items-center gap-4">
            <div class="hero__tagline">Bersama<br>Mengalirkan<br>Manfaat</div>
            <div class="hero__sigap">
               <img src="{{ asset('assets/img/logo-sigap.png') }}" alt="Sigap Membangun Negeri"
                  onerror="this.closest('.hero__sigap').style.display='none'">
            </div>
         </div>
      </div>

      {{-- Sambutan --}}
      <div class="row">
         <div class="col-xl-9 col-lg-8">
            <h1 class="hero__title">
               Selamat Datang,<br><span>{{ auth()->user()->name ?? 'Pengguna' }}!</span>
            </h1>

            @php
               $user = auth()->user();
               $isSuperAdmin = $user && $user->role && $user->role->slug === 'super-admin';
               $canManageUser = $isSuperAdmin || ($user && $user->hasPermission('user.index', 'read'));
               $canManageAccess = $isSuperAdmin || ($user && $user->hasPermission('permission.index', 'read'));
               $canManageBarang = $isSuperAdmin || ($user && $user->hasPermission('barang.index', 'read'));
               $canManageTransaksi = $isSuperAdmin || ($user && $user->hasPermission('transaksi-masuk.index', 'read'));
            @endphp

            @if($canManageUser || $canManageAccess)
               <p class="hero__lead">
                  Sistem siap digunakan. Anda punya akses untuk mengelola master data, pengguna, dan hak akses di portal
                  ini.
               </p>
            @else
               <p class="hero__lead">
                  Sistem siap digunakan. Jelajahi dan kelola data Anda melalui menu yang tersedia di samping layar.
               </p>
            @endif

            <div class="d-flex flex-wrap gap-3">
               @if($canManageUser)
                  <a href="{{ route('user.index') }}" class="hero__btn hero__btn--solid">
                     <i class="ri-group-line ri-20px"></i> Kelola Pengguna
                     <i class="ri-arrow-right-line ri-18px"></i>
                  </a>
               @elseif($canManageBarang)
                  <a href="{{ route('barang.index') }}" class="hero__btn hero__btn--solid">
                     <i class="ri-archive-line ri-20px"></i> Master Barang
                     <i class="ri-arrow-right-line ri-18px"></i>
                  </a>
               @endif

               @if($canManageAccess)
                  <a href="{{ route('permission.index') }}" class="hero__btn hero__btn--ghost">
                     <i class="ri-lock-line ri-20px"></i> Cek Akses
                     <i class="ri-arrow-right-line ri-18px"></i>
                  </a>
               @elseif($canManageTransaksi)
                  <a href="{{ route('transaksi-masuk.index') }}" class="hero__btn hero__btn--ghost">
                     <i class="ri-arrow-left-down-line ri-20px"></i> Transaksi Masuk
                     <i class="ri-arrow-right-line ri-18px"></i>
                  </a>
               @endif
            </div>
         </div>
      </div>

      {{-- Empat pilar --}}
      <div class="hero__pillars-wrap">
         <div class="hero__pillars">
            <div class="hero__pillar">
               <div class="hero__pillar-icon"><i class="ri-building-2-line ri-20px"></i></div>
               <span>Infrastruktur<br>Sumber Daya Air</span>
            </div>
            <div class="hero__pillar">
               <div class="hero__pillar-icon"><i class="ri-team-line ri-20px"></i></div>
               <span>Melayani<br>Masyarakat</span>
            </div>
            <div class="hero__pillar">
               <div class="hero__pillar-icon"><i class="ri-leaf-line ri-20px"></i></div>
               <span>Menjaga<br>Lingkungan</span>
            </div>
            <div class="hero__pillar">
               <div class="hero__pillar-icon"><i class="ri-settings-4-line ri-20px"></i></div>
               <span>Untuk<br>Indonesia Maju</span>
            </div>
         </div>
      </div>

   </div>

   {{-- Lokasi & slogan, duduk di atas pita navy --}}
   <div class="hero__foot">
      <div class="d-flex align-items-center gap-3">
         <div class="hero__pin"><i class="ri-map-pin-2-fill ri-18px"></i></div>
         <div>
            <div class="hero__place-title">Makassar, Sulawesi Selatan</div>
            <div class="hero__place-sub">Balai Besar Wilayah Sungai Pompengan Jeneberang</div>
         </div>
      </div>
      <div class="hero__slogan d-none d-lg-block">
         <span>Air Untuk Negeri</span>
         <strong>Mengalirkan Kehidupan</strong>
      </div>
   </div>

</div>