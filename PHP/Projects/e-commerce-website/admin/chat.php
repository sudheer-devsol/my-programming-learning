<?php
$base = '../';
require_once '../includes/init.php';
requireAdmin();
$pageTitle = 'Customer Chat';

// List every customer who has sent at least one chat message
$customers = $db->select(
    "SELECT DISTINCT users.id, users.full_name, users.email,
        (SELECT COUNT(*) FROM chat_messages WHERE user_id = users.id AND sender = 'user' AND is_read = 0) AS unread
     FROM chat_messages
     JOIN users ON chat_messages.user_id = users.id
     ORDER BY users.full_name ASC"
);

include '../includes/header.php';
?>

<h1 class="page-title">Customer Chat</h1>

<?php if (count($customers) === 0): ?>
    <div class="empty-state">
        <i class='bx bx-chat'></i>
        <h3>No chat conversations yet</h3>
        <p>Customer messages will show up here.</p>
    </div>
<?php else: ?>
    <div class="table-wrap mt-20">
        <table>
            <tr><th>Customer</th><th>Email</th><th>Unread</th><th></th></tr>
            <?php foreach ($customers as $c): ?>
                <tr>
                    <td><?php echo htmlspecialchars($c['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($c['email']); ?></td>
                    <td><?php echo $c['unread'] > 0 ? "<span class='status-badge status-pending'>" . $c['unread'] . " new</span>" : '<span class="small-text">-</span>'; ?></td>
                    <td><a href="chat-reply.php?user_id=<?php echo $c['id']; ?>" class="btn btn-small"><i class='bx bx-chat'></i> Open Chat</a></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>
