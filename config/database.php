<?php
require_once 'config.php';

// Create users table with enhanced security
$sql_users = "CREATE TABLE IF NOT EXISTS users (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE,
    remember_token VARCHAR(64) NULL,
    last_login DATETIME NULL,
    login_attempts INT DEFAULT 0,
    locked_until DATETIME NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if (!mysqli_query($conn, $sql_users)) {
    die("ERROR: Could not create users table. " . mysqli_error($conn));
}

// Create projects table
$sql_projects = "CREATE TABLE IF NOT EXISTS projects (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    image_url VARCHAR(255),
    github_url VARCHAR(255),
    demo_url VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if (!mysqli_query($conn, $sql_projects)) {
    die("ERROR: Could not create projects table. " . mysqli_error($conn));
}

// Create about table (single or multiple entries allowed)
$sql_about = "CREATE TABLE IF NOT EXISTS about (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100) DEFAULT NULL,
    content TEXT,
    image_url VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if (!mysqli_query($conn, $sql_about)) {
    die("ERROR: Could not create about table. " . mysqli_error($conn));
}

// Create contacts table
$sql_contacts = "CREATE TABLE IF NOT EXISTS contacts (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    type VARCHAR(50) NOT NULL,
    value VARCHAR(255) NOT NULL,
    icon VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if (!mysqli_query($conn, $sql_contacts)) {
    die("ERROR: Could not create contacts table. " . mysqli_error($conn));
}

// Create educations table
$sql_educations = "CREATE TABLE IF NOT EXISTS educations (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    degree VARCHAR(100) NOT NULL,
    institution VARCHAR(100) NOT NULL,
    duration VARCHAR(50) DEFAULT NULL,
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if (!mysqli_query($conn, $sql_educations)) {
    die("ERROR: Could not create educations table. " . mysqli_error($conn));
}

// Create experiences table
$sql_experiences = "CREATE TABLE IF NOT EXISTS experiences (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    company VARCHAR(100) NOT NULL,
    duration VARCHAR(50) DEFAULT NULL,
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if (!mysqli_query($conn, $sql_experiences)) {
    die("ERROR: Could not create experiences table. " . mysqli_error($conn));
}

// Create sessions table for better session management
$sql_sessions = "CREATE TABLE IF NOT EXISTS user_sessions (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    session_id VARCHAR(255) NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_activity DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

if (!mysqli_query($conn, $sql_sessions)) {
    die("ERROR: Could not create sessions table. " . mysqli_error($conn));
}

// Create login attempts table for security monitoring
$sql_login_attempts = "CREATE TABLE IF NOT EXISTS login_attempts (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    ip_address VARCHAR(45) NOT NULL,
    username VARCHAR(50),
    success BOOLEAN DEFAULT FALSE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)";

if (!mysqli_query($conn, $sql_login_attempts)) {
    die("ERROR: Could not create login attempts table. " . mysqli_error($conn));
}

// Insert default admin user if not exists
$check_admin = "SELECT * FROM users WHERE username = 'admin'";
$result = mysqli_query($conn, $check_admin);

if (mysqli_num_rows($result) == 0) {
    // Create default admin user (password: admin123)
    $hashed_password = password_hash('admin123', PASSWORD_DEFAULT);
    $sql_insert_admin = "INSERT INTO users (username, password, email) VALUES ('admin', '$hashed_password', 'admin@portfolio.com')";
    
    if (!mysqli_query($conn, $sql_insert_admin)) {
        echo "WARNING: Could not create default admin user. " . mysqli_error($conn);
    } else {
        echo "Default admin user created successfully.<br>";
        echo "Username: admin<br>";
        echo "Password: admin123<br>";
        echo "<strong>IMPORTANT: Change this password immediately after first login!</strong><br>";
    }
}

// Add indexes for better performance
$indexes = [
    "CREATE INDEX IF NOT EXISTS idx_users_username ON users(username)",
    "CREATE INDEX IF NOT EXISTS idx_users_email ON users(email)",
    "CREATE INDEX IF NOT EXISTS idx_sessions_user_id ON user_sessions(user_id)",
    "CREATE INDEX IF NOT EXISTS idx_sessions_session_id ON user_sessions(session_id)",
    "CREATE INDEX IF NOT EXISTS idx_login_attempts_ip ON login_attempts(ip_address)",
    "CREATE INDEX IF NOT EXISTS idx_login_attempts_created ON login_attempts(created_at)"
];

foreach ($indexes as $index_sql) {
    if (!mysqli_query($conn, $index_sql)) {
        echo "WARNING: Could not create index. " . mysqli_error($conn) . "<br>";
    }
}

echo "<br>Database setup completed successfully with enhanced security features!";
echo "<br><br><strong>Security Features Added:</strong>";
echo "<br>• Rate limiting for login attempts";
echo "<br>• Session management and tracking";
echo "<br>• Remember me functionality";
echo "<br>• IP address logging";
echo "<br>• Enhanced user authentication";
?>