<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../login.php");
    exit;
}
require_once "../config/config.php";

$type = $value = $icon = "";
$type_err = $value_err = "";
$is_edit = false;
$contact_id = 0;

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $contact_id = $_POST["id"] ?? 0;
    $is_edit = !empty($contact_id);

    if(empty(trim($_POST["type"]))){
        $type_err = "Please enter contact type.";
    } else { $type = trim($_POST["type"]); }

    if(empty(trim($_POST["value"]))){
        $value_err = "Please enter contact value.";
    } else { $value = trim($_POST["value"]); }

    $icon = trim($_POST["icon"] ?? "");

    if(empty($type_err) && empty($value_err)){
        if($is_edit){
            $sql = "UPDATE contacts SET type = ?, value = ?, icon = ? WHERE id = ?";
            if($stmt = mysqli_prepare($conn, $sql)){
                mysqli_stmt_bind_param($stmt, "sssi", $type, $value, $icon, $contact_id);
                if(mysqli_stmt_execute($stmt)){
                    header("location: dashboard.php#tab-contacts");
                    exit();
                } else { echo "Oops! Something went wrong. Please try again later."; }
            }
        } else {
            $sql = "INSERT INTO contacts (type, value, icon) VALUES (?, ?, ?)";
            if($stmt = mysqli_prepare($conn, $sql)){
                mysqli_stmt_bind_param($stmt, "sss", $type, $value, $icon);
                if(mysqli_stmt_execute($stmt)){
                    header("location: dashboard.php#tab-contacts");
                    exit();
                } else { echo "Oops! Something went wrong. Please try again later."; }
            }
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($conn);
} else {
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        $contact_id = trim($_GET["id"]);
        $is_edit = true;
        $sql = "SELECT * FROM contacts WHERE id = ?";
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "i", $contact_id);
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
                if(mysqli_num_rows($result) == 1){
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    $type = $row["type"]; $value = $row["value"]; $icon = $row["icon"]; 
                } else { header("location: error.php"); exit(); }
            } else { echo "Oops! Something went wrong. Please try again later."; }
        }
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $is_edit ? "Edit" : "Add"; ?> Contact - Portfolio Admin</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .form-container { max-width: 800px; margin: 20px auto; padding: 20px; background-color: white; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .form-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .form-title { font-size: 24px; margin: 0; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 600; }
        .form-group input { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; font-size: 16px; }
        .form-group .invalid-feedback { color: red; font-size: 14px; margin-top: 5px; }
        .btn-submit { background-color: rgb(53, 53, 53); color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .btn-submit:hover { background-color: black; }
        .btn-cancel { background-color: #f44336; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; font-size: 16px; text-decoration: none; margin-left: 10px; }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="form-header">
            <h2 class="form-title"><?php echo $is_edit ? "Edit" : "Add New"; ?> Contact</h2>
            <a href="dashboard.php#tab-contacts" class="btn-cancel" style="background-color: #2196F3;">Back to Dashboard</a>
        </div>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Type</label>
                <input type="text" name="type" class="form-control <?php echo (!empty($type_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $type; ?>">
                <span class="invalid-feedback"><?php echo $type_err; ?></span>
            </div>
            <div class="form-group">
                <label>Value</label>
                <input type="text" name="value" class="form-control <?php echo (!empty($value_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $value; ?>">
                <span class="invalid-feedback"><?php echo $value_err; ?></span>
            </div>
            <div class="form-group">
                <label>Icon (optional URL or class)</label>
                <input type="text" name="icon" class="form-control" value="<?php echo $icon; ?>">
            </div>
            <?php if($is_edit): ?>
                <input type="hidden" name="id" value="<?php echo $contact_id; ?>"/>
            <?php endif; ?>
            <div class="form-group">
                <input type="submit" class="btn-submit" value="<?php echo $is_edit ? 'Update' : 'Create'; ?> Contact">
                <a href="dashboard.php#tab-contacts" class="btn-cancel">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>


