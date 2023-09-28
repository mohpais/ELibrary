<?php
    // Include the functions.php file from the main theme directory
    require_once 'helpers/authorize.php';
    require_once 'helpers/functions.php';
    require_once 'config/connection.php';
    if (
        !isset($_SESSION['user']['jurusan']) && 
        !isset($_SESSION['user']['semester']) && 
        !isset($_SESSION['user']['tanggal_bergabung'])) {
            header("Location: data-diri.php");
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>E-Library | Persetujuan</title>
    <link href="assets/css/styles.css" rel="stylesheet" />
    <link href="assets/css/custom.css" rel="stylesheet" />
    <!-- DataTables Library -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0 !important;
            /* margin-left: 0 !important; */
            border: 0 !important
        }

        .div.dataTables_wrapper div.dataTables_length select {
            width: 3rem !important;
        }

        .form-select, .datatable-selector {
            background-position: right 0.35rem center !important;
        }
    </style>
</head>

<body class="sb-nav-fixed">
    <!-- Navbar -->
    <?php include 'includes/navbar.php' ?>
    <!-- End Navbar -->
    <div id="layoutSidenav">
        <!-- Sidebar -->
        <?php include 'includes/sidebar.php' ?>
        <!-- End Sidebar -->
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Persetujuan</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item">Panel</li>
                        <li class="breadcrumb-item active">Persetujuan</li>
                    </ol>
                    <div class="row">
                        <div class="col-12">
                            <div class="card mb-4">
                                <div class="card-header d-flex">
                                    <div class="h5 title my-auto">
                                        Data Pengajuan Mahasiswa
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table id="approvalTable" class="display" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Mahasiswa</th>
                                                <th>Tipe Pengajuan</th>
                                                <th>Judul</th>
                                                <th>Tanggal Dibuat</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr>
                                                <th>No.</th>
                                                <th>Mahasiswa</th>
                                                <th>Tipe Pengajuan</th>
                                                <th>Judul</th>
                                                <th>Tanggal Dibuat</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <!-- Navbar -->
            <?php include 'includes/footer.php' ?>
            <!-- End Navbar -->
        </div>
    </div>
    <script src="assets/js/scripts.js"></script>
    <!-- Font Awesome Library -->
    <script src="assets/lib/font-awesome/all.js"></script>
    <!-- JQuery Library -->
    <script src="assets/lib/jquery/jquery.min.js"></script>
    <!-- Bootstrap Library -->
    <script src="assets/lib/bootstrap/bootstrap.bundle.min.js"></script>
    <!-- DataTables Library -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $('#approvalTable').DataTable({
                "language": {
                    "emptyTable": "Tidak ada data!",
                    "search": "Cari Judul:",
                    "paginate": {
                        "first": "Data Pertama",
                        "previous": "Sebelumnya",
                        "next": "Selanjutnya",
                        "last": "Data Terakhir"
                    },
                    "aria": {
                        "paginate": {
                            "first": 'Data Pertama',
                            "previous": 'Sebelumnya',
                            "next": 'Selanjutnya',
                            "last": 'Data Terakhir'
                        }
                    },
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty": "Tidak ada catatan yang tersedia",
                    "infoFiltered": "(Difilter dari _MAX_ total data)",
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                },
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "services/document/get-awaiting-decision-service.php", // Replace with the URL of your PHP script
                    "type": "POST"
                },
                "columns": [
                    { 
                        "data": null, // Use null as the data source
                        "render": function (data, type, row, meta) {
                            // Render the row index (meta.row) + 1 as the numbering index
                            return meta.row + 1;
                        }
                    }, // Add this line for the numbering index
                    { "data": "nama_lengkap" },
                    { "data": "tipe" },
                    { "data": "judul" },
                    { "data": "dibuat_pada" },
                    { 
                        "data": "id",
                        "render": function (data, type, row) {
                            let tipe = row.tipe === "Skripsi" ? 2 : 1;
                            return '<button id="btnResponse" class="btn btn-primary" data-id="' + data + '" data-tipe="' + tipe + '"><i class="fa-solid fa-pen-to-square"></i></button>';
                        }
                    },
                ],
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "order": [
                    [4, "desc"]
                ]
            });
        });

        $('#approvalTable').on('click', 'button#btnResponse', function () {
            let dataId = $(this).data('id');
            let dataTipe = $(this).data('tipe');
            let url = dataTipe === 1 ? `laporan-kerja-praktek.php?id=${dataId}` : `skripsi.php?id=${dataId}`;
            window.location.href = url;
        });
        // $("#btnResponse").click(function() {
        //     var dataId = $(this).data('id');
        //     var dataTipe = $(this).data('tipe');
        //     console.log(dataId, dataTipe);
        // })
    </script>
</body>

</html>