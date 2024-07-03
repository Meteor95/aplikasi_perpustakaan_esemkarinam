<!doctype html>
<html lang="id" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">
<head>
   @include('includes.assetsheader')
   @yield('css_load')
</head>
<body>
   @if($data['tipe_halaman'] === "login")
   <div class="auth-page-wrapper pt-5">
      @yield('konten_utama')
   </div>
   @else
   <div id="layout-wrapper">
      @yield('konten_utama')
   </div>
   @endif
@include('includes.assetsfooter', ['tipe_halaman' => $data['tipe_halaman']])
@yield('js_load')
</body>
</html>