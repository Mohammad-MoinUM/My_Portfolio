<?php
// Initialize the session
session_start();

// Clear remember me cookie if it exists
if (isset($_COOKIE['remember_token'])) {
    // Get user ID from session to clear token from database
    if (isset($_SESSION['id'])) {
        require_once "../config/config.php";
        
        // Clear the remember token from database
        $clear_token_sql = "UPDATE users SET remember_token = NULL WHERE id = ?";
        if ($stmt = mysqli_prepare($conn, $clear_token_sql)) {
            mysqli_stmt_bind_param($stmt, "i", $_SESSION['id']);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }
    
    // Delete the cookie
    setcookie('remember_token', '', time() - 3600, '/');
}

// Unset all of the session variables
$_SESSION = array();

// Destroy the session.
session_destroy();

// Redirect to login page
header("location: ../login.php");
exit;
?>