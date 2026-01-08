@extends('layouts.app')

@section('title', 'Detail Evaluasi - ' . $user->name)
@section('page-title', 'Evaluasi: ' . $user->name)

@section('content')
<div class="row">
    <div class="col-12 col-lg-4">
        <!-- Profile Card -->
        <div class="card animate-fadeIn mb-4">
            <div class="card-body text-center">
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="avatar-xl mb-3">
                <h4 class="mb-1">{{ $user->name }}</h4>
                <p class="text-muted mb-2">{{ $user->email }}</p>
                <span class="badge badge-info">{{ $user->department?->name ?? 'No Department' }}</span>
                
                <hr class="my-3">
                
                <a href="{{ route('evaluations.create', ['user_id' => $user->id]) }}" class="btn btn-primary w-100">
                    <i class="fas fa-star"></i>
                    Beri Evaluasi Baru
                </a>
            </div>
        </div>
        
        <!-- Grade Legend -->
        <div class="card animate-fadeIn">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle text-primary"></i>
                    Keterangan Grade
                </h3>
            </div>
            <div class="card-body">
                @foreach($gradeParams as $grade)
                <div class="d-flex align-center gap-2 {{ !$loop->last ? 'mb-2' : '' }}">
                    <span class="badge" style="background: {{ $grade->color }}; min-width: 40px;">{{ $grade->grade }}</span>
                    <span class="fs-sm">{{ $grade->label }} ({{ $grade->min_score }}-{{ $grade->max_score }})</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <div class="col-12 col-lg-8">
        @forelse($evaluations as $period => $evals)
        @php $combined = $periodScores[$period] ?? null; @endphp
        <div class="card animate-fadeIn mb-4">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-calendar text-primary"></i>
                    Periode: {{ $period }}
                </h3>
                @if($combined)
                <div class="d-flex align-center gap-2">
                    @if($combined['is_complete'])
                    <span class="badge badge-success"><i class="fas fa-check"></i> Lengkap</span>
                    @else
                    <span class="badge badge-warning"><i class="fas fa-clock"></i> Pending</span>
                    @endif
                </div>
                @endif
            </div>
            <div class="card-body">
                <!-- Combined Score -->
                @if($combined)
                <div class="row mb-4">
                    <div class="col-4 text-center">
                        <div class="text-muted fs-sm">Kabinet</div>
                        <div class="h4 mb-0">{{ $combined['kabinet_score'] ?: '-' }}</div>
                    </div>
                    <div class="col-4 text-center">
                        <div class="text-muted fs-sm">BPH</div>
                        <div class="h4 mb-0">{{ $combined['bph_score'] ?: '-' }}</div>
                    </div>
                    <div class="col-4 text-center">
                        <div class="text-muted fs-sm">Final</div>
                        <div class="h3 mb-0">
                            {{ $combined['final_score'] }}
                            @if($combined['grade'])
                            <span class="badge ms-1" style="background: {{ $combined['grade']->color }};">{{ $combined['grade']->grade }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Individual Evaluations -->
                @foreach($evals as $eval)
                <div class="p-3 mb-3" style="background: var(--gray-50); border-radius: 8px;">
                    <div class="d-flex justify-between align-center mb-3">
                        <div>
                            <span class="badge badge-{{ $eval->evaluator_type === 'bph' ? 'primary' : 'info' }}">
                                {{ strtoupper($eval->evaluator_type) }}
                            </span>
                            <span class="ms-2 text-muted fs-sm">oleh {{ $eval->evaluator->name }}</span>
                        </div>
                        <div class="d-flex gap-1">
                            <a href="{{ route('evaluations.edit', $eval) }}" class="btn btn-sm btn-outline-primary btn-icon">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    </div>
                    
                    <div class="row">
                        @php
                            $criteria = [
                                'kehadiran' => 'Kehadiran',
                                'kedisiplinan' => 'Kedisiplinan',
                                'tanggung_jawab' => 'Tanggung Jawab',
                                'kerjasama' => 'Kerjasama',
                                'inisiatif' => 'Inisiatif',
                                'komunikasi' => 'Komunikasi',
                            ];
                        @endphp
                        @foreach($criteria as $key => $label)
                        <div class="col-6 col-md-4 mb-2">
                            <div class="d-flex justify-between align-center">
                                <span class="fs-sm">{{ $label }}</span>
                                <div class="d-flex align-center gap-1">
                                    @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-circle fs-xs {{ $i <= $eval->$key ? 'text-warning' : 'text-muted' }}" style="opacity: {{ $i <= $eval->$key ? 1 : 0.3 }};"></i>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="d-flex justify-between align-center mt-3 pt-3" style="border-top: 1px solid var(--border-color);">
                        <span class="fw-semibold">Total Score</span>
                        <span class="h5 mb-0" style="color: {{ $eval->grade_color }};">{{ $eval->total_score }}</span>
                    </div>
                    
                    @if($eval->notes)
                    <div class="mt-3 p-2" style="background: var(--bg-primary); border-radius: 6px;">
                        <div class="text-muted fs-xs mb-1">Catatan:</div>
                        <div class="fs-sm">{{ $eval->notes }}</div>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @empty
        <div class="card animate-fadeIn">
            <div class="card-body">
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <h5 class="empty-state-title">Belum Ada Evaluasi</h5>
                    <p class="empty-state-text">Staff ini belum memiliki evaluasi.</p>
                    <a href="{{ route('evaluations.create', ['user_id' => $user->id]) }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Beri Evaluasi
                    </a>
                </div>
            </div>
        </div>
        @endforelse
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('evaluations.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>
@endsection
