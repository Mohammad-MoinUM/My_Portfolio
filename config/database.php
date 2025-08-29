<?php
require_once 'config.php';

// Create users table
$sql_users = "CREATE TABLE IF NOT EXISTS users (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
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

// Insert default admin user if not exists
$check_admin = "SELECT * FROM users WHERE username = 'admin'";
$result = mysqli_query($conn, $check_admin);

if (mysqli_num_rows($result) == 0) {
    // Create default admin user (password: admin123)
    $hashed_password = password_hash('admin123', PASSWORD_DEFAULT);
    $sql_insert_admin = "INSERT INTO users (username, password) VALUES ('admin', '$hashed_password')";
    
    if (!mysqli_query($conn, $sql_insert_admin)) {
        echo "WARNING: Could not create default admin user. " . mysqli_error($conn);
    } else {
        echo "Default admin user created successfully.";
    }
}

echo "Database setup completed successfully.";
?>