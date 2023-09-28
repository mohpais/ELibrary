<nav class="sb-topnav navbar navbar-expand navbar-dark bg-ubk shadow-sm">
    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-0 mx-2" id="sidebarToggle" href="#!"><i
            class="fas fa-bars"></i></button>
    <!-- Navbar Brand-->
    <a class="navbar-brand" href="index.html">Perpustakaan Online</a>
    <!-- Navbar-->
    <ul class="navbar-nav ms-auto me-3 me-lg-4">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                <i class="fas fa-user fa-fw"></i>
                <span><?php echo $_SESSION['user']['nama_lengkap'] ?></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="data-diri.php">Data Diri</a></li>
                <li>
                    <hr class="dropdown-divider" />
                </li>
                <li><a href="services/auth/logout-service.php" class="dropdown-item" href="#!">Keluar</a></li>
            </ul>
        </li>
    </ul>
</nav>