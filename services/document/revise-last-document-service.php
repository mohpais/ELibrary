<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    session_start();
    $success = false;
    $message = "Unsupported HTTP method";
    // Include the database connection details from database.php
    require_once '../../config/connection.php';
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $code_user = $_SESSION['user']['kode'];
        $id = $_POST['id'];
        $tipe_dokumen = $_POST['tipe_dokumen'];

        // Perform database connection
        $conn = connect_to_database();
        try {
            // Start a transaction
            $conn->beginTransaction();

            $query1 = "UPDATE tbl_pengajuan SET status_pengajuan_id = 3 WHERE id = '$id'";
            $stmt1 = $conn->prepare($query1);
            $stmt1->execute();

            // Insert data into Table Proses Pengajuan
            $queryProses = <<<EOD
                INSERT INTO 
                    tbl_proses_pengajuan (
                        pengajuan_id, 
                        status_pengajuan_id, 
                        tampilkan, 
                        dibuat_oleh
                    ) 
                    VALUES 
                    (:pengajuan_id, :status_pengajuan_id, :tampilkan, :akun_id)
            EOD;
            // Insert aksi pengguna 
            $stmt2 = $conn->prepare($queryProses);
            $stmt2->execute(array(
                ":pengajuan_id" => $id,
                ":status_pengajuan_id" => 14,
                ":tampilkan" => 1,
                ":akun_id" => $code_user
            ));

            // Insert status proses 
            $stmt3 = $conn->prepare($queryProses);
            $stmt3->execute(array(
                ":pengajuan_id" => $id,
                ":status_pengajuan_id" => 3,
                ":tampilkan" => 0,
                ":akun_id" => $code_user
            ));

            $targetDir = "../../uploads/"; // Specify the directory where you want to save the uploaded files
            $success = true;
            if (!empty($_FILES["surat_validasi"]["name"])) {
                $extension = pathinfo($_FILES["surat_validasi"]["name"], PATHINFO_EXTENSION);
                $filename_surat_validasi = "Surat-Validasi-" . $tipe_dokumen . "-" . $code_user . "." . $extension;
                $targetFile = $targetDir . basename($filename_surat_validasi);
                // Check if the file already exists
                if (file_exists($targetFile)) {
                    if (unlink($targetFile)) {
                        if (move_uploaded_file($_FILES["surat_validasi"]["tmp_name"], $targetFile)) {
                            $success = true;
                        } else {
                            throw new Exception("Error uploading '$filename_surat_validasi'.");
                        }
                    } else {
                        throw new Exception("Error deleting '$filename_surat_validasi'.");
                    }
                } else {
                    // Try to move the uploaded file to the specified directory
                    if (move_uploaded_file($_FILES["surat_validasi"]["tmp_name"], $targetFile)) {
                        $success = true;
                    } else {
                        throw new Exception("Error uploading '$filename_surat_validasi'.");
                    }
                }
            }
            if (!empty($_FILES["dokumen_akhir"]["name"])) {
                $extension = pathinfo($_FILES["dokumen_akhir"]["name"], PATHINFO_EXTENSION);
                $filename_dokumen_akhir = $tipe_dokumen . "-Final-" . $code_user . "." . $extension;
                $targetFile = $targetDir . basename($filename_dokumen_akhir);
                // Check if the file already exists
                if (file_exists($targetFile)) {
                    if (unlink($targetFile)) {
                        if (move_uploaded_file($_FILES["dokumen_akhir"]["tmp_name"], $targetFile)) {
                            $success = true;
                        } else {
                            throw new Exception("Error uploading '$filename_dokumen_akhir'.");
                        }
                    } else {
                        throw new Exception("Error deleting '$filename_dokumen_akhir'.");
                    }
                } else {
                    // Try to move the uploaded file to the specified directory
                    if (move_uploaded_file($_FILES["dokumen_akhir"]["tmp_name"], $targetFile)) {
                        $success = true;
                    } else {
                        throw new Exception("Error uploading '$filename_dokumen_akhir'.");
                    }
                }
            }
            
            $message = "Berhasil merivisi dokumen akhir";
            // Commit the transaction if everything is successful
            $conn->commit();

        } catch (PDOException $e) {
            $success = false;
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