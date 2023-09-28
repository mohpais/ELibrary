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
            $tanggapan = $_POST['tanggapan'];
            // Start a transaction
            $conn->beginTransaction();
            $stmt  = $conn->prepare("SELECT * FROM `tbl_pengajuan` WHERE id = ?");
            $stmt->execute([$pengajuan_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            switch ($tanggapan) {
                case 'Terima':
                    if ($result && $result['status_pengajuan_id'] === 3) {
                        // Response untuk last dokumen
                        if ($result['surat_validasi']) {
                            $stmt1 = $conn->prepare("INSERT INTO tbl_proses_pengajuan (pengajuan_id, status_pengajuan_id, catatan, dibuat_oleh) VALUES (?, 11, ?, ?)");
                            $stmt1->execute([$pengajuan_id, $catatan, $kode_user]);
                            
                            $stmt2 = $conn->prepare("INSERT INTO tbl_proses_pengajuan (pengajuan_id, status_pengajuan_id, tampilkan, dibuat_oleh) VALUES (?, 6, 0, ?)");
                            $stmt2->execute([$pengajuan_id, $kode_user]);

                            $stmt3 = $conn->prepare("UPDATE tbl_pengajuan SET status_pengajuan_id = 6, terakhir_diubah_oleh = ? WHERE id = ?");
                            $stmt3->execute([$kode_user, $pengajuan_id]);
                        // Response untuk proposal baru
                        } else {
                            $stmt1 = $conn->prepare("INSERT INTO tbl_proses_pengajuan (pengajuan_id, status_pengajuan_id, catatan, dibuat_oleh) VALUES (?, 4, ?, ?)");
                            $stmt1->execute([$pengajuan_id, $catatan, $kode_user]);
                            
                            $stmt2 = $conn->prepare("INSERT INTO tbl_proses_pengajuan (pengajuan_id, status_pengajuan_id, tampilkan, dibuat_oleh) VALUES (?, 9, 0, ?)");
                            $stmt2->execute([$pengajuan_id, $kode_user]);

                            $stmt3 = $conn->prepare("UPDATE tbl_pengajuan SET status_pengajuan_id = 9, terakhir_diubah_oleh = ? WHERE id = ?");
                            $stmt3->execute([$kode_user, $pengajuan_id]);
                        }
                    } else {
                        throw new Exception("Pengajuan tidak ditemukan!");
                    }
                    $message = "Berhasil menerima pengajuan!";
                    break;
                case 'Revisi':
                    if ($result && $result['status_pengajuan_id'] === 3) {
                        // Response untuk last dokumen
                        if ($result['surat_validasi']) {
                            $stmt1 = $conn->prepare("INSERT INTO tbl_proses_pengajuan (pengajuan_id, status_pengajuan_id, catatan, dibuat_oleh) VALUES (?, 12, ?, ?)");
                            $stmt1->execute([$pengajuan_id, $catatan, $kode_user]);
                            
                            $stmt2 = $conn->prepare("INSERT INTO tbl_proses_pengajuan (pengajuan_id, status_pengajuan_id, tampilkan, dibuat_oleh) VALUES (?, 13, 0, ?)");
                            $stmt2->execute([$pengajuan_id, $kode_user]);

                            $stmt3 = $conn->prepare("UPDATE tbl_pengajuan SET status_pengajuan_id = 13, terakhir_diubah_oleh = ? WHERE id = ?");
                            $stmt3->execute([$kode_user, $pengajuan_id]);
                        // Response untuk proposal baru
                        } else {
                            $stmt1 = $conn->prepare("INSERT INTO tbl_proses_pengajuan (pengajuan_id, status_pengajuan_id, catatan, dibuat_oleh) VALUES (?, 5, ?, ?)");
                            $stmt1->execute([$pengajuan_id, $catatan, $kode_user]);
                            
                            $stmt2 = $conn->prepare("INSERT INTO tbl_proses_pengajuan (pengajuan_id, status_pengajuan_id, tampilkan, dibuat_oleh) VALUES (?, 7, 0, ?)");
                            $stmt2->execute([$pengajuan_id, $kode_user]);

                            $stmt3 = $conn->prepare("UPDATE tbl_pengajuan SET status_pengajuan_id = 7, terakhir_diubah_oleh = ? WHERE id = ?");
                            $stmt3->execute([$kode_user, $pengajuan_id]);
                        }
                    } else {
                        throw new Exception("Pengajuan tidak ditemukan!");
                    }
                    $message = "Berhasil meminta pengajuan untuk direvisi!";
                    break;
                default:
                    break;
            }

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