@extends('layouts.app')

@section('title', $department->name . ' - Evaluasi Staff')
@section('page-title', 'Evaluasi Staff')

@push('styles')
<style>
.breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 1.5rem;
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

.staff-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.month-filter {
    display: flex;
    align-items: center;
    gap: 10px;
}

.month-filter label {
    font-size: 0.9rem;
    color: var(--text-muted);
}

.month-select {
    padding: 8px 16px;
    border-radius: 8px;
    border: 1px solid var(--border-color);
    background: var(--bg-card);
    font-size: 0.9rem;
    cursor: pointer;
}

.staff-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1rem;
}

.staff-card {
    background: var(--bg-card);
    border-radius: 16px;
    padding: 1.25rem;
    border: 1px solid var(--border-color);
    box-shadow: var(--shadow), var(--shadow-primary);
    transition: all 0.3s ease;
}

.staff-card:hover {
    box-shadow: var(--shadow-lg), 0 8px 24px -4px var(--primary-light);
}

.staff-card.evaluated {
    border-left: 4px solid var(--success);
}

.staff-header-row {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 1rem;
}

.staff-avatar {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid var(--primary-light);
}

.staff-info {
    flex: 1;
}

.staff-name {
    font-weight: 600;
    font-size: 1rem;
    margin-bottom: 2px;
}

.staff-email {
    font-size: 0.8rem;
    color: var(--text-muted);
}

.staff-scores {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 8px;
    margin-bottom: 1rem;
    padding: 12px;
    background: var(--gray-50);
    border-radius: 10px;
}

.score-item {
    display: flex;
    justify-content: space-between;
    font-size: 0.8rem;
}

.score-label {
    color: var(--text-muted);
}

.score-value {
    font-weight: 600;
}

.score-value.pending {
    color: var(--warning);
}

.score-value.done {
    color: var(--success);
}

.final-score {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px;
    background: linear-gradient(135deg, var(--primary-light), white);
    border-radius: 10px;
    margin-bottom: 1rem;
}

.final-label {
    font-size: 0.85rem;
    color: var(--text-secondary);
}

.final-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--primary);
}

.grade-badge {
    padding: 4px 12px;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.9rem;
}

.staff-actions {
    display: flex;
    gap: 8px;
}

.staff-actions .btn {
    flex: 1;
}

.no-evaluation {
    text-align: center;
    padding: 1rem;
    color: var(--text-muted);
    font-size: 0.85rem;
}

.status-badge {
    font-size: 0.7rem;
    padding: 3px 8px;
    border-radius: 6px;
    font-weight: 500;
}

.status-badge.evaluated {
    background: var(--success-light);
    color: var(--success);
}

.status-badge.pending {
    background: var(--warning-light);
    color: var(--warning);
}

@media (max-width: 768px) {
    .staff-grid {
        grid-template-columns: 1fr;
    }
    
    .staff-header {
        flex-direction: column;
        align-items: stretch;
    }
}
</style>
@endpush

@section('content')
<!-- Breadcrumb -->
<nav class="breadcrumb">
    <a href="{{ route('evaluations.index') }}">
        <i class="fas fa-star"></i> Evaluasi
    </a>
    <span class="separator">/</span>
    <span class="current">{{ $department->name }}</span>
</nav>

<!-- Header -->
<div class="staff-header">
    <h3 style="font-size: 1.1rem; font-weight: 600; margin: 0;">
        <i class="fas fa-users text-primary"></i>
        Staff {{ $department->name }}
    </h3>
    
    <div class="month-filter">
        <label><i class="fas fa-calendar"></i> Bulan:</label>
        <select class="month-select" onchange="changeMonth(this.value)">
            @foreach($availableMonths as $value => $label)
                <option value="{{ $value }}" {{ $month === $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
</div>

<!-- Staff Grid -->
<div class="staff-grid">
    @forelse($staffMembers as $staff)
        <div class="staff-card {{ $staff->has_evaluated ? 'evaluated' : '' }}">
            <div class="staff-header-row">
                <img src="{{ $staff->avatar_url }}" alt="{{ $staff->name }}" class="staff-avatar">
                <div class="staff-info">
                    <div class="staff-name">{{ $staff->name }}</div>
                    <div class="staff-email">{{ $staff->email }}</div>
                </div>
                @if($staff->has_evaluated)
                    <span class="status-badge evaluated">
                        <i class="fas fa-check"></i> Dinilai
                    </span>
                @else
                    <span class="status-badge pending">
                        <i class="fas fa-clock"></i> Belum
                    </span>
                @endif
            </div>
            
            @if($staff->evaluation_data)
                <div class="staff-scores">
                    <div class="score-item">
                        <span class="score-label">Kabinet</span>
                        <span class="score-value {{ $staff->evaluation_data['has_kabinet'] ? 'done' : 'pending' }}">
                            {{ $staff->evaluation_data['has_kabinet'] ? number_format($staff->evaluation_data['kabinet_score'], 1) : '-' }}
                        </span>
                    </div>
                    <div class="score-item">
                        <span class="score-label">BPH</span>
                        <span class="score-value {{ $staff->evaluation_data['has_bph'] ? 'done' : 'pending' }}">
                            {{ $staff->evaluation_data['has_bph'] ? number_format($staff->evaluation_data['bph_score'], 1) : '-' }}
                        </span>
                    </div>
                </div>
                
                <div class="final-score">
                    <span class="final-label">Skor Final</span>
                    <div class="d-flex align-center gap-2">
                        <span class="final-value">{{ number_format($staff->evaluation_data['final_score'], 1) }}</span>
                        @if($staff->evaluation_data['grade'])
                            <span class="grade-badge" style="background: {{ $staff->evaluation_data['grade']->color }}20; color: {{ $staff->evaluation_data['grade']->color }};">
                                {{ $staff->evaluation_data['grade']->grade }}
                            </span>
                        @endif
                    </div>
                </div>
            @else
                <div class="no-evaluation">
                    <i class="fas fa-info-circle"></i> Belum ada evaluasi bulan ini
                </div>
            @endif
            
            <div class="staff-actions">
                @if($staff->has_evaluated)
                    <a href="{{ route('evaluations.show', ['user' => $staff]) }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-eye"></i> Lihat Detail
                    </a>
                @else
                    <a href="{{ route('evaluations.create', ['user_id' => $staff->id, 'month' => $month]) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-star"></i> Nilai Sekarang
                    </a>
                @endif
            </div>
        </div>
    @empty
        <div class="card" style="grid-column: 1 / -1;">
            <div class="card-body text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h4>Tidak ada staff</h4>
                <p class="text-muted">Departemen ini belum memiliki staff aktif</p>
            </div>
        </div>
    @endforelse
</div>
@endsection

@push('scripts')
<script>
function changeMonth(month) {
    const url = new URL(window.location.href);
    url.searchParams.set('month', month);
    window.location.href = url.toString();
}
</script>
@endpush
