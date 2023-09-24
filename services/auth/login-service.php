<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    session_start();
    $success = false;
    $message = "Unsupported HTTP method";
    // Include the database connection details from database.php
    require_once '../../config/connection.php';
    // Check if the login form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $kd_user = $_POST['kode_user'];
        $password = $_POST['password'];
        // Perform database connection
        $conn = connect_to_database();
        // Prepare and execute the query to find the user by kd_user
        $stmt = $conn->prepare("SELECT akun.*, role.jabatan role FROM tbl_akun akun join tbl_jabatan role on role.id = akun.jabatan_id WHERE kode = :kd_user");
        // bind parameter ke query
        $stmt->bindParam(':kd_user', $kd_user);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        // jika user terdaftar dan password input yang dimasukkan sama dengan password didatabase
        if ($user && password_verify($password, $user['password'])) {
            // Store the user ID or any relevant data in the session for future use
            $_SESSION['user'] = $user;
            $success = true;
            $message = "Login berhasil!";
        } else {
            $message = "Kode user atau kata sandi salah!.";
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
?>