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

// Get current page from URL parameter
$current_page = isset($_GET['page']) ? $_GET['page'] : 'overview';

// Fetch summary data for overview
$summary_data = [];
if($current_page == 'overview') {
    // Count projects
    $sql = "SELECT COUNT(*) as count FROM projects";
    $result = mysqli_query($conn, $sql);
    $summary_data['projects'] = mysqli_fetch_assoc($result)['count'];
    
    // Count about entries
    $sql = "SELECT COUNT(*) as count FROM about";
    $result = mysqli_query($conn, $sql);
    $summary_data['about'] = mysqli_fetch_assoc($result)['count'];
    
    // Count contacts
    $sql = "SELECT COUNT(*) as count FROM contacts";
    $result = mysqli_query($conn, $sql);
    $summary_data['contacts'] = mysqli_fetch_assoc($result)['count'];
    
    // Count educations
    $sql = "SELECT COUNT(*) as count FROM educations";
    $result = mysqli_query($conn, $sql);
    $summary_data['educations'] = mysqli_fetch_assoc($result)['count'];
    
    // Count experiences
    $sql = "SELECT COUNT(*) as count FROM experiences";
    $result = mysqli_query($conn, $sql);
    $summary_data['experiences'] = mysqli_fetch_assoc($result)['count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Portfolio</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --admin-primary: #667eea;
            --admin-secondary: #764ba2;
            --admin-success: #4facfe;
            --admin-warning: #43e97b;
            --admin-danger: #fa709a;
            --admin-dark: #2c3e50;
            --admin-light: #ecf0f1;
            --admin-white: #ffffff;
            --admin-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --admin-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --admin-border-radius: 15px;
        }

        body {
            background: #f8f9fa;
            font-family: 'Inter', sans-serif;
        }

        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background: var(--admin-white);
            box-shadow: var(--admin-shadow);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 2rem 1.5rem;
            background: var(--admin-gradient);
            color: var(--admin-white);
            text-align: center;
        }

        .sidebar-header h2 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 700;
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-item {
            list-style: none;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            color: var(--admin-dark);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .nav-link:hover, .nav-link.active {
            background: rgba(102, 126, 234, 0.1);
            color: var(--admin-primary);
            border-left-color: var(--admin-primary);
        }

        .nav-link i {
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
        }

        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 2rem;
        }

        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--admin-light);
        }

        .content-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--admin-dark);
            margin: 0;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: var(--admin-gradient);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--admin-white);
            font-weight: 700;
        }

        .btn-logout {
            background: var(--admin-danger);
            color: var(--admin-white);
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .btn-logout:hover {
            background: #e74c3c;
            transform: translateY(-2px);
        }

        .overview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--admin-white);
            padding: 1.5rem;
            border-radius: var(--admin-border-radius);
            box-shadow: var(--admin-shadow);
            text-align: center;
            transition: all 0.3s ease;
            border-left: 4px solid var(--admin-primary);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .stat-card.projects { border-left-color: var(--admin-success); }
        .stat-card.about { border-left-color: var(--admin-warning); }
        .stat-card.contacts { border-left-color: var(--admin-primary); }
        .stat-card.educations { border-left-color: var(--admin-secondary); }
        .stat-card.experiences { border-left-color: var(--admin-danger); }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--admin-primary);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: var(--admin-dark);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .quick-actions {
            background: var(--admin-white);
            padding: 2rem;
            border-radius: var(--admin-border-radius);
            box-shadow: var(--admin-shadow);
        }

        .quick-actions h3 {
            margin: 0 0 1.5rem 0;
            color: var(--admin-dark);
            font-size: 1.5rem;
        }

        .action-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .action-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1.5rem;
            background: var(--admin-light);
            border: none;
            border-radius: 10px;
            cursor: pointer;
            text-decoration: none;
            color: var(--admin-dark);
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            background: var(--admin-primary);
            color: var(--admin-white);
            transform: translateY(-3px);
        }

        .action-btn i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .action-btn span {
            font-weight: 600;
            text-align: center;
        }

        .welcome-section {
            background: var(--admin-gradient);
            color: var(--admin-white);
            padding: 2rem;
            border-radius: var(--admin-border-radius);
            margin-bottom: 2rem;
            text-align: center;
        }

        .welcome-section h2 {
            margin: 0 0 1rem 0;
            font-size: 2.5rem;
            font-weight: 700;
        }

        .welcome-section p {
            margin: 0;
            font-size: 1.1rem;
            opacity: 0.9;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                z-index: 1000;
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding: 1rem;
            }

            .mobile-toggle {
                display: block;
                position: fixed;
                top: 1rem;
                left: 1rem;
                z-index: 1001;
                background: var(--admin-primary);
                color: var(--admin-white);
                border: none;
                padding: 0.5rem;
                border-radius: 5px;
                cursor: pointer;
            }

            .overview-grid {
                grid-template-columns: 1fr;
            }

            .action-grid {
                grid-template-columns: 1fr;
            }
        }

        .mobile-toggle {
            display: none;
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .overlay.open {
            display: block;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Mobile Toggle Button -->
        <button class="mobile-toggle" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Overlay for mobile -->
        <div class="overlay" id="overlay" onclick="closeSidebar()"></div>

        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h2><i class="fas fa-cog"></i> Admin Panel</h2>
            </div>
            <ul class="sidebar-nav">
                <li class="nav-item">
                    <a href="?page=overview" class="nav-link <?php echo $current_page == 'overview' ? 'active' : ''; ?>">
                        <i class="fas fa-tachometer-alt"></i>
                        Overview
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?page=projects" class="nav-link <?php echo $current_page == 'projects' ? 'active' : ''; ?>">
                        <i class="fas fa-project-diagram"></i>
                        Projects
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?page=about" class="nav-link <?php echo $current_page == 'about' ? 'active' : ''; ?>">
                        <i class="fas fa-user"></i>
                        About
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?page=contacts" class="nav-link <?php echo $current_page == 'contacts' ? 'active' : ''; ?>">
                        <i class="fas fa-address-book"></i>
                        Contacts
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?page=education" class="nav-link <?php echo $current_page == 'education' ? 'active' : ''; ?>">
                        <i class="fas fa-graduation-cap"></i>
                        Education
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?page=experience" class="nav-link <?php echo $current_page == 'experience' ? 'active' : ''; ?>">
                        <i class="fas fa-briefcase"></i>
                        Experience
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?page=profile" class="nav-link <?php echo $current_page == 'profile' ? 'active' : ''; ?>">
                        <i class="fas fa-user-cog"></i>
                        Profile
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="content-header">
                <h1 class="content-title">
                    <?php
                    switch($current_page) {
                        case 'overview': echo 'Dashboard Overview'; break;
                        case 'projects': echo 'Projects Management'; break;
                        case 'about': echo 'About Management'; break;
                        case 'contacts': echo 'Contact Management'; break;
                        case 'education': echo 'Education Management'; break;
                        case 'experience': echo 'Experience Management'; break;
                        case 'profile': echo 'Profile Settings'; break;
                        default: echo 'Dashboard Overview'; break;
                    }
                    ?>
                </h1>
                <div class="user-info">
                    <div class="user-avatar">
                        <?php echo strtoupper(substr($_SESSION["username"], 0, 1)); ?>
                    </div>
                    <span><?php echo htmlspecialchars($_SESSION["username"]); ?></span>
                    <a href="logout.php" class="btn-logout">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>

            <?php if($current_page == 'overview'): ?>
                <!-- Overview Content -->
                <div class="welcome-section">
                    <h2>Welcome back, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</h2>
                    <p>Manage your portfolio content and settings from this dashboard.</p>
                </div>

                <div class="overview-grid">
                    <div class="stat-card projects">
                        <div class="stat-number"><?php echo $summary_data['projects']; ?></div>
                        <div class="stat-label">Projects</div>
                    </div>
                    <div class="stat-card about">
                        <div class="stat-number"><?php echo $summary_data['about']; ?></div>
                        <div class="stat-label">About Entries</div>
                    </div>
                    <div class="stat-card contacts">
                        <div class="stat-number"><?php echo $summary_data['contacts']; ?></div>
                        <div class="stat-label">Contacts</div>
                    </div>
                    <div class="stat-card educations">
                        <div class="stat-number"><?php echo $summary_data['educations']; ?></div>
                        <div class="stat-label">Education</div>
                    </div>
                    <div class="stat-card experiences">
                        <div class="stat-number"><?php echo $summary_data['experiences']; ?></div>
                        <div class="stat-label">Experience</div>
                    </div>
                </div>

                <div class="quick-actions">
                    <h3>Quick Actions</h3>
                    <div class="action-grid">
                        <a href="?page=projects" class="action-btn">
                            <i class="fas fa-plus"></i>
                            <span>Add Project</span>
                        </a>
                        <a href="?page=about" class="action-btn">
                            <i class="fas fa-edit"></i>
                            <span>Edit About</span>
                        </a>
                        <a href="?page=contacts" class="action-btn">
                            <i class="fas fa-phone"></i>
                            <span>Add Contact</span>
                        </a>
                        <a href="?page=education" class="action-btn">
                            <i class="fas fa-graduation-cap"></i>
                            <span>Add Education</span>
                        </a>
                        <a href="?page=experience" class="action-btn">
                            <i class="fas fa-briefcase"></i>
                            <span>Add Experience</span>
                        </a>
                        <a href="../index.php" class="action-btn">
                            <i class="fas fa-eye"></i>
                            <span>View Portfolio</span>
                        </a>
                    </div>
                </div>

            <?php elseif($current_page == 'projects'): ?>
                <!-- Projects Management -->
                <?php include 'pages/projects.php'; ?>

            <?php elseif($current_page == 'about'): ?>
                <!-- About Management -->
                <?php include 'pages/about.php'; ?>

            <?php elseif($current_page == 'contacts'): ?>
                <!-- Contact Management -->
                <?php include 'pages/contacts.php'; ?>

            <?php elseif($current_page == 'education'): ?>
                <!-- Education Management -->
                <?php include 'pages/education.php'; ?>

            <?php elseif($current_page == 'experience'): ?>
                <!-- Experience Management -->
                <?php include 'pages/experience.php'; ?>

            <?php elseif($current_page == 'profile'): ?>
                <!-- Profile Settings -->
                <?php include 'pages/profile.php'; ?>

            <?php endif; ?>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('open');
        }

        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            sidebar.classList.remove('open');
            overlay.classList.remove('open');
        }

        // Close sidebar when clicking on a nav link on mobile
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 768) {
                    closeSidebar();
                }
            });
        });

        // Close sidebar when resizing to desktop
        window.addEventListener('resize', () => {
            if (window.innerWidth > 768) {
                closeSidebar();
            }
        });
    </script>
</body>
</html>