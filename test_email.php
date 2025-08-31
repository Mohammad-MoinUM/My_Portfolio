<?php
// Simple email testing script
echo "<h2>Email Functionality Test</h2>";

// Test if mail function exists
if (function_exists('mail')) {
    echo "<p style='color: green;'>✓ PHP mail() function is available</p>";
    
    // Test basic mail functionality
    $to = "test@example.com";
    $subject = "Test Email";
    $message = "This is a test email to verify mail functionality.";
    $headers = "From: test@yourportfolio.com\r\n";
    
    $result = mail($to, $subject, $message, $headers);
    
    if ($result) {
        echo "<p style='color: green;'>✓ Basic mail() function test passed</p>";
    } else {
        echo "<p style='color: orange;'>⚠ Basic mail() function test failed (this is normal in local development)</p>";
    }
    
} else {
    echo "<p style='color: red;'>✗ PHP mail() function is not available</p>";
}

// Check mail configuration
echo "<h3>Mail Configuration:</h3>";
echo "<p><strong>SMTP:</strong> " . (ini_get('SMTP') ?: 'Not set') . "</p>";
echo "<p><strong>smtp_port:</strong> " . (ini_get('smtp_port') ?: 'Not set') . "</p>";
echo "<p><strong>sendmail_path:</strong> " . (ini_get('sendmail_path') ?: 'Not set') . "</p>";

// For XAMPP users
echo "<h3>XAMPP Mail Setup:</h3>";
echo "<p>To enable email in XAMPP:</p>";
echo "<ol>";
echo "<li>Open XAMPP Control Panel</li>";
echo "<li>Click on 'Config' button for Apache</li>";
echo "<li>Select 'php.ini'</li>";
echo "<li>Find and uncomment these lines:</li>";
echo "<ul>";
echo "<li>;SMTP = localhost</li>";
echo "<li>;smtp_port = 25</li>";
echo "<li>;sendmail_path = \"C:\\xampp\\sendmail\\sendmail.exe -t\"</li>";
echo "</ul>";
echo "<li>Configure sendmail.ini in C:\\xampp\\sendmail\\</li>";
echo "</ol>";

echo "<hr>";
echo "<p><strong>Note:</strong> In production, use a proper email service like:</p>";
echo "<ul>";
echo "<li>PHPMailer with SMTP</li>";
echo "<li>SendGrid</li>";
echo "<li>Mailgun</li>";
echo "<li>Amazon SES</li>";
echo "</ul>";

echo "<hr>";
echo "<p><a href='contact.php'>Test Contact Form</a> | <a href='index.php'>Back to Portfolio</a></p>";
?>
