<?php
$base = '';
require_once 'includes/init.php';
requireLogin();
$pageTitle = 'Chat Support';

$messages = $db->select(
    "SELECT * FROM chat_messages WHERE user_id = ? ORDER BY created_at ASC",
    "i",
    [$_SESSION['user_id']]
);

include 'includes/header.php';
?>

<h1 class="page-title text-center">Chat with Support</h1>
<p class="section-sub text-center" style="margin-left:0;">We usually reply within a few minutes</p>

<div class="chat-box">
    <div class="chat-messages" id="chatMessages">
        <?php foreach ($messages as $msg): ?>
            <div class="chat-msg <?php echo $msg['sender']; ?>">
                <?php echo htmlspecialchars($msg['message']); ?>
                <small><?php echo timeAgo($msg['created_at']); ?></small>
            </div>
        <?php endforeach; ?>
    </div>
    <form class="chat-input-row" id="chatForm">
        <input type="text" id="chatInput" placeholder="Type your message..." autocomplete="off" required>
        <button type="submit"><i class='bx bx-send'></i></button>
    </form>
</div>

<script>
/* ==========================================================
   Customer chat - Topic 19 (Ajax)
   We send new messages with fetch() and poll every 3 seconds
   for new replies from the admin, without reloading the page.
========================================================== */
var chatMessages = document.getElementById('chatMessages');
var chatForm = document.getElementById('chatForm');
var chatInput = document.getElementById('chatInput');

function scrollToBottom() {
    chatMessages.scrollTop = chatMessages.scrollHeight;
}
scrollToBottom();

chatForm.addEventListener('submit', function (e) {
    e.preventDefault();
    var text = chatInput.value.trim();
    if (text === '') return;

    fetch('chat-handler.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'action=send&message=' + encodeURIComponent(text)
    })
    .then(function (res) { return res.json(); })
    .then(function (data) {
        if (data.success) {
            chatInput.value = '';
            loadMessages();
        }
    });
});

function loadMessages() {
    fetch('chat-handler.php?action=fetch')
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

// Poll for new messages every 3 seconds (simple beginner-friendly approach)
setInterval(loadMessages, 3000);
</script>

<?php include 'includes/footer.php'; ?>
