<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../login.php");
    exit;
}
require_once "../config/config.php";

if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    $sql = "DELETE FROM contacts WHERE id = ?";
    if($stmt = mysqli_prepare($conn, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        $param_id = trim($_GET["id"]);
        if(mysqli_stmt_execute($stmt)){
            header("location: dashboard.php#tab-contacts");
            exit();
        } else { echo "Oops! Something went wrong. Please try again later."; }
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    header("location: dashboard.php#tab-contacts");
    exit();
}
?>


