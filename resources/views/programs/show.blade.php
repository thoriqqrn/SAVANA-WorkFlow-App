@extends('layouts.app')

@section('title', $program->name)
@section('page-title', $program->name)

@section('content')
<div class="row">
    <div class="col-12 col-lg-4">
        <div class="card animate-fadeIn mb-4">
            <div class="card-body">
                <div class="d-flex align-center gap-3 mb-3">
                    <div class="stat-icon primary" style="width: 56px; height: 56px; font-size: 1.5rem;">
                        <i class="fas fa-project-diagram"></i>
                    </div>
                    <div class="flex-1">
                        <h5 class="mb-1">{{ $program->name }}</h5>
                        <span class="badge badge-{{ $program->status === 'completed' ? 'success' : ($program->status === 'active' ? 'warning' : 'secondary') }}">
                            {{ ucfirst($program->status) }}
                        </span>
                    </div>
                </div>
                
                @if($program->description)
                <p class="text-muted fs-sm">{{ $program->description }}</p>
                @endif
                
                <hr class="my-3">
                
                <div class="d-flex justify-between mb-2">
                    <span class="text-muted">Departemen</span>
                    <span class="fw-semibold">{{ $program->department->name ?? '-' }}</span>
                </div>
                <div class="d-flex justify-between mb-2">
                    <span class="text-muted">Periode</span>
                    <span class="fw-semibold">{{ $program->start_date->format('d M') }} - {{ $program->end_date->format('d M Y') }}</span>
                </div>
                <div class="d-flex justify-between mb-2">
                    <span class="text-muted">PIC</span>
                    <span class="fw-semibold">{{ $program->creator->name ?? '-' }}</span>
                </div>
                
                <hr class="my-3">
                
                <div class="mb-3">
                    <label class="text-muted fs-sm">Progress</label>
                    <div class="d-flex align-center gap-2">
                        <div class="progress flex-1" style="height: 10px;">
                            <div class="progress-bar {{ $program->progress_percentage >= 100 ? 'success' : '' }}" style="width: {{ $program->progress_percentage }}%;"></div>
                        </div>
                        <span class="fw-bold">{{ $program->progress_percentage }}%</span>
                    </div>
                </div>
                
                @if(auth()->user()->hasRole(['admin', 'bph', 'kabinet']))
                <div class="d-flex gap-2">
                    <a href="{{ route('programs.edit', $program) }}" class="btn btn-primary flex-1">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('programs.index') }}" class="btn btn-secondary flex-1">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
                @else
                <a href="{{ route('programs.index') }}" class="btn btn-secondary w-100">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                @endif
            </div>
        </div>
        
        <!-- Team Members -->
        <div class="card animate-fadeIn">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-users text-primary"></i>
                    Tim
                </h3>
                @if(auth()->user()->hasRole(['admin', 'bph', 'kabinet']))
                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                    <i class="fas fa-plus"></i>
                </button>
                @endif
            </div>
            <div class="card-body">
                @forelse($program->members as $member)
                <div class="d-flex align-center gap-2 {{ !$loop->last ? 'mb-3' : '' }}">
                    <img src="{{ $member->avatar_url }}" alt="{{ $member->name }}" class="avatar-sm">
                    <div class="flex-1">
                        <div class="fw-semibold fs-sm">{{ $member->name }}</div>
                        <span class="badge badge-{{ $member->pivot->role === 'leader' ? 'primary' : 'secondary' }} fs-xs">
                            {{ ucfirst($member->pivot->role) }}
                        </span>
                    </div>
                    @if(auth()->user()->hasRole(['admin', 'bph', 'kabinet']))
                    <form action="{{ route('programs.members.remove', [$program, $member]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger btn-icon" title="Hapus">
                            <i class="fas fa-times"></i>
                        </button>
                    </form>
                    @endif
                </div>
                @empty
                <p class="text-muted text-center mb-0">Belum ada anggota tim</p>
                @endforelse
            </div>
        </div>
    </div>
    
    <div class="col-12 col-lg-8">
        <!-- Tasks -->
        <div class="card animate-fadeIn mb-4">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-tasks text-primary"></i>
                    Task
                </h3>
                @if(auth()->user()->hasRole(['admin', 'bph', 'kabinet']))
                <a href="{{ route('tasks.create') }}?program_id={{ $program->id }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i> Tambah Task
                </a>
                @endif
            </div>
            <div class="card-body p-0">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Task</th>
                                <th>Assignee</th>
                                <th>Status</th>
                                <th>Progress</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($program->tasks as $task)
                            <tr>
                                <td class="fw-semibold">{{ $task->title }}</td>
                                <td>
                                    @if($task->assignee)
                                    <div class="d-flex align-center gap-2">
                                        <img src="{{ $task->assignee->avatar_url }}" alt="" class="avatar-sm">
                                        <span class="fs-sm">{{ $task->assignee->name }}</span>
                                    </div>
                                    @else
                                    <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-{{ $task->status_badge }}">
                                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                    </span>
                                </td>
                                <td style="min-width: 100px;">
                                    <div class="d-flex align-center gap-2">
                                        <div class="progress" style="flex: 1; height: 6px;">
                                            <div class="progress-bar" style="width: {{ $task->progress }}%;"></div>
                                        </div>
                                        <span class="fs-xs">{{ $task->progress }}%</span>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('tasks.show', $task) }}" class="btn btn-sm btn-secondary btn-icon">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    Belum ada task
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Timelines -->
        <div class="card animate-fadeIn">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-calendar text-primary"></i>
                    Timeline
                </h3>
            </div>
            <div class="card-body">
                @forelse($program->timelines as $timeline)
                <div class="d-flex gap-3 {{ !$loop->last ? 'mb-3 pb-3' : '' }}" style="{{ !$loop->last ? 'border-bottom: 1px solid var(--border-color);' : '' }}">
                    <div style="width: 4px; background: {{ $timeline->color }}; border-radius: 4px;"></div>
                    <div class="flex-1">
                        <div class="fw-semibold">{{ $timeline->title }}</div>
                        <div class="text-muted fs-xs">
                            {{ $timeline->start_date->format('d M') }} - {{ $timeline->end_date->format('d M Y') }}
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-muted text-center mb-0">Belum ada timeline</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@if(auth()->user()->hasRole(['admin', 'bph', 'kabinet']))
<!-- Add Member Modal -->
<div class="modal fade" id="addMemberModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('programs.members.add', $program) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Anggota Tim</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">User</label>
                        <select name="user_id" class="form-control form-select" required>
                            <option value="">-- Pilih User --</option>
                            @foreach(\App\Models\User::active()->get() as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-control form-select" required>
                            <option value="member">Member</option>
                            <option value="leader">Leader</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
