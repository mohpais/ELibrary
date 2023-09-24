<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-light shadow" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Pages</div>
                <a class="nav-link <?php echo get_current_url() === 'dashboard' ? 'active' : '' ?>" href="dashboard.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Beranda
                </a>
                <!-- <div class="sb-sidenav-menu-heading">Interface</div> -->
                <?php
                    if ($_SESSION['user']['semester'] >= 6) {
                ?>
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts"
                        aria-expanded="false" aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                        Unggahan
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne"
                        data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="layout-static.html">Laporan Kerja Praktek</a>
                            <a class="nav-link" href="layout-sidenav-light.html">Tugas Akhir (Skripsi)</a>
                        </nav>
                    </div>
                <?php } ?>
                <?php
                    if ($_SESSION['user']['role'] === 'Admin') {
                ?>
                    <div class="sb-sidenav-menu-heading">Master Data</div>
                    <a class="nav-link" href="charts.html">
                        <div class="sb-nav-link-icon"><i class="fas fa-user-plus"></i></div>
                        Pendaftaran
                    </a>
                    <a class="nav-link" href="tables.html">
                        <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                        Publikasi
                    </a>
                <?php } ?>
            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            <b><?php echo $_SESSION['user']['role'] ?></b>
        </div>
    </nav>
</div>