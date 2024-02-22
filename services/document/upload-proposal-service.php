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
        $judul = $_POST['judul'];
        $pembimbing = $_POST['pembimbing'];
        $tipe_dokumen = $_POST['tipe_dokumen'];
        $documentType = $tipe_dokumen == 2 ? 'Skripsi' : 'LKP';
        $extension = pathinfo($_FILES["dokumen_proposal"]["name"], PATHINFO_EXTENSION);
        // Get current date and time as a timestamp
        $currentTimestamp = time();
        $filename = "Proposal-" . $documentType . "-" . $code_user . "-" . $currentTimestamp .  "." . $extension;
        $targetFile = "";
        // Perform database connection
        $conn = connect_to_database();
        try {
            // Start a transaction
            $conn->beginTransaction();
            $kaprodi_role_id = $_SESSION['user']['jurusan'] == 'Sistem Informasi' ? 2 : 3;
            $stmtcheck  = $conn->prepare('SELECT * FROM tbl_akun WHERE jabatan_id = :kaprodi_role_id');
            // Bind parameters
            $stmtcheck->bindParam(':kaprodi_role_id', $kaprodi_role_id);
            $stmtcheck->execute();
            $kaprodi = $stmtcheck->fetch(PDO::FETCH_ASSOC);
            // Check if kaprodi is exists
            if (!$kaprodi) {
                $success = false;
                $message = 'Tidak ada data Kaprodi! Silahkan hubungi bagian administrasi!';
            } else {
                // Insert data into Table Pengajuan dengan LKP
                $query1 = <<<EOD
                    INSERT INTO tbl_pengajuan (
                        tipe_pengajuan_id, 
                        status_pengajuan_id, 
                        judul, 
                        dosen_pembimbing, 
                        dokumen_pengajuan, 
                        dibuat_oleh, 
                        terakhir_diubah_oleh
                    ) VALUES (:tipe_dokumen, 3, :judul, :pembimbing, :filename, :akun_id, :akun_id)
                EOD;
                $stmt1 = $conn->prepare($query1);
                // bind parameter ke query
                $params1 = array(
                    ":tipe_dokumen" => $tipe_dokumen,
                    ":judul" => $judul,
                    ":pembimbing" => $pembimbing,
                    ":filename" => $filename,
                    ":akun_id" => $code_user
                );
                $stmt1->execute($params1);
                $pengajuan_id = $conn->lastInsertId();

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
                $params2 = array(
                    ":pengajuan_id" => $pengajuan_id,
                    ":status_pengajuan_id" => 2,
                    ":tampilkan" => 1,
                    ":akun_id" => $code_user
                );
                $stmt2->execute($params2);

                // Insert status proses 
                $stmt3 = $conn->prepare($queryProses);
                $params3 = array(
                    ":pengajuan_id" => $pengajuan_id,
                    ":status_pengajuan_id" => 3,
                    ":tampilkan" => 1,
                    ":akun_id" => $code_user
                );
                $stmt3->execute($params3);
                
                $targetDir = "../../uploads/"; // Specify the directory where you want to save the uploaded files
                $targetFile = $targetDir . basename($filename);
                // Check if the file already exists
                if (file_exists($targetFile)) {
                    throw new Exception("File sudah ada.");
                } else {
                    // Try to move the uploaded file to the specified directory
                    if (move_uploaded_file($_FILES["dokumen_proposal"]["tmp_name"], $targetFile)) {
                        $success = true;
                    } else {
                        throw new Exception("Error uploading '$filename'.");
                    }
                }
                $message = $pengajuan_id;
            }
            
            // Commit the transaction if everything is successful
            $conn->commit();

        } catch (PDOException $e) {
            $success = false;
            // if ($targetFile != "") {
            //     unlink($targetFile);
            // }
            // An exception was thrown, so perform a rollback
            $conn->rollback();
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