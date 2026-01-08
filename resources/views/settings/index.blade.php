@extends('layouts.app')

@section('title', 'Pengaturan')
@section('page-title', 'Pengaturan Aplikasi')

@push('styles')
<style>
.color-picker-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 12px;
    margin-top: 8px;
}
.color-option {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    cursor: pointer;
    border: 3px solid transparent;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
}
.color-option:hover {
    transform: scale(1.1);
}
.color-option.selected {
    border-color: var(--text-primary);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}
.color-option i {
    color: white;
    font-size: 1.25rem;
    display: none;
}
.color-option.selected i {
    display: block;
}
.color-preview {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-top: 12px;
    padding: 12px 16px;
    background: var(--gray-100);
    border-radius: 8px;
}
.color-preview-box {
    width: 32px;
    height: 32px;
    border-radius: 8px;
}
</style>
@endpush

@section('content')
<div class="row justify-center">
    <div class="col-12 col-lg-8">
        <div class="card animate-fadeIn">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-cog text-primary"></i>
                    Pengaturan
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('settings.update', 'general') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label for="app_name" class="form-label">Nama Aplikasi</label>
                        <input type="text" id="app_name" name="app_name" class="form-control" value="{{ $settings['app_name']?->value ?? 'SAVANA' }}">
                        <small class="text-muted">Nama yang ditampilkan di sidebar dan tab browser</small>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Warna Tema</label>
                        <div class="color-picker-grid">
                            @php
                                $colors = [
                                    'purple' => '#7C3AED',
                                    'blue' => '#3B82F6',
                                    'green' => '#10B981',
                                    'red' => '#EF4444',
                                    'orange' => '#F59E0B',
                                    'pink' => '#EC4899',
                                    'indigo' => '#6366F1',
                                    'teal' => '#14B8A6',
                                    'cyan' => '#06B6D4',
                                    'rose' => '#F43F5E',
                                    'amber' => '#F59E0B',
                                    'slate' => '#64748B',
                                ];
                                $currentColor = $settings['theme_color']?->value ?? 'purple';
                            @endphp
                            @foreach($colors as $name => $hex)
                            <label class="color-option {{ $currentColor === $name ? 'selected' : '' }}" 
                                   style="background: {{ $hex }};"
                                   onclick="selectColor('{{ $name }}', '{{ $hex }}')">
                                <input type="radio" name="theme_color" value="{{ $name }}" style="display:none;" {{ $currentColor === $name ? 'checked' : '' }}>
                                <i class="fas fa-check"></i>
                            </label>
                            @endforeach
                        </div>
                        <div class="color-preview">
                            <div class="color-preview-box" id="colorPreviewBox" style="background: {{ $colors[$currentColor] ?? '#7C3AED' }};"></div>
                            <span>Warna tema saat ini: <strong id="colorPreviewName">{{ ucfirst($currentColor) }}</strong></span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="organization_name" class="form-label">Nama Organisasi</label>
                        <input type="text" id="organization_name" name="organization_name" class="form-control" value="{{ $settings['organization_name']?->value ?? '' }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="evaluation_period" class="form-label">Periode Evaluasi</label>
                        <select id="evaluation_period" name="evaluation_period" class="form-control form-select">
                            <option value="monthly" {{ ($settings['evaluation_period']?->value ?? '') === 'monthly' ? 'selected' : '' }}>Bulanan</option>
                            <option value="quarterly" {{ ($settings['evaluation_period']?->value ?? 'quarterly') === 'quarterly' ? 'selected' : '' }}>Per Kuartal</option>
                            <option value="semester" {{ ($settings['evaluation_period']?->value ?? '') === 'semester' ? 'selected' : '' }}>Per Semester</option>
                            <option value="yearly" {{ ($settings['evaluation_period']?->value ?? '') === 'yearly' ? 'selected' : '' }}>Tahunan</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Simpan Pengaturan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function selectColor(name, hex) {
    document.querySelectorAll('.color-option').forEach(el => el.classList.remove('selected'));
    event.currentTarget.classList.add('selected');
    document.getElementById('colorPreviewBox').style.background = hex;
    document.getElementById('colorPreviewName').textContent = name.charAt(0).toUpperCase() + name.slice(1);
}
</script>
@endpush
