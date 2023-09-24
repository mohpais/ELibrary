<?php
    // Start the session to check if the user is logged in
    session_start();

    // Check if the user is logged in and authorized
    // Replace this condition with your own authorization logic
    if (!isset($_SESSION['user'])) {
        // Redirect the user to the sign-in page
        session_destroy();
        header('Location: sign-in.php');
        exit; // Make sure to exit to prevent further script execution
    }
?>