<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    session_start();
    $success = false;
    $message = "Unsupported HTTP method";
    // Include the database connection details from database.php
    require_once '../../config/connection.php';
    // Check if the register form is submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $kd_user = $_POST['kode'];
        $fullname = $_POST['nama_lengkap'];
        $jabatan_id = $_POST['jabatan_id'];
        $jurusan = $_POST['jurusan'];

        // Perform database connection
        $conn = connect_to_database();

        // Prepare and execute the query to find the user by kd_user
        $stmt = $conn->prepare("SELECT akun.* FROM tbl_akun akun WHERE kode = :kd_user");
        // Bind parameters
        $stmt->bindParam(':kd_user', $kd_user);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        // jika user terdaftar
        if ($user) {
            // Prepare and execute the query to insert user details into the database
            $query = "UPDATE tbl_akun set kode = :kd_user, jabatan_id = :jabatan_id, nama_lengkap = :name, jurusan = :jurusan WHERE kode = :kd_user";
            $stmt = $conn->prepare($query);
            // bind parameter ke query
            $params = array(
                ":kd_user" => $kd_user,
                ":jabatan_id" => $jabatan_id,
                ":name" => $fullname,
                ":jurusan" => $jurusan,
                ":created_by" => $_SESSION['user']['kode']
            );
            // execute query untuk menyimpan data user ke database
            $success = $stmt->execute($params);
            if ($success) {
                $message = "Berhasil mengubah data pengguna!";
            } else {
                $message = "Terdapat kesalahan saat mengubah data pengguna!";
            }
        } else {
            $message = "Akun tidak terdaftar!";
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