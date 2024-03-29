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
        $password = "ubk2023";

        // Hash and encrypt the password using password_hash()
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        if ($hashed_password == false) {
            $error = password_get_info();
            // You can log the error or take appropriate action based on the error information.
            $message = "Password hash failed: " . $error['algo'];
        }

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
            $message = "Akun sudah terdaftar!";
        } else {
            // Prepare and execute the query to insert user details into the database
            $query = "INSERT INTO tbl_akun (kode, jabatan_id, nama_lengkap, password, jurusan, dibuat_oleh, terakhir_diubah_oleh) VALUES (:kd_user, :jabatan_id, :name, :password, :jurusan, :created_by, :created_by)";
            $stmt = $conn->prepare($query);
            // bind parameter ke query
            $params = array(
                ":kd_user" => $kd_user,
                ":jabatan_id" => $jabatan_id,
                ":name" => $fullname,
                ":password" => $hashed_password,
                ":jurusan" => $jurusan,
                ":created_by" => $_SESSION['user']['kode']
            );
            // execute query untuk menyimpan data user ke database
            // $saved = $stmt->execute($params);
            $success = $stmt->execute($params);
            if ($success) {
                $message = "Berhasil menambahkan pengguna!";
            } else {
                $message = "Terdapat kesalahan saat menambahkan pengguna!";
            }
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