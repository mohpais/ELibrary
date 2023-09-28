<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    session_start();
    $success = false;
    $message = "";
    // Include the database connection details from database.php
    require_once '../../config/connection.php';
    $kode_user = $_SESSION['user']['kode'];
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Perform database connection
        $conn  = connect_to_database();
        // Exec query
        $query = "SELECT * FROM `tbl_pengajuan` WHERE dibuat_oleh = ? AND tipe_pengajuan_id = ?";
        $stmt  = $conn->prepare($query);
        $stmt->execute([$kode_user, $_POST['tipe_pengajuan']]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            $success = true;
            $message = $data;
        }

    }
    
    $jsonResponse = json_encode(array(
        'success' => $success,
        'message' => $message
    ));
    // Set the Content-Type header to indicate JSON response
    header('Content-Type: application/json');
    // Send the JSON response back to the client
    echo $jsonResponse;