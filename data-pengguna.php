<?php
    // Include the functions.php file from the main theme directory
    require_once 'helpers/authorize.php';
    require_once 'helpers/functions.php';
    require_once 'config/connection.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>E-Library | Data Pengguna</title>
    <link href="assets/css/styles.css" rel="stylesheet" />
    <link href="assets/css/custom.css" rel="stylesheet" />
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
                    <h1 class="mt-4">Data Pengguna</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item">Panel</li>
                        <li class="breadcrumb-item active">Data Pengguna</li>
                    </ol>
                    <div class="row">
                        <div class="col-12">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-auto ms-auto">
                                            <a type="button" href="" class="btn btn-sm btn-success">
                                                <i class="fas fa-user-plus"></i>
                                                Tambah Pengguna
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table id="userTable" class="display" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Nama Lengkap</th>
                                                <th>Email</th>
                                                <th>Jabatan</th>
                                                <th>No Telp</th>
                                                <th>Tanggal Bergabung</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr>
                                                <th>No.</th>
                                                <th>Nama Lengkap</th>
                                                <th>Email</th>
                                                <th>Jabatan</th>
                                                <th>No Telp</th>
                                                <th>Tanggal Bergabung</th>
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
    <!-- Bootstrap Library -->
    <script src="assets/lib/bootstrap/bootstrap.bundle.min.js"></script>
    <!-- DataTables Library -->
    <script src="assets/lib/DataTables/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="assets/lib/DataTables/dataTables.bootstrap5.min.js" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $('#userTable').DataTable({
                "language": {
                    "emptyTable": "Tidak ada data!",
                    "search": "Cari Pengguna:",
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
                    "url": "services/user/get-users-service.php", // Replace with the URL of your PHP script
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
                    { 
                        "data": "email",
                        "render": function (data, type, row) {
                            // console.log(data);
                            // Check if the data is null, and if so, display a hyphen
                            return !data ? '-' : data;
                        }
                    },
                    { "data": "role" },
                    {
                         "data": "no_telp" ,
                        "render": function (data, type, row) {
                            // console.log(data);
                            // Check if the data is null, and if so, display a hyphen
                            return !data ? '-' : data;
                        }
                    },
                    { "data": "tanggal_bergabung" },
                    { 
                        "data": "id",
                        "render": function (data, type, row) {
                            return '<button id="btnResponse" class="btn btn-warning" data-id="' + data + '"><i class="fa-solid fa-pen-to-square"></i></button>';
                        }
                    },
                ],
                "columnDefs": [
                    {
                        "targets": [0], // Index of the column you want to center (0-based index)
                        "className": "text-center" // Add the CSS class for text centering
                    }
                ],
                "createdRow": function(row, data, dataIndex) {
                    // Customize the row creation process here
                    // 'row' is the row element
                    // 'data' is the data for the current row
                    // 'dataIndex' is the index of the current row in the dataset

                    // Example: Add a class to a cell in the first column based on its data
                    console.log(row, data, dataIndex);
                    if (!data.email) {
                        $('td', row).eq(2).addClass('text-center');
                    } else if (!data.no_telp) {
                        $('td', row).eq(4).addClass('text-center');
                    }
                },
                "lengthMenu": [5, 25, 50, 100],
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "order": [
                    [5, "desc"]
                ]
            });
        });

        $('#userTable').on('click', 'button#btnResponse', function () {
            let dataId = $(this).data('id');
            let dataTipe = $(this).data('tipe');
            let url = dataTipe === 1 ? `laporan-kerja-praktek.php?id=${dataId}` : `skripsi.php?id=${dataId}`;
            window.location.href = url;
        });
    </script>
</body>

</html>