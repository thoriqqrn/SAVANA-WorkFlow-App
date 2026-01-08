@extends('layouts.app')

@section('title', 'Timeline Global')
@section('page-title', 'Timeline Global Organisasi')

@section('content')
<div class="card animate-fadeIn">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-globe text-primary"></i>
            Timeline Global
        </h3>
        @if(auth()->user()->hasRole(['admin', 'bph']))
        <a href="{{ route('timelines.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Tambah
        </a>
        @endif
    </div>
    <div class="card-body">
        @if($timelines->count() > 0)
        <div class="timeline-container">
            @foreach($timelines as $timeline)
            <div class="d-flex gap-3 mb-4 pb-4" style="border-bottom: 1px solid var(--border-color);">
                <div style="width: 4px; background: {{ $timeline->color }}; border-radius: 4px;"></div>
                <div class="flex-1">
                    <div class="d-flex justify-between align-center mb-2">
                        <h5 class="mb-0">{{ $timeline->title }}</h5>
                        @if($timeline->end_date->isPast())
                            <span class="badge badge-secondary">Selesai</span>
                        @elseif($timeline->start_date->isFuture())
                            <span class="badge badge-info">Akan Datang</span>
                        @else
                            <span class="badge badge-success">Berlangsung</span>
                        @endif
                    </div>
                    @if($timeline->description)
                    <p class="text-muted fs-sm mb-2">{{ $timeline->description }}</p>
                    @endif
                    <div class="text-muted fs-xs">
                        <i class="fas fa-calendar"></i>
                        {{ $timeline->start_date->format('d M Y') }} - {{ $timeline->end_date->format('d M Y') }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-calendar-times"></i>
            </div>
            <h5 class="empty-state-title">Belum ada timeline</h5>
            <p class="empty-state-text">Timeline global organisasi belum ditambahkan.</p>
        </div>
        @endif
    </div>
</div>
@endsection
