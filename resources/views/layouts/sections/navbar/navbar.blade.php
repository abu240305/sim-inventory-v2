@php
   use Illuminate\Support\Facades\Auth;
   use Illuminate\Support\Facades\Route;
   $containerNav = $configData['contentLayout'] === 'compact' ? 'container-xxl' : 'container-fluid';
   $navbarDetached = $navbarDetached ?? '';
@endphp

<!-- Navbar -->
@if (isset($navbarDetached) && $navbarDetached == 'navbar-detached')
   <nav
      class="layout-navbar {{ $containerNav }} navbar navbar-expand-xl {{ $navbarDetached }} align-items-center bg-navbar-theme"
      id="layout-navbar">
@endif
@if (isset($navbarDetached) && $navbarDetached == '')
   <nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
      <div class="{{ $containerNav }}">
@endif

<!--  Brand demo (display only for navbar-full and hide on below xl) -->
@if (isset($navbarFull))
   <div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-6">
      <a href="{{ url('/') }}" class="app-brand-link gap-2">
         <span class="app-brand-logo demo">
            @if (config('variables.templateLogo'))
               <img src="{{ asset('storage/' . config('variables.templateLogo')) }}" alt="Logo" height="30">
            @else
               @include('_partials.macros', ['width' => 25, 'withbg' => 'var(--bs-primary)'])
            @endif
         </span>
         <span class="app-brand-text demo menu-text fw-semibold">{{ config('variables.templateName') }}</span>
      </a>
      @if (isset($menuHorizontal))
         <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-xl-none">
            <i class="ri-close-fill align-middle"></i>
         </a>
      @endif
   </div>
@endif

<!-- ! Not required for layout-without-menu -->
@if (!isset($navbarHideToggle))
   <div
      class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0{{ isset($menuHorizontal) ? ' d-xl-none ' : '' }} {{ isset($contentNavbar) ? ' d-xl-none ' : '' }}">
      <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
         <i class="ri-menu-fill ri-22px"></i>
      </a>
   </div>
@endif

<div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

   <ul class="navbar-nav flex-row align-items-center ms-auto">



      @if ($configData['hasCustomizer'] == true)
         <!-- Style Switcher -->
         <li class="nav-item dropdown-style-switcher dropdown me-1 me-xl-0">
            <a class="nav-link btn btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow"
               href="javascript:void(0);" data-bs-toggle="dropdown">
               <i class='ri-22px'></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end dropdown-styles">
               <li>
                  <a class="dropdown-item" href="javascript:void(0);" data-theme="light">
                     <span class="align-middle"><i class='ri-sun-line ri-22px me-3'></i>Light</span>
                  </a>
               </li>
               <li>
                  <a class="dropdown-item" href="javascript:void(0);" data-theme="dark">
                     <span class="align-middle"><i class="ri-moon-clear-line ri-22px me-3"></i>Dark</span>
                  </a>
               </li>
               <li>
                  <a class="dropdown-item" href="javascript:void(0);" data-theme="system">
                     <span class="align-middle"><i class="ri-computer-line ri-22px me-3"></i>System</span>
                  </a>
               </li>
            </ul>
         </li>
         <!-- / Style Switcher -->
      @endif





      <!-- User -->
      <li class="nav-item navbar-dropdown dropdown-user dropdown">
         <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
            <div class="avatar avatar-online">
               <img
                  src="{{ Auth::user() && Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('assets/img/avatars/1.png') }}"
                  alt class="rounded-circle">
            </div>
         </a>
         <ul class="dropdown-menu dropdown-menu-end">
            <li>
               <a class="dropdown-item" href="{{ route('profile.index') }}">
                  <div class="d-flex">
                     <div class="flex-shrink-0 me-2">
                        <div class="avatar avatar-online">
                           <img
                              src="{{ Auth::user() && Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('assets/img/avatars/1.png') }}"
                              alt class="rounded-circle">
                        </div>
                     </div>
                     <div class="flex-grow-1">
                        <span class="fw-medium d-block small">
                           @if (Auth::check())
                              {{ Auth::user()->name }}
                           @else
                              John Doe
                           @endif
                        </span>
                        <small
                           class="text-muted">{{ Auth::user() && Auth::user()->role ? Auth::user()->role->name : 'Guest' }}</small>
                     </div>
                  </div>
               </a>
            </li>
            <li>
               <div class="dropdown-divider"></div>
            </li>
            <li>
               <a class="dropdown-item" href="{{ route('profile.index') }}">
                  <i class="ri-user-3-line ri-22px me-3"></i><span class="align-middle">My Profile</span>
               </a>
            </li>


            @if (Auth::check())
               <li>
                  <div class="d-grid px-4 pt-2 pb-1">
                     <a class="btn btn-sm btn-danger d-flex" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <small class="align-middle">Logout</small>
                        <i class="ri-logout-box-r-line ms-2 ri-16px"></i>
                     </a>
                  </div>
               </li>
               <form method="POST" id="logout-form" action="{{ route('logout') }}">
                  @csrf
               </form>
            @else
               <li>
                  <div class="d-grid px-4 pt-2 pb-1">
                     <a class="btn btn-sm btn-danger d-flex"
                        href="{{ Route::has('login') ? route('login') : url('auth/login-basic') }}">
                        <small class="align-middle">Login</small>
                        <i class="ri-logout-box-r-line ms-2 ri-16px"></i>
                     </a>
                  </div>
               </li>
            @endif
         </ul>
      </li>
      <!--/ User -->
   </ul>
</div>


@if (!isset($navbarDetached))
   </div>
@endif
</nav>
<!-- / Navbar -->
