@extends('layouts.app')

@section('title', 'Pengumuman')
@section('page-title', 'Pengumuman')

@push('styles')
<style>
.post-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    margin-bottom: 16px;
    overflow: hidden;
}
.post-header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    border-bottom: 1px solid var(--border-color);
}
.post-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    object-fit: cover;
}
.post-user-name {
    font-weight: 600;
    color: var(--text-primary);
}
.post-time {
    font-size: 0.8rem;
    color: var(--text-muted);
}
.post-content {
    padding: 16px;
    font-size: 1rem;
    line-height: 1.6;
    white-space: pre-wrap;
}
.post-poll {
    padding: 0 16px 16px;
}
.poll-question {
    font-weight: 600;
    margin-bottom: 12px;
}
.poll-option {
    background: var(--gray-100);
    border-radius: 8px;
    padding: 12px 16px;
    margin-bottom: 8px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    transition: all 0.2s;
}
.poll-option:hover:not(.voted) {
    background: var(--primary-light);
}
.poll-option.voted {
    cursor: default;
}
.poll-option .poll-bar {
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    background: var(--primary-light);
    transition: width 0.5s ease;
    z-index: 0;
}
.poll-option .poll-text {
    position: relative;
    z-index: 1;
    display: flex;
    justify-content: space-between;
}
.poll-option.selected .poll-bar {
    background: var(--primary);
    opacity: 0.2;
}
.poll-option.selected {
    border: 2px solid var(--primary);
}
.poll-meta {
    font-size: 0.8rem;
    color: var(--text-muted);
    margin-top: 8px;
}
.post-actions {
    display: flex;
    border-top: 1px solid var(--border-color);
    padding: 8px 16px;
    gap: 8px;
}
.action-btn {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 8px;
    border: none;
    background: transparent;
    color: var(--text-muted);
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 0.875rem;
}
.action-btn:hover {
    background: var(--gray-100);
    color: var(--primary);
}
.action-btn.active {
    color: var(--primary);
}
.reactions-bar {
    display: flex;
    gap: 4px;
    padding: 8px 16px;
    flex-wrap: wrap;
}
.reaction-btn {
    padding: 4px 10px;
    border: 1px solid var(--border-color);
    border-radius: 20px;
    background: var(--bg-card);
    cursor: pointer;
    font-size: 1rem;
    transition: all 0.2s;
}
.reaction-btn:hover {
    transform: scale(1.1);
    border-color: var(--primary);
}
.reaction-btn.active {
    background: var(--primary-light);
    border-color: var(--primary);
}
.reaction-summary {
    display: flex;
    gap: 8px;
    padding: 8px 16px;
    font-size: 0.8rem;
    color: var(--text-muted);
}
.comments-section {
    border-top: 1px solid var(--border-color);
    padding: 16px;
    display: none;
}
.comments-section.show {
    display: block;
}
.comment-item {
    display: flex;
    gap: 12px;
    margin-bottom: 12px;
}
.comment-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
}
.comment-content {
    flex: 1;
    background: var(--gray-100);
    padding: 10px 14px;
    border-radius: 12px;
}
.comment-name {
    font-weight: 600;
    font-size: 0.875rem;
}
.comment-text {
    font-size: 0.875rem;
    margin-top: 2px;
}
.comment-time {
    font-size: 0.7rem;
    color: var(--text-muted);
    margin-top: 4px;
}
.comment-form {
    display: flex;
    gap: 8px;
    margin-top: 12px;
}
.comment-form input {
    flex: 1;
    padding: 10px 14px;
    border: 1px solid var(--border-color);
    border-radius: 20px;
    background: var(--gray-50);
    font-size: 0.875rem;
}
.create-post {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 16px;
    margin-bottom: 24px;
}
.create-post textarea {
    width: 100%;
    border: none;
    background: transparent;
    resize: none;
    font-size: 1rem;
    line-height: 1.5;
    min-height: 80px;
}
.create-post textarea:focus {
    outline: none;
}
.poll-creator {
    background: var(--gray-50);
    border-radius: 12px;
    padding: 16px;
    margin-top: 12px;
    display: none;
}
.poll-creator.show {
    display: block;
}
.poll-option-input {
    display: flex;
    gap: 8px;
    margin-bottom: 8px;
}
.poll-option-input input {
    flex: 1;
    padding: 10px 14px;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    font-size: 0.875rem;
}
.delete-btn {
    position: absolute;
    top: 16px;
    right: 16px;
    background: none;
    border: none;
    color: var(--text-muted);
    cursor: pointer;
    padding: 4px 8px;
    border-radius: 4px;
}
.delete-btn:hover {
    background: var(--danger-light);
    color: var(--danger);
}
</style>
@endpush

@section('content')
<!-- Create Post -->
<div class="create-post animate-fadeIn">
    <form action="{{ route('announcements.store') }}" method="POST" id="createPostForm">
        @csrf
        <div class="d-flex gap-3">
            <img src="{{ auth()->user()->avatar_url }}" alt="" class="post-avatar">
            <div class="flex-1">
                <textarea name="content" placeholder="Apa yang ingin kamu umumkan?" required></textarea>
                
                <div class="poll-creator" id="pollCreator">
                    <input type="hidden" name="has_poll" id="hasPollInput" value="0">
                    <div class="form-group mb-3">
                        <input type="text" name="poll_question" class="form-control" placeholder="Pertanyaan polling...">
                    </div>
                    <div id="pollOptionsContainer">
                        <div class="poll-option-input">
                            <input type="text" name="poll_options[]" placeholder="Opsi 1">
                            <button type="button" class="btn btn-secondary btn-sm btn-icon" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
                        </div>
                        <div class="poll-option-input">
                            <input type="text" name="poll_options[]" placeholder="Opsi 2">
                            <button type="button" class="btn btn-secondary btn-sm btn-icon" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary btn-sm mt-2" onclick="addPollOption()">
                        <i class="fas fa-plus"></i> Tambah Opsi
                    </button>
                    <div class="form-group mt-3">
                        <select name="poll_duration" class="form-control form-select">
                            <option value="24">1 Hari</option>
                            <option value="72">3 Hari</option>
                            <option value="168">7 Hari</option>
                        </select>
                    </div>
                </div>
                
                <div class="d-flex justify-between align-center mt-3">
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-secondary btn-sm" onclick="togglePollCreator()">
                            <i class="fas fa-poll"></i> Poll
                        </button>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Posting
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Feed -->
@forelse($announcements as $post)
<div class="post-card animate-fadeIn" style="position: relative;">
    @if($post->user_id === auth()->id() || auth()->user()->isAdmin())
    <form action="{{ route('announcements.destroy', $post) }}" method="POST" class="delete-btn" onsubmit="return confirm('Hapus pengumuman ini?')">
        @csrf
        @method('DELETE')
        <button type="submit"><i class="fas fa-trash"></i></button>
    </form>
    @endif
    
    <div class="post-header">
        <img src="{{ $post->user->avatar_url }}" alt="" class="post-avatar">
        <div>
            <div class="post-user-name">{{ $post->user->name }}</div>
            <div class="post-time">{{ $post->created_at->diffForHumans() }}</div>
        </div>
    </div>
    
    <div class="post-content">{{ $post->content }}</div>
    
    @if($post->has_poll)
    <div class="post-poll">
        <div class="poll-question">📊 {{ $post->poll_question }}</div>
        @php
            $hasVoted = $post->hasUserVoted(auth()->id());
            $userVoteId = $post->getUserVoteOptionId(auth()->id());
        @endphp
        @foreach($post->pollOptions as $option)
        <div class="poll-option {{ $hasVoted ? 'voted' : '' }} {{ $userVoteId === $option->id ? 'selected' : '' }}" 
             data-option-id="{{ $option->id }}" 
             data-post-id="{{ $post->id }}"
             onclick="votePoll(this)">
            <div class="poll-bar" style="width: {{ $hasVoted ? $option->percentage : 0 }}%;"></div>
            <div class="poll-text">
                <span>{{ $option->option_text }}</span>
                @if($hasVoted)
                <span>{{ $option->percentage }}%</span>
                @endif
            </div>
        </div>
        @endforeach
        <div class="poll-meta">
            {{ $post->total_votes }} votes
            @if($post->poll_ends_at)
            • {{ $post->isPollActive() ? 'Berakhir ' . $post->poll_ends_at->diffForHumans() : 'Sudah berakhir' }}
            @endif
        </div>
    </div>
    @endif
    
    <!-- Reactions Summary -->
    @if($post->reactions->count() > 0)
    <div class="reaction-summary">
        @php $reactionCounts = $post->reaction_counts; @endphp
        @foreach($reactionCounts as $type => $count)
            @php
                $emoji = match($type) {
                    'like' => '👍',
                    'love' => '❤️',
                    'haha' => '😂',
                    'wow' => '😮',
                    'sad' => '😢',
                    'angry' => '😡',
                    default => '👍'
                };
            @endphp
            <span>{{ $emoji }} {{ $count }}</span>
        @endforeach
    </div>
    @endif
    
    <!-- Reactions Bar -->
    <div class="reactions-bar" id="reactions-{{ $post->id }}" style="display: none;">
        @php $userReaction = $post->getUserReaction(auth()->id()); @endphp
        <button class="reaction-btn {{ $userReaction === 'like' ? 'active' : '' }}" onclick="react({{ $post->id }}, 'like')">👍</button>
        <button class="reaction-btn {{ $userReaction === 'love' ? 'active' : '' }}" onclick="react({{ $post->id }}, 'love')">❤️</button>
        <button class="reaction-btn {{ $userReaction === 'haha' ? 'active' : '' }}" onclick="react({{ $post->id }}, 'haha')">😂</button>
        <button class="reaction-btn {{ $userReaction === 'wow' ? 'active' : '' }}" onclick="react({{ $post->id }}, 'wow')">😮</button>
        <button class="reaction-btn {{ $userReaction === 'sad' ? 'active' : '' }}" onclick="react({{ $post->id }}, 'sad')">😢</button>
        <button class="reaction-btn {{ $userReaction === 'angry' ? 'active' : '' }}" onclick="react({{ $post->id }}, 'angry')">😡</button>
    </div>
    
    <div class="post-actions">
        <button class="action-btn" onclick="toggleReactions({{ $post->id }})">
            <i class="far fa-thumbs-up"></i> Reaksi
        </button>
        <button class="action-btn" onclick="toggleComments({{ $post->id }})">
            <i class="far fa-comment"></i> Komentar ({{ $post->comments->count() }})
        </button>
    </div>
    
    <!-- Comments Section -->
    <div class="comments-section" id="comments-{{ $post->id }}">
        @foreach($post->comments->take(5) as $comment)
        <div class="comment-item">
            <img src="{{ $comment->user->avatar_url }}" alt="" class="comment-avatar">
            <div class="comment-content">
                <div class="comment-name">{{ $comment->user->name }}</div>
                <div class="comment-text">{{ $comment->content }}</div>
                <div class="comment-time">{{ $comment->created_at->diffForHumans() }}</div>
            </div>
        </div>
        @endforeach
        
        <form action="{{ route('announcements.comment', $post) }}" method="POST" class="comment-form">
            @csrf
            <input type="text" name="content" placeholder="Tulis komentar..." required>
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-paper-plane"></i></button>
        </form>
    </div>
</div>
@empty
<div class="card">
    <div class="card-body text-center py-5">
        <i class="fas fa-bullhorn text-muted" style="font-size: 4rem;"></i>
        <h5 class="mt-3">Belum ada pengumuman</h5>
        <p class="text-muted">Jadilah yang pertama membuat pengumuman!</p>
    </div>
</div>
@endforelse

{{ $announcements->links() }}
@endsection

@push('scripts')
<script>
function togglePollCreator() {
    const creator = document.getElementById('pollCreator');
    const input = document.getElementById('hasPollInput');
    creator.classList.toggle('show');
    input.value = creator.classList.contains('show') ? '1' : '0';
}

function addPollOption() {
    const container = document.getElementById('pollOptionsContainer');
    const count = container.children.length + 1;
    if (count > 6) return alert('Maksimal 6 opsi');
    
    const div = document.createElement('div');
    div.className = 'poll-option-input';
    div.innerHTML = `
        <input type="text" name="poll_options[]" placeholder="Opsi ${count}">
        <button type="button" class="btn btn-secondary btn-sm btn-icon" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
    `;
    container.appendChild(div);
}

function toggleReactions(postId) {
    document.getElementById('reactions-' + postId).style.display = 
        document.getElementById('reactions-' + postId).style.display === 'none' ? 'flex' : 'none';
}

function toggleComments(postId) {
    document.getElementById('comments-' + postId).classList.toggle('show');
}

function react(postId, type) {
    fetch(`/announcements/${postId}/react`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ type })
    })
    .then(r => r.json())
    .then(() => location.reload());
}

function votePoll(element) {
    if (element.classList.contains('voted')) return;
    
    const postId = element.dataset.postId;
    const optionId = element.dataset.optionId;
    
    fetch(`/announcements/${postId}/vote`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ option_id: optionId })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.error);
        }
    });
}
</script>
@endpush
