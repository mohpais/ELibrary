<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    session_start();
    $success = false;
    $message = "Unsupported HTTP method";
    // Include the database connection details from database.php
    require_once '../../config/connection.php';
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $kode_user = $_SESSION['user']['kode'];
        $id = $_POST['id'];
        $tipe_dokumen = $_POST['tipe_dokumen'];
        $filename_surat_validasi = "Surat-Validasi-" . $tipe_dokumen . "-" . $kode_user . "." . $extension = pathinfo($_FILES["surat_validasi"]["name"], PATHINFO_EXTENSION);
        $filename_dokumen_akhir = $tipe_dokumen . "-Final-" . $kode_user . "." . $extension = pathinfo($_FILES["dokumen_akhir"]["name"], PATHINFO_EXTENSION);
        $targetDir = "../../uploads/"; // Specify the directory where you want to save the uploaded files
        // Perform database connection
        $conn = connect_to_database();
        try {
            // Start a transaction
            $conn->beginTransaction();
            
            // Upload surat validasi into Table Pengajuan
            $stmt = $conn->prepare("UPDATE tbl_pengajuan SET status_pengajuan_id = 3, surat_validasi = ?, terakhir_diubah_oleh = ? WHERE id = ?");
            $stmt->execute([$filename_surat_validasi, $kode_user, $id]);
            $targetFile1 = $targetDir . basename($filename_surat_validasi);
            // Check if the file already exists
            if (file_exists($targetFile1)) {
                throw new Exception("File sudah ada.");
            } else {
                // Try to move the uploaded file to the specified directory
                if (move_uploaded_file($_FILES["surat_validasi"]["tmp_name"], $targetFile1)) {
                    $success = true;
                } else {
                    throw new Exception("Error uploading file.");
                }
            }
            // Insert data into Table Proses Pengajuan
            $stmt2 = $conn->prepare("INSERT INTO tbl_proses_pengajuan (pengajuan_id, status_pengajuan_id, dibuat_oleh) VALUES (?, 10, ?)");
            $stmt2->execute([$id, $kode_user]);
            
            $stmt3 = $conn->prepare("INSERT INTO tbl_proses_pengajuan (pengajuan_id, status_pengajuan_id, tampilkan, dibuat_oleh) VALUES (?, 3, 0, ?)");
            $stmt3->execute([$id, $kode_user]);
 

            // Insert data into Table Proses Pengajuan
            $query4 = <<<EOD
                INSERT INTO 
                    tbl_dokumen_akhir (
                        pengajuan_id, 
                        dokumen_akhir, 
                        dibuat_oleh,
                        terakhir_diubah_oleh
                    ) 
                    VALUES 
                    (:pengajuan_id, :dokumen_akhir, :akun_id, :akun_id)
            EOD;
            // Insert aksi pengguna 
            $stmt4 = $conn->prepare($query4);
            $params4 = array(
                ":pengajuan_id" => $id,
                ":dokumen_akhir" => $filename_dokumen_akhir,
                ":akun_id" => $kode_user
            );
            $stmt4->execute($params4);
            
            $targetFile2 = $targetDir . basename($filename_dokumen_akhir);
            // Check if the file already exists
            if (file_exists($targetFile2)) {
                throw new Exception("File sudah ada.");
            } else {
                // Try to move the uploaded file to the specified directory
                if (move_uploaded_file($_FILES["dokumen_akhir"]["tmp_name"], $targetFile2)) {
                    $success = true;
                } else {
                    unlink($targetFile1);
                    throw new Exception("Error uploading file.");
                }
            }
            $message = "Success upload dokumen akhir!";
            // Commit the transaction if everything is successful
            $conn->commit();

        } catch (PDOException $e) {
            // An exception was thrown, so perform a rollback
            $conn->rollback();

            // Handle the exception (e.g., log the error, display a message)
            $message = "Error: " . $e->getMessage();
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