<?php
// Test script to verify projects are being fetched from database
require_once "config/config.php";

echo "<h2>Projects Database Test</h2>";

// Test if database exists
$db_selected = mysqli_select_db($conn, DB_NAME);
if ($db_selected) {
    echo "<p style='color: green;'>✓ Database '" . DB_NAME . "' exists!</p>";
    
    // Test if projects table exists
    $query = "SHOW TABLES LIKE 'projects'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        echo "<p style='color: green;'>✓ Table 'projects' exists!</p>";
        
        // Count projects in table
        $count_query = "SELECT COUNT(*) as count FROM projects";
        $count_result = mysqli_query($conn, $count_query);
        $count = mysqli_fetch_assoc($count_result)['count'];
        echo "<p style='color: blue;'>ℹ Table 'projects' has $count records.</p>";
        
        // List all projects
        $projects_query = "SELECT * FROM projects ORDER BY created_at DESC";
        $projects_result = mysqli_query($conn, $projects_query);
        
        if (mysqli_num_rows($projects_result) > 0) {
            echo "<h3>Projects in Database:</h3>";
            echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
            echo "<tr><th>ID</th><th>Title</th><th>Description</th><th>Image URL</th><th>GitHub URL</th><th>Demo URL</th></tr>";
            
            while ($row = mysqli_fetch_assoc($projects_result)) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                echo "<td>" . htmlspecialchars($row['image_url']) . "</td>";
                echo "<td>" . htmlspecialchars($row['github_url']) . "</td>";
                echo "<td>" . htmlspecialchars($row['demo_url']) . "</td>";
                echo "</tr>";
            }
            
            echo "</table>";
        } else {
            echo "<p style='color: orange;'>⚠ No projects found in the database.</p>";
        }
    } else {
        echo "<p style='color: red;'>✗ Table 'projects' does not exist!</p>";
    }
} else {
    echo "<p style='color: red;'>✗ Database '" . DB_NAME . "' does not exist!</p>";
}

echo "<p><a href='setup.php'>Run Setup</a> | <a href='index.php'>View Portfolio</a> | <a href='login.php'>Admin Login</a></p>";
?>