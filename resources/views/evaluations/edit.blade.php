@extends('layouts.app')

@section('title', 'Edit Evaluasi')
@section('page-title', 'Edit Evaluasi')

@push('styles')
<style>
.star-rating {
    display: flex;
    gap: 8px;
}
.star-rating input {
    display: none;
}
.star-rating label {
    cursor: pointer;
    font-size: 24px;
    color: var(--gray-300);
    transition: color 0.2s, transform 0.2s;
}
.star-rating label:hover,
.star-rating label:hover ~ label,
.star-rating input:checked ~ label {
    color: #F59E0B;
}
.star-rating .star-value {
    font-weight: bold;
    min-width: 30px;
    text-align: center;
}
.criteria-card {
    background: var(--gray-50);
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 12px;
}
</style>
@endpush

@section('content')
<div class="row justify-center">
    <div class="col-12 col-lg-8">
        <div class="card animate-fadeIn">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-edit text-primary"></i>
                    Edit Evaluasi ({{ strtoupper($evaluation->evaluator_type) }})
                </h3>
            </div>
            <div class="card-body">
                <div class="d-flex align-center gap-3 mb-4 p-3" style="background: var(--gray-50); border-radius: 8px;">
                    <img src="{{ $evaluation->user->avatar_url }}" alt="{{ $evaluation->user->name }}" class="avatar-lg">
                    <div>
                        <h5 class="mb-1">{{ $evaluation->user->name }}</h5>
                        <p class="text-muted mb-0">{{ $evaluation->user->department?->name ?? 'No Department' }}</p>
                        <span class="badge badge-secondary">Periode: {{ $evaluation->period }}</span>
                    </div>
                </div>
                
                <form action="{{ route('evaluations.update', $evaluation) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <h5 class="mb-3">
                        <i class="fas fa-clipboard-check text-primary"></i>
                        Penilaian (1-5)
                    </h5>
                    
                    @php
                        $criteria = [
                            'kehadiran' => ['name' => 'Kehadiran', 'desc' => 'Tingkat kehadiran di rapat dan kegiatan'],
                            'kedisiplinan' => ['name' => 'Kedisiplinan', 'desc' => 'Ketepatan waktu dan kepatuhan aturan'],
                            'tanggung_jawab' => ['name' => 'Tanggung Jawab', 'desc' => 'Penyelesaian tugas dengan baik'],
                            'kerjasama' => ['name' => 'Kerjasama', 'desc' => 'Kemampuan bekerja dalam tim'],
                            'inisiatif' => ['name' => 'Inisiatif', 'desc' => 'Proaktif dan ide kreatif'],
                            'komunikasi' => ['name' => 'Komunikasi', 'desc' => 'Skill komunikasi dan koordinasi'],
                        ];
                    @endphp
                    
                    <div class="row">
                        @foreach($criteria as $key => $crit)
                        <div class="col-12 col-md-6">
                            <div class="criteria-card">
                                <div class="fw-semibold mb-1">{{ $crit['name'] }}</div>
                                <div class="text-muted fs-xs mb-2">{{ $crit['desc'] }}</div>
                                <div class="star-rating" data-input="{{ $key }}">
                                    @for($i = 5; $i >= 1; $i--)
                                    <input type="radio" name="{{ $key }}" id="{{ $key }}_{{ $i }}" value="{{ $i }}" {{ old($key, $evaluation->$key) == $i ? 'checked' : '' }} required>
                                    <label for="{{ $key }}_{{ $i }}" title="{{ $i }}">
                                        <i class="fas fa-circle"></i>
                                    </label>
                                    @endfor
                                    <span class="star-value" id="{{ $key }}_value">{{ old($key, $evaluation->$key) }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="form-group mt-3">
                        <label for="notes" class="form-label">Catatan / Feedback</label>
                        <textarea id="notes" name="notes" class="form-control" rows="3">{{ old('notes', $evaluation->notes) }}</textarea>
                    </div>
                    
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Update
                        </button>
                        <a href="{{ route('evaluations.show', ['user' => $evaluation->user_id]) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                            Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.star-rating input').forEach(input => {
    input.addEventListener('change', function() {
        document.getElementById(this.name + '_value').textContent = this.value;
    });
});
</script>
@endpush
