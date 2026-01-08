@extends('layouts.app')

@section('title', 'Kumpulan Link')
@section('page-title', 'Kumpulan Link')

@section('content')
<div class="card animate-fadeIn">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-link text-primary"></i>
            Link Berguna
        </h3>
        @if(auth()->user()->hasRole(['admin', 'bph']))
        <a href="{{ route('links.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Tambah Link
        </a>
        @endif
    </div>
    <div class="card-body">
        @forelse($linksByCategory as $categoryKey => $links)
        @php 
            $cat = $categories[$categoryKey] ?? ['name' => 'Lainnya', 'icon' => 'fas fa-link'];
        @endphp
        <div class="mb-4">
            <h5 class="mb-3 d-flex align-center gap-2">
                <i class="{{ $cat['icon'] }} text-primary"></i>
                {{ $cat['name'] }}
            </h5>
            <div class="row">
                @foreach($links as $link)
                <div class="col-12 col-md-6 col-lg-4 mb-3">
                    <div class="link-card">
                        <div class="link-card-icon">
                            <i class="{{ $link->icon }}"></i>
                        </div>
                        <div class="link-card-content">
                            <h6 class="link-card-title">{{ $link->title }}</h6>
                            @if($link->description)
                            <p class="link-card-desc">{{ $link->description }}</p>
                            @endif
                        </div>
                        <div class="link-card-actions">
                            <a href="{{ $link->url }}" target="_blank" class="btn btn-primary btn-sm">
                                <i class="fas fa-external-link-alt"></i> Buka
                            </a>
                            @if(auth()->user()->hasRole(['admin', 'bph']))
                            <a href="{{ route('links.edit', $link) }}" class="btn btn-secondary btn-sm btn-icon">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @empty
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-link"></i>
            </div>
            <h5 class="empty-state-title">Belum Ada Link</h5>
            <p class="empty-state-text">Admin atau BPH belum menambahkan link.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection

@push('styles')
<style>
.link-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 16px;
    height: 100%;
    display: flex;
    flex-direction: column;
    transition: all 0.2s ease;
}
.link-card:hover {
    border-color: var(--primary);
    box-shadow: var(--shadow-purple);
    transform: translateY(-2px);
}
.link-card-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: var(--primary-light);
    color: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    margin-bottom: 12px;
}
.link-card-content {
    flex: 1;
}
.link-card-title {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 4px;
}
.link-card-desc {
    font-size: 0.8rem;
    color: var(--text-muted);
    margin-bottom: 12px;
    line-height: 1.4;
}
.link-card-actions {
    display: flex;
    gap: 8px;
}
</style>
@endpush
