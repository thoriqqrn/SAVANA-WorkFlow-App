@extends('layouts.app')

@section('title', 'Proker Saya')
@section('page-title', 'Proker Saya')

@section('content')
<div class="card animate-fadeIn">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-project-diagram text-primary"></i>
            Daftar Proker Saya
        </h3>
    </div>
    <div class="card-body p-0">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama Proker</th>
                        <th>Departemen</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Progress</th>
                        <th>Periode</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($programs as $program)
                    @php
                        $user = auth()->user();
                        $isPic = $program->pics->contains('id', $user->id);
                        $isMember = $program->members->contains('id', $user->id);
                    @endphp
                    <tr>
                        <td>
                            <a href="{{ route('programs.show', $program) }}" class="fw-semibold">
                                {{ $program->name }}
                            </a>
                        </td>
                        <td class="fs-sm">{{ $program->department?->name ?? '-' }}</td>
                        <td>
                            @if($isPic)
                            <span class="badge badge-primary"><i class="fas fa-star"></i> PIC</span>
                            @elseif($isMember)
                            <span class="badge badge-info">Member</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $statusBadge = match($program->status) {
                                    'planning' => 'secondary',
                                    'active' => 'warning',
                                    'completed' => 'success',
                                    'cancelled' => 'danger',
                                    default => 'secondary',
                                };
                            @endphp
                            <span class="badge badge-{{ $statusBadge }}">{{ ucfirst($program->status) }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-center gap-2">
                                <div class="progress" style="flex: 1; max-width: 100px;">
                                    <div class="progress-bar {{ $program->progress >= 100 ? 'success' : 'primary' }}" style="width: {{ $program->progress }}%;"></div>
                                </div>
                                <span class="fs-sm fw-semibold">{{ $program->progress }}%</span>
                            </div>
                        </td>
                        <td class="fs-sm text-muted">
                            {{ $program->start_date->format('d M') }} - {{ $program->end_date->format('d M Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            Belum ada proker yang Anda ikuti
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
