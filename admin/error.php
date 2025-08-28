<?php
// Initialize the session
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - Portfolio Admin</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .error-container {
            max-width: 600px;
            margin: 100px auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .error-icon {
            font-size: 48px;
            color: #f44336;
            margin-bottom: 20px;
        }
        .error-title {
            font-size: 24px;
            margin-bottom: 15px;
        }
        .error-message {
            margin-bottom: 20px;
            color: #555;
        }
        .btn-back {
            background-color: rgb(53, 53, 53);
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }
        .btn-back:hover {
            background-color: black;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">⚠️</div>
        <h2 class="error-title">Oops! An Error Occurred</h2>
        <p class="error-message">Something went wrong. Please try again later or contact the administrator.</p>
        <?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
            <a href="dashboard.php" class="btn-back">Back to Dashboard</a>
        <?php else: ?>
            <a href="../login.php" class="btn-back">Back to Login</a>
        <?php endif; ?>
    </div>
</body>
</html>