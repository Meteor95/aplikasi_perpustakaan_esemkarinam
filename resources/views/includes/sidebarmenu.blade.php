<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <a href="{{url('/beranda')}}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ asset('v1/assets/images/logo_pgri_.png')}}" alt="" height="50" class="mt-2">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('v1/assets/images/logo_pgri.png')}}" alt="" height="75" class="mt-2">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="{{url('/beranda')}}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ asset('v1/assets/images/logo_pgri.png')}}" alt="" height="50" class="mt-2">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('v1/assets/images/logo_pgri_.png')}}" alt="" height="75" class="mt-2">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">Fitur Aplikasi [MENU]</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link {{($data['menu_utama_aktif'] == "dashboard" ? "active" : "")}}" href="{{url('/beranda')}}">
                        <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Beranda</span>
                    </a>
                </li>
                @if ($data['userInfo']->id_hakakses == "1")
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarTablesPerpus" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarTablesPerpus">
                        <i class="ri-book-fill"></i> <span data-key="t-widgets">Master Buku</span>
                    </a>
                    <div class="collapse menu-dropdown {{($data['menu_utama_aktif'] == "master_perpustakaan" ? "active" : "" )}} {{($data['menu_utama_aktif'] === "master_perpustakaan" ? "show" : "" )}}" id="sidebarTablesPerpus">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{url('/perpustakaan/daftar_buku')}}" class="nav-link {{($data['sub_menu_utama_aktif'] == "daftar_buku" ? "active" : "" )}}" data-key="t-basic-tables">Daftar Buku</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('/perpustakaan/atribut')}}" class="nav-link {{($data['sub_menu_utama_aktif'] == "atribut" ? "active" : "" )}}"  data-key="t-basic-tables">Atribut Buku</a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarTablesPerpusPinjam" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarTablesPerpusPinjam">
                        <i class="bx bxs-report"></i> <span data-key="t-widgets">Master Transaksi</span>
                    </a>
                    <div class="collapse menu-dropdown {{($data['menu_utama_aktif'] == "master_peminjaman" ? "active" : "" )}} {{($data['menu_utama_aktif'] === "master_peminjaman" ? "show" : "" )}}" id="sidebarTablesPerpusPinjam">
                        <ul class="nav nav-sm flex-column">
                            @if ($data['userInfo']->id_hakakses == "1")
                            <li class="nav-item">
                                <a href="{{url('/perpustakaan/daftar_peminjaman')}}" class="nav-link {{($data['sub_menu_utama_aktif'] == "transaksi_pinjam" ? "active" : "" )}}" data-key="t-basic-tables">Transaksi Pinjaman</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('/perpustakaan/daftar_pengembalian')}}" class="nav-link {{($data['sub_menu_utama_aktif'] == "transaksi_kembali" ? "active" : "" )}}" data-key="t-basic-tables">Transaksi Pengembalian</a>
                            </li>
                            @endif
                            @if ($data['userInfo']->id_hakakses == "3")
                            <li class="nav-item">
                                <a href="{{url('/murid/pinjam_buku')}}" class="nav-link {{($data['sub_menu_utama_aktif'] == "siswa_pinjam_buku" ? "active" : "" )}}" data-key="t-basic-tables">Siswa Pinjam Buku Yuk</a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
                @if ($data['userInfo']->id_hakakses == "1")
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarTablesha" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarTablesha">
                        <i class="ri-honour-line"></i> <span data-key="t-widgets">Pengaturan Pegawai</span>
                    </a>
                    <div class="collapse menu-dropdown {{($data['menu_utama_aktif'] == "kredential_dan_petugas" ? "active" : "" )}} {{($data['menu_utama_aktif'] === "kredential_dan_petugas" ? "show" : "" )}}" id="sidebarTablesha">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{url('/pegawai/daftarpengguna')}}" class="nav-link {{($data['sub_menu_utama_aktif'] == "daftar_petugas" ? "active" : "" )}}" data-key="t-basic-tables">Daftar Petugas</a>
                            </li>
                            <!--<li class="nav-item">
                                <a href="{{url('/pegawai/hakakses')}}" class="nav-link {{($data['sub_menu_utama_aktif'] == "daftar_permision" ? "active" : "" )}}" data-key="t-basic-tables">Hak Akses</a>
                            </li>-->
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarTables" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarTables">
                        <i class="ri-folder-user-line"></i> <span data-key="t-tables">Pengaturan Siswa</span>
                    </a>
                    <div class="collapse menu-dropdown {{($data['menu_utama_aktif'] == "daftarmurid" ? "active" : "" )}} {{($data['menu_utama_aktif'] === "daftarmurid" ? "show" : "" )}}" id="sidebarTables">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{url('/murid/subbukuinduk')}}" class="nav-link {{($data['sub_menu_utama_aktif'] == "sub_buku_induk" ? "active" : "" )}}" data-key="t-basic-tables">Sub Buku Induk</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('/murid/kelas')}}" class="nav-link {{($data['sub_menu_utama_aktif'] == "kelas" ? "active" : "" )}}"  data-key="t-basic-tables">Kelas</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('/murid/tahunajaran')}}" class="nav-link {{($data['sub_menu_utama_aktif'] == "tahun_ajaran" ? "active" : "" )}}"  data-key="t-grid-js">Tahun Ajaran</a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif
            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
<div class="vertical-overlay"></div>
<div class="main-content">
    @yield('konten_paneladmin')
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center">
                        <p class="mb-0 text-muted">&copy;
                            <script>document.write(new Date().getFullYear())</script> SMKS PGRI 6 MALANG. Crafted with <i class="mdi mdi-heart text-danger"></i> by Sandi Idris Zulfikar a.k.a TIM IT</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>
<!-- end main content-->
<button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top"><i class="ri-arrow-up-line"></i></button>