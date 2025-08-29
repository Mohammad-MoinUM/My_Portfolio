<?php
// Fetch contacts
$sql = "SELECT * FROM contacts ORDER BY created_at DESC";
$res = mysqli_query($conn, $sql);
$contacts = $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
?>

<div class="content-section">
    <div class="section-header">
        <h2>Contact Management</h2>
        <a href="contact_form.php" class="btn-primary">
            <i class="fas fa-plus"></i> Add Contact
        </a>
    </div>
    
    <?php if(empty($contacts)): ?>
        <div class="empty-state">
            <i class="fas fa-address-book"></i>
            <h3>No contacts found</h3>
            <p>Click "Add Contact" to create your first contact entry.</p>
        </div>
    <?php else: ?>
        <div class="data-table">
            <table>
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
                            <td>
                                <span class="contact-type"><?php echo htmlspecialchars($contact['type']); ?></span>
                            </td>
                            <td>
                                <?php if (stripos($contact['type'], 'email') !== false): ?>
                                    <a href="mailto:<?php echo htmlspecialchars($contact['value']); ?>" class="link">
                                        <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($contact['value']); ?>
                                    </a>
                                <?php elseif (stripos($contact['type'], 'phone') !== false): ?>
                                    <a href="tel:<?php echo preg_replace('/[^+0-9]/', '', $contact['value']); ?>" class="link">
                                        <i class="fas fa-phone"></i> <?php echo htmlspecialchars($contact['value']); ?>
                                    </a>
                                <?php elseif (preg_match('/^https?:/i', $contact['value'])): ?>
                                    <a href="<?php echo htmlspecialchars($contact['value']); ?>" target="_blank" class="link">
                                        <i class="fas fa-external-link-alt"></i> <?php echo htmlspecialchars($contact['value']); ?>
                                    </a>
                                <?php else: ?>
                                    <?php echo htmlspecialchars($contact['value']); ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if(!empty($contact['icon'])): ?>
                                    <i class="<?php echo htmlspecialchars($contact['icon']); ?>"></i>
                                <?php else: ?>
                                    <span class="no-icon">No Icon</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($contact['created_at'])); ?></td>
                            <td class="actions">
                                <a href="contact_form.php?id=<?php echo $contact['id']; ?>" class="btn-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="delete_contact.php?id=<?php echo $contact['id']; ?>" class="btn-delete" title="Delete" 
                                   onclick="return confirm('Delete this contact?');">
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
.contact-type {
    background: var(--admin-primary);
    color: var(--admin-white);
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}

.no-icon {
    color: #999;
    font-style: italic;
    font-size: 0.9rem;
}
</style>
