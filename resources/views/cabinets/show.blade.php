@extends('layouts.app')

@section('title', 'Detail Kabinet')
@section('page-title', $cabinet->name)

@section('content')
<div class="row">
    <div class="col-12 col-lg-4">
        <div class="card animate-fadeIn">
            <div class="card-body">
                <div class="d-flex align-center gap-3 mb-3">
                    <div class="stat-icon primary" style="width: 56px; height: 56px; font-size: 1.5rem;">
                        <i class="fas fa-landmark"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">{{ $cabinet->name }}</h4>
                        <span class="text-muted">{{ $cabinet->year }}</span>
                    </div>
                </div>
                
                <div class="text-center mt-3">
                    @if($cabinet->status === 'active')
                        <span class="badge badge-success" style="font-size: 1rem; padding: 0.5rem 1rem;">
                            <i class="fas fa-star"></i> Kabinet Aktif
                        </span>
                    @else
                        <span class="badge badge-secondary" style="font-size: 1rem; padding: 0.5rem 1rem;">
                            Inactive
                        </span>
                    @endif
                </div>
                
                <hr class="my-3">
                
                <div class="d-flex justify-between mb-2">
                    <span class="text-muted">Total Departemen</span>
                    <span class="fw-semibold">{{ $cabinet->departments->count() }}</span>
                </div>
                <div class="d-flex justify-between mb-2">
                    <span class="text-muted">Total Anggota</span>
                    <span class="fw-semibold">{{ $cabinet->departments->sum(fn($d) => $d->users->count()) }}</span>
                </div>
                
                <div class="d-flex gap-2 mt-4">
                    <a href="{{ route('cabinets.edit', $cabinet) }}" class="btn btn-primary flex-1">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('cabinets.index') }}" class="btn btn-secondary flex-1">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-lg-8">
        <div class="card animate-fadeIn">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-building text-primary"></i>
                    Departemen
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Kepala Departemen</th>
                                <th>Anggota</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cabinet->departments as $department)
                            <tr>
                                <td>
                                    <a href="{{ route('departments.show', $department) }}" class="fw-semibold">
                                        {{ $department->name }}
                                    </a>
                                </td>
                                <td>
                                    @php $head = $department->head @endphp
                                    @if($head)
                                        <div class="d-flex align-center gap-2">
                                            <img src="{{ $head->avatar_url }}" alt="{{ $head->name }}" class="avatar-sm">
                                            <span>{{ $head->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-info">{{ $department->users->count() }} orang</span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $department->status === 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($department->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    Belum ada departemen
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
