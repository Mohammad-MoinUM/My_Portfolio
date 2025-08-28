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

// Fetch projects from database
$sql = "SELECT * FROM projects ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
$projects = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Fetch about entries
$sql_about = "SELECT * FROM about ORDER BY created_at DESC";
$res_about = mysqli_query($conn, $sql_about);
$abouts = $res_about ? mysqli_fetch_all($res_about, MYSQLI_ASSOC) : [];

// Fetch contacts
$sql_contacts = "SELECT * FROM contacts ORDER BY created_at DESC";
$res_contacts = mysqli_query($conn, $sql_contacts);
$contacts = $res_contacts ? mysqli_fetch_all($res_contacts, MYSQLI_ASSOC) : [];

// Fetch educations
$sql_educations = "SELECT * FROM educations ORDER BY created_at DESC";
$res_educations = mysqli_query($conn, $sql_educations);
$educations = $res_educations ? mysqli_fetch_all($res_educations, MYSQLI_ASSOC) : [];

// Fetch experiences
$sql_experiences = "SELECT * FROM experiences ORDER BY created_at DESC";
$res_experiences = mysqli_query($conn, $sql_experiences);
$experiences = $res_experiences ? mysqli_fetch_all($res_experiences, MYSQLI_ASSOC) : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Portfolio</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .dashboard-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .dashboard-title {
            font-size: 24px;
            margin: 0;
        }
        .btn-add {
            background-color: rgb(53, 53, 53);
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }
        .btn-add:hover {
            background-color: black;
        }
        .projects-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .projects-table th, .projects-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .projects-table th {
            background-color: #f2f2f2;
        }
        .projects-table tr:hover {
            background-color: #f5f5f5;
        }
        .action-buttons a {
            display: inline-block;
            margin-right: 10px;
            padding: 5px 10px;
            border-radius: 4px;
            text-decoration: none;
        }
        .btn-edit {
            background-color: #4CAF50;
            color: white;
        }
        .btn-delete {
            background-color: #f44336;
            color: white;
        }
        .btn-logout {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }
        .no-projects {
            text-align: center;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 4px;
        }
        .thumbnail {
            width: 100px;
            height: auto;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h2 class="dashboard-title">Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</h2>
            <div>
                <a href="../index.html" class="btn-add" style="background-color: #2196F3;">View Portfolio</a>
                <a href="logout.php" class="btn-logout">Logout</a>
            </div>
        </div>
        
        <div style="margin: 10px 0 20px;">
            <a href="#tab-projects" class="btn-add">Projects</a>
            <a href="#tab-about" class="btn-add">About</a>
            <a href="#tab-contacts" class="btn-add">Contact</a>
            <a href="#tab-education" class="btn-add">Education</a>
            <a href="#tab-experience" class="btn-add">Experience</a>
        </div>
        
        <div id="tab-projects" class="projects-section">
            <div class="dashboard-header">
                <h3>Projects Management</h3>
                <a href="project_form.php" class="btn-add">Add New Project</a>
            </div>
            
            <?php if(empty($projects)): ?>
                <div class="no-projects">
                    <p>No projects found. Click "Add New Project" to create your first project.</p>
                </div>
            <?php else: ?>
                <table class="projects-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>GitHub URL</th>
                            <th>Demo URL</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($projects as $project): ?>
                            <tr>
                                <td><?php echo $project['id']; ?></td>
                                <td>
                                    <?php if(!empty($project['image_url'])): ?>
                                        <img src="<?php echo $project['image_url']; ?>" alt="<?php echo $project['title']; ?>" class="thumbnail">
                                    <?php else: ?>
                                        No Image
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($project['title']); ?></td>
                                <td><?php echo htmlspecialchars(substr($project['description'], 0, 50)) . (strlen($project['description']) > 50 ? '...' : ''); ?></td>
                                <td><?php echo htmlspecialchars($project['github_url']); ?></td>
                                <td><?php echo htmlspecialchars($project['demo_url']); ?></td>
                                <td><?php echo date('M d, Y', strtotime($project['created_at'])); ?></td>
                                <td class="action-buttons">
                                    <a href="project_form.php?id=<?php echo $project['id']; ?>" class="btn-edit">Edit</a>
                                    <a href="delete_project.php?id=<?php echo $project['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this project?');">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <div id="tab-about" class="projects-section">
            <div class="dashboard-header">
                <h3>About Management</h3>
                <a href="about_form.php" class="btn-add">Add About Entry</a>
            </div>
            <?php if(empty($abouts)): ?>
                <div class="no-projects">
                    <p>No about entries. Click "Add About Entry" to create one.</p>
                </div>
            <?php else: ?>
                <table class="projects-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Summary</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($abouts as $about): ?>
                            <tr>
                                <td><?php echo $about['id']; ?></td>
                                <td>
                                    <?php if(!empty($about['image_url'])): ?>
                                        <img src="<?php echo $about['image_url']; ?>" alt="about" class="thumbnail">
                                    <?php else: ?>
                                        No Image
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($about['title']); ?></td>
                                <td><?php echo htmlspecialchars(substr($about['content'], 0, 80)) . (strlen($about['content']) > 80 ? '...' : ''); ?></td>
                                <td><?php echo date('M d, Y', strtotime($about['created_at'])); ?></td>
                                <td class="action-buttons">
                                    <a href="about_form.php?id=<?php echo $about['id']; ?>" class="btn-edit">Edit</a>
                                    <a href="delete_about.php?id=<?php echo $about['id']; ?>" class="btn-delete" onclick="return confirm('Delete this about entry?');">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <div id="tab-contacts" class="projects-section">
            <div class="dashboard-header">
                <h3>Contact Management</h3>
                <a href="contact_form.php" class="btn-add">Add Contact</a>
            </div>
            <?php if(empty($contacts)): ?>
                <div class="no-projects">
                    <p>No contacts found. Click "Add Contact" to create one.</p>
                </div>
            <?php else: ?>
                <table class="projects-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Type</th>
                            <th>Value</th>
                            <th>Icon</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($contacts as $contact): ?>
                            <tr>
                                <td><?php echo $contact['id']; ?></td>
                                <td><?php echo htmlspecialchars($contact['type']); ?></td>
                                <td><?php echo htmlspecialchars($contact['value']); ?></td>
                                <td><?php echo htmlspecialchars($contact['icon']); ?></td>
                                <td><?php echo date('M d, Y', strtotime($contact['created_at'])); ?></td>
                                <td class="action-buttons">
                                    <a href="contact_form.php?id=<?php echo $contact['id']; ?>" class="btn-edit">Edit</a>
                                    <a href="delete_contact.php?id=<?php echo $contact['id']; ?>" class="btn-delete" onclick="return confirm('Delete this contact?');">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <div id="tab-education" class="projects-section">
            <div class="dashboard-header">
                <h3>Education Management</h3>
                <a href="education_form.php" class="btn-add">Add Education</a>
            </div>
            <?php if(empty($educations)): ?>
                <div class="no-projects">
                    <p>No education records. Click "Add Education" to create one.</p>
                </div>
            <?php else: ?>
                <table class="projects-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Degree</th>
                            <th>Institution</th>
                            <th>Duration</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($educations as $edu): ?>
                            <tr>
                                <td><?php echo $edu['id']; ?></td>
                                <td><?php echo htmlspecialchars($edu['degree']); ?></td>
                                <td><?php echo htmlspecialchars($edu['institution']); ?></td>
                                <td><?php echo htmlspecialchars($edu['duration']); ?></td>
                                <td class="action-buttons">
                                    <a href="education_form.php?id=<?php echo $edu['id']; ?>" class="btn-edit">Edit</a>
                                    <a href="delete_education.php?id=<?php echo $edu['id']; ?>" class="btn-delete" onclick="return confirm('Delete this education record?');">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <div id="tab-experience" class="projects-section">
            <div class="dashboard-header">
                <h3>Experience Management</h3>
                <a href="experience_form.php" class="btn-add">Add Experience</a>
            </div>
            <?php if(empty($experiences)): ?>
                <div class="no-projects">
                    <p>No experience records. Click "Add Experience" to create one.</p>
                </div>
            <?php else: ?>
                <table class="projects-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Company</th>
                            <th>Duration</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($experiences as $exp): ?>
                            <tr>
                                <td><?php echo $exp['id']; ?></td>
                                <td><?php echo htmlspecialchars($exp['title']); ?></td>
                                <td><?php echo htmlspecialchars($exp['company']); ?></td>
                                <td><?php echo htmlspecialchars($exp['duration']); ?></td>
                                <td class="action-buttons">
                                    <a href="experience_form.php?id=<?php echo $exp['id']; ?>" class="btn-edit">Edit</a>
                                    <a href="delete_experience.php?id=<?php echo $exp['id']; ?>" class="btn-delete" onclick="return confirm('Delete this experience record?');">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>