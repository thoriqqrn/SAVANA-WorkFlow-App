@extends('layouts.app')

@section('title', 'Program Kerja')
@section('page-title', 'Program Kerja')

@section('content')
<div class="card animate-fadeIn">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-project-diagram text-primary"></i>
            Daftar Program Kerja
        </h3>
        @if(auth()->user()->hasRole(['admin', 'bph', 'kabinet']))
        <a href="{{ route('programs.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Tambah Program
        </a>
        @endif
    </div>
    <div class="card-body p-0">
        <div class="table-container">
            <table class="table datatable">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Departemen</th>
                        <th>Periode</th>
                        <th>Task</th>
                        <th>Progress</th>
                        <th>Status</th>
                        <th class="no-sort" style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($programs as $program)
                    <tr>
                        <td class="fw-semibold">{{ $program->name }}</td>
                        <td class="fs-sm">{{ $program->department->name ?? '-' }}</td>
                        <td class="fs-sm">
                            {{ $program->start_date->format('d M') }} - {{ $program->end_date->format('d M Y') }}
                        </td>
                        <td>
                            <span class="badge badge-info">{{ $program->tasks_count }} task</span>
                        </td>
                        <td style="min-width: 120px;">
                            @php $progress = $program->progress_percentage @endphp
                            <div class="d-flex align-center gap-2">
                                <div class="progress" style="flex: 1; height: 6px;">
                                    <div class="progress-bar {{ $progress >= 100 ? 'success' : '' }}" style="width: {{ $progress }}%;"></div>
                                </div>
                                <span class="fs-xs text-muted" style="width: 35px;">{{ $progress }}%</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-{{ $program->status === 'completed' ? 'success' : ($program->status === 'active' ? 'warning' : ($program->status === 'cancelled' ? 'danger' : 'secondary')) }}">
                                {{ ucfirst($program->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('programs.show', $program) }}" class="btn btn-sm btn-secondary btn-icon" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(auth()->user()->hasRole(['admin', 'bph', 'kabinet']))
                                <a href="{{ route('programs.edit', $program) }}" class="btn btn-sm btn-primary btn-icon" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif
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
