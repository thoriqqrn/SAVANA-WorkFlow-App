@extends('layouts.app')

@section('title', 'Timeline')
@section('page-title', 'Timeline')

@section('content')
<div class="card animate-fadeIn">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-calendar-alt text-primary"></i>
            Semua Timeline
        </h3>
        @if(auth()->user()->hasRole(['admin', 'bph', 'kabinet']))
        <a href="{{ route('timelines.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Tambah Timeline
        </a>
        @endif
    </div>
    <div class="card-body p-0">
        <div class="table-container">
            <table class="table datatable">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Tipe</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        @if(auth()->user()->hasRole(['admin', 'bph', 'kabinet']))
                        <th class="no-sort" style="width: 100px;">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($timelines as $timeline)
                    <tr>
                        <td>
                            <div class="d-flex align-center gap-2">
                                <div style="width: 12px; height: 12px; background: {{ $timeline->color }}; border-radius: 50%;"></div>
                                <span class="fw-semibold">{{ $timeline->title }}</span>
                            </div>
                            @if($timeline->description)
                            <div class="text-muted fs-xs">{{ Str::limit($timeline->description, 50) }}</div>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-{{ $timeline->type === 'global' ? 'primary' : ($timeline->type === 'department' ? 'info' : 'secondary') }}">
                                {{ ucfirst($timeline->type) }}
                            </span>
                            @if($timeline->department)
                                <div class="fs-xs text-muted">{{ $timeline->department->name }}</div>
                            @endif
                            @if($timeline->program)
                                <div class="fs-xs text-muted">{{ $timeline->program->name }}</div>
                            @endif
                        </td>
                        <td class="fs-sm">
                            {{ $timeline->start_date->format('d M') }} - {{ $timeline->end_date->format('d M Y') }}
                        </td>
                        <td>
                            @if($timeline->end_date->isPast())
                                <span class="badge badge-secondary">Selesai</span>
                            @elseif($timeline->start_date->isFuture())
                                <span class="badge badge-info">Akan Datang</span>
                            @else
                                <span class="badge badge-success">Berlangsung</span>
                            @endif
                        </td>
                        @if(auth()->user()->hasRole(['admin', 'bph', 'kabinet']))
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('timelines.edit', $timeline) }}" class="btn btn-sm btn-primary btn-icon" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('timelines.destroy', $timeline) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger btn-icon" data-confirm-delete="{{ $timeline->title }}" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
