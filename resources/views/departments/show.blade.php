@extends('layouts.app')

@section('title', 'Detail Departemen')
@section('page-title', $department->name)

@section('content')
<div class="row">
    <div class="col-12 col-lg-4">
        <div class="card animate-fadeIn">
            <div class="card-body">
                <div class="d-flex align-center gap-3 mb-3">
                    <div class="stat-icon primary" style="width: 56px; height: 56px; font-size: 1.5rem;">
                        <i class="fas fa-building"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">{{ $department->name }}</h4>
                        <span class="badge badge-{{ $department->status === 'active' ? 'success' : 'secondary' }}">
                            {{ ucfirst($department->status) }}
                        </span>
                    </div>
                </div>
                
                @if($department->description)
                <p class="text-muted fs-sm">{{ $department->description }}</p>
                @endif
                
                <hr class="my-3">
                
                <div class="d-flex justify-between mb-2">
                    <span class="text-muted">Kabinet</span>
                    <span class="fw-semibold">{{ $department->cabinet?->name ?? '-' }}</span>
                </div>
                <div class="d-flex justify-between mb-2">
                    <span class="text-muted">Total Anggota</span>
                    <span class="fw-semibold">{{ $department->users->count() }} orang</span>
                </div>
                <div class="d-flex justify-between mb-2">
                    <span class="text-muted">Total Proker</span>
                    <span class="fw-semibold">{{ $department->programs->count() }} proker</span>
                </div>
                
                <div class="d-flex gap-2 mt-4">
                    <a href="{{ route('departments.edit', $department) }}" class="btn btn-primary flex-1">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('departments.index') }}" class="btn btn-secondary flex-1">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-lg-8">
        <!-- Members -->
        <div class="card animate-fadeIn mb-4">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-users text-primary"></i>
                    Anggota Departemen
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($department->users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-center gap-2">
                                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="avatar-sm">
                                        <span class="fw-semibold">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge badge-{{ $user->role?->name === 'kabinet' ? 'info' : 'secondary' }}">
                                        {{ ucfirst($user->role?->name ?? '-') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $user->status === 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    Belum ada anggota
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Programs -->
        <div class="card animate-fadeIn">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-project-diagram text-primary"></i>
                    Program Kerja
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama Proker</th>
                                <th>Periode</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($department->programs as $program)
                            <tr>
                                <td>
                                    <a href="{{ route('programs.show', $program) }}" class="fw-semibold">
                                        {{ $program->name }}
                                    </a>
                                </td>
                                <td class="fs-sm text-muted">
                                    {{ $program->start_date->format('d M') }} - {{ $program->end_date->format('d M Y') }}
                                </td>
                                <td>
                                    <span class="badge badge-{{ $program->status === 'completed' ? 'success' : ($program->status === 'active' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($program->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">
                                    Belum ada proker
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
