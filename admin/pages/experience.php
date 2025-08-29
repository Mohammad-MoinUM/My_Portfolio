<?php
// Fetch experiences
$sql = "SELECT * FROM experiences ORDER BY created_at DESC";
$res = mysqli_query($conn, $sql);
$experiences = $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
?>

<div class="content-section">
    <div class="section-header">
        <h2>Experience Management</h2>
        <a href="experience_form.php" class="btn-primary">
            <i class="fas fa-plus"></i> Add Experience
        </a>
    </div>
    
    <?php if(empty($experiences)): ?>
        <div class="empty-state">
            <i class="fas fa-briefcase"></i>
            <h3>No experience records found</h3>
            <p>Click "Add Experience" to create your first experience record.</p>
        </div>
    <?php else: ?>
        <div class="data-table">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Company</th>
                        <th>Duration</th>
                        <th>Description</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($experiences as $exp): ?>
                        <tr>
                            <td><?php echo $exp['id']; ?></td>
                            <td>
                                <strong><?php echo htmlspecialchars($exp['title']); ?></strong>
                            </td>
                            <td><?php echo htmlspecialchars($exp['company']); ?></td>
                            <td>
                                <?php if(!empty($exp['duration'])): ?>
                                    <span class="duration"><?php echo htmlspecialchars($exp['duration']); ?></span>
                                <?php else: ?>
                                    <span class="no-duration">No Duration</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if(!empty($exp['description'])): ?>
                                    <?php echo htmlspecialchars(substr($exp['description'], 0, 60)) . (strlen($exp['description']) > 60 ? '...' : ''); ?>
                                <?php else: ?>
                                    <span class="no-description">No Description</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($exp['created_at'])); ?></td>
                            <td class="actions">
                                <a href="experience_form.php?id=<?php echo $exp['id']; ?>" class="btn-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="delete_experience.php?id=<?php echo $exp['id']; ?>" class="btn-delete" title="Delete" 
                                   onclick="return confirm('Delete this experience record?');">
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
.duration {
    background: var(--admin-warning);
    color: var(--admin-white);
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 600;
}

.no-duration, .no-description {
    color: #999;
    font-style: italic;
    font-size: 0.9rem;
}
</style>
