<?php
    // Include the functions.php file from the main theme directory
    require_once 'helpers/authorize.php';
    require_once 'helpers/functions.php';
    require_once 'config/connection.php';
    if (
        $_SESSION['user']['role'] == "Mahasiswa" &&
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
    <title>E-Library | Beranda</title>
    <link href="assets/css/styles.css" rel="stylesheet" />
    <link href="assets/css/custom.css" rel="stylesheet" />
    <!-- Toast Library -->
    <link href="assets/lib/toast/toast.min.css" rel="stylesheet" />
    <!-- DataTables Library -->
    <link rel="stylesheet" href="assets/lib/DataTables/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="assets/lib/DataTables/jquery.dataTables.min.css">
    <style>
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0 !important;
            border: 0 !important
        }

        .form-select, .datatable-selector {
            background-position: right 0.35rem center !important;
            min-width: 3rem !important;
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
                    <h1 class="mt-4">Beranda</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item">Panel</li>
                        <li class="breadcrumb-item active">Beranda</li>
                    </ol>
                    <div class="row">
                        <div class="col-12">
                            <div class="card mb-4">
                                <div class="card-header d-flex">
                                    <div class="h5 title my-auto">
                                        Data Perpustakaan Online
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table id="libraryTable" class="display" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Nama Mahasiswa</th>
                                                <th>Dosen Pembimbing</th>
                                                <th>Tipe Dokumen</th>
                                                <th>Judul Dokumen</th>
                                                <th>Dibuat Pada</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr>
                                                <th>No.</th>
                                                <th>Nama Mahasiswa</th>
                                                <th>Dosen Pembimbing</th>
                                                <th>Tipe Dokumen</th>
                                                <th>Judul Dokumen</th>
                                                <th>Dibuat Pada</th>
                                                <th></th>
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
    <script src="assets/lib/jquery/jquery.validate.min.js"></script>
    <script src="assets/lib/jquery/additional-methods.min.js"></script>
    <!-- Bootstrap Library -->
    <script src="assets/lib/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="assets/lib/bootstrap/bootstrap-datepicker.min.js"></script>
    <!-- Toast Library -->
    <script src="assets/lib/toast/toast.min.js"></script>
    <!-- DataTables Library -->
    <script src="assets/lib/DataTables/jquery.dataTables.min.js"></script>
    <script src="assets/lib/DataTables/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#libraryTable').DataTable({
                "language": {
                    "emptyTable": "Tidak ada dokumen yang terpublish.",
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
                    "url": "services/document/get-document-finish-service.php", // Replace with the URL of your PHP script
                    "type": "POST"
                },
                "columns": [
                    // Add this line for the numbering index
                    { 
                        "data": null, // Use null as the data source
                        "render": function (data, type, row, meta) {
                            // Render the row index (meta.row) + 1 as the numbering index
                            return meta.row + 1;
                        }
                    },
                    { "data": "nama_lengkap" },
                    { "data": "dosen_pembimbing" },
                    { 
                        "data": "tipe",
                        "render": function (data, type, row) {
                            // console.log(data);
                            // Check if the data is null, and if so, display a hyphen
                            return !data ? '-' : data;
                        }
                    },
                    { "data": "judul" },
                    { 
                        "data": "dibuat_pada",
                        "render": function (data) {
                            var date = new Date(data);
                            var day = date.getDate();
                            var month = date.getMonth() + 1; // Months are 0-based
                            var year = date.getFullYear();
                            return day + '/' +  month + '/' + year;
                        }
                    },
                    { 
                        "data": "dokumen_akhir",
                        "orderable": false,
                        "render": function (data, type, row) {
                            return '<a target="_blank" id="btnResponse" type="button" class="btn btn-primary" href="services/download-file.php?file=' + data + '" target="_blank"><i class="fa-solid fa-file-arrow-down"></i></a>';
                        }
                    },
                ],
                "columnDefs": [
                    {
                        "targets": [0], // Index of the column you want to center (0-based index)
                        "className": "text-center" // Add the CSS class for text centering
                    }
                ],
                "lengthMenu": [5, 25, 50, 100],
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "order": [
                    [6, "desc"]
                ]
            });
        })
    </script>
</body>

</html>