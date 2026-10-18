@php
   $themeColor = $configData['themeColor'] ?? '#666cff';
   [$r, $g, $b] = sscanf($themeColor, '#%02x%02x%02x');
   $themeColorRgb = "$r, $g, $b";

   // Warna hover (10% lebih gelap)
   $darkerR = max(0, floor($r * 0.9));
   $darkerG = max(0, floor($g * 0.9));
   $darkerB = max(0, floor($b * 0.9));
   $themeColorDarker = sprintf('#%02x%02x%02x', $darkerR, $darkerG, $darkerB);
@endphp

<!-- BEGIN: Theme CSS-->
<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap" rel="stylesheet">

@vite(['resources/assets/vendor/fonts/remixicon/remixicon.scss', 'resources/assets/vendor/fonts/flag-icons.scss', 'resources/assets/vendor/libs/node-waves/node-waves.scss'])
<!-- Core CSS -->
@vite(['resources/assets/vendor/scss' . $configData['rtlSupport'] . '/core' . ($configData['style'] !== 'light' ? '-' . $configData['style'] : '') . '.scss', 'resources/assets/vendor/scss' . $configData['rtlSupport'] . '/' . $configData['theme'] . ($configData['style'] !== 'light' ? '-' . $configData['style'] : '') . '.scss', 'resources/assets/css/demo.css'])


<!-- Vendor Styles -->
@vite(['resources/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.scss', 'resources/assets/vendor/libs/typeahead-js/typeahead.scss'])
@yield('vendor-style')

<!-- Page Styles -->
@yield('page-style')

<style id="dynamic-theme-style">
   :root {
      --bs-primary: {{ $themeColor }};
      --bs-primary-rgb: {{ $themeColorRgb }};
      --bs-primary-border-color: {{ $themeColor }};
      --bs-primary-hover: {{ $themeColorDarker }};
      --primary-color: {{ $themeColor }};
      --bs-primary-bg-subtle: rgba({{ $themeColorRgb }}, 0.08);
      --bs-primary-border-subtle: rgba({{ $themeColorRgb }}, 0.2);
   }

   /* 1. Warna Edit Button (Btn-Primary) & Hover */
   .btn-primary {
      background-color: {{ $themeColor }} !important;
      border-color: {{ $themeColor }} !important;
      color: #fff !important;
   }

   .btn-primary:hover,
   .btn-primary:focus,
   .btn-primary:active {
      background-color: {{ $themeColorDarker }} !important;
      border-color: {{ $themeColorDarker }} !important;
      color: #fff !important;
   }

   /* 2. Field Form Input On-Focus (Border & Shadow) */
   .form-control:focus,
   .form-select:focus,
   .input-group:focus-within .input-group-text,
   .input-group:focus-within .form-control {
      border-color: {{ $themeColor }} !important;
      box-shadow: 0 0 0.25rem 0.05rem rgba({{ $themeColorRgb }}, 0.1) !important;
   }

   /* Menangani label melayang agar warnanya senada saat aktif */
   .form-floating>.form-control:focus~label,
   .form-floating>.form-control:not(:placeholder-shown)~label {
      color: {{ $themeColor }} !important;
   }

   /* Sidebar Active State */
   .layout-menu .menu-item.active>.menu-link:not(.menu-toggle) {
      background: linear-gradient(72.47deg, {{ $themeColor }} 22.16%, rgba({{ $themeColorRgb }}, 0.7) 76.47%) !important;
   }

   .layout-menu .menu-inner>.menu-item.active>.menu-link {
      background-color: rgba({{ $themeColorRgb }}, 0.08) !important;
      color: {{ $themeColor }} !important;
   }

   /* ===================================================
      RESPONSIVE & COMPACT THEME FOR ALL LAPTOPS (NO SCROLL)
      =================================================== */
   /* 1. Global Container Full Width Utilization */
   .layout-page .container-fluid,
   .layout-page .container-xxl {
      padding-left: 1rem !important;
      padding-right: 1rem !important;
      max-width: 100% !important;
   }
   @media (min-width: 768px) {
      .layout-page .container-fluid,
      .layout-page .container-xxl {
         padding-left: 1.25rem !important;
         padding-right: 1.25rem !important;
      }
   }

   /* 2. Compact Global Typography for Laptop Screens */
   body {
      font-size: 0.875rem;
   }

   /* 3. Sleek Compact Stat Cards */
   .stat-card {
      transition: transform 0.18s ease, box-shadow 0.18s ease;
      border-radius: 8px !important;
   }
   .stat-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(67, 89, 113, 0.08) !important;
   }

   /* 4. Compact Table Styling - NO Horizontal Scroll on Laptops */
   .table {
      font-size: 0.8125rem !important; /* ~13px compact */
      width: 100% !important;
      margin-bottom: 0 !important;
   }
   .table thead th {
      font-size: 0.72rem !important;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      font-weight: 600 !important;
      color: #566a7f !important;
      padding: 0.55rem 0.65rem !important;
      background-color: #f8f9fa !important;
      vertical-align: middle;
   }
   .table tbody td {
      padding: 0.5rem 0.65rem !important;
      vertical-align: middle;
      line-height: 1.35;
   }
   .table-responsive {
      overflow-x: auto;
      scrollbar-width: thin;
   }
   /* Hilangkan white-space: nowrap yang memaksa scrollbar di semua tabel */
   .table-responsive.text-nowrap {
      white-space: normal !important;
   }
   .table-responsive td,
   .table-responsive th {
      white-space: normal;
   }

   /* 5. Compact Badges & Buttons */
   .badge {
      font-size: 0.725rem !important;
      padding: 0.25rem 0.45rem !important;
      font-weight: 500;
      letter-spacing: 0.2px;
   }
   .btn-xs {
      padding: 0.2rem 0.45rem !important;
      font-size: 0.725rem !important;
      line-height: 1.25;
      border-radius: 4px !important;
   }
   .btn-icon.btn-xs {
      width: 26px !important;
      height: 26px !important;
      padding: 0 !important;
      display: inline-flex !important;
      align-items: center !important;
      justify-content: center !important;
   }
   .btn-sm {
      padding: 0.3rem 0.6rem !important;
      font-size: 0.785rem !important;
   }

   /* 6. Compact Card Header & Filter Navs */
   .card-header {
      padding: 0.75rem 1.15rem !important;
   }
   .card-body {
      padding: 0.9rem 1.15rem !important;
   }
   .nav-pills .nav-link {
      padding: 0.35rem 0.75rem !important;
      font-size: 0.785rem !important;
   }
</style>
