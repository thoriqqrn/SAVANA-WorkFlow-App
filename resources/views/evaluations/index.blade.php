@extends('layouts.app')

@section('title', 'Evaluasi Staff')
@section('page-title', 'Evaluasi Staff')

@section('content')
<!-- Grade Legend -->
<div class="card animate-fadeIn mb-4">
    <div class="card-body py-3">
        <div class="d-flex align-center gap-4 flex-wrap">
            <span class="text-muted fw-semibold">Grade:</span>
            @foreach($gradeParams as $grade)
            <div class="d-flex align-center gap-2">
                <span class="badge" style="background: {{ $grade->color }};">{{ $grade->grade }}</span>
                <span class="fs-sm">{{ $grade->label }} ({{ $grade->min_score }}-{{ $grade->max_score }})</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="card animate-fadeIn">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-star text-warning"></i>
            Daftar Staff & Evaluasi
        </h3>
        <a href="{{ route('evaluations.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Beri Evaluasi
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-container">
            <table class="table datatable">
                <thead>
                    <tr>
                        <th>Staff</th>
                        <th>Departemen</th>
                        <th>Periode Terakhir</th>
                        <th>Nilai Kabinet</th>
                        <th>Nilai BPH</th>
                        <th>Final Score</th>
                        <th>Grade</th>
                        <th class="no-sort" style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($staffMembers as $staff)
                    <tr>
                        <td>
                            <div class="d-flex align-center gap-2">
                                <img src="{{ $staff->avatar_url }}" alt="{{ $staff->name }}" class="avatar-sm">
                                <span class="fw-semibold">{{ $staff->name }}</span>
                            </div>
                        </td>
                        <td class="fs-sm">{{ $staff->department?->name ?? '-' }}</td>
                        <td>{{ $staff->latest_period ?? '-' }}</td>
                        <td>
                            @if($staff->latest_evaluation)
                                @if($staff->latest_evaluation['kabinet_score'] > 0)
                                    <span class="badge badge-info">{{ $staff->latest_evaluation['kabinet_score'] }}</span>
                                @else
                                    <span class="badge badge-secondary">Pending</span>
                                @endif
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($staff->latest_evaluation)
                                @if($staff->latest_evaluation['bph_score'] > 0)
                                    <span class="badge badge-primary">{{ $staff->latest_evaluation['bph_score'] }}</span>
                                @else
                                    <span class="badge badge-secondary">Pending</span>
                                @endif
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($staff->latest_evaluation)
                                <span class="fw-bold">{{ $staff->latest_evaluation['final_score'] }}</span>
                                @if(!$staff->latest_evaluation['is_complete'])
                                    <i class="fas fa-clock text-warning ms-1" title="Menunggu evaluasi lengkap"></i>
                                @endif
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($staff->latest_evaluation && $staff->latest_evaluation['grade'])
                                <span class="badge" style="background: {{ $staff->latest_evaluation['grade']->color }};">
                                    {{ $staff->latest_evaluation['grade']->grade }} - {{ $staff->latest_evaluation['grade']->label }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('evaluations.show', $staff) }}" class="btn btn-sm btn-secondary btn-icon" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('evaluations.create', ['user_id' => $staff->id]) }}" class="btn btn-sm btn-primary btn-icon" title="Beri Evaluasi">
                                    <i class="fas fa-star"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
