<?php
    // Include the functions.php file from the main theme directory
    require_once 'helpers/authorize.php';
    require_once 'helpers/functions.php';
    require_once 'config/connection.php';
    if ($_SESSION['user']['role'] == 'Mahasiswa' && $_SESSION['user']['semester'] < 8) header("Location: beranda.php");
    // Perform database connection
    $conn = connect_to_database();
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $query = <<<EOD
            SELECT 
                p.judul,
                p.status_pengajuan_id,
                p.dosen_pembimbing,
                p.dokumen_pengajuan,
                p.surat_validasi,
                da.dokumen_akhir,
                tp.tipe,
                sp.status,
                ak.nama_lengkap,
                p.dibuat_oleh,
                p.dibuat_pada
            FROM 
                `tbl_pengajuan` p
                LEFT JOIN
                    `tbl_tipe_pengajuan` tp
                    ON
                        tp.id = p.tipe_pengajuan_id
                LEFT JOIN
                    `tbl_status_pengajuan` sp
                    ON
                        sp.id = p.status_pengajuan_id
                LEFT JOIN
                    `tbl_dokumen_akhir` da
                    ON
                        da.pengajuan_id = p.id
                LEFT JOIN
                    `tbl_akun` ak
                    ON
                        ak.kode = p.dibuat_oleh
            WHERE 
                p.id=?
        EOD;
        $stmt = $conn->prepare($query);
        // bind parameter ke query
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$result) header("Location: 404.php");
    } else {
        if (strpos($_SESSION['user']['role'], 'Kaprodi') != false) header("Location: beranda.php");
        $query = "SELECT p.id FROM `tbl_pengajuan` p WHERE p.dibuat_oleh=? AND p.tipe_pengajuan_id = 2";
        $stmt = $conn->prepare($query);
        // bind parameter ke query
        $stmt->execute([$_SESSION['user']['kode']]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) header("Location: skripsi.php?id=" . $result['id']);
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
        <title>E-Library | Skripsi</title>
        <link href="assets/css/styles.css" rel="stylesheet" />
        <link href="assets/css/custom.css" rel="stylesheet" />
        <!-- Toast Library -->
        <link href="assets/lib/toast/toast.min.css" rel="stylesheet" />
        <!-- Bootstrap Library -->
        <link href="assets/lib/bootstrap/bootstrap-datepicker.min.css" rel="stylesheet" />
        <style>
            .label {
                font-weight: 600;
                color: rgba(1, 41, 112, 0.6);
            }

            #waiting-proses .card-title {
                color: #700101;
            }

            #waiting-proses .row {
                margin-bottom: .95rem;
                font-size: 1rem;
            }

            .bg-success-200 {
                /* background-color: rgb(110, 255, 127); */
                background-color: rgb(105, 224, 90);
            }

            .bg-muted {
                background-color: #e2e3e5;
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
                        <h1 class="mt-4">Skripsi</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item">Panel</li>
                            <li class="breadcrumb-item active">Skripsi</li>
                        </ol>
                        <?php if (!isset($id) || $result['status_pengajuan_id'] == 7) { ?>
                            <?php if (!isset($id)) { ?>
                                <div id="unggah-dokumen" class="row">
                                    <div class="col-12">
                                        <div class="card card-body shadow-sm">
                                            <h4 class="card-title mb-3">Unggah Dokumen</h4>
                                            <form id="proposalForm" novalidate>
                                                <div class="row mb-3">
                                                    <label for="judul" class="col-md-3 col-form-label">Judul <span class="text-danger fw-bold">*</span></label>
                                                    <div class="col-md-9">
                                                        <input 
                                                            id="judul" 
                                                            name="judul" 
                                                            type="text" 
                                                            class="form-control" 
                                                            placeholder="Masukkan judul ..."
                                                        />
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="pembimbing" class="col-md-3 col-form-label">Dosen Pembimbing <span class="text-danger fw-bold">*</span></label>
                                                    <div class="col-md-9">
                                                        <input 
                                                            id="pembimbing" 
                                                            name="pembimbing" 
                                                            type="text" 
                                                            class="form-control" 
                                                            placeholder="Masukkan dosen pembimbing ..."
                                                        />
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="dokumen_proposal" class="col-md-3 col-form-label">Dokumen Proposal <span class="text-danger fw-bold">*</span></label>
                                                    <div class="col-md-9">
                                                        <!-- <input 
                                                            id="dokumen_proposal" 
                                                            name="dokumen_proposal" 
                                                            type="file" 
                                                            class="form-control" 
                                                            accept="application/msword, application/pdf, application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                                                            required
                                                        /> -->
                                                        <input 
                                                            id="dokumen_proposal" 
                                                            name="dokumen_proposal" 
                                                            type="file" 
                                                            class="form-control" 
                                                            accept="application/pdf"
                                                            required
                                                        />
                                                    </div>
                                                </div>
                                                <div class="row mt-4">
                                                    <div class="col">
                                                        <p class="fz-10 text-muted">(<span class="text-danger"><b>*</b></span>) <b>Mandatori</b> wajib diisi.</p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-auto mx-auto">
                                                        <button id="btnSubmit" type="submit" class="btn btn-sm btn-primary">Submit</button>
                                                        <button id="btnSimpan" type="button" class="btn btn-sm btn-secondary">Simpan</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php } elseif ($result['status_pengajuan_id'] == 7) { ?>
                                <div id="revise-dokumen" class="row">
                                    <div class="col-8">
                                        <div class="card card-body shadow-sm">
                                            <h4 class="card-title mb-3">Revisi Dokumen</h4>
                                            <form id="reviseProposalForm" novalidate>
                                                <input type="hidden" name="pengajuan_id" value="<?php echo $id; ?>">
                                                <div class="row mb-3">
                                                    <label for="judul" class="col-md-3 col-form-label">Judul <span class="text-danger fw-bold">*</span></label>
                                                    <div class="col-md-9">
                                                        <input 
                                                            id="judul" 
                                                            name="judul" 
                                                            type="text" 
                                                            class="form-control" 
                                                            placeholder="Masukkan judul ..."
                                                            value="<?php echo $result['judul'] ?>"
                                                        />
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="pembimbing" class="col-md-3 col-form-label">Dosen Pembimbing <span class="text-danger fw-bold">*</span></label>
                                                    <div class="col-md-9">
                                                        <input 
                                                            id="pembimbing" 
                                                            name="pembimbing" 
                                                            type="text" 
                                                            class="form-control" 
                                                            placeholder="Masukkan dosen pembimbing ..."
                                                            value="<?php echo $result['dosen_pembimbing'] ?>"
                                                        />
                                                    </div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-md-3"></div>
                                                    <div class="col-md-9 d-flex">
                                                        <a target="_blank" href="services/download-file.php?file=<?php echo $result['dokumen_pengajuan']; ?>">
                                                            <span class="badge my-auto bg-info"><i class="fas fa-download"></i> <?php echo $result['dokumen_pengajuan'] ?></span>
                                                        </a>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <label for="dokumen_proposal" class="col-md-3 col-form-label">Ganti Dokumen Proposal</label>
                                                    <div class="col-md-9">
                                                        <!-- <input 
                                                            id="dokumen_proposal" 
                                                            name="dokumen_proposal" 
                                                            type="file" 
                                                            class="form-control" 
                                                            accept="application/msword, application/pdf, application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                                                        /> -->
                                                        <input 
                                                            id="dokumen_proposal" 
                                                            name="dokumen_proposal" 
                                                            type="file" 
                                                            class="form-control" 
                                                            accept="application/pdf"
                                                        />
                                                    </div>
                                                </div>
                                                <div class="row mt-4">
                                                    <div class="col">
                                                        <p class="fz-10 text-muted">(<span class="text-danger"><b>*</b></span>) <b>Mandatori</b> wajib diisi.</p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-auto mx-auto">
                                                        <button type="submit" class="btn btn-sm btn-warning">Revisi</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="col-xl-4">
                                        <div class="card card-body shadow-sm">
                                            <div class="row mb-4">
                                                <div class="col h5 card-title my-auto">Proses</div>
                                            </div>
                                            <?php 
                                                $i = 1;
                                                $query = <<<EOD
                                                    SELECT 
                                                        ak.nama_lengkap user,
                                                        ak.role,
                                                        sp.status,
                                                        pp.catatan,
                                                        pp.dokumen_revisi,
                                                        pp.tampilkan,
                                                        pp.dibuat_pada
                                                    FROM 
                                                        tbl_proses_pengajuan pp 
                                                        JOIN (
                                                            SELECT ak.*, rl.jabatan `role` FROM tbl_akun ak JOIN tbl_jabatan rl ON rl.id = ak.jabatan_id
                                                        ) ak
                                                            ON
                                                                ak.kode = pp.dibuat_oleh
                                                        JOIN
                                                            tbl_status_pengajuan sp
                                                            ON
                                                                sp.id = pp.status_pengajuan_id
                                                    WHERE 
                                                        pp.pengajuan_id = ? AND pp.tampilkan = 1
                                                    ORDER BY pp.id DESC
                                                EOD;
                                                $stmt = $conn->prepare($query);
                                                // bind parameter ke query
                                                $stmt->execute([$id]);
                                                $hasil = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                                foreach ($hasil as $row) {
                                            ?>
                                                <div class="row mx-2 mb-0">
                                                    <div class="col pb-3 px-1 <?php echo $i != count($hasil) ? 'border-start' : '' ?> <?php echo $i == 1 ? 'border-success' : 'border-primary-200' ?>">
                                                        <div class="position-relative">
                                                            <div class="rounded-circle position-absolute <?php echo $i == 1 ? 'bg-success' : 'bg-muted' ?>" style="width: 15px; height: 15px; top: 0px; z-index: 2; left: -12px;"></div>
                                                        </div>
                                                        <div class="ps-3 position-relative text-dark">
                                                            <div class="d-flex justify-content-between">
                                                                <p class="mb-0 text-bold" style="font-size: 14px; margin-top: -5px;"><?php echo $row['role'] ?></p>
                                                                <?php
                                                                    $timestamp = strtotime($row['dibuat_pada']);
                                                                    $formattedDate = date('d-M-Y H:m', $timestamp);
                                                                ?>
                                                                <span class="text-muted my-auto" style="font-size: 11px">
                                                                    <span style="font-size: 11px"><?php echo $formattedDate ?></span>
                                                                </span>
                                                            </div>
                                                            <p class="text-muted mb-0" style="font-size: 12px">
                                                                <span class="text-dark text-bold"><?php echo $row['user'] ?></span> - <i><?php echo $row['status'] ?></i>
                                                            </p>
                                                            <?php if ($row['catatan']) { ?>
                                                                <div class="card card-body mt-1 p-2 border-0 shadow-none" style="border-radius: 7px; background-color: #e2e3e5;">
                                                                    <p class="mb-1 text-dark text-bold" style="font-size: 10px">Komentar: </p>
                                                                    <?php if (isset($row['dokumen_revisi'])) { ?>
                                                                    <div class="d-flex justify-content-between">
                                                                        <p class="mb-0 text-dark" style="font-size: 12px"><?php echo $row['catatan'] ?></p>
                                                                        <a target="_blank" href="download-file.php?file=<?php echo $row['dokumen_revisi'] ?>">
                                                                            <i class="fa-solid fa-file-arrow-down"></i>
                                                                        </a>
                                                                    </div>
                                                                    <?php } else { ?>
                                                                        <p class="mb-0 text-dark" style="font-size: 12px"><?php echo $row['catatan'] ?></p>
                                                                    <?php } ?>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php $i++ ?>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } else { ?>
                            <div id="waiting-proses" class="row">
                                <div class="col-md-8">
                                    <div class="card card-body shadow-sm">
                                        <div class="container-fluid">
                                            <div class="row mb-4">
                                                <div class="col h5 card-title my-auto">Data Proposal</div>
                                                <div class="col-auto rounded bg-success px-2 py-1 fz-12 text-white"><?php echo $result['status'] ?></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4 label">Judul</div>
                                                <div class="col-md-8"><?php echo $result['judul'] ?></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4 label ">Pembimbing</div>
                                                <div class="col-md-8"><?php echo $result['dosen_pembimbing'] ?></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4 label ">Dokumen Proposal</div>
                                                <div class="col-md-8">
                                                    <a target="_blank" href="services/download-file.php?file=<?php echo $result['dokumen_pengajuan'] ?>">
                                                        <i class="fas fa-download"></i> <?php echo $result['dokumen_pengajuan'] ?>
                                                    </a>
                                                </div>
                                            </div>
                                            <?php if ($result['surat_validasi'] && $result['status_pengajuan_id'] != 13) { ?>
                                                <div class="row">
                                                    <div class="col-md-4 label ">Surat Validasi</div>
                                                    <div class="col-md-8">
                                                        <a target="_blank" href="services/download-file.php?file=<?php echo $result['surat_validasi'] ?>">
                                                            <i class="fas fa-download"></i> <?php echo $result['surat_validasi'] ?>
                                                        </a>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <?php if ($result['dokumen_akhir'] && $result['status_pengajuan_id'] != 13) { ?>
                                                <div class="row">
                                                    <div class="col-md-4 label ">Dokumen Akhir</div>
                                                    <div class="col-md-8">
                                                        <a target="_blank" href="services/download-file.php?file=<?php echo $result['dokumen_akhir'] ?>">
                                                            <i class="fas fa-download"></i> <?php echo $result['dokumen_akhir'] ?>
                                                        </a>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <div class="row">
                                                <div class="col-md-4 label ">Diajukan Oleh</div>
                                                <div class="col-md-8"><?php echo $result['nama_lengkap'] ?></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4 label ">Tanggal Pengajuan</div>
                                                <div class="col-md-8">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    <?php
                                                        $timestamp = strtotime($result['dibuat_pada']);
                                                        $formattedDate = date('d-m-Y H:m', $timestamp);
                                                        echo $formattedDate . ' WIB';
                                                    ?>
                                                </div>
                                            </div>
                                            <?php 
                                                if (
                                                    strpos($_SESSION['user']['role'], 'Kaprodi') !== false && 
                                                    // in_array($result['status_pengajuan_id'], array(3, 7))
                                                    $result['status_pengajuan_id'] == 3
                                                ) { 
                                            ?>
                                            <!-- <div class="row">
                                                <div class="col-md-4 label ">Catatan</div>
                                                <div class="col-md-8">
                                                    <textarea class="form-control" name="catatan" id="catatan" rows="5"></textarea>
                                                    <div id="validationCatatanFeedback" class="invalid-feedback">
                                                        Catatan tidak boleh kosong!
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-auto mx-auto">
                                                    <?php if ($result['surat_validasi'] && $result['dokumen_akhir']) { ?>
                                                        <button id="action-publish" type="button" class="btn btn-sm btn-info">Publish</button>
                                                    <?php } else { ?>
                                                        <button id="action-accept" type="button" class="btn btn-sm btn-success">Terima</button>
                                                    <?php } ?>
                                                    <button id="action-revise" type="button" class="btn btn-sm btn-warning">Revisi</button>
                                                </div>
                                            </div> -->
                                            <div class="row my-4">
                                                <div class="col-auto mx-auto">
                                                    <?php if ($result['surat_validasi'] && $result['dokumen_akhir']) { ?>
                                                        <button id="action-publish" type="button" class="btn btn-sm btn-info">Publish</button>
                                                    <?php } else { ?>
                                                        <button id="action-accept" type="button" class="btn btn-sm btn-success">Terima</button>
                                                    <?php } ?>
                                                    <button id="action-revisi" type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#revisiPengajuan">
                                                        Revisi
                                                    </button>
                                                </div>
                                            </div>
                                            <?php } ?>
                                            <?php 
                                                if (
                                                    strpos($_SESSION['user']['role'], 'Mahasiswa') !== false &&
                                                    in_array($result['status_pengajuan_id'], array(9, 13))
                                                ) { 
                                            ?>
                                                <?php if ($result['status_pengajuan_id'] == 9) { ?>
                                                    <div class="row my-4">
                                                        <div class="col h5 card-title my-auto">Upload Dokumen Final</div>
                                                    </div>
                                                    <form id="lastDocumentForm" method="post" novalidate>
                                                        <div class="row mb-3">
                                                            <label for="surat_validasi" class="col-md-3 col-form-label">Surat Validasi <span class="text-danger fw-bold">*</span></label>
                                                            <div class="col-md-9">
                                                                <!-- <input 
                                                                    id="surat_validasi" 
                                                                    name="surat_validasi" 
                                                                    type="file" 
                                                                    class="form-control" 
                                                                    accept="application/msword, application/pdf, application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                                                                    required
                                                                /> -->
                                                                <input 
                                                                    id="surat_validasi" 
                                                                    name="surat_validasi" 
                                                                    type="file" 
                                                                    class="form-control" 
                                                                    accept="application/pdf"
                                                                    required
                                                                />
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label for="dokumen_akhir" class="col-md-3 col-form-label">Dokumen Akhir <span class="text-danger fw-bold">*</span></label>
                                                            <div class="col-md-9">
                                                                <input 
                                                                    id="dokumen_akhir" 
                                                                    name="dokumen_akhir" 
                                                                    type="file" 
                                                                    class="form-control" 
                                                                    accept="application/pdf"
                                                                    required
                                                                />
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-auto mx-auto">
                                                                <button id="btnSubmit" type="submit" class="btn btn-sm btn-primary">Unggah</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                <?php } elseif ($result['status_pengajuan_id'] == 13) { ?>
                                                    <div class="row my-4">
                                                        <div class="col h5 card-title my-auto">Revisi Dokumen Final</div>
                                                    </div>
                                                    <form id="reviseLastDocumentForm" method="post" novalidate>
                                                        <div class="row mb-2">
                                                            <label for="surat_validasi" class="col-md-3 col-form-label"></label>
                                                            <div class="col-md-9">
                                                                <a target="_blank" href="services/download-file.php?file=<?php echo $result['surat_validasi']; ?>">
                                                                    <span class="badge bg-info"><?php echo $result['surat_validasi']; ?></span>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label for="surat_validasi" class="col-md-3 col-form-label">Ganti Surat Validasi</label>
                                                            <div class="col-md-9">
                                                                <!-- <input 
                                                                    id="surat_validasi" 
                                                                    name="surat_validasi" 
                                                                    type="file" 
                                                                    class="form-control" 
                                                                    accept="application/msword, application/pdf, application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                                                                /> -->
                                                                <input 
                                                                    id="surat_validasi" 
                                                                    name="surat_validasi" 
                                                                    type="file" 
                                                                    class="form-control" 
                                                                    accept="application/pdf"
                                                                />
                                                            </div>
                                                        </div>
                                                        <div class="row mb-2">
                                                            <label for="surat_validasi" class="col-md-3 col-form-label"></label>
                                                            <div class="col-md-9">
                                                                <a target="_blank" href="services/download-file.php?file=<?php echo $result['dokumen_akhir']; ?>">
                                                                    <span class="badge bg-info"><?php echo $result['dokumen_akhir']; ?></span>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label for="dokumen_akhir" class="col-md-3 col-form-label">Dokumen Akhir <span class="text-danger fw-bold">*</span></label>
                                                            <div class="col-md-9">
                                                                <input 
                                                                    id="dokumen_akhir" 
                                                                    name="dokumen_akhir" 
                                                                    type="file" 
                                                                    class="form-control" 
                                                                    accept="application/pdf"
                                                                />
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-auto mx-auto">
                                                                <button id="btnRevise" type="submit" class="btn btn-sm btn-warning">Update</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                <?php } ?>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4">
                                    <div class="card card-body shadow-sm">
                                        <div class="row mb-4">
                                            <div class="col h5 card-title my-auto">Proses</div>
                                        </div>
                                        <?php 
                                            $i = 1;
                                            $query = <<<EOD
                                                SELECT 
                                                    ak.nama_lengkap user,
                                                    ak.role,
                                                    sp.status,
                                                    pp.catatan,
                                                    pp.dokumen_revisi,
                                                    pp.tampilkan,
                                                    pp.dibuat_pada
                                                FROM 
                                                    tbl_proses_pengajuan pp 
                                                    JOIN (
                                                        SELECT ak.*, rl.jabatan `role` FROM tbl_akun ak JOIN tbl_jabatan rl ON rl.id = ak.jabatan_id
                                                    ) ak
                                                        ON
                                                            ak.kode = pp.dibuat_oleh
                                                    JOIN
                                                        tbl_status_pengajuan sp
                                                        ON
                                                            sp.id = pp.status_pengajuan_id
                                                WHERE 
                                                    pp.pengajuan_id = ? AND pp.tampilkan = 1
                                                ORDER BY pp.id DESC
                                            EOD;
                                            $stmt = $conn->prepare($query);
                                            // bind parameter ke query
                                            $stmt->execute([$id]);
                                            $hasil = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                            foreach ($hasil as $row) {
                                        ?>
                                            <div class="row mx-2 mb-0">
                                                <div class="col pb-3 px-1 <?php echo $i != count($hasil) ? 'border-start' : '' ?> <?php echo $i == 1 ? 'border-success' : 'border-primary-200' ?>">
                                                    <div class="position-relative">
                                                        <div class="rounded-circle position-absolute <?php echo $i == 1 ? 'bg-success' : 'bg-muted' ?>" style="width: 15px; height: 15px; top: 0px; z-index: 2; left: -12px;"></div>
                                                    </div>
                                                    <div class="ps-3 position-relative text-dark">
                                                        <div class="d-flex justify-content-between">
                                                            <p class="mb-0 text-bold" style="font-size: 14px; margin-top: -5px;"><?php echo $row['role'] ?></p>
                                                            <?php
                                                                $timestamp = strtotime($row['dibuat_pada']);
                                                                $formattedDate = date('d-M-Y H:m', $timestamp);
                                                            ?>
                                                            <span class="text-muted my-auto" style="font-size: 11px">
                                                                <span style="font-size: 11px"><?php echo $formattedDate ?></span>
                                                            </span>
                                                        </div>
                                                        <p class="text-muted mb-0" style="font-size: 12px">
                                                            <span class="text-dark text-bold"><?php echo $row['user'] ?></span> - <i><?php echo $row['status'] ?></i>
                                                        </p>
                                                        <?php if ($row['catatan']) { ?>
                                                            <div class="card card-body mt-1 p-2 border-0 shadow-none" style="border-radius: 7px; background-color: #e2e3e5;">
                                                                <p class="mb-1 text-dark text-bold" style="font-size: 10px">Komentar: </p>
                                                                <?php if (isset($row['dokumen_revisi'])) { ?>
                                                                <div class="d-flex justify-content-between">
                                                                    <p class="mb-0 text-dark" style="font-size: 12px"><?php echo $row['catatan'] ?></p>
                                                                    <a href="download-file.php?file=<?php echo $row['dokumen_revisi'] ?>">
                                                                        <i class="fa-solid fa-file-arrow-down"></i>
                                                                    </a>
                                                                </div>
                                                                <?php } else { ?>
                                                                    <p class="mb-0 text-dark" style="font-size: 12px"><?php echo $row['catatan'] ?></p>
                                                                <?php } ?>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php $i++ ?>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </main>
                <!-- Navbar -->
                <?php include 'includes/footer.php' ?>
                <!-- End Navbar -->
            </div>
        </div>
        <div class="modal fade" id="revisiPengajuan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="revisiPengajuanLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="askReviseProposalForm" novalidate>
                        <div class="modal-header">
                            <h4 class="modal-title" id="revisiPengajuanLabel">Revisi Pengajuan</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-1">
                                <label for="dokumen_revisi" class="form-label">Dokumen Revisi</label>
                                <!-- <input 
                                    id="dokumen_revisi" 
                                    name="dokumen_revisi" 
                                    type="file" 
                                    class="form-control" 
                                    accept="application/msword, application/pdf, application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                                /> -->
                                <input 
                                    id="dokumen_revisi" 
                                    name="dokumen_revisi" 
                                    type="file" 
                                    class="form-control" 
                                    accept="application/pdf"
                                />
                            </div>
                            <div class="mb-1">
                                <label for="catatan" class="form-label">Catatan <span class="text-danger fw-bold">*</span></label>
                                <textarea class="form-control" name="catatan" id="catatan" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-warning">Revisi</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script src="assets/js/scripts.js"></script>
        <!-- JQuery Library -->
        <script src="assets/lib/jquery/jquery.min.js"></script>
        <script src="assets/lib/jquery/jquery.validate.min.js"></script>
        <script src="assets/lib/jquery/additional-methods.min.js"></script>
        <!-- Bootstrap Library -->
        <script src="assets/lib/bootstrap/bootstrap.bundle.min.js"></script>
        <script src="assets/lib/bootstrap/bootstrap-datepicker.min.js"></script>
        <!-- Font Awesome Library -->
        <script src="assets/lib/font-awesome/all.js"></script>
        <!-- Toast Library -->
        <script src="assets/lib/toast/toast.min.js"></script>
        <script>
            $(document).ready(function () {
                var id = getUrlParameter('id');
                if (!id) {
                    let payload = {
                        tipe_pengajuan: 2
                    }
                    $.ajax({
                        method:"POST",
                        url: "services/document/get-document-by-user-service.php",
                        data: payload,
                        success: function(response) {
                            // console.log(response);
                            if(response.success) {
                                var document = response.message;
                                window.location.href=`skripsi.php?id=${document.id}`;
                            }
                        }
                    });
                }
                // Add validator
                // $.validator.addMethod("file_size_validator", function(value, element) {
                //     var maxSize = 1024 * 1024; // 1 MB (adjust as needed)
                //     var fileInput = element;

                //     // Check if a file has been selected
                //     if (fileInput.files && fileInput.files[0]) {
                //         var fileSize = fileInput.files[0].size;
                //         return fileSize <= maxSize;
                //     }

                //     return true;
                // }, "Ukuran file apa pun tidak boleh melebihi 1 MB");

                // Form validate submit proposalForm
                $("#proposalForm").validate({
                    rules: {
                        judul: "required",
                        pembimbing: "required",
                        dokumen_proposal: {
                            required: true,
                            extension: "pdf", // Add valid file extensions here
                            // file_size_validator: true, // 2 MB (adjust as needed)
                        }
                    },
                    messages: {
                        judul: "Judul tidak boleh kosong.",
                        pembimbing: "Dosen pembimbing tidak boleh kosong.",
                        dokumen_proposal: {
                            required: "Dokumen tidak boleh kosong.",
                            extension: "File yang diterima pdf"
                        }
                    },
                    submitHandler: function(form) {
                        // debugger;
                        event.preventDefault();
                        $(form).find("#btnSubmit, #btnSimpan").prop("disabled", true);
                        // Use FormData to handle file and other form data
                        var formData = new FormData(form);
                        formData.append('tipe_dokumen', 2);
                        $.ajax({
                            method:"POST",
                            url: "services/document/upload-proposal-service.php",
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function(response) {
                                // console.log(response);
                                if(response.success) {
                                    // console.log(response.message);
                                    toastr.success("File berhasil diupload!");
                                    // Reload the page after 3 seconds
                                    setTimeout(function() {
                                        var id = response.message;
                                        window.location.reload();
                                    }, 2000);
                                } else {
                                    $(form).find("#btnSubmit, #btnSimpan").prop("disabled", false);
                                    toastr.error(response.message);
                                }
                            }
                        });
                    }
                });

                // Form validate revise reviseProposalForm
                $("#reviseProposalForm").validate({
                    rules: {
                        judul: "required",
                        pembimbing: "required",
                        dokumen_proposal: {
                            // extension: "doc|docx|pdf", // Add valid file extensions here
                            extension: "pdf", // Add valid file extensions here
                            // file_size_validator: true, // 2 MB (adjust as needed)
                        }
                    },
                    messages: {
                        judul: "Judul tidak boleh kosong.",
                        pembimbing: "Dosen pembimbing tidak boleh kosong.",
                        dokumen_proposal: {
                            required: "Dokumen tidak boleh kosong.",
                            extension: "File yang diterima pdf"
                        }
                    },
                    submitHandler: function(form) {
                        // debugger;
                        event.preventDefault();
                        console.log(form);
                        // Use FormData to handle file and other form data
                        var formData = new FormData(form);
                        formData.append('tipe_dokumen', 2);
                        $.ajax({
                            method:"POST",
                            url: "services/document/revise-proposal-service.php",
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function(response) {
                                // console.log(response);
                                if(response.success) {
                                    toastr.success(response.message);
                                    // Reload the page after 3 seconds
                                    setTimeout(function() {
                                        window.location.reload();
                                    }, 2000);
                                } else {
                                    toastr.error(response.message);
                                }
                            }
                        });
                    }
                });

                // Form validate submit lastDocumentForm
                $("#lastDocumentForm").validate({
                    rules: {
                        surat_validasi: {
                            required: true,
                            // extension: "doc|docx|pdf", // Add valid file extensions here
                            extension: "pdf", // Add valid file extensions here
                            // file_size_validator: true, // 2 MB (adjust as needed)
                        },
                        lkp_document: {
                            required: true,
                            extension: "pdf", // Add valid file extensions here
                            // file_size_validator: true, // 2 MB (adjust as needed)
                        }
                    },
                    messages: {
                        surat_validasi: {
                            required: "Dokumen tidak boleh kosong.",
                            extension: "File yang diterima pdf"
                        },
                        lkp_document: {
                            required: "Dokumen tidak boleh kosong.",
                            extension: "File yang diterima pdf"
                        }
                    },
                    submitHandler: function(form) {
                        // debugger;
                        event.preventDefault();
                        $(form).find("#btnSubmit").prop("disabled", true);
                        // Use FormData to handle file and other form data
                        var formData = new FormData(form);
                        formData.append('id', id);
                        formData.append('tipe_dokumen', 'SKRIPSI');
                        $.ajax({
                            method:"POST",
                            url: "services/document/upload-last-document-service.php",
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function(response) {
                                // console.log(response);
                                if(response.success) {
                                    toastr.success(response.message);
                                    setTimeout(function() {
                                        window.location.reload();
                                    }, 2000);
                                } else {
                                    $(form).find("#btnSubmit").prop("disabled", false);
                                    toastr.error(response.message);
                                }
                            }
                        });
                    }
                });

                // Form validate submit lastDocumentForm
                $("#reviseLastDocumentForm").validate({
                    rules: {
                        surat_validasi: {
                            // extension: "doc|docx|pdf", // Add valid file extensions here
                            extension: "pdf", // Add valid file extensions here
                            // file_size_validator: true, // 2 MB (adjust as needed)
                        },
                        lkp_document: {
                            extension: "pdf", // Add valid file extensions here
                            // file_size_validator: true, // 2 MB (adjust as needed)
                        }
                    },
                    messages: {
                        surat_validasi: {
                            extension: "File yang diterima pdf"
                        },
                        lkp_document: {
                            extension: "File yang diterima pdf"
                        }
                    },
                    submitHandler: function(form) {
                        // debugger;
                        event.preventDefault();
                        $(form).find("#btnRevise").prop("disabled", true);
                        // Use FormData to handle file and other form data
                        var formData = new FormData(form);
                        formData.append('id', id);
                        formData.append('tipe_dokumen', 'SKRIPSI');
                        $.ajax({
                            method:"POST",
                            url: "services/document/revise-last-document-service.php",
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function(response) {
                                // console.log(response);
                                if(response.success) {
                                    toastr.success(response.message);
                                    setTimeout(function() {
                                        window.location.reload();
                                    }, 2000);
                                } else {
                                    $(form).find("#btnRevise").prop("disabled", false);
                                    toastr.error(response.message);
                                }
                            }
                        });
                    }
                });

                // Form ask revisi submit form
                $("#askReviseProposalForm").validate({
                    rules: {
                        dokumen_revisi: {
                            extension: "pdf", // Add valid file extensions here
                            // file_size_validator: true, // 2 MB (adjust as needed)
                        },
                        catatan: "required"
                    },
                    messages: {
                        dokumen_revisi: {
                            extension: "File yang diterima pdf."
                        },
                        catatan: "Catatan tidak boleh kosong."
                    },
                    submitHandler: function(form) {
                        event.preventDefault();
                        $(form).find("button").prop("disabled", true);
                        // Use FormData to handle file and other form data
                        var formData = new FormData(form);
                        formData.append('id', id);
                        // console.log(form);
                        $.ajax({
                            method:"POST",
                            url: "services/document/ask-revise-proposal-service.php",
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function(response) {
                                if(response.success) {
                                    toastr.success(response.message);
                                    setTimeout(function() {
                                        window.location.href = "persetujuan.php";
                                    }, 2000);
                                } else {
                                    $(form).find("button").prop("disabled", false);
                                    toastr.error(response.message);
                                }
                            }
                        });
                    }
                });

                // Button acc proposal handler
                $('#action-publish, #action-accept').click(function () {
                    var catatan = $("textarea#catatan").val();
                    let payload = { id, catatan, tanggapan: "Terima" };
                    $('#action-publish, #action-accept, #action-revisi').prop("disabled", true);
                    $.ajax({
                        method:"POST",
                        url: "services/document/response-kaprodi-service.php",
                        data: payload,
                        success: function(response) {
                            if(response.success) {
                                toastr.success(response.message);
                                setTimeout(() => {
                                    window.location.href = 'persetujuan.php';
                                }, 2000);
                            } else {
                                $('#action-publish, #action-accept').prop("disabled", false);
                                toastr.error(response.message);
                            }
                        }
                    });
                });

                // Button rev proposal handler
                $('#action-revise').click(function () {
                    var catatan = $("textarea#catatan").val();
                    let payload = { id, catatan, tanggapan: "Revisi" };
                    $('#action-revise').prop("disabled", true);
                    $.ajax({
                        method:"POST",
                        url: "services/document/response-kaprodi-service.php",
                        data: payload,
                        success: function(response) {
                            if(response.success) {
                                toastr.success(response.message);
                                setTimeout(() => {
                                    window.location.href = 'persetujuan.php';
                                }, 2000);
                            } else {
                                $('#action-revise').prop("disabled", false);
                                toastr.error(response.message);
                            }
                        }
                    });
                });

                // Button acc document handler
                // $('#action-publish').click(function () {
                //     var catatan = $("textarea#catatan").val();
                //     var payload = { id, catatan, tanggapan: "Revisi" }

                //     $.ajax({
                //         method:"POST",
                //         url: "services/document/accept-last-dokumen-service.php",
                //         data: payload,
                //         success: function(response) {
                //             if(response.success) {
                //                 toastr.success(response.message);
                //                 setTimeout(() => {
                //                     window.location.reload();
                //                 }, 2000);
                //             } else {
                //                 toastr.error(response.message);
                //             }
                //         }
                //     });
                // });
            });
        </script>
    </body>
</html>
