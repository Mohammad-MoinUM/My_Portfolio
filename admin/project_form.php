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
$title = $description = $image_url = $github_url = $demo_url = "";
$title_err = $description_err = $image_url_err = $github_url_err = $demo_url_err = "";
$is_edit = false;
$project_id = 0;

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Get hidden input value (for edit mode)
    $project_id = $_POST["id"] ?? 0;
    $is_edit = !empty($project_id);
    
    // Validate title
    if(empty(trim($_POST["title"]))){
        $title_err = "Please enter a title.";
    } else{
        $title = trim($_POST["title"]);
    }
    
    // Validate description
    if(empty(trim($_POST["description"]))){
        $description_err = "Please enter a description.";
    } else{
        $description = trim($_POST["description"]);
    }
    
    // Image URL is optional
    $image_url = trim($_POST["image_url"] ?? "");
    
    // GitHub URL is optional
    $github_url = trim($_POST["github_url"] ?? "");
    
    // Demo URL is optional
    $demo_url = trim($_POST["demo_url"] ?? "");
    
    // Check input errors before inserting in database
    if(empty($title_err) && empty($description_err)){
        if($is_edit){
            // Prepare an update statement
            $sql = "UPDATE projects SET title = ?, description = ?, image_url = ?, github_url = ?, demo_url = ? WHERE id = ?";
            
            if($stmt = mysqli_prepare($conn, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "sssssi", $param_title, $param_description, $param_image_url, $param_github_url, $param_demo_url, $param_id);
                
                // Set parameters
                $param_title = $title;
                $param_description = $description;
                $param_image_url = $image_url;
                $param_github_url = $github_url;
                $param_demo_url = $demo_url;
                $param_id = $project_id;
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    // Records updated successfully. Redirect to dashboard
                    header("location: dashboard.php");
                    exit();
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }
            }
        } else {
            // Prepare an insert statement
            $sql = "INSERT INTO projects (title, description, image_url, github_url, demo_url) VALUES (?, ?, ?, ?, ?)";
            
            if($stmt = mysqli_prepare($conn, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "sssss", $param_title, $param_description, $param_image_url, $param_github_url, $param_demo_url);
                
                // Set parameters
                $param_title = $title;
                $param_description = $description;
                $param_image_url = $image_url;
                $param_github_url = $github_url;
                $param_demo_url = $demo_url;
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    // Records created successfully. Redirect to dashboard
                    header("location: dashboard.php");
                    exit();
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($conn);
} else {
    // Check if this is an edit request
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $project_id = trim($_GET["id"]);
        $is_edit = true;
        
        // Prepare a select statement
        $sql = "SELECT * FROM projects WHERE id = ?";
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $project_id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
                
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $title = $row["title"];
                    $description = $row["description"];
                    $image_url = $row["image_url"];
                    $github_url = $row["github_url"];
                    $demo_url = $row["demo_url"];
                } else{
                    // URL doesn't contain valid id parameter. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
        
        // Close connection
        mysqli_close($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $is_edit ? "Edit" : "Add"; ?> Project - Portfolio Admin</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .form-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .form-title {
            font-size: 24px;
            margin: 0;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .form-group textarea {
            height: 150px;
        }
        .form-group .invalid-feedback {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }
        .btn-submit {
            background-color: rgb(53, 53, 53);
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn-submit:hover {
            background-color: black;
        }
        .btn-cancel {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="form-header">
            <h2 class="form-title"><?php echo $is_edit ? "Edit" : "Add New"; ?> Project</h2>
            <a href="dashboard.php" class="btn-cancel" style="background-color: #2196F3;">Back to Dashboard</a>
        </div>
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" class="form-control <?php echo (!empty($title_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $title; ?>">
                <span class="invalid-feedback"><?php echo $title_err; ?></span>
            </div>
            
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control <?php echo (!empty($description_err)) ? 'is-invalid' : ''; ?>"><?php echo $description; ?></textarea>
                <span class="invalid-feedback"><?php echo $description_err; ?></span>
            </div>
            
            <div class="form-group">
                <label>Image URL</label>
                <input type="text" name="image_url" class="form-control <?php echo (!empty($image_url_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $image_url; ?>">
                <span class="invalid-feedback"><?php echo $image_url_err; ?></span>
                <small>Enter the path to your image (e.g., ../assets/project-1.png)</small>
            </div>
            
            <div class="form-group">
                <label>GitHub URL</label>
                <input type="text" name="github_url" class="form-control <?php echo (!empty($github_url_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $github_url; ?>">
                <span class="invalid-feedback"><?php echo $github_url_err; ?></span>
            </div>
            
            <div class="form-group">
                <label>Demo URL</label>
                <input type="text" name="demo_url" class="form-control <?php echo (!empty($demo_url_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $demo_url; ?>">
                <span class="invalid-feedback"><?php echo $demo_url_err; ?></span>
            </div>
            
            <?php if($is_edit): ?>
                <input type="hidden" name="id" value="<?php echo $project_id; ?>"/>
            <?php endif; ?>
            
            <div class="form-group">
                <input type="submit" class="btn-submit" value="<?php echo $is_edit ? 'Update' : 'Create'; ?> Project">
                <a href="dashboard.php" class="btn-cancel">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>