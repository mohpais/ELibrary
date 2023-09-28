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
        $pengajuan_id = $_POST['pengajuan_id'];
        $judul = $_POST['judul'];
        $pembimbing = $_POST['pembimbing'];
        $tipe_dokumen = $_POST['tipe_dokumen'];

        // Perform database connection
        $conn = connect_to_database();
        try {
            // Start a transaction
            $conn->beginTransaction();

            $query1 = "UPDATE tbl_pengajuan SET status_pengajuan_id = 3, judul = :judul, dosen_pembimbing = :pembimbing WHERE id = :pengajuan_id";
            $stmt1 = $conn->prepare($query1);
            // bind parameter ke query
            $params1 = array(
                ":judul" => $judul,
                ":pembimbing" => $pembimbing,
                ":pengajuan_id" => $pengajuan_id
            );
            $stmt1->execute($params1);

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
                ":pengajuan_id" => $pengajuan_id,
                ":status_pengajuan_id" => 8,
                ":tampilkan" => 1,
                ":akun_id" => $code_user
            ));

            // Insert status proses 
            $stmt3 = $conn->prepare($queryProses);
            $stmt3->execute(array(
                ":pengajuan_id" => $pengajuan_id,
                ":status_pengajuan_id" => 3,
                ":tampilkan" => 0,
                ":akun_id" => $code_user
            ));

            $success = true;
            if (!empty($_FILES["dokumen_proposal"]["name"])) {
                $targetDir = "../../uploads/"; // Specify the directory where you want to save the uploaded files
                $documentType = $tipe_dokumen == 2 ? 'Skripsi' : 'LKP';
                $extension = pathinfo($_FILES["dokumen_proposal"]["name"], PATHINFO_EXTENSION);
                $filename = "Proposal-" . $documentType . "-" . $code_user . "." . $extension;
                $targetFile = $targetDir . basename($filename);
                // Check if the file already exists
                if (file_exists($targetFile)) {
                    if (unlink($targetFile)) {
                        if (move_uploaded_file($_FILES["dokumen_proposal"]["tmp_name"], $targetFile)) {
                            $success = true;
                        } else {
                            throw new Exception("Error uploading '$filename'.");
                        }
                    } else {
                        throw new Exception("Error deleting '$filename'.");
                    }
                } else {
                    // Try to move the uploaded file to the specified directory
                    if (move_uploaded_file($_FILES["dokumen_proposal"]["tmp_name"], $targetFile)) {
                        $success = true;
                    } else {
                        throw new Exception("Error uploading '$filename'.");
                    }
                }
            }
            
            $message = "Berhasil merivisi pengajuan";
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