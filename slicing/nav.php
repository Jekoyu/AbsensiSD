<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-icon rotate-n-15">

                </div>
                <div class="sidebar-brand-text mx-3"><?php include 'env.php';
                                                        echo $school ?></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Siswa
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="siswa.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Daftar Siswa</span>
                </a>

            </li>

            <li class="nav-item">
                <a class="nav-link collapsed" href="input.php">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Input Siswa Baru</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">



            <div class="sidebar-heading">
                Absensi
            </div>
            <li class="nav-item">
                <a class="nav-link" href="absensi.php">
                    <i class="fas fa-fw fa-check"></i>
                    <span>Input Absensi</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="daftarabsensi.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Lihat Absensi</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="jamabsensi.php">
                    <i class="fas fa-fw fa-clock"></i>
                    <span>Jam Absensi</span></a>
            </li>
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>