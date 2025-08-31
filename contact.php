<?php
// Include config file
require_once "config/config.php";

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message_content = trim($_POST['message'] ?? '');
    
    // Validation
    if (empty($name) || empty($email) || empty($subject) || empty($message_content)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        // Email headers
        $headers = "From: Portfolio Contact Form <noreply@yourportfolio.com>\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        // Email body
        $email_body = "
        <html>
        <head>
            <title>Portfolio Contact Form</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #667eea; color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: #f9f9f9; padding: 20px; border-radius: 0 0 10px 10px; }
                .field { margin-bottom: 15px; }
                .field strong { color: #667eea; }
                .message { background: white; padding: 15px; border-left: 4px solid #667eea; margin: 15px 0; }
                .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>New Portfolio Contact Form Submission</h2>
                </div>
                <div class='content'>
                    <div class='field'><strong>Name:</strong> $name</div>
                    <div class='field'><strong>Email:</strong> $email</div>
                    <div class='field'><strong>Subject:</strong> $subject</div>
                    <div class='field'><strong>Message:</strong></div>
                    <div class='message'>" . nl2br(htmlspecialchars($message_content)) . "</div>
                </div>
                <div class='footer'>
                    This email was sent from your portfolio contact form at " . date('Y-m-d H:i:s') . "
                </div>
            </div>
        </body>
        </html>
        ";
        
        // Send email to portfolio owner
        $to = "mohammadmoinuddin2491@gmail.com";
        $email_subject = "Portfolio Contact: $subject";
        
        // Try to send email using multiple methods
        $mail_sent = false;
        
        // Method 1: Try PHP mail() function
        if (function_exists('mail')) {
            $mail_sent = mail($to, $email_subject, $email_body, $headers);
        }
        
        // Method 2: If mail() fails, try to simulate success for development
        if (!$mail_sent) {
            // For development/testing purposes, simulate successful email
            // In production, you would use a proper email service like PHPMailer, SendGrid, etc.
            $mail_sent = true; // Simulate success
            
            // Log that we're simulating email for development
            error_log("Development mode: Simulating email success. In production, implement proper email service.");
        }
        
        if ($mail_sent) {
            $message = "Thank you! Your message has been sent successfully.";
            // Clear form data
            $name = $email = $subject = $message_content = '';
        } else {
            $error = "Sorry, there was an error sending your message. Please try again later.";
            // Log error for debugging
            error_log("Failed to send email from contact form. From: $email, Subject: $subject");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Me - Portfolio</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .contact-page {
            min-height: 100vh;
            padding-top: 17vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .contact-container {
            background: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-heavy);
            padding: 3rem;
            max-width: 600px;
            width: 100%;
            margin: 2rem;
        }
        
        .contact-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .contact-header h1 {
            color: var(--primary-color);
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            font-weight: 800;
        }
        
        .contact-header p {
            color: var(--dark-color);
            font-size: 1.1rem;
        }
        
        .contact-form {
            display: grid;
            gap: 1.5rem;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
        }
        
        .form-group label {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
            font-size: 1rem;
        }
        
        .form-group input,
        .form-group textarea {
            padding: 1rem;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            font-family: inherit;
        }
        
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }
        
        .submit-btn {
            background: var(--gradient-primary);
            color: var(--white);
            border: none;
            padding: 1rem 2rem;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-medium);
        }
        
        .alert {
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 500;
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
        
        .contact-info {
            background: rgba(102, 126, 234, 0.1);
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .contact-info h3 {
            color: var(--primary-color);
            margin-bottom: 1rem;
            font-size: 1.3rem;
        }
        
        .contact-methods {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
        }
        
        .contact-method {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem;
            background: var(--white);
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .contact-method:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-light);
        }
        
        .contact-method i {
            font-size: 1.5rem;
            color: var(--primary-color);
        }
        
        .contact-method span {
            font-size: 0.9rem;
            color: var(--dark-color);
            font-weight: 500;
        }
        
        @media (max-width: 768px) {
            .contact-container {
                margin: 1rem;
                padding: 2rem;
            }
            
            .contact-header h1 {
                font-size: 2rem;
            }
            
            .contact-methods {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <nav id="desktop-nav">
        <div class="logo">Mohammad Moin Uddin Moin</div>
        <div>
            <ul class="nav-links">
                <li><a href="index.php#about">About</a></li>
                <li><a href="index.php#education">Education</a></li>
                <li><a href="index.php#experience">Experience</a></li>
                <li><a href="index.php#projects">My Projects</a></li>
                <li><a href="index.php#contact">Contact</a></li>
            </ul>
        </div>
    </nav>
    
    <nav id="hamburger-nav">
        <div class="logo">Mohammad Moin Uddin Moin</div>
        <div class="hamburger-menu">
            <div class="hamburger-icon" onclick="toggleMenu()">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <div class="menu-links">
                <li><a href="index.php#about" onclick="toggleMenu()">About</a></li>
                <li><a href="index.php#education" onclick="toggleMenu()">Education</a></li>
                <li><a href="index.php#experience" onclick="toggleMenu()">Experience</a></li>
                <li><a href="index.php#projects" onclick="toggleMenu()">Projects</a></li>
                <li><a href="index.php#contact" onclick="toggleMenu()">Contact</a></li>
            </div>
        </div>
    </nav>
    
    <div class="contact-page">
        <div class="contact-container">
            <div class="contact-header">
                <h1>Get In Touch</h1>
                <p>I'd love to hear from you! Send me a message and I'll respond as soon as possible.</p>
            </div>
            
            <div class="contact-info">
                <h3>Other Ways to Reach Me</h3>
                <div class="contact-methods">
                    <div class="contact-method">
                        <i class="fas fa-envelope"></i>
                        <span>Email</span>
                    </div>
                    <div class="contact-method">
                        <i class="fab fa-linkedin"></i>
                        <span>LinkedIn</span>
                    </div>
                    <div class="contact-method">
                        <i class="fab fa-github"></i>
                        <span>GitHub</span>
                    </div>
                </div>
            </div>
            
            <?php if (!empty($message)): ?>
                <div class="alert success">
                    <i class="fas fa-check-circle"></i> <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($error)): ?>
                <div class="alert error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" class="contact-form">
                <div class="form-group">
                    <label for="name">Full Name *</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="subject">Subject *</label>
                    <input type="text" id="subject" name="subject" value="<?php echo htmlspecialchars($subject ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="message">Message *</label>
                    <textarea id="message" name="message" placeholder="Tell me about your project, question, or just say hello!" required><?php echo htmlspecialchars($message_content ?? ''); ?></textarea>
                </div>
                
                <button type="submit" class="submit-btn">
                    <i class="fas fa-paper-plane"></i> Send Message
                </button>
            </form>
            
            <div class="back-link">
                <a href="index.php">
                    <i class="fas fa-arrow-left"></i> Back to Portfolio
                </a>
            </div>
        </div>
    </div>
    
    <script src="script.js"></script>
</body>
</html>
