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
    $kode_user = $_SESSION['user']['kode'];
    $role_user = $_SESSION['user']['role'];
    $jurusan   = $role_user == "Kaprodi SI" ? "Sistem Informasi" : "Sistem Komputer";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        try {
            // Perform database connection
            $conn  = connect_to_database();
            // DataTables Setting
            $start = $_POST['start'];
            $length = $_POST['length'];
            $orderColumn = $_POST['order'][0]['column'];
            $orderDir = $_POST['order'][0]['dir'];
            $columns = array(
                0 => 'id',
                1 => 'nama_lengkap',
                2 => 'dosen_pembimbing',
                3 => 'tipe',
                4 => 'judul',
                5 => 'dokumen_akhir',
                6 => 'dibuat_pada'
            );
            $orderBy = $columns[$orderColumn];
            $searchValue = $_POST['search']['value'];

            // Query
            $query = <<<EOD
                SELECT 
                    p.id,
                    ak.nama_lengkap,
                    p.dosen_pembimbing,
                    tp.tipe,
                    p.judul,
                    da.dokumen_akhir,
                    p.dibuat_pada
                FROM 
                    `tbl_pengajuan` p
                    LEFT JOIN
                        `tbl_tipe_pengajuan` tp
                        ON
                            tp.id = p.tipe_pengajuan_id
                    LEFT JOIN
                        `tbl_dokumen_akhir` da
                        ON
                            da.pengajuan_id = p.id
                    LEFT JOIN
                        `tbl_akun` ak
                        ON
                            ak.kode = p.dibuat_oleh
                WHERE 
                    p.status_pengajuan_id = 6
            EOD;
            // Total records without filtering
            $totalRecordsQuery  = "SELECT COUNT(dataQ.id) as total FROM ($query) dataQ";
            $totalRecordsResult = $conn->prepare($totalRecordsQuery);
            $totalRecordsResult->execute();
            $totalRecords       = $totalRecordsResult->fetch(PDO::FETCH_ASSOC)['total'];
            if (empty($searchValue)) {
                // Total records without filtering
                $totalFiltered = $totalRecords;
                // Get records of data
                $query .= " ORDER BY $orderBy $orderDir LIMIT $length OFFSET $start";
            } else {
                // Total records with filtering
                $totalFilteredQuery  = "SELECT COUNT(dataQ.id) as total FROM ($query) dataQ WHERE dataQ.judul LIKE '%$searchValue%'";
                $totalFilteredResult = $conn->prepare($totalFilteredQuery);
                $totalFilteredResult->execute();
                $totalFiltered       = $totalFilteredResult->fetch(PDO::FETCH_ASSOC)['total'];
                // Get records of data
                $query .= " AND judul LIKE '%$searchValue%' ORDER BY $orderBy $orderDir LIMIT $length OFFSET $start";
            }
            $stmt    = $conn->prepare($query);
            $stmt->execute();
            $result  = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Prepare the response json
            $jsonResponse = array(
                "draw"            => intval($_POST['draw']),
                "recordsTotal"    => intval($totalRecords),
                "recordsFiltered" => intval($totalFiltered),
                "data"            => $result
            );
            // $jsonResponse = json_encode($totalFiltered);
            echo json_encode($jsonResponse);
        } catch (Throwable $th) {
            echo json_encode($th);
        }
    }