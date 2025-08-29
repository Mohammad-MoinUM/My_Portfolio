<?php
// Test database connection
require_once "config/config.php";

if ($conn) {
    echo "<h2>Database Connection Test</h2>";
    echo "<p style='color: green;'>✓ MySQL connection successful!</p>";
    
    // Test if database exists
    $db_selected = mysqli_select_db($conn, DB_NAME);
    if ($db_selected) {
        echo "<p style='color: green;'>✓ Database '" . DB_NAME . "' exists!</p>";
        
        // Test if tables exist
        $tables = ['users', 'projects'];
        foreach ($tables as $table) {
            $query = "SHOW TABLES LIKE '$table'";
            $result = mysqli_query($conn, $query);
            
            if (mysqli_num_rows($result) > 0) {
                echo "<p style='color: green;'>✓ Table '$table' exists!</p>";
                
                // Count records in table
                $count_query = "SELECT COUNT(*) as count FROM $table";
                $count_result = mysqli_query($conn, $count_query);
                $count = mysqli_fetch_assoc($count_result)['count'];
                echo "<p style='color: blue;'>ℹ Table '$table' has $count records.</p>";
            } else {
                echo "<p style='color: red;'>✗ Table '$table' does not exist!</p>";
            }
        }
    } else {
        echo "<p style='color: red;'>✗ Database '" . DB_NAME . "' does not exist!</p>";
    }
} else {
    echo "<p style='color: red;'>✗ MySQL connection failed: " . mysqli_connect_error() . "</p>";
}

echo "<p><a href='setup.php'>Run Setup</a> | <a href='login.php'>Go to Login</a> | <a href='index.html'>Back to Portfolio</a></p>";
?>