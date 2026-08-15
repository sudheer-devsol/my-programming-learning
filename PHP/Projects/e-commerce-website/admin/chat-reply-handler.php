<?php
/*
    admin/chat-reply-handler.php
    ---------------------------------------------
    Topic 19: Ajax - background endpoint for the admin chat page.
    Returns JSON only, no HTML.
*/
$base = '../';
require_once '../includes/init.php';
header('Content-Type: application/json');

if (!isAdmin()) {
    echo json_encode(['success' => false, 'message' => 'Not authorized']);
    exit;
}

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$userId = (int)($_GET['user_id'] ?? $_POST['user_id'] ?? 0);

if ($action === 'send') {
    $message = clean($_POST['message'] ?? '');
    if ($message === '' || $userId === 0) {
        echo json_encode(['success' => false]);
        exit;
    }
    $db->run(
        "INSERT INTO chat_messages (user_id, sender, message) VALUES (?, 'admin', ?)",
        "is",
        [$userId, $message]
    );
    echo json_encode(['success' => true]);
    exit;
}

if ($action === 'fetch') {
    $rows = $db->select(
        "SELECT sender, message, created_at FROM chat_messages WHERE user_id = ? ORDER BY created_at ASC",
        "i",
        [$userId]
    );
    $formatted = [];
    foreach ($rows as $row) {
        $formatted[] = [
            'sender' => $row['sender'],
            'message' => $row['message'],
            'time_ago' => timeAgo($row['created_at'])
        ];
    }
    echo json_encode(['success' => true, 'messages' => $formatted]);
    exit;
}

echo json_encode(['success' => false]);
?>
