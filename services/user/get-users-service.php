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
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        try {
            // Perform database connection
            $conn  = connect_to_database();
            // DataTables Setting
            $start = $_POST['start'];
            $length = $_POST['length'];
            $orderColumn = $_POST['order'][0]['column'];
            $orderDir = $_POST['order'][0]['dir'];
            // Define column DataTable
            $columns = array(
                0 => 'kode',
                1 => 'nama_lengkap',
                2 => 'email',
                3 => 'role',
                4 => 'no_telp',
                5 => 'tanggal_bergabung'
            );
            $orderBy = $columns[$orderColumn];
            $searchValue = $_POST['search']['value'];

            // Query
            $query = "SELECT akun.kode, akun.nama_lengkap, akun.email, role.jabatan role, akun.no_telp, akun.tanggal_bergabung FROM tbl_akun akun join tbl_jabatan role on role.id = akun.jabatan_id WHERE kode != $kode_user";
            // Total records without filtering
            $totalRecordsQuery  = "SELECT COUNT(dataQ.kode) as total FROM ($query) dataQ";
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
                $totalFilteredQuery  = "SELECT COUNT(dataQ.kode) as total FROM ($query) dataQ WHERE dataQ.nama_lengkap LIKE '%$searchValue%'";
                $totalFilteredResult = $conn->prepare($totalFilteredQuery);
                $totalFilteredResult->execute();
                $totalFiltered       = $totalFilteredResult->fetch(PDO::FETCH_ASSOC)['total'];
                // Get records of data
                $query .= " AND nama_lengkap LIKE '%$searchValue%' ORDER BY $orderBy $orderDir LIMIT $length OFFSET $start";
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
    