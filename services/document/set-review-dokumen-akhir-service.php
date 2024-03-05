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
        $id = $_POST['dokumen_akhir_id'];
        // Perform database connection
        $conn = connect_to_database();
        try {
            // Start a transaction
            $conn->beginTransaction();
            // Check review dokumen_akhir
            $queryCheck = "SELECT COUNT(id) as total FROM tbl_unduhan_dokumen_akhir WHERE dokumen_akhir_id = ? AND dibuat_oleh = ?";
            $stmtCheck  = $conn->prepare($queryCheck);
            $stmtCheck->execute([$id, $kode_user]);

            $totalCheck = $stmtCheck->fetch(PDO::FETCH_ASSOC)['total'];
            if ($totalCheck == 0) {
                // Set review dokumen
                $queryInsert = "INSERT INTO tbl_unduhan_dokumen_akhir (dokumen_akhir_id, dibuat_oleh) VALUES (?, ?)";
                $stmtInsert  = $conn->prepare($queryInsert);
                $stmtInsert->execute([$id, $kode_user]);
                
                $message = "Success review dokumen akhir!";
            } else {
                $message = "Dokumen akhir sudah pernah direview!";
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
?>