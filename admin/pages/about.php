<?php
// Fetch about entries
$sql = "SELECT * FROM about ORDER BY created_at DESC";
$res = mysqli_query($conn, $sql);
$abouts = $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
?>

<div class="content-section">
    <div class="section-header">
        <h2>About Management</h2>
        <a href="about_form.php" class="btn-primary">
            <i class="fas fa-plus"></i> Add About Entry
        </a>
    </div>
    
    <?php if(empty($abouts)): ?>
        <div class="empty-state">
            <i class="fas fa-user"></i>
            <h3>No about entries found</h3>
            <p>Click "Add About Entry" to create your first about section.</p>
        </div>
    <?php else: ?>
        <div class="data-table">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Content</th>
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
                                    <img src="<?php echo $about['image_url']; ?>" alt="About" class="thumbnail">
                                <?php else: ?>
                                    <span class="no-image">No Image</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($about['title'] ?? 'No Title'); ?></td>
                            <td><?php echo htmlspecialchars(substr($about['content'], 0, 80)) . (strlen($about['content']) > 80 ? '...' : ''); ?></td>
                            <td><?php echo date('M d, Y', strtotime($about['created_at'])); ?></td>
                            <td class="actions">
                                <a href="about_form.php?id=<?php echo $about['id']; ?>" class="btn-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="delete_about.php?id=<?php echo $about['id']; ?>" class="btn-delete" title="Delete" 
                                   onclick="return confirm('Delete this about entry?');">
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
