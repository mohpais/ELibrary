<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    session_start();
    $success = false;
    $message = "";
    // Set the Content-Type header to indicate JSON response
    header('Content-Type: application/json');
    // Include the database connection details from database.php
    require_once '../../config/connection.php';
    $kode_user    = $_SESSION['user']['kode'];
    $role_user    = $_SESSION['user']['role'];
    $jurusan    = $_SESSION['user']['jurusan'];
    $jurusan_kode = $role_user == "Kaprodi SI" ? "72%" : "71%";
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        try {
            // Perform database connection
            $conn  = connect_to_database();
            // Query counting
            $queryCount = <<<EOD
                WITH mahasiswa_counts AS (
                    SELECT 
                        COUNT(*) AS count 
                    FROM 
                        tbl_akun
                    WHERE
                        jabatan_id = 1
                    GROUP BY jabatan_id
                ), lkp_counts AS (
                    SELECT 
                        COUNT(*) AS count 
                    FROM 
                        tbl_pengajuan
                    WHERE
                        tipe_pengajuan_id = 1
                    GROUP BY tipe_pengajuan_id
                ), skripsi_counts AS (
                    SELECT 
                        COUNT(*) AS count 
                    FROM 
                        tbl_pengajuan
                    WHERE
                        tipe_pengajuan_id = 2
                    GROUP BY tipe_pengajuan_id
                )
                SELECT 
                    COALESCE((SELECT count FROM mahasiswa_counts), 0) AS mahasiswa_count,
                    COALESCE((SELECT count FROM lkp_counts), 0) AS lkp_count,
                    COALESCE((SELECT count FROM skripsi_counts), 0) AS skripsi_count
            EOD;
            $stmtCount  = $conn->prepare($queryCount);
            // $stmtCount->execute(array(
            //     ':jurusan' => $jurusan,
            //     ':jurusan_kode' => $jurusan_kode
            // ));
            $stmtCount->execute();

            $dataCount  = $stmtCount->fetch(PDO::FETCH_ASSOC);

            // Query statistic
            $queryStatistic = <<<EOD
                SELECT 
                    pengajuan.id,
                    tipe.tipe,
                    pengajuan.judul,
                    pengajuan.dosen_pembimbing,
                    akun.nama_lengkap,
                    akun.jurusan,
                    CONCAT('TA ', YEAR(akun.tanggal_bergabung)) AS tahun_angkatan
                FROM
                    tbl_pengajuan pengajuan
                    JOIN 
                        tbl_tipe_pengajuan tipe
                        ON
                            tipe.id = pengajuan.tipe_pengajuan_id
                    JOIN 
                        tbl_akun akun
                        ON
                            akun.kode = pengajuan.dibuat_oleh
            EOD;
            $stmtStatistic  = $conn->prepare($queryStatistic);
            // $stmtStatistic->execute(array(
            //     ':jurusan_kode' => $jurusan_kode
            // ));
            $stmtStatistic->execute();

            $dataStatistic  = $stmtStatistic->fetchAll(PDO::FETCH_ASSOC);

            // Prepare the response json
            $jsonResponse = array(
                "success"         => true,
                "count"           => $dataCount,
                "statistic"       => $dataStatistic
            );
            // $jsonResponse = json_encode($totalFiltered);
            echo json_encode($jsonResponse);
        } catch (Exception $ex) {
            echo json_encode(array(
                "success" => false,
                "message" => $ex
            ));
        }
    }