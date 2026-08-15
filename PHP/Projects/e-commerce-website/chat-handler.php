<?php
/*
    chat-handler.php
    ---------------------------------------------
    Topic 19: Ajax
    This file does NOT output any HTML. It only returns JSON.
    It is called in the background by chat.php using fetch().
*/
$base = '';
require_once 'includes/init.php';
header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$action = $_GET['action'] ?? $_POST['action'] ?? '';

if ($action === 'send') {
    $message = clean($_POST['message'] ?? '');
    if ($message === '') {
        echo json_encode(['success' => false, 'message' => 'Empty message']);
        exit;
    }
    $db->run(
        "INSERT INTO chat_messages (user_id, sender, message) VALUES (?, 'user', ?)",
        "is",
        [$_SESSION['user_id'], $message]
    );
    echo json_encode(['success' => true]);
    exit;
}

if ($action === 'fetch') {
    $rows = $db->select(
        "SELECT sender, message, created_at FROM chat_messages WHERE user_id = ? ORDER BY created_at ASC",
        "i",
        [$_SESSION['user_id']]
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

echo json_encode(['success' => false, 'message' => 'Unknown action']);
?>
