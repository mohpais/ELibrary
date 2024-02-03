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
                                            <button id="openModalBtn" type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modal-tambah-pengguna">
                                                <i class="fas fa-user-plus"></i>
                                                Tambah Pengguna
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table id="userTable" class="display" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Kode Pengguna</th>
                                                <th>Nama Lengkap</th>
                                                <th>Email</th>
                                                <th>JabatanID</th>
                                                <th>Jabatan</th>
                                                <th>No Telp</th>
                                                <th>Jurusan</th>
                                                <th>Tanggal Bergabung</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr>
                                                <th>No.</th>
                                                <th>Kode Pengguna</th>
                                                <th>Nama Lengkap</th>
                                                <th>Email</th>
                                                <th>JabatanID</th>
                                                <th>Jabatan</th>
                                                <th>No Telp</th>
                                                <th>Jurusan</th>
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
    <!-- Modal Tambah Pengguna -->
    <div class="modal fade" id="modal-tambah-pengguna" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="tambahPenggunaForm" novalidate>
                    <div class="modal-header">
                        <h4 class="modal-title" id="staticBackdropLabel">Tambah Pengguna</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-1">
                            <label for="kode" class="form-label">Kode Pengguna <span class="text-danger fw-bold">*</span></label>
                            <input 
                                id="kode" 
                                type="text" 
                                name="kode" 
                                class="form-control" 
                                placeholder="Masukkan kode pengguna ..."
                            />
                        </div>
                        <div class="mb-1">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger fw-bold">*</span></label>
                            <input 
                                id="nama_lengkap" 
                                type="text" 
                                name="nama_lengkap" 
                                class="form-control" 
                                placeholder="Masukkan nama pengguna ..."
                            />
                        </div>
                        <?php 
                            // Perform database connection
                            $conn = connect_to_database();
                            $query = <<<EOD
                                SELECT 
                                    * 
                                FROM 
                                    `tbl_jabatan`
                                WHERE
                                    id NOT IN (
                                        SELECT id FROM `tbl_jabatan` WHERE jabatan LIKE '%Kaprodi%' AND id IN (SELECT jabatan_id FROM `tbl_akun`)
                                    )
                            EOD;
                            $stmt = $conn->prepare($query);
                            $stmt->execute();
                            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                        <div class="mb-1">
                            <label for="jabatan_id" class="form-label">Jabatan <span class="text-danger fw-bold">*</span></label>
                            <select id="jabatan_id" name="jabatan_id" class="form-select">
                                <option value="" selected disabled>-- Pilih Salah Satu --</option>
                                <?php foreach ($results as $result) { ?>
                                    <option value="<?php echo $result['id']; ?>"><?php echo $result['jabatan']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="mb-1">
                            <label for="jurusan" class="form-label">Jurusan <span class="text-danger fw-bold">*</span></label>
                            <select id="jurusan" name="jurusan" class="form-select">
                                <option value="" selected disabled>-- Pilih Salah Satu --</option>
                                <option value="Sistem Informasi">Sistem Informasi</option>
                                <option value="Sistem Komputer">Sistem Komputer</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Tutup</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Modal -->

    <!-- Modal Edit Pengguna -->
    <div class="modal fade" id="modal-edit-pengguna" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editPenggunaForm" novalidate>
                    <div class="modal-header">
                        <h4 class="modal-title" id="staticBackdropLabel">Edit Pengguna</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-1">
                            <label for="kode" class="form-label">Kode Pengguna <span class="text-danger fw-bold">*</span></label>
                            <input 
                                id="kode" 
                                type="text" 
                                name="kode" 
                                class="form-control" 
                                placeholder="Masukkan kode pengguna ..."
                            />
                        </div>
                        <div class="mb-1">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger fw-bold">*</span></label>
                            <input 
                                id="nama_lengkap" 
                                type="text" 
                                name="nama_lengkap" 
                                class="form-control" 
                                placeholder="Masukkan nama pengguna ..."
                            />
                        </div>
                        <?php 
                            // Perform database connection
                            $conn = connect_to_database();
                            $query = 'SELECT * FROM `tbl_jabatan`';
                            $stmt = $conn->prepare($query);
                            $stmt->execute();
                            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                        <div class="mb-1">
                            <label for="jabatan_id" class="form-label">Jabatan <span class="text-danger fw-bold">*</span></label>
                            <select id="jabatan_id" name="jabatan_id" class="form-select">
                                <option value="" disabled>-- Pilih Salah Satu --</option>
                                <?php foreach ($results as $result) { ?>
                                    <option value="<?php echo $result['id']; ?>"><?php echo $result['jabatan']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="mb-1">
                            <label for="jurusan" class="form-label">Jurusan <span class="text-danger fw-bold">*</span></label>
                            <select id="jurusan" name="jurusan" class="form-select">
                                <option value="" disabled>-- Pilih Salah Satu --</option>
                                <option value="Sistem Informasi">Sistem Informasi</option>
                                <option value="Sistem Komputer">Sistem Komputer</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning">Edit</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Tutup</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Modal -->

    <script src="assets/js/scripts.js"></script>
    <!-- JQuery Library -->
    <script src="assets/lib/jquery/jquery.min.js"></script>
    <script src="assets/lib/jquery/jquery.validate.min.js"></script>
    <script src="assets/lib/jquery/additional-methods.min.js"></script>
    <!-- Font Awesome Library -->
    <script src="assets/lib/font-awesome/all.js"></script>
    <!-- Bootstrap Library -->
    <script src="assets/lib/bootstrap/bootstrap.bundle.min.js"></script>
    <!-- Toast Library -->
    <script src="assets/lib/toast/toast.min.js"></script>
    <!-- DataTables Library -->
    <script src="assets/lib/DataTables/jquery.dataTables.min.js"></script>
    <script src="assets/lib/DataTables/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            var table = $('#userTable').DataTable({
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
                    { "data": "kode" },
                    { "data": "nama_lengkap" },
                    { 
                        "data": "email",
                        "render": function (data, type, row) {
                            // console.log(data);
                            // Check if the data is null, and if so, display a hyphen
                            return !data ? '-' : data;
                        }
                    },
                    { "data": "role_id", "visible": false },
                    { "data": "role" },
                    {
                        "data": "no_telp" ,
                        "render": function (data, type, row) {
                            // console.log(data);
                            // Check if the data is null, and if so, display a hyphen
                            return !data ? '-' : data;
                        }
                    },
                    { "data": "jurusan", "visible": false },
                    { "data": "tanggal_bergabung" },
                    { 
                        "data": "id",
                        "orderable": false,
                        "render": function (data, type, row) {
                            return '<button id="btnEdit" class="btn btn-warning" data-id="' + data + '"><i class="fa-solid fa-pen-to-square"></i></button>';
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
                    if (!data.email) {
                        $('td', row).eq(3).addClass('text-center');
                    }
                    if (!data.no_telp) {
                        $('td', row).eq(5).addClass('text-center');
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

            // Form validate submit proposalForm
            $("#tambahPenggunaForm").validate({
                rules: {
                    kode: "required",
                    nama_lengkap: "required",
                    jabatan_id: "required",
                    jurusan: "required",
                },
                messages: {
                    kode: "Kode pengguna tidak boleh kosong.",
                    nama_lengkap: "Nama lengkap tidak boleh kosong.",
                    jabatan_id: "Jabatan tidak boleh kosong.",
                    jurusan: "Jurusan tidak boleh kosong."
                },
                submitHandler: function(form) {
                    // debugger;
                    event.preventDefault();
                    // Use FormData to handle file and other form data
                    let payload = $(form).serialize();
                    $.ajax({
                        method:"POST",
                        url: "services/user/add-user-service.php",
                        data: payload,
                        success: function(response) {
                            // console.log(response);
                            if(response.success) {
                                // console.log(response.message);
                                $("#modal-tambah-pengguna").modal('show');
                                toastr.success(response.message);
                                // Reload the page after 3 seconds
                                setTimeout(function() {
                                    var id = response.message;
                                    window.location.reload();
                                }, 2000);
                            } else {
                                toastr.error(response.message);
                            }
                        }
                    });
                }
            });

            // Form validate submit proposalForm
            $("#editPenggunaForm").validate({
                rules: {
                    kode: "required",
                    nama_lengkap: "required",
                    jabatan_id: "required",
                    jurusan: "required",
                },
                messages: {
                    kode: "Kode pengguna tidak boleh kosong.",
                    nama_lengkap: "Nama lengkap tidak boleh kosong.",
                    jabatan_id: "Jabatan tidak boleh kosong.",
                    jurusan: "Jurusan tidak boleh kosong."
                },
                submitHandler: function(form) {
                    // debugger;
                    event.preventDefault();
                    // Use FormData to handle file and other form data
                    let payload = $(form).serialize();
                    $.ajax({
                        method:"POST",
                        url: "services/user/edit-user-service.php",
                        data: payload,
                        success: function(response) {
                            // console.log(response);
                            if(response.success) {
                                // console.log(response.message);
                                $("#modal-edit-pengguna").modal('show');
                                toastr.success(response.message);
                                // Reload the page after 3 seconds
                                setTimeout(function() {
                                    var id = response.message;
                                    window.location.reload();
                                }, 2000);
                            } else {
                                toastr.error(response.message);
                            }
                        }
                    });
                }
            });
                
            
            $('#userTable').on('click', 'button#btnEdit', function () {
                var rowIndex = table.row($(this).closest('tr')).index();
                var rowData = table.row(rowIndex).data();

                console.log(rowData);
                $('form#editPenggunaForm').find('#kode').val(rowData.kode);
                $('form#editPenggunaForm').find('#nama_lengkap').val(rowData.nama_lengkap);
                $('form#editPenggunaForm').find('#jabatan_id').val(rowData.role_id);
                $('form#editPenggunaForm').find('#jurusan').val(rowData.jurusan);

                $("#modal-edit-pengguna").modal('show');
            });
        });

    </script>
</body>

</html>