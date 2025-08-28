<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../login.php");
    exit;
}
require_once "../config/config.php";

$title = $company = $duration = $description = "";
$title_err = $company_err = "";
$is_edit = false; $exp_id = 0;

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $exp_id = $_POST["id"] ?? 0; $is_edit = !empty($exp_id);

    if(empty(trim($_POST["title"]))){ $title_err = "Please enter title."; } else { $title = trim($_POST["title"]); }
    if(empty(trim($_POST["company"]))){ $company_err = "Please enter company."; } else { $company = trim($_POST["company"]); }
    $duration = trim($_POST["duration"] ?? "");
    $description = trim($_POST["description"] ?? "");

    if(empty($title_err) && empty($company_err)){
        if($is_edit){
            $sql = "UPDATE experiences SET title = ?, company = ?, duration = ?, description = ? WHERE id = ?";
            if($stmt = mysqli_prepare($conn, $sql)){
                mysqli_stmt_bind_param($stmt, "ssssi", $title, $company, $duration, $description, $exp_id);
                if(mysqli_stmt_execute($stmt)){ header("location: dashboard.php#tab-experience"); exit(); }
                else { echo "Oops! Something went wrong. Please try again later."; }
            }
        } else {
            $sql = "INSERT INTO experiences (title, company, duration, description) VALUES (?, ?, ?, ?)";
            if($stmt = mysqli_prepare($conn, $sql)){
                mysqli_stmt_bind_param($stmt, "ssss", $title, $company, $duration, $description);
                if(mysqli_stmt_execute($stmt)){ header("location: dashboard.php#tab-experience"); exit(); }
                else { echo "Oops! Something went wrong. Please try again later."; }
            }
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($conn);
} else {
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        $exp_id = trim($_GET["id"]); $is_edit = true;
        $sql = "SELECT * FROM experiences WHERE id = ?";
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "i", $exp_id);
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
                if(mysqli_num_rows($result) == 1){
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    $title = $row["title"]; $company = $row["company"]; $duration = $row["duration"]; $description = $row["description"]; 
                } else { header("location: error.php"); exit(); }
            } else { echo "Oops! Something went wrong. Please try again later."; }
        }
        mysqli_stmt_close($stmt); mysqli_close($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $is_edit ? "Edit" : "Add"; ?> Experience - Portfolio Admin</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .form-container { max-width: 800px; margin: 20px auto; padding: 20px; background-color: white; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .form-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .form-title { font-size: 24px; margin: 0; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 600; }
        .form-group input, .form-group textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; font-size: 16px; }
        .form-group textarea { height: 120px; }
        .form-group .invalid-feedback { color: red; font-size: 14px; margin-top: 5px; }
        .btn-submit { background-color: rgb(53, 53, 53); color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .btn-submit:hover { background-color: black; }
        .btn-cancel { background-color: #f44336; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; font-size: 16px; text-decoration: none; margin-left: 10px; }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="form-header">
            <h2 class="form-title"><?php echo $is_edit ? "Edit" : "Add New"; ?> Experience</h2>
            <a href="dashboard.php#tab-experience" class="btn-cancel" style="background-color: #2196F3;">Back to Dashboard</a>
        </div>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" class="form-control <?php echo (!empty($title_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $title; ?>">
                <span class="invalid-feedback"><?php echo $title_err; ?></span>
            </div>
            <div class="form-group">
                <label>Company</label>
                <input type="text" name="company" class="form-control <?php echo (!empty($company_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $company; ?>">
                <span class="invalid-feedback"><?php echo $company_err; ?></span>
            </div>
            <div class="form-group">
                <label>Duration</label>
                <input type="text" name="duration" class="form-control" value="<?php echo $duration; ?>">
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control"><?php echo $description; ?></textarea>
            </div>
            <?php if($is_edit): ?>
                <input type="hidden" name="id" value="<?php echo $exp_id; ?>"/>
            <?php endif; ?>
            <div class="form-group">
                <input type="submit" class="btn-submit" value="<?php echo $is_edit ? 'Update' : 'Create'; ?> Experience">
                <a href="dashboard.php#tab-experience" class="btn-cancel">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>


