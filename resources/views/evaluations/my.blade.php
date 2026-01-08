@extends('layouts.app')

@section('title', 'Nilai Saya')
@section('page-title', 'Nilai Saya')

@section('content')
@php $user = auth()->user(); @endphp

<div class="row">
    <div class="col-12 col-lg-4">
        <!-- Profile Card -->
        <div class="card animate-fadeIn mb-4">
            <div class="card-body text-center">
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="avatar-xl mb-3">
                <h4 class="mb-1">{{ $user->name }}</h4>
                <span class="badge badge-info">{{ $user->department?->name ?? 'No Department' }}</span>
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
                    <span class="fs-sm">{{ $grade->label }}</span>
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
                @if($combined && $combined['grade'])
                <span class="badge" style="background: {{ $combined['grade']->color }};">
                    {{ $combined['grade']->grade }} - {{ $combined['grade']->label }}
                </span>
                @endif
            </div>
            <div class="card-body">
                <!-- Combined Score -->
                @if($combined)
                <div class="row mb-4 text-center">
                    <div class="col-4">
                        <div class="text-muted fs-sm">Dari Kabinet</div>
                        <div class="h4 mb-0 {{ $combined['kabinet_score'] ? '' : 'text-muted' }}">
                            {{ $combined['kabinet_score'] ?: 'Pending' }}
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="text-muted fs-sm">Dari BPH</div>
                        <div class="h4 mb-0 {{ $combined['bph_score'] ? '' : 'text-muted' }}">
                            {{ $combined['bph_score'] ?: 'Pending' }}
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="text-muted fs-sm">Nilai Akhir</div>
                        <div class="h3 mb-0" style="color: {{ $combined['grade']?->color ?? 'inherit' }};">
                            {{ $combined['final_score'] }}
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Detail per Evaluator -->
                @foreach($evals as $eval)
                <div class="p-3 mb-3" style="background: var(--gray-50); border-radius: 8px;">
                    <div class="d-flex align-center gap-2 mb-3">
                        <span class="badge badge-{{ $eval->evaluator_type === 'bph' ? 'primary' : 'info' }}">
                            {{ strtoupper($eval->evaluator_type) }}
                        </span>
                        <span class="text-muted fs-sm">{{ $eval->created_at->format('d M Y') }}</span>
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
                    
                    @if($eval->notes)
                    <div class="mt-3 p-2" style="background: var(--bg-primary); border-radius: 6px;">
                        <div class="text-muted fs-xs mb-1">Feedback:</div>
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
                    <p class="empty-state-text">Anda belum memiliki evaluasi dari Kabinet atau BPH.</p>
                </div>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection
