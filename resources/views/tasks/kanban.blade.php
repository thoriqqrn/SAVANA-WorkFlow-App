@extends('layouts.app')

@section('title', $title . ' - Kanban')
@section('page-title', $title)

@push('styles')
<style>
.kanban-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.9rem;
}

.breadcrumb a {
    color: var(--text-muted);
    text-decoration: none;
}

.breadcrumb a:hover {
    color: var(--primary);
}

.breadcrumb .separator {
    color: var(--text-muted);
}

.breadcrumb .current {
    color: var(--text-primary);
    font-weight: 500;
}

.kanban-container {
    display: flex;
    gap: 1rem;
    overflow-x: auto;
    padding-bottom: 1rem;
    min-height: calc(100vh - 240px);
}

.kanban-column {
    flex: 0 0 300px;
    background: var(--gray-100);
    border-radius: 16px;
    display: flex;
    flex-direction: column;
    max-height: calc(100vh - 200px);
}

.column-header {
    padding: 1rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 2px solid;
}

.column-header.todo {
    border-color: #6B7280;
}

.column-header.in_progress {
    border-color: #F59E0B;
}

.column-header.pending {
    border-color: #8B5CF6;
}

.column-header.done {
    border-color: #10B981;
}

.column-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    font-size: 0.95rem;
}

.column-title .dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
}

.column-title .dot.todo { background: #6B7280; }
.column-title .dot.in_progress { background: #F59E0B; }
.column-title .dot.pending { background: #8B5CF6; }
.column-title .dot.done { background: #10B981; }

.column-count {
    background: var(--gray-200);
    color: var(--text-secondary);
    font-size: 0.75rem;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 12px;
}

.column-body {
    flex: 1;
    padding: 0.75rem;
    overflow-y: auto;
    min-height: 200px;
}

.task-card {
    background: var(--bg-card);
    border-radius: 12px;
    padding: 1rem;
    margin-bottom: 0.75rem;
    cursor: grab;
    box-shadow: var(--shadow);
    transition: all 0.2s ease;
    border: 1px solid var(--border-color);
}

.task-card:hover {
    box-shadow: var(--shadow-md), 0 4px 12px -2px var(--primary-light);
    transform: translateY(-2px);
}

.task-card.dragging {
    opacity: 0.8;
    transform: rotate(3deg);
    box-shadow: var(--shadow-lg);
}

.task-card.ghost {
    opacity: 0.4;
    background: var(--primary-light);
}

.task-title {
    font-weight: 600;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
    color: var(--text-primary);
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.task-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 0.75rem;
}

.task-badge {
    font-size: 0.7rem;
    padding: 3px 8px;
    border-radius: 6px;
    font-weight: 500;
}

.task-badge.priority-high {
    background: var(--danger-light);
    color: var(--danger);
}

.task-badge.priority-medium {
    background: var(--primary-light);
    color: var(--primary);
}

.task-badge.priority-low {
    background: var(--info-light);
    color: var(--info);
}

.task-badge.overdue {
    background: var(--danger-light);
    color: var(--danger);
}

.task-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 0.75rem;
    border-top: 1px solid var(--border-color);
}

.task-assignee {
    display: flex;
    align-items: center;
    gap: 6px;
}

.task-assignee img {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    object-fit: cover;
}

.task-assignee span {
    font-size: 0.75rem;
    color: var(--text-muted);
}

.task-deadline {
    font-size: 0.75rem;
    color: var(--text-muted);
    display: flex;
    align-items: center;
    gap: 4px;
}

.task-deadline.overdue {
    color: var(--danger);
}

.add-task-btn {
    width: 100%;
    padding: 0.75rem;
    background: transparent;
    border: 2px dashed var(--border-color);
    border-radius: 10px;
    color: var(--text-muted);
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    font-size: 0.875rem;
}

.add-task-btn:hover {
    border-color: var(--primary);
    color: var(--primary);
    background: var(--primary-light);
}

/* Mobile */
@media (max-width: 768px) {
    .kanban-container {
        padding: 0 0 1rem;
    }
    
    .kanban-column {
        flex: 0 0 85vw;
    }
}
</style>
@endpush

@section('content')
<!-- Header -->
<div class="kanban-header">
    <nav class="breadcrumb">
        <a href="{{ route('tasks.index') }}">
            <i class="fas fa-tasks"></i> Tasks
        </a>
        @if(isset($department))
        <span class="separator">/</span>
        <a href="{{ route('tasks.department', $department) }}">{{ $department->name }}</a>
        @elseif(isset($program))
        <span class="separator">/</span>
        <a href="{{ route('tasks.department', $program->department) }}">{{ $program->department->name }}</a>
        @endif
        <span class="separator">/</span>
        <span class="current">{{ $title }}</span>
    </nav>
    
    <a href="{{ $createUrl }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Task
    </a>
</div>

<!-- Kanban Board -->
<div class="kanban-container">
    @php
        $columns = [
            'todo' => ['label' => 'To Do', 'icon' => 'circle'],
            'in_progress' => ['label' => 'In Progress', 'icon' => 'spinner'],
            'pending' => ['label' => 'Pending', 'icon' => 'pause-circle'],
            'done' => ['label' => 'Done', 'icon' => 'check-circle'],
        ];
    @endphp
    
    @foreach($columns as $status => $column)
    <div class="kanban-column" data-status="{{ $status }}">
        <div class="column-header {{ $status }}">
            <div class="column-title">
                <span class="dot {{ $status }}"></span>
                {{ $column['label'] }}
            </div>
            <span class="column-count">{{ isset($tasks[$status]) ? $tasks[$status]->count() : 0 }}</span>
        </div>
        <div class="column-body" id="column-{{ $status }}">
            @if(isset($tasks[$status]))
                @foreach($tasks[$status] as $task)
                <div class="task-card" data-id="{{ $task->id }}" onclick="viewTask({{ $task->id }})">
                    <div class="task-title">{{ $task->title }}</div>
                    <div class="task-meta">
                        <span class="task-badge priority-{{ $task->priority }}">
                            {{ $task->priority_label }}
                        </span>
                        @if($task->is_overdue)
                        <span class="task-badge overdue">
                            <i class="fas fa-exclamation-triangle"></i> Overdue
                        </span>
                        @endif
                    </div>
                    <div class="task-footer">
                        @if($task->assignee)
                        <div class="task-assignee">
                            <img src="{{ $task->assignee->avatar_url }}" alt="">
                            <span>{{ Str::limit($task->assignee->name, 15) }}</span>
                        </div>
                        @else
                        <div class="task-assignee">
                            <i class="fas fa-user-slash text-muted"></i>
                            <span>Unassigned</span>
                        </div>
                        @endif
                        @if($task->deadline)
                        <div class="task-deadline {{ $task->is_overdue ? 'overdue' : '' }}">
                            <i class="fas fa-calendar"></i>
                            {{ $task->deadline->format('d M') }}
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            @endif
        </div>
    </div>
    @endforeach
</div>
@endsection

@push('scripts')
<!-- SortableJS -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const columns = document.querySelectorAll('.column-body');
    
    columns.forEach(column => {
        new Sortable(column, {
            group: 'kanban',
            animation: 200,
            ghostClass: 'ghost',
            dragClass: 'dragging',
            onEnd: function(evt) {
                const taskId = evt.item.dataset.id;
                const newStatus = evt.to.id.replace('column-', '');
                
                // Update via AJAX
                fetch(`/tasks/${taskId}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ status: newStatus })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateColumnCounts();
                        showToast('Status berhasil diupdate!', 'success');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Gagal mengupdate status', 'error');
                });
            }
        });
    });
});

function updateColumnCounts() {
    document.querySelectorAll('.kanban-column').forEach(column => {
        const count = column.querySelector('.column-body').children.length;
        column.querySelector('.column-count').textContent = count;
    });
}

function viewTask(taskId) {
    window.location.href = `/tasks/${taskId}`;
}
</script>
@endpush
