<?php
$base = '../';
require_once '../includes/init.php';
requireAdmin();

$userId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;
$customer = $userModel->findById($userId);

if (!$customer) {
    header('Location: chat.php');
    exit;
}

$pageTitle = 'Chat with ' . $customer['full_name'];

// Mark this customer's messages as read
$db->run("UPDATE chat_messages SET is_read = 1 WHERE user_id = ? AND sender = 'user'", "i", [$userId]);

$messages = $db->select(
    "SELECT * FROM chat_messages WHERE user_id = ? ORDER BY created_at ASC",
    "i",
    [$userId]
);

include '../includes/header.php';
?>

<a href="chat.php" class="back-link mt-20"><i class='bx bx-arrow-back'></i> Back to all conversations</a>
<h1 class="page-title">Chat with <?php echo htmlspecialchars($customer['full_name']); ?></h1>

<div class="chat-box mt-20">
    <div class="chat-messages" id="chatMessages">
        <?php foreach ($messages as $msg): ?>
            <div class="chat-msg <?php echo $msg['sender']; ?>">
                <?php echo htmlspecialchars($msg['message']); ?>
                <small><?php echo timeAgo($msg['created_at']); ?></small>
            </div>
        <?php endforeach; ?>
    </div>
    <form class="chat-input-row" id="chatForm">
        <input type="text" id="chatInput" placeholder="Type your reply..." autocomplete="off" required>
        <button type="submit"><i class='bx bx-send'></i></button>
    </form>
</div>

<script>
var chatMessages = document.getElementById('chatMessages');
var chatForm = document.getElementById('chatForm');
var chatInput = document.getElementById('chatInput');
var userId = <?php echo (int)$userId; ?>;

function scrollToBottom() { chatMessages.scrollTop = chatMessages.scrollHeight; }
scrollToBottom();

chatForm.addEventListener('submit', function (e) {
    e.preventDefault();
    var text = chatInput.value.trim();
    if (text === '') return;

    fetch('chat-reply-handler.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'action=send&message=' + encodeURIComponent(text) + '&user_id=' + userId
    })
    .then(function (res) { return res.json(); })
    .then(function () { chatInput.value = ''; loadMessages(); });
});

function loadMessages() {
    fetch('chat-reply-handler.php?action=fetch&user_id=' + userId)
        .then(function (res) { return res.json(); })
        .then(function (data) {
            chatMessages.innerHTML = '';
            data.messages.forEach(function (msg) {
                var div = document.createElement('div');
                div.className = 'chat-msg ' + msg.sender;
                div.textContent = msg.message;
                var small = document.createElement('small');
                small.textContent = msg.time_ago;
                div.appendChild(small);
                chatMessages.appendChild(div);
            });
            scrollToBottom();
        });
}
setInterval(loadMessages, 3000);
</script>

<?php include '../includes/footer.php'; ?>
