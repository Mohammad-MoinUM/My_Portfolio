<?php
// Handle password change
$password_message = '';
$password_error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate current password
    $sql = "SELECT password FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $_SESSION["id"]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    
    if (password_verify($current_password, $user['password'])) {
        if ($new_password === $confirm_password) {
            if (strlen($new_password) >= 8) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_sql = "UPDATE users SET password = ? WHERE id = ?";
                $update_stmt = mysqli_prepare($conn, $update_sql);
                mysqli_stmt_bind_param($update_stmt, "si", $hashed_password, $_SESSION["id"]);
                
                if (mysqli_stmt_execute($update_stmt)) {
                    $password_message = "Password updated successfully!";
                } else {
                    $password_error = "Error updating password. Please try again.";
                }
            } else {
                $password_error = "New password must be at least 8 characters long.";
            }
        } else {
            $password_error = "New passwords do not match.";
        }
    } else {
        $password_error = "Current password is incorrect.";
    }
}
?>

<div class="content-section">
    <div class="section-header">
        <h2>Profile Settings</h2>
    </div>
    
    <div class="profile-content">
        <div class="profile-section">
            <h3><i class="fas fa-user"></i> Account Information</h3>
            <div class="info-grid">
                <div class="info-item">
                    <label>Username:</label>
                    <span><?php echo htmlspecialchars($_SESSION["username"]); ?></span>
                </div>
                <div class="info-item">
                    <label>User ID:</label>
                    <span><?php echo $_SESSION["id"]; ?></span>
                </div>
                <div class="info-item">
                    <label>Account Type:</label>
                    <span class="badge admin">Administrator</span>
                </div>
            </div>
        </div>
        
        <div class="profile-section">
            <h3><i class="fas fa-lock"></i> Change Password</h3>
            
            <?php if (!empty($password_message)): ?>
                <div class="alert success">
                    <i class="fas fa-check-circle"></i> <?php echo $password_message; ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($password_error)): ?>
                <div class="alert error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $password_error; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" class="password-form">
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password" required>
                </div>
                
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" required minlength="8">
                    <small>Password must be at least 8 characters long</small>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required minlength="8">
                </div>
                
                <button type="submit" name="change_password" class="btn-primary">
                    <i class="fas fa-key"></i> Change Password
                </button>
            </form>
        </div>
        
        <div class="profile-section">
            <h3><i class="fas fa-shield-alt"></i> Security Tips</h3>
            <div class="security-tips">
                <div class="tip">
                    <i class="fas fa-check"></i>
                    <span>Use a strong password with at least 8 characters</span>
                </div>
                <div class="tip">
                    <i class="fas fa-check"></i>
                    <span>Include uppercase, lowercase, numbers, and symbols</span>
                </div>
                <div class="tip">
                    <i class="fas fa-check"></i>
                    <span>Never share your password with anyone</span>
                </div>
                <div class="tip">
                    <i class="fas fa-check"></i>
                    <span>Log out when using shared computers</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.profile-content {
    padding: 2rem;
}

.profile-section {
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: #f8f9fa;
    border-radius: 10px;
    border-left: 4px solid var(--admin-primary);
}

.profile-section h3 {
    margin: 0 0 1rem 0;
    color: var(--admin-dark);
    font-size: 1.3rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.info-item label {
    font-weight: 600;
    color: var(--admin-dark);
    font-size: 0.9rem;
}

.info-item span {
    color: #666;
    font-size: 1rem;
}

.badge {
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}

.badge.admin {
    background: var(--admin-primary);
    color: var(--admin-white);
}

.password-form {
    max-width: 400px;
}

.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: var(--admin-dark);
}

.form-group input {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

.form-group input:focus {
    outline: none;
    border-color: var(--admin-primary);
    box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.1);
}

.form-group small {
    display: block;
    margin-top: 0.25rem;
    color: #666;
    font-size: 0.8rem;
}

.alert {
    padding: 1rem;
    border-radius: 5px;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.alert.success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert.error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.security-tips {
    display: grid;
    gap: 0.75rem;
}

.tip {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    background: var(--admin-white);
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.tip i {
    color: var(--admin-success);
    font-size: 1.1rem;
}

.tip span {
    color: var(--admin-dark);
    font-size: 0.95rem;
}

@media (max-width: 768px) {
    .profile-content {
        padding: 1rem;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .password-form {
        max-width: 100%;
    }
}
</style>
