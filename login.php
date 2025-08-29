<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect to dashboard
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: admin/dashboard.php");
    exit;
}

// Include config file
require_once "config/config.php";

// Generate CSRF token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Rate limiting
$ip = $_SERVER['REMOTE_ADDR'];
$rate_limit_key = "login_attempts_" . $ip;
$max_attempts = 5;
$lockout_time = 900; // 15 minutes

// Check if IP is locked out
if (isset($_SESSION[$rate_limit_key]) && $_SESSION[$rate_limit_key]['count'] >= $max_attempts) {
    $time_elapsed = time() - $_SESSION[$rate_limit_key]['time'];
    if ($time_elapsed < $lockout_time) {
        $remaining_time = $lockout_time - $time_elapsed;
        $error_message = "Too many login attempts. Please try again in " . ceil($remaining_time / 60) . " minutes.";
    } else {
        // Reset after lockout period
        unset($_SESSION[$rate_limit_key]);
    }
}

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $login_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST" && !isset($error_message)){
    
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $login_err = "Invalid request. Please try again.";
    } else {
        
        // Check if username is empty
        if(empty(trim($_POST["username"]))){
            $username_err = "Please enter username.";
        } else{
            $username = trim($_POST["username"]);
        }
        
        // Check if password is empty
        if(empty(trim($_POST["password"]))){
            $password_err = "Please enter your password.";
        } else{
            $password = trim($_POST["password"]);
        }
        
        // Validate credentials
        if(empty($username_err) && empty($password_err)){
            
            // Check rate limiting
            if (isset($_SESSION[$rate_limit_key]) && $_SESSION[$rate_limit_key]['count'] >= $max_attempts) {
                $login_err = "Too many login attempts. Please try again later.";
            } else {
                
                // Prepare a select statement
                $sql = "SELECT id, username, password FROM users WHERE username = ?";
                
                if($stmt = mysqli_prepare($conn, $sql)){
                    // Bind variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($stmt, "s", $param_username);
                    
                    // Set parameters
                    $param_username = $username;
                    
                    // Attempt to execute the prepared statement
                    if(mysqli_stmt_execute($stmt)){
                        // Store result
                        mysqli_stmt_store_result($stmt);
                        
                        // Check if username exists, if yes then verify password
                        if(mysqli_stmt_num_rows($stmt) == 1){                    
                            // Bind result variables
                            mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                            if(mysqli_stmt_fetch($stmt)){
                                if(password_verify($password, $hashed_password)){
                                    // Password is correct, so start a new session
                                    session_regenerate_id(true); // Prevent session fixation
                                    
                                    // Store data in session variables
                                    $_SESSION["loggedin"] = true;
                                    $_SESSION["id"] = $id;
                                    $_SESSION["username"] = $username;
                                    $_SESSION["login_time"] = time();
                                    $_SESSION["user_agent"] = $_SERVER['HTTP_USER_AGENT'];
                                    
                                    // Clear rate limiting
                                    unset($_SESSION[$rate_limit_key]);
                                    
                                    // Set secure cookies
                                    if (isset($_POST['remember_me'])) {
                                        $token = bin2hex(random_bytes(32));
                                        setcookie('remember_token', $token, time() + (86400 * 30), '/', '', true, true); // 30 days, secure, httponly
                                        
                                        // Store token in database (you'll need to add a remember_token column)
                                        $update_sql = "UPDATE users SET remember_token = ? WHERE id = ?";
                                        $update_stmt = mysqli_prepare($conn, $update_sql);
                                        mysqli_stmt_bind_param($update_stmt, "si", $token, $id);
                                        mysqli_stmt_execute($update_stmt);
                                    }
                                    
                                    // Redirect user to dashboard page
                                    header("location: admin/dashboard.php");
                                    exit;
                                } else{
                                    // Password is not valid
                                    $login_err = "Invalid username or password.";
                                    incrementLoginAttempts($ip, $rate_limit_key);
                                }
                            }
                        } else{
                            // Username doesn't exist
                            $login_err = "Invalid username or password.";
                            incrementLoginAttempts($ip, $rate_limit_key);
                        }
                    } else{
                        echo "Oops! Something went wrong. Please try again later.";
                    }

                    // Close statement
                    mysqli_stmt_close($stmt);
                }
            }
        }
    }
    
    // Close connection
    mysqli_close($conn);
}

// Function to increment login attempts
function incrementLoginAttempts($ip, $rate_limit_key) {
    if (!isset($_SESSION[$rate_limit_key])) {
        $_SESSION[$rate_limit_key] = ['count' => 1, 'time' => time()];
    } else {
        $_SESSION[$rate_limit_key]['count']++;
        $_SESSION[$rate_limit_key]['time'] = time();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Portfolio Admin</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
        }
        
        .login-container {
            max-width: 450px;
            width: 100%;
            margin: 2rem;
            padding: 3rem;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-header h2 {
            color: var(--primary-color);
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            font-weight: 800;
        }
        
        .login-header p {
            color: var(--dark-color);
            font-size: 1.1rem;
            opacity: 0.8;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--dark-color);
            font-size: 1rem;
        }
        
        .form-group input {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .form-group .invalid-feedback {
            color: #dc3545;
            font-size: 0.9rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }
        
        .remember-me input[type="checkbox"] {
            width: auto;
            margin: 0;
        }
        
        .remember-me label {
            margin: 0;
            font-size: 0.95rem;
            color: var(--dark-color);
        }
        
        .btn-login {
            background: var(--gradient-primary);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 50px;
            cursor: pointer;
            font-size: 1.1rem;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }
        
        .btn-login:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 500;
        }
        
        .alert.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .back-link {
            text-align: center;
            margin-top: 2rem;
        }
        
        .back-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .back-link a:hover {
            color: var(--accent-color);
            transform: translateX(-5px);
        }
        
        .security-info {
            background: rgba(102, 126, 234, 0.1);
            padding: 1rem;
            border-radius: 10px;
            margin-top: 1.5rem;
            text-align: center;
        }
        
        .security-info p {
            margin: 0;
            font-size: 0.9rem;
            color: var(--dark-color);
            opacity: 0.8;
        }
        
        @media (max-width: 768px) {
            .login-container {
                margin: 1rem;
                padding: 2rem;
            }
            
            .login-header h2 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h2><i class="fas fa-lock"></i> Admin Login</h2>
            <p>Access your portfolio management dashboard</p>
        </div>
        
        <?php 
        if(!empty($login_err)){
            echo '<div class="alert error"><i class="fas fa-exclamation-circle"></i>' . $login_err . '</div>';
        }
        
        if(isset($error_message)){
            echo '<div class="alert error"><i class="fas fa-exclamation-circle"></i>' . $error_message . '</div>';
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="<?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($username); ?>" required>
                <?php if(!empty($username_err)): ?>
                    <span class="invalid-feedback"><i class="fas fa-exclamation-triangle"></i> <?php echo $username_err; ?></span>
                <?php endif; ?>
            </div>    
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="<?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" required>
                <?php if(!empty($password_err)): ?>
                    <span class="invalid-feedback"><i class="fas fa-exclamation-triangle"></i> <?php echo $password_err; ?></span>
                <?php endif; ?>
            </div>
            
            <div class="remember-me">
                <input type="checkbox" id="remember_me" name="remember_me">
                <label for="remember_me">Remember me for 30 days</label>
            </div>
            
            <button type="submit" class="btn-login" <?php echo isset($error_message) ? 'disabled' : ''; ?>>
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
        </form>
        
        <div class="back-link">
            <a href="index.php">
                <i class="fas fa-arrow-left"></i> Back to Portfolio
            </a>
        </div>
        
        <div class="security-info">
            <p><i class="fas fa-shield-alt"></i> Secure login with rate limiting and CSRF protection</p>
        </div>
    </div>
</body>
</html>