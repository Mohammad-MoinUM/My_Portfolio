<?php
// Fetch educations
$sql = "SELECT * FROM educations ORDER BY created_at DESC";
$res = mysqli_query($conn, $sql);
$educations = $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
?>

<div class="content-section">
    <div class="section-header">
        <h2>Education Management</h2>
        <a href="education_form.php" class="btn-primary">
            <i class="fas fa-plus"></i> Add Education
        </a>
    </div>
    
    <?php if(empty($educations)): ?>
        <div class="empty-state">
            <i class="fas fa-graduation-cap"></i>
            <h3>No education records found</h3>
            <p>Click "Add Education" to create your first education record.</p>
        </div>
    <?php else: ?>
        <div class="data-table">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Degree</th>
                        <th>Institution</th>
                        <th>Duration</th>
                        <th>Description</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($educations as $edu): ?>
                        <tr>
                            <td><?php echo $edu['id']; ?></td>
                            <td>
                                <strong><?php echo htmlspecialchars($edu['degree']); ?></strong>
                            </td>
                            <td><?php echo htmlspecialchars($edu['institution']); ?></td>
                            <td>
                                <?php if(!empty($edu['duration'])): ?>
                                    <span class="duration"><?php echo htmlspecialchars($edu['duration']); ?></span>
                                <?php else: ?>
                                    <span class="no-duration">No Duration</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if(!empty($edu['description'])): ?>
                                    <?php echo htmlspecialchars(substr($edu['description'], 0, 60)) . (strlen($edu['description']) > 60 ? '...' : ''); ?>
                                <?php else: ?>
                                    <span class="no-description">No Description</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($edu['created_at'])); ?></td>
                            <td class="actions">
                                <a href="education_form.php?id=<?php echo $edu['id']; ?>" class="btn-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="delete_education.php?id=<?php echo $edu['id']; ?>" class="btn-delete" title="Delete" 
                                   onclick="return confirm('Delete this education record?');">
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
    background: var(--admin-success);
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
