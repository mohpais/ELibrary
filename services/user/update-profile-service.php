<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    session_start();
    $success = false;
    $message = "Unsupported HTTP method";
    // Include the database connection details from database.php
    require_once '../../config/connection.php';
    // Check if the update profile form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nama_lengkap = $_POST['full_name'];
        $no_telp = $_POST['no_telp'];
        $email = $_POST['email'];
        $jurusan = isset($_POST['jurusan']) ? $_POST['jurusan'] : null;
        $semester = isset($_POST['semester']) ? $_POST['semester'] : null;
        $tanggal_bergabung = isset($_POST['tanggal_bergabung']) ? $_POST['tanggal_bergabung'] : null;
        // Perform database connection
        $conn = connect_to_database();
        $kd_user = $_POST['kode_user'];
        // Prepare and execute the query to find the user by kd_user
        $stmt = $conn->prepare("SELECT akun.* FROM tbl_akun akun WHERE kode = :kd_user");
        // Bind parameters
        $stmt->bindParam(':kd_user', $kd_user);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        // jika user terdaftar
        if ($user) {
            $query = "UPDATE tbl_akun SET nama_lengkap=:nama_lengkap, no_telp=:no_telp, email=:email, jurusan=:jurusan, semester=:semester, tanggal_bergabung=:tanggal_bergabung WHERE kode=:kd_user";
            $stmt = $conn->prepare($query);
            // bind parameter ke query
            $params = array(
                ":nama_lengkap" => $nama_lengkap,
                ":no_telp" => $no_telp,
                ":email" => $email,
                ":jurusan" => $jurusan,
                ":semester" => $semester,
                ":tanggal_bergabung" => $tanggal_bergabung,
                ":kd_user" => $kd_user
            );
        
            // eksekusi query untuk menyimpan ke database
            $success = $stmt->execute($params);
            // Update session login
            if ($success) {
                $_SESSION['user']['nama_lengkap'] = $_POST['full_name'];
                $_SESSION['user']['no_telp'] = $_POST['no_telp'];
                $_SESSION['user']['email'] = $_POST['email'];
                $_SESSION['user']['jurusan'] = $_POST['jurusan'];
                $_SESSION['user']['semester'] = $_POST['semester'];
                $_SESSION['user']['tanggal_bergabung'] = $_POST['tanggal_bergabung'];
            }
            $message = "Update profile " . $success ? 'berhasil' : "gagal" . "!";
        } else {
            $message = "User tidak ditemukan";
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

