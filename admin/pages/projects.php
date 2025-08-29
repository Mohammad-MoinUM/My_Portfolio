<?php
// Fetch projects from database
$sql = "SELECT * FROM projects ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
$projects = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<div class="content-section">
    <div class="section-header">
        <h2>Projects Management</h2>
        <a href="project_form.php" class="btn-primary">
            <i class="fas fa-plus"></i> Add New Project
        </a>
    </div>
    
    <?php if(empty($projects)): ?>
        <div class="empty-state">
            <i class="fas fa-project-diagram"></i>
            <h3>No projects found</h3>
            <p>Click "Add New Project" to create your first project.</p>
        </div>
    <?php else: ?>
        <div class="data-table">
            <table>
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
                                    <span class="no-image">No Image</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($project['title']); ?></td>
                            <td><?php echo htmlspecialchars(substr($project['description'], 0, 50)) . (strlen($project['description']) > 50 ? '...' : ''); ?></td>
                            <td>
                                <?php if(!empty($project['github_url'])): ?>
                                    <a href="<?php echo htmlspecialchars($project['github_url']); ?>" target="_blank" class="link">
                                        <i class="fab fa-github"></i> View
                                    </a>
                                <?php else: ?>
                                    <span class="no-link">No Link</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if(!empty($project['demo_url'])): ?>
                                    <a href="<?php echo htmlspecialchars($project['demo_url']); ?>" target="_blank" class="link">
                                        <i class="fas fa-external-link-alt"></i> Demo
                                    </a>
                                <?php else: ?>
                                    <span class="no-link">No Link</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($project['created_at'])); ?></td>
                            <td class="actions">
                                <a href="project_form.php?id=<?php echo $project['id']; ?>" class="btn-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="delete_project.php?id=<?php echo $project['id']; ?>" class="btn-delete" title="Delete" 
                                   onclick="return confirm('Are you sure you want to delete this project?');">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<style>
.content-section {
    background: var(--admin-white);
    border-radius: var(--admin-border-radius);
    box-shadow: var(--admin-shadow);
    overflow: hidden;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem 2rem;
    border-bottom: 1px solid var(--admin-light);
}

.section-header h2 {
    margin: 0;
    color: var(--admin-dark);
    font-size: 1.5rem;
}

.btn-primary {
    background: var(--admin-gradient);
    color: var(--admin-white);
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: var(--admin-dark);
}

.empty-state i {
    font-size: 4rem;
    color: var(--admin-light);
    margin-bottom: 1rem;
}

.empty-state h3 {
    margin: 0 0 0.5rem 0;
    font-size: 1.5rem;
}

.empty-state p {
    margin: 0;
    color: #666;
}

.data-table {
    overflow-x: auto;
}

.data-table table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th,
.data-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid var(--admin-light);
}

.data-table th {
    background: #f8f9fa;
    font-weight: 600;
    color: var(--admin-dark);
}

.data-table tr:hover {
    background: rgba(102, 126, 234, 0.05);
}

.thumbnail {
    width: 60px;
    height: 40px;
    object-fit: cover;
    border-radius: 5px;
    border: 1px solid var(--admin-light);
}

.no-image, .no-link {
    color: #999;
    font-style: italic;
    font-size: 0.9rem;
}

.link {
    color: var(--admin-primary);
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.25rem;
    font-weight: 500;
}

.link:hover {
    color: var(--admin-secondary);
}

.actions {
    display: flex;
    gap: 0.5rem;
}

.btn-edit, .btn-delete {
    padding: 0.5rem;
    border-radius: 5px;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 35px;
    height: 35px;
    transition: all 0.3s ease;
}

.btn-edit {
    background: var(--admin-success);
    color: var(--admin-white);
}

.btn-edit:hover {
    background: #45a049;
    transform: translateY(-2px);
}

.btn-delete {
    background: var(--admin-danger);
    color: var(--admin-white);
}

.btn-delete:hover {
    background: #e74c3c;
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .section-header {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }
    
    .btn-primary {
        justify-content: center;
    }
    
    .data-table {
        font-size: 0.9rem;
    }
    
    .data-table th,
    .data-table td {
        padding: 0.75rem 0.5rem;
    }
}
</style>
