<?php
// Include config file
require_once "config/config.php";

// Fetch projects from database
$sql = "SELECT * FROM projects ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
$projects = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Projects - Portfolio</title>
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="mediaqueries.css" />
</head>
<body>
    <nav id="desktop-nav">
        <div class="logo">Mohammad Moin Uddin Moin</div>
        <div>
            <ul class="nav-links">
                <li><a href="index.html">Home</a></li>
                <li><a href="index.html#about">About</a></li>
                <li><a href="index.html#experience">Experience</a></li>
                <li><a href="projects.php">My Projects</a></li>
                <li><a href="index.html#contact">Contact</a></li>
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
                <li><a href="index.html" onclick="toggleMenu()">Home</a></li>
                <li><a href="index.html#about" onclick="toggleMenu()">About</a></li>
                <li><a href="index.html#experience" onclick="toggleMenu()">Experience</a></li>
                <li><a href="projects.php" onclick="toggleMenu()">Projects</a></li>
                <li><a href="index.html#contact" onclick="toggleMenu()">Contact</a></li>
            </div>
        </div>
    </nav>
    
    <section id="projects">
        <p class="section__text__p1">Browse My Recent</p>
        <h1 class="title">Projects</h1>
        <div class="experience-details-container">
            <div class="about-containers">
                <?php if(empty($projects)): ?>
                    <p>No projects available at the moment.</p>
                <?php else: ?>
                    <?php foreach($projects as $project): ?>
                        <div class="details-container color-container">
                            <div class="article-container">
                                <img
                                    src="<?php echo !empty($project['image_url']) ? $project['image_url'] : './assets/project-1.png'; ?>"
                                    alt="<?php echo htmlspecialchars($project['title']); ?>"
                                    class="project-img"
                                />
                            </div>
                            <h2 class="experience-sub-title project-title"><?php echo htmlspecialchars($project['title']); ?></h2>
                            <div class="btn-container">
                                <?php if(!empty($project['github_url'])): ?>
                                    <button
                                        class="btn btn-color-2 project-btn"
                                        onclick="location.href='<?php echo htmlspecialchars($project['github_url']); ?>'"
                                    >
                                        Github
                                    </button>
                                <?php endif; ?>
                                <?php if(!empty($project['demo_url'])): ?>
                                    <button
                                        class="btn btn-color-2 project-btn"
                                        onclick="location.href='<?php echo htmlspecialchars($project['demo_url']); ?>'"
                                    >
                                        Live Demo
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
    
    <footer>
        <nav>
            <div class="nav-links-container">
                <ul class="nav-links">
                    <li><a href="index.html#about">About</a></li>
                    <li><a href="index.html#experience">Experience</a></li>
                    <li><a href="projects.php">Projects</a></li>
                    <li><a href="index.html#contact">Contact</a></li>
                </ul>
            </div>
        </nav>
        <p>Copyright &#169; 2023 Mohammad Moin Uddin Moin. All Rights Reserved.</p>
    </footer>
    <script src="script.js"></script>
</body>
</html>