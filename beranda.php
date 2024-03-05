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

    // Encode the user's role as JSON and embed it into the HTML
    echo '<script>';
    echo 'var userRole = ' . json_encode($_SESSION['user']['role']) . ';';
    echo '</script>';
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

        .border-left-primary {
            border-left: 0.25rem solid #4e73df !important;
        }

        .border-left-danger {
            border-left: .25rem solid #e74a3b!important
        }

        .font-weight-bold {
            font-weight: 700 !important;
        }

        .text-gray-300 {
            color: #dddfeb !important;
        }

        .text-xs {
            font-size: .7rem;
        }

        .nav-tabs .nav-link {
            font-weight: 400 !important;
            color: #adb5bd !important;
        }

        .nav-tabs .nav-link.active {
            font-weight: 700 !important;
            color: rgb(188 30 45) !important;
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
                    <h4 class="mt-4">
                        <?php 
                            if (strpos($_SESSION['user']['role'], 'Kaprodi') !== false) { 
                                echo 'Laporan Akhir (' . $_SESSION['user']['jurusan'] . ')';
                            } else {
                                echo 'Beranda';
                            }
                        ?>
                    </h4>
                    <?php if (strpos($_SESSION['user']['role'], 'Kaprodi') === false) { ?>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item">Panel</li>
                            <li class="breadcrumb-item active">Beranda</li>
                        </ol>
                    <?php } ?>
                    <?php if (strpos($_SESSION['user']['role'], 'Kaprodi') !== false) { ?>
                        <div class="row mt-3">
                            <div class="col-12 col-md-4 mb-2">
                                <div class="w-100 d-flex flex-column gap-4">
                                    <div class="card border-left-danger shadow w-100 h-100 py-2">
                                        <div class="card-body">
                                            <div class="row no-gutters align-items-center">
                                                <div class="col mr-2">
                                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                                        Jumlah Mahasiswa</div>
                                                    <div id="mahasiswa_count" class="h5 mb-0 font-weight-bold text-gray-800">0</div>
                                                </div>
                                                <div class="col-auto">
                                                    <i class="fas fa-user fa-2x text-gray-300"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card border-left-danger shadow w-100 h-100 py-2">
                                        <div class="card-body">
                                            <div class="row no-gutters align-items-center">
                                                <div class="col mr-2">
                                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                                        Laporan Kerja Praktek Terunggah</div>
                                                    <div id="lkp_count" class="h5 mb-0 font-weight-bold text-gray-800">0</div>
                                                </div>
                                                <div class="col-auto">
                                                    <i class="fas fa-file fa-2x text-gray-300"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card border-left-danger shadow w-100 h-100 py-2">
                                        <div class="card-body">
                                            <div class="row no-gutters align-items-center">
                                                <div class="col mr-2">
                                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                                        Skripsi Terunggah</div>
                                                    <div id="skripsi_count" class="h5 mb-0 font-weight-bold text-gray-800">0</div>
                                                </div>
                                                <div class="col-auto">
                                                    <i class="fas fa-book fa-2x text-gray-300"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-8 mb-2">
                                <div class="card shadow mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-area me-1"></i>
                                        Statistik Unggahan per Tahun Angkatan
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-pie pt-2 pb-2">
                                            <canvas id="myDocumentChart" width="100%" height="35"></canvas>
                                        </div>
                                        <div class="text-center small pb-0">
                                            <span class="me-2">
                                                <i class="fas fa-circle text-primary"></i> Laporan Kerja Praktek
                                            </span>
                                            <span class="">
                                                <i class="fas fa-circle text-success"></i> Skripsi
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="row">
                        <div class="col-12">
                            <div class="card mb-4">
                                <div class="card-header d-flex">
                                    <div class="h5 title my-auto">
                                        Data Perpustakaan Online
                                    </div>
                                </div>
                                <div class="card-body py-2 px-0">
                                    <ul class="nav nav-tabs nav-justified mb-3" id="document-tab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="pills-lkp-tab" data-bs-toggle="pill" data-bs-target="#pills-lkp" type="button" role="tab" aria-controls="lkp-lkp" aria-selected="true">Laporan Kerja Praktek</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="pills-skripsi-tab" data-bs-toggle="pill" data-bs-target="#pills-skripsi" type="button" role="tab" aria-controls="lkp-skripsi" aria-selected="false">Skripsi</button>
                                        </li>
                                    </ul>
                                    <div class="tab-content px-3" id="pills-tabContent">
                                        <div class="tab-pane fade show active" id="pills-lkp" role="tabpanel" aria-labelledby="pills-lkp-tab">
                                            <table id="lkpTable" class="display" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Nama Mahasiswa</th>
                                                        <th>Dosen Pembimbing</th>
                                                        <th>Tipe Dokumen</th>
                                                        <th>Judul Dokumen</th>
                                                        <th>Dibuat Pada</th>
                                                        <th>Dilihat Sebanyak</th>
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
                                                        <th>Dilihat Sebanyak</th>
                                                        <th></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                        <div class="tab-pane fade" id="pills-skripsi" role="tabpanel" aria-labelledby="pills-skripsi-tab">
                                            <table id="skripsiTable" class="display" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Nama Mahasiswa</th>
                                                        <th>Dosen Pembimbing</th>
                                                        <th>Tipe Dokumen</th>
                                                        <th>Judul Dokumen</th>
                                                        <th>Dibuat Pada</th>
                                                        <th>Dilihat Sebanyak</th>
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
                                                        <th>Dilihat Sebanyak</th>
                                                        <th></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
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
        function setReviewDocument(dokumen_akhir_id, filename) {
                // Send data to API
                $.ajax({
                    url: 'services/document/set-review-dokumen-akhir-service.php',
                    method: 'POST',
                    data: { dokumen_akhir_id: dokumen_akhir_id }, // Adjust data to be sent as needed
                    success: function(response) {
                        let { success, message } = response;
                        if (success) {
                            console.log(response);
                            // Proceed with download after successful API call
                            // You may also want to handle errors here
                            var downloadUrl = 'services/download-file.php?file=' + filename;
                            window.open(downloadUrl, '_blank');
                            // window.location.href = 'services/download-file.php?file=' + filename;
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle errors
                        console.error(xhr.responseText);
                    }
                });
            }
    </script>
    <script>
        $(document).ready(function() {
            var lkpTable = $('#lkpTable').DataTable({
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
                    "url": "services/document/get-document-lkp-finish-service.php", // Replace with the URL of your PHP script
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
                        "data": "review" ,
                        "render": function (data, type, row, meta) {
                            // Render the row index (meta.row) + 1 as the numbering index
                            return `<div><i class="fas fa-eye me-1 text-muted" style="font-size: 14px"></i>${data}</div>`;
                            // return meta.row + 1;
                        }
                    },
                    { 
                        "data": "dokumen_akhir",
                        "orderable": false,
                        "render": function (data, type, row) {
                            // return '<a target="_blank" id="btnResponse" type="button" class="btn btn-primary" href="services/download-file.php?file=' + data + '" target="_blank" onclick="setReviewDocument(' + row.dokumen_akhir_id + ')"><i class="fa-solid fa-file-arrow-down"></i></a>';
                            return '<button id="btnResponse" type="button" class="btn btn-primary" data-id="' + row.dokumen_akhir_id + '" onClick="setReviewDocument(' + row.dokumen_akhir_id + ', `' + data + '`)"><i class="fa-solid fa-file-arrow-down"></i></a>';
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
                    [5, "desc"]
                ]
            });
            
            var skripsiTable = $('#skripsiTable').DataTable({
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
                    "url": "services/document/get-document-skripsi-finish-service.php", // Replace with the URL of your PHP script
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
                        "data": "review" ,
                        "render": function (data, type, row, meta) {
                            // Render the row index (meta.row) + 1 as the numbering index
                            return `<div><i class="fas fa-eye me-1"></i>${data}</div>`;
                            // return meta.row + 1;
                        }
                    },
                    { 
                        "data": "dokumen_akhir",
                        "orderable": false,
                        "render": function (data, type, row) {
                            // return '<a target="_blank" id="btnResponse" type="button" class="btn btn-primary" href="services/download-file.php?file=' + data + '" target="_blank"><i class="fa-solid fa-file-arrow-down"></i></a>';
                            return '<button id="btnResponse" type="button" class="btn btn-primary" data-id="' + row.dokumen_akhir_id + '" onClick="setReviewDocument(' + row.dokumen_akhir_id + ', `' + data + '`)"><i class="fa-solid fa-file-arrow-down"></i></a>';
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
                    [5, "desc"]
                ]
            });

            // Event listener for tab change 
            $('#document-tab button').on('shown.bs.tab', function (e) {
                // Get the ID of the newly shown tab
                var tabId = $(e.target).data('bs-target');
                // Update DataTable based on tab ID
                if (tabId === '#pills-skripsi') {
                    // Reload DataTable for Document Skripsi
                    skripsiTable.ajax.reload();
                }
                // If it's the Document Attachment tab
                else if (tabId === "#pills-lkp") {
                    // Reload DataTable for Document Attachment
                    lkpTable.ajax.reload();
                }
            });

        })
    </script>
    <!-- Chart JS Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script>
        // Set new default font family and font color to mimic Bootstrap's default styling
        Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
        Chart.defaults.global.defaultFontColor = '#292b2c';
        if (userRole.includes("Kaprodi")) {
            $.ajax({
                method:"GET",
                url: "services/document/get-statistic-elibrary-service.php",
                success: function(response) {
                    // console.log(response);
                    if(response.success) {
                        let { count, statistic } = response;
                        $('#mahasiswa_count').text(count.mahasiswa_count);
                        $('#lkp_count').text(count.lkp_count);
                        $('#skripsi_count').text(count.skripsi_count);
    
                        // Extract all unique tahun_angkatan from statistic
                        const tahunAngkatanSet = new Set(statistic.map(entry => entry.tahun_angkatan));
    
                        // Define default types
                        const defaultTypes = ["Skripsi", "Laporan Kerja Praktek"];
    
                        // Generate data with default counts
                        const data = [];
                        for (const tahunAngkatan of tahunAngkatanSet) {
                            for (const defaultType of defaultTypes) {
                                const count = statistic.filter(entry => entry.tahun_angkatan === tahunAngkatan && entry.tipe === defaultType).length;
                                data.push({ tahun_angkatan: tahunAngkatan, tipe: defaultType, count: count });
                            }
                        }
    
                        // Sort the data by tahun_angkatan in ascending order
                        data.sort((a, b) => {
                            if (a.tahun_angkatan < b.tahun_angkatan) return -1;
                            if (a.tahun_angkatan > b.tahun_angkatan) return 1;
                            return 0;
                        });
    
                        // Find the highest count
                        let highestCount = 0;
                            for (const entry of data) {
                            if (entry.count > highestCount) {
                                highestCount = entry.count;
                            }
                        }
    
                        // Extract unique tahun_angkatan values from data
                        const uniqueTahunAngkatan = Array.from(new Set(data.map(entry => entry.tahun_angkatan)));
    
                        const datasets = [
                            {
                                label: "Laporan Kerja Praktek",
                                backgroundColor: "rgba(2,117,216,1)",
                                borderColor: "rgba(2,117,216,1)",
                                // Get count for LKP document type for each tahun_angkatan
                                data: uniqueTahunAngkatan.map(tahunAngkatan => {
                                    const entry = data.find(entry => entry.tahun_angkatan === tahunAngkatan && entry.tipe === "Laporan Kerja Praktek");
                                    return entry ? entry.count : 0;
                                }),
                            },
                            {
                                label: "Skripsi",
                                backgroundColor: "rgba(28, 200, 138, 1)",
                                borderColor: "rgba(28, 200, 138, 1)",
                                // Get count for Skripsi document type for each tahun_angkatan
                                data: uniqueTahunAngkatan.map(tahunAngkatan => {
                                    const entry = data.find(entry => entry.tahun_angkatan === tahunAngkatan && entry.tipe === "Skripsi");
                                    return entry ? entry.count : 0;
                                }),
                            }
                        ];
                        setChart(uniqueTahunAngkatan, datasets, highestCount);
                    } else {
                        toastr.error(response.message);
                    }
                }
            });
        }

        function setChart(uniqueTahunAngkatan, datasets, highestCount) {
            // Area Chart Example
            var ctx = document.getElementById("myDocumentChart");
            var myLineChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: uniqueTahunAngkatan,
                    datasets,
                },
                options: {
                    scales: {
                        xAxes: [{
                            time: {
                                unit: 'date'
                            },
                            gridLines: {
                                display: false
                            },
                            ticks: {
                                maxTicksLimit: 7
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                min: 0,
                                max: highestCount * 10,
                                maxTicksLimit: 5
                            },
                            gridLines: {
                                display: true
                            }
                        }],
                    },
                    legend: {
                        display: false
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                var datasetLabel = data.datasets[tooltipItem.datasetIndex].label || '';
                                return datasetLabel + ': ' + tooltipItem.yLabel;
                            }
                        }
                    }
                }
            });
        }

    </script>
</body>

</html>