<header id="page-topbar">
    <div class="layout-width">
        <div class="navbar-header">
            <div class="d-flex">
                <!-- LOGO -->
                <div class="navbar-brand-box horizontal-logo">
                    <a href="{{url('/beranda')}}" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="{{ asset('v1/assets/images/logo_pgri.png')}}" alt="" height="50" class="mt-2">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ asset('v1/assets/images/logo_pgri_.png')}}" alt="" height="75" class="mt-2">
                        </span>
                    </a>

                    <a href="{{url('/beranda')}}" class="logo logo-light">
                        <span class="logo-sm">
                            <img src="{{ asset('v1/assets/images/logo_pgri.png')}}" alt="" height="50" class="mt-2">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ asset('v1/assets/images/logo_pgri_.png')}}" alt="" height="75" class="mt-2">
                        </span>
                    </a>
                </div>

                <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger material-shadow-none" id="topnav-hamburger-icon">
                    <span class="hamburger-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>
            </div>

            <div class="d-flex align-items-center">

                <div class="ms-1 header-item d-none d-sm-flex">
                    <button type="button" class="btn btn-icon btn-topbar material-shadow-none btn-ghost-secondary rounded-circle" data-toggle="fullscreen">
                        <i class='bx bx-fullscreen fs-22'></i>
                    </button>
                </div>

                <div class="ms-1 header-item d-none d-sm-flex">
                    <button type="button" class="btn btn-icon btn-topbar material-shadow-none btn-ghost-secondary rounded-circle light-dark-mode">
                        <i class='bx bx-moon fs-22'></i>
                    </button>
                </div>

                <div class="dropdown ms-sm-3 header-item topbar-user">
                    <button type="button" class="btn material-shadow-none" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="d-flex align-items-center">
                            <img class="rounded-circle header-profile-user" src="{{ asset('v1/assets/images/logo_pgri.png')}}" alt="Header Avatar">
                            <span class="text-start ms-xl-2">
                                <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">{{strtoupper($data['userInfo']->username)}}</span>
                                <span class="d-none d-xl-block ms-1 fs-12 user-name-sub-text">{{strtoupper($data['userInfo']->nama_hak_akses)}}</span>
                            </span>
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <!-- item-->
                        <h6 class="dropdown-header">Selamat Datang {{strtoupper($data['userInfo']->username)}}!</h6>
                        <a class="dropdown-item" href="{{url('/keluar')}}"><i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span class="align-middle" data-key="t-logout">Logout</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>