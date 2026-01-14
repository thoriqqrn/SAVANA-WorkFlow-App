@extends('layouts.app')

@section('title', 'Evaluasi Staff')
@section('page-title', 'Evaluasi Staff')

@push('styles')
<style>
.eval-page {
    display: grid;
    grid-template-columns: 350px 1fr;
    gap: 1.5rem;
}

/* Ranking Card */
.ranking-card {
    background: var(--bg-card);
    border-radius: 16px;
    border: 1px solid var(--border-color);
    box-shadow: var(--shadow), var(--shadow-primary);
    overflow: hidden;
    position: sticky;
    top: 80px;
    max-height: calc(100vh - 100px);
}

.ranking-header {
    background: linear-gradient(135deg, var(--primary), var(--primary-hover));
    color: white;
    padding: 1.25rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.ranking-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 600;
    font-size: 1rem;
}

.ranking-title i {
    font-size: 1.25rem;
}

.month-select {
    background: rgba(255,255,255,0.2);
    border: 1px solid rgba(255,255,255,0.3);
    color: white;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 0.85rem;
    cursor: pointer;
}

.month-select option {
    color: #333;
}

.ranking-body {
    padding: 1rem;
    overflow-y: auto;
    max-height: calc(100vh - 200px);
}

.ranking-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    border-radius: 12px;
    margin-bottom: 8px;
    background: var(--gray-50);
    transition: all 0.2s;
}

.ranking-item:hover {
    background: var(--primary-light);
}

.ranking-item.gold {
    background: linear-gradient(135deg, #FEF3C7, #FDE68A);
    border: 1px solid #F59E0B;
}

.ranking-item.silver {
    background: linear-gradient(135deg, #F3F4F6, #E5E7EB);
    border: 1px solid #9CA3AF;
}

.ranking-item.bronze {
    background: linear-gradient(135deg, #FED7AA, #FDBA74);
    border: 1px solid #F97316;
}

.rank-badge {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.9rem;
    flex-shrink: 0;
}

.rank-badge.gold {
    background: linear-gradient(135deg, #F59E0B, #D97706);
    color: white;
}

.rank-badge.silver {
    background: linear-gradient(135deg, #9CA3AF, #6B7280);
    color: white;
}

.rank-badge.bronze {
    background: linear-gradient(135deg, #F97316, #EA580C);
    color: white;
}

.rank-badge.normal {
    background: var(--gray-200);
    color: var(--text-secondary);
}

.ranking-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}

.ranking-info {
    flex: 1;
    min-width: 0;
}

.ranking-name {
    font-weight: 600;
    font-size: 0.9rem;
    color: var(--text-primary);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.ranking-dept {
    font-size: 0.75rem;
    color: var(--text-muted);
}

.ranking-score {
    text-align: right;
}

.score-value {
    font-weight: 700;
    font-size: 1.1rem;
    color: var(--primary);
}

.score-grade {
    font-size: 0.7rem;
    padding: 2px 6px;
    border-radius: 4px;
    font-weight: 600;
}

.empty-ranking {
    text-align: center;
    padding: 2rem;
    color: var(--text-muted);
}

.empty-ranking i {
    font-size: 2.5rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

/* Department Grid */
.dept-section {
    flex: 1;
}

.dept-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 1rem;
}

.dept-card {
    background: var(--bg-card);
    border-radius: 16px;
    padding: 1.25rem;
    border: 1px solid var(--border-color);
    box-shadow: var(--shadow), var(--shadow-primary);
    transition: all 0.3s ease;
    cursor: pointer;
    text-decoration: none;
    color: inherit;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.dept-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg), 0 8px 24px -4px var(--primary-light);
}

.dept-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: white;
    flex-shrink: 0;
    background: linear-gradient(135deg, #3B82F6, #60A5FA);
}

.dept-info {
    flex: 1;
}

.dept-name {
    font-weight: 600;
    font-size: 0.95rem;
    margin-bottom: 4px;
}

.dept-stats {
    display: flex;
    gap: 12px;
    font-size: 0.8rem;
    color: var(--text-muted);
}

.dept-stat {
    display: flex;
    align-items: center;
    gap: 4px;
}

.dept-stat.done {
    color: var(--success);
}

/* Mobile */
@media (max-width: 1024px) {
    .eval-page {
        grid-template-columns: 1fr;
    }
    
    .ranking-card {
        position: static;
        max-height: none;
    }
    
    .ranking-body {
        max-height: 300px;
    }
}

@media (max-width: 768px) {
    .dept-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@section('content')
<div class="eval-page">
    <!-- Ranking Panel -->
    <div class="ranking-card">
        <div class="ranking-header">
            <div class="ranking-title">
                <i class="fas fa-trophy"></i>
                Best Staff of the Month
            </div>
            <select class="month-select" id="monthSelect" onchange="changeMonth(this.value)">
                @foreach($availableMonths as $value => $label)
                    <option value="{{ $value }}" {{ $month === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="ranking-body" id="rankingBody">
            @if(count($ranking) > 0)
                @foreach($ranking as $index => $staff)
                    @php
                        $rankClass = match($index) {
                            0 => 'gold',
                            1 => 'silver',
                            2 => 'bronze',
                            default => ''
                        };
                        $badgeClass = match($index) {
                            0 => 'gold',
                            1 => 'silver',
                            2 => 'bronze',
                            default => 'normal'
                        };
                    @endphp
                    <div class="ranking-item {{ $rankClass }}">
                        <div class="rank-badge {{ $badgeClass }}">
                            @if($index < 3)
                                <i class="fas fa-crown"></i>
                            @else
                                {{ $index + 1 }}
                            @endif
                        </div>
                        <img src="{{ $staff['avatar_url'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($staff['name']) }}" 
                             alt="" class="ranking-avatar">
                        <div class="ranking-info">
                            <div class="ranking-name">{{ $staff['name'] }}</div>
                            <div class="ranking-dept">{{ $staff['department']['name'] ?? '-' }}</div>
                        </div>
                        <div class="ranking-score">
                            <div class="score-value">{{ number_format($staff['evaluation_score'], 1) }}</div>
                            @if(isset($staff['evaluation_data']['grade']))
                                <span class="score-grade" style="background: {{ $staff['evaluation_data']['grade']['color'] }}20; color: {{ $staff['evaluation_data']['grade']['color'] }};">
                                    {{ $staff['evaluation_data']['grade']['grade'] }}
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-ranking">
                    <i class="fas fa-chart-line"></i>
                    <p>Belum ada data evaluasi<br>untuk bulan ini</p>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Department Cards -->
    <div class="dept-section">
        <h3 class="mb-4" style="font-size: 1.1rem; font-weight: 600;">
            <i class="fas fa-building text-primary"></i>
            Pilih Departemen
        </h3>
        <div class="dept-grid">
            @foreach($departments as $department)
                <a href="{{ route('evaluations.department', ['department' => $department, 'month' => $month]) }}" class="dept-card">
                    <div class="dept-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="dept-info">
                        <div class="dept-name">{{ $department->name }}</div>
                        <div class="dept-stats">
                            <span class="dept-stat">
                                <i class="fas fa-user"></i>
                                {{ $department->users_count ?? 0 }} staff
                            </span>
                            <span class="dept-stat done">
                                <i class="fas fa-check"></i>
                                {{ $department->evaluated_count ?? 0 }} dinilai
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        
        @if($departments->isEmpty())
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-building fa-3x text-muted mb-3"></i>
                    <h4>Belum ada departemen</h4>
                </div>
            </div>
        @endif
    </div>
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
