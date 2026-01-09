@php
    $chatUsers = \App\Models\User::where('id', '!=', auth()->id())
        ->where('status', 'active')
        ->orderBy('name')
        ->get(['id', 'name', 'avatar', 'role_id']);
    $currentUserId = auth()->id();
@endphp
<!-- Floating Chat Button & Modal -->
<div id="floatingChat" class="floating-chat">
    <!-- Toggle Button -->
    <button type="button" id="chatToggle" class="chat-toggle" onclick="toggleChat()">
        <i class="fas fa-comments"></i>
        <span class="chat-badge" id="chatBadge" style="display: none;">0</span>
    </button>
    
    <!-- Chat Window -->
    <div id="chatWindow" class="chat-window">
        <div class="chat-header">
            <div id="chatHeaderDefault" class="d-flex align-center gap-2">
                <i class="fas fa-comments"></i>
                <span class="fw-semibold">Pesan</span>
            </div>
            <div id="chatHeaderConvo" class="d-flex align-center gap-2" style="display: none;">
                <button type="button" class="chat-back-btn" onclick="backToList()">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <img id="chatUserAvatar" src="" alt="" class="chat-avatar">
                <span id="chatUserName" class="fw-semibold"></span>
            </div>
            <button type="button" class="chat-close-btn" onclick="toggleChat()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="chat-body">
            <!-- User List -->
            <div id="chatUserList" class="chat-user-list">
                <div class="chat-search">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Cari user..." id="chatSearch" onkeyup="filterUsers()">
                </div>
                <div id="userListContainer" class="user-list-container"></div>
            </div>
            
            <!-- Conversation -->
            <div id="chatConversation" class="chat-conversation" style="display: none;">
                <div id="chatMessages" class="chat-messages"></div>
                <form id="chatForm" class="chat-input-form" onsubmit="sendMessage(event)">
                    <input type="text" id="chatInput" placeholder="Ketik pesan..." autocomplete="off">
                    <button type="submit">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.floating-chat {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 1050;
}

.chat-toggle {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), var(--primary-soft));
    border: none;
    color: white;
    font-size: 1.5rem;
    cursor: pointer;
    box-shadow: 0 4px 20px var(--primary-light);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    transition: all 0.3s ease;
}

.chat-toggle:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 24px rgba(124, 58, 237, 0.5);
}

.chat-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #EF4444;
    color: white;
    border-radius: 50%;
    min-width: 24px;
    height: 24px;
    font-size: 0.75rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid white;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

.chat-window {
    position: absolute;
    bottom: 75px;
    right: 0;
    width: 380px;
    max-width: calc(100vw - 48px);
    height: 520px;
    max-height: calc(100vh - 150px);
    background: var(--bg-card);
    border-radius: 16px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    overflow: hidden;
    display: none;
    flex-direction: column;
    animation: chatSlideUp 0.3s ease;
}

@keyframes chatSlideUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.chat-window.show { display: flex; }

.chat-header {
    padding: 16px 20px;
    background: linear-gradient(135deg, var(--primary), var(--primary-soft));
    color: white;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.chat-back-btn, .chat-close-btn {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
}

.chat-back-btn:hover, .chat-close-btn:hover {
    background: rgba(255, 255, 255, 0.3);
}

.chat-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
}

.chat-body {
    flex: 1;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.chat-user-list {
    flex: 1;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.chat-search {
    padding: 12px 16px;
    display: flex;
    align-items: center;
    gap: 10px;
    border-bottom: 1px solid var(--border-color);
    background: var(--gray-50);
}

.chat-search i { color: var(--text-muted); }

.chat-search input {
    flex: 1;
    border: none;
    background: transparent;
    font-size: 0.875rem;
    color: var(--text-primary);
    outline: none;
}

.user-list-container {
    flex: 1;
    overflow-y: auto;
}

.chat-user-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 16px;
    cursor: pointer;
    transition: background 0.2s;
    border-bottom: 1px solid var(--border-color);
}

.chat-user-item:hover { background: var(--gray-50); }
.chat-user-item.has-unread { background: rgba(124, 58, 237, 0.05); }

.chat-user-item img {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    object-fit: cover;
}

.chat-user-item .user-info {
    flex: 1;
    min-width: 0;
}

.chat-user-item .user-name {
    font-weight: 600;
    font-size: 0.9rem;
    color: var(--text-primary);
}

.chat-user-item .user-preview {
    font-size: 0.8rem;
    color: var(--text-muted);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 180px;
}

.chat-user-item .user-preview.unread {
    color: var(--text-primary);
    font-weight: 500;
}

.chat-user-item .user-meta {
    text-align: right;
    flex-shrink: 0;
}

.chat-user-item .user-time {
    font-size: 0.7rem;
    color: var(--text-muted);
}

.chat-user-item .unread-badge {
    background: #EF4444;
    color: white;
    border-radius: 50%;
    min-width: 20px;
    height: 20px;
    font-size: 0.7rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 4px;
    margin-left: auto;
}

.chat-conversation {
    flex: 1;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    background: #F8FAFC;
}

[data-theme="dark"] .chat-messages { background: #0F172A; }

.chat-message {
    max-width: 80%;
    padding: 10px 14px;
    border-radius: 16px;
    font-size: 0.875rem;
    line-height: 1.4;
}

.chat-message.mine {
    background: linear-gradient(135deg, var(--primary), var(--primary-soft));
    color: white;
    align-self: flex-end;
    border-bottom-right-radius: 4px;
}

.chat-message.theirs {
    background: white;
    color: var(--text-primary);
    align-self: flex-start;
    border-bottom-left-radius: 4px;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

[data-theme="dark"] .chat-message.theirs { background: #1E293B; }

.chat-message .time {
    font-size: 0.65rem;
    opacity: 0.7;
    margin-top: 4px;
    text-align: right;
}

.chat-input-form {
    padding: 12px 16px;
    display: flex;
    gap: 10px;
    border-top: 1px solid var(--border-color);
    background: var(--bg-card);
}

.chat-input-form input {
    flex: 1;
    padding: 12px 16px;
    border: 1px solid var(--border-color);
    border-radius: 24px;
    font-size: 0.875rem;
    background: var(--gray-50);
    color: var(--text-primary);
    outline: none;
}

.chat-input-form input:focus { border-color: var(--primary); }

.chat-input-form button {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), var(--primary-soft));
    border: none;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.2s;
}

.chat-input-form button:hover { transform: scale(1.05); }

.chat-empty {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: var(--text-muted);
    padding: 40px;
    text-align: center;
}

.chat-empty i {
    font-size: 3rem;
    margin-bottom: 16px;
    opacity: 0.5;
}

@media (max-width: 480px) {
    .chat-window {
        width: calc(100vw - 32px);
        height: calc(100vh - 100px);
        bottom: 70px;
        right: -8px;
    }
}
</style>

<script>
const chatUsers = @json($chatUsers->map(fn($u) => ['id' => $u->id, 'name' => $u->name, 'avatar_url' => $u->avatar_url]));
const currentUserId = {{ $currentUserId }};
let currentChatUser = null;
let chatInterval = null;
let conversationData = [];

function toggleChat() {
    const chatWindow = document.getElementById('chatWindow');
    chatWindow.classList.toggle('show');
    
    if (chatWindow.classList.contains('show')) {
        buildUserList();
        startPolling();
    } else {
        stopPolling();
    }
}

function buildUserList() {
    const container = document.getElementById('userListContainer');
    container.innerHTML = '<div class="text-center py-4 text-muted">Loading...</div>';
    
    // Get message stats for each user
    Promise.all(chatUsers.map(user => 
        fetch(`/messages/conversation/${user.id}`)
            .then(r => r.json())
            .then(data => ({
                user,
                lastMessage: data.messages[data.messages.length - 1] || null,
                unreadCount: data.messages.filter(m => !m.is_mine && !m.is_read).length || 0
            }))
            .catch(() => ({ user, lastMessage: null, unreadCount: 0 }))
    )).then(results => {
        // Sort by last message time (newest first)
        results.sort((a, b) => {
            if (!a.lastMessage && !b.lastMessage) return 0;
            if (!a.lastMessage) return 1;
            if (!b.lastMessage) return -1;
            return new Date(b.lastMessage.created_at_raw || 0) - new Date(a.lastMessage.created_at_raw || 0);
        });
        
        conversationData = results;
        renderUserList();
    });
}

function renderUserList() {
    const container = document.getElementById('userListContainer');
    
    if (conversationData.length === 0) {
        container.innerHTML = '<div class="chat-empty"><i class="fas fa-users"></i><div>Tidak ada user</div></div>';
        return;
    }
    
    container.innerHTML = conversationData.map(({ user, lastMessage, unreadCount }) => {
        const hasUnread = unreadCount > 0;
        const preview = lastMessage ? (lastMessage.is_mine ? 'Anda: ' : '') + lastMessage.content.substring(0, 30) + (lastMessage.content.length > 30 ? '...' : '') : 'Belum ada pesan';
        const time = lastMessage ? lastMessage.created_at : '';
        
        return `
            <div class="chat-user-item ${hasUnread ? 'has-unread' : ''}" onclick="openConversation(${user.id}, '${user.name.replace(/'/g, "\\'")}', '${user.avatar_url}')">
                <img src="${user.avatar_url}" alt="${user.name}">
                <div class="user-info">
                    <div class="user-name">${user.name}</div>
                    <div class="user-preview ${hasUnread ? 'unread' : ''}">${preview}</div>
                </div>
                <div class="user-meta">
                    <div class="user-time">${time}</div>
                    ${hasUnread ? `<div class="unread-badge">${unreadCount}</div>` : ''}
                </div>
            </div>
        `;
    }).join('');
}

function filterUsers() {
    const query = document.getElementById('chatSearch').value.toLowerCase();
    document.querySelectorAll('.chat-user-item').forEach(item => {
        const name = item.querySelector('.user-name').textContent.toLowerCase();
        item.style.display = name.includes(query) ? 'flex' : 'none';
    });
}

function openConversation(userId, name, avatar) {
    currentChatUser = userId;
    
    document.getElementById('chatUserList').style.display = 'none';
    document.getElementById('chatConversation').style.display = 'flex';
    document.getElementById('chatHeaderDefault').style.display = 'none';
    document.getElementById('chatHeaderConvo').style.display = 'flex';
    
    document.getElementById('chatUserName').textContent = name;
    document.getElementById('chatUserAvatar').src = avatar;
    
    loadMessages();
}

function backToList() {
    currentChatUser = null;
    
    document.getElementById('chatUserList').style.display = 'flex';
    document.getElementById('chatConversation').style.display = 'none';
    document.getElementById('chatHeaderDefault').style.display = 'flex';
    document.getElementById('chatHeaderConvo').style.display = 'none';
    
    buildUserList();
}

function loadMessages() {
    if (!currentChatUser) return;
    
    fetch(`/messages/conversation/${currentChatUser}`)
        .then(r => r.json())
        .then(data => {
            const container = document.getElementById('chatMessages');
            if (data.messages.length === 0) {
                container.innerHTML = '<div class="chat-empty"><i class="fas fa-comments"></i><div>Mulai percakapan</div></div>';
            } else {
                container.innerHTML = data.messages.map(m => `
                    <div class="chat-message ${m.is_mine ? 'mine' : 'theirs'}">
                        <div>${m.content}</div>
                        <div class="time">${m.created_at}</div>
                    </div>
                `).join('');
            }
            container.scrollTop = container.scrollHeight;
        });
}

function sendMessage(e) {
    e.preventDefault();
    const input = document.getElementById('chatInput');
    const content = input.value.trim();
    
    if (!content || !currentChatUser) return;
    
    fetch(`/messages/send/${currentChatUser}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ content })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            input.value = '';
            loadMessages();
        }
    });
}

function updateUnreadBadge() {
    fetch('/messages/unread-count')
        .then(r => r.json())
        .then(data => {
            const badge = document.getElementById('chatBadge');
            if (data.count > 0) {
                badge.textContent = data.count;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        })
        .catch(() => {});
}

function startPolling() {
    updateUnreadBadge();
    chatInterval = setInterval(() => {
        updateUnreadBadge();
        if (currentChatUser) loadMessages();
    }, 5000);
}

function stopPolling() {
    if (chatInterval) {
        clearInterval(chatInterval);
        chatInterval = null;
    }
}

document.addEventListener('DOMContentLoaded', updateUnreadBadge);
</script>
