<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    session_start();
    $success = false;
    $message = "Unsupported HTTP method";
    // Include the database connection details from database.php
    require_once '../../config/connection.php';
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Perform database connection
        $conn = connect_to_database();
        try {
            $kode_user = $_SESSION['user']['kode'];
            $pengajuan_id = $_POST['id'];
            $catatan = $_POST['catatan'];
            $filename_dokumen_revisi = null;
            if (isset($_FILES["dokumen_revisi"]["name"])) {
                $filename_dokumen_revisi = "Dokumen Revisi-" . time() . "-" . $kode_user . "." . $extension = pathinfo($_FILES["dokumen_revisi"]["name"], PATHINFO_EXTENSION);
            }

            // Start a transaction
            $conn->beginTransaction();
            $stmt  = $conn->prepare("SELECT * FROM `tbl_pengajuan` WHERE id = ?");
            $stmt->execute([$pengajuan_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result && $result['status_pengajuan_id'] == 3) {
                // Response untuk last dokumen
                if ($result['surat_validasi']) {
                    $stmt1 = $conn->prepare("INSERT INTO tbl_proses_pengajuan (pengajuan_id, status_pengajuan_id, catatan, dokumen_revisi, dibuat_oleh) VALUES (?, 12, ?, ?, ?)");
                    $stmt1->execute([$pengajuan_id, $catatan, $filename_dokumen_revisi, $kode_user]);
                    
                    $stmt2 = $conn->prepare("INSERT INTO tbl_proses_pengajuan (pengajuan_id, status_pengajuan_id, tampilkan, dibuat_oleh) VALUES (?, 13, 0, ?)");
                    $stmt2->execute([$pengajuan_id, $kode_user]);

                    $stmt3 = $conn->prepare("UPDATE tbl_pengajuan SET status_pengajuan_id = 13, terakhir_diubah_oleh = ? WHERE id = ?");
                    $stmt3->execute([$kode_user, $pengajuan_id]);
                // Response untuk proposal baru
                } else {
                    $stmt1 = $conn->prepare("INSERT INTO tbl_proses_pengajuan (pengajuan_id, status_pengajuan_id, catatan, dokumen_revisi, dibuat_oleh) VALUES (?, 5, ?, ?, ?)");
                    $stmt1->execute([$pengajuan_id, $catatan, $filename_dokumen_revisi, $kode_user]);
                    
                    $stmt2 = $conn->prepare("INSERT INTO tbl_proses_pengajuan (pengajuan_id, status_pengajuan_id, tampilkan, dibuat_oleh) VALUES (?, 7, 0, ?)");
                    $stmt2->execute([$pengajuan_id, $kode_user]);

                    $stmt3 = $conn->prepare("UPDATE tbl_pengajuan SET status_pengajuan_id = 7, terakhir_diubah_oleh = ? WHERE id = ?");
                    $stmt3->execute([$kode_user, $pengajuan_id]);
                }
                if ($filename_dokumen_revisi != null) {
                    $targetDir = "../../uploads/";
                    $targetFile = $targetDir . basename($filename_dokumen_revisi);
                    // Check if the file already exists
                    if (file_exists($targetFile)) {
                        throw new Exception("File sudah ada.");
                    } else {
                        // Try to move the uploaded file to the specified directory
                        if (move_uploaded_file($_FILES["dokumen_revisi"]["tmp_name"], $targetFile)) {
                            $success = true;
                        } else {
                            throw new Exception("Error uploading file.");
                        }
                    }
                }
            } else {
                throw new Exception("Pengajuan tidak ditemukan!");
            }
            $message = "Berhasil meminta pengajuan untuk direvisi!";

            $success = true;
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