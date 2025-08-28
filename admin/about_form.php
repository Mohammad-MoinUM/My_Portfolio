<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../login.php");
    exit;
}

// Include config file
require_once "../config/config.php";

// Define variables and initialize with empty values
$title = $content = $image_url = "";
$title_err = $content_err = $image_url_err = "";
$is_edit = false;
$about_id = 0;

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $about_id = $_POST["id"] ?? 0;
    $is_edit = !empty($about_id);

    // Validate title (optional)
    $title = trim($_POST["title"] ?? "");

    // Validate content
    if(empty(trim($_POST["content"]))){
        $content_err = "Please enter content.";
    } else{
        $content = trim($_POST["content"]);
    }

    // Image URL optional
    $image_url = trim($_POST["image_url"] ?? "");

    if(empty($content_err)){
        if($is_edit){
            $sql = "UPDATE about SET title = ?, content = ?, image_url = ? WHERE id = ?";
            if($stmt = mysqli_prepare($conn, $sql)){
                mysqli_stmt_bind_param($stmt, "sssi", $param_title, $param_content, $param_image_url, $param_id);
                $param_title = $title;
                $param_content = $content;
                $param_image_url = $image_url;
                $param_id = $about_id;
                if(mysqli_stmt_execute($stmt)){
                    header("location: dashboard.php#tab-about");
                    exit();
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }
            }
        } else {
            $sql = "INSERT INTO about (title, content, image_url) VALUES (?, ?, ?)";
            if($stmt = mysqli_prepare($conn, $sql)){
                mysqli_stmt_bind_param($stmt, "sss", $param_title, $param_content, $param_image_url);
                $param_title = $title;
                $param_content = $content;
                $param_image_url = $image_url;
                if(mysqli_stmt_execute($stmt)){
                    header("location: dashboard.php#tab-about");
                    exit();
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }
            }
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($conn);
} else {
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        $about_id = trim($_GET["id"]);
        $is_edit = true;
        $sql = "SELECT * FROM about WHERE id = ?";
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            $param_id = $about_id;
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
                if(mysqli_num_rows($result) == 1){
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    $title = $row["title"];    
                    $content = $row["content"]; 
                    $image_url = $row["image_url"]; 
                } else {
                    header("location: error.php");
                    exit();
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
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
    <title><?php echo $is_edit ? "Edit" : "Add"; ?> About - Portfolio Admin</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .form-container { max-width: 800px; margin: 20px auto; padding: 20px; background-color: white; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .form-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .form-title { font-size: 24px; margin: 0; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 600; }
        .form-group input, .form-group textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; font-size: 16px; }
        .form-group textarea { height: 150px; }
        .form-group .invalid-feedback { color: red; font-size: 14px; margin-top: 5px; }
        .btn-submit { background-color: rgb(53, 53, 53); color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .btn-submit:hover { background-color: black; }
        .btn-cancel { background-color: #f44336; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; font-size: 16px; text-decoration: none; margin-left: 10px; }
    </style>
    </head>
<body>
    <div class="form-container">
        <div class="form-header">
            <h2 class="form-title"><?php echo $is_edit ? "Edit" : "Add New"; ?> About Entry</h2>
            <a href="dashboard.php#tab-about" class="btn-cancel" style="background-color: #2196F3;">Back to Dashboard</a>
        </div>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Title (optional)</label>
                <input type="text" name="title" class="form-control" value="<?php echo $title; ?>">
            </div>
            <div class="form-group">
                <label>Content</label>
                <textarea name="content" class="form-control <?php echo (!empty($content_err)) ? 'is-invalid' : ''; ?>"><?php echo $content; ?></textarea>
                <span class="invalid-feedback"><?php echo $content_err; ?></span>
            </div>
            <div class="form-group">
                <label>Image URL</label>
                <input type="text" name="image_url" class="form-control" value="<?php echo $image_url; ?>">
                <small>Enter the path to your image (e.g., ../assets/profile-pic.png)</small>
            </div>
            <?php if($is_edit): ?>
                <input type="hidden" name="id" value="<?php echo $about_id; ?>"/>
            <?php endif; ?>
            <div class="form-group">
                <input type="submit" class="btn-submit" value="<?php echo $is_edit ? 'Update' : 'Create'; ?> About">
                <a href="dashboard.php#tab-about" class="btn-cancel">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>


