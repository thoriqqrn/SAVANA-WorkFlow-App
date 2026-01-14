@extends('layouts.app')

@section('title', 'Beri Evaluasi')
@section('page-title', 'Beri Evaluasi Staff')

@push('styles')
<style>
.staff-info-card {
    background: linear-gradient(135deg, var(--primary-light), white);
    border-radius: 16px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.staff-avatar-lg {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid white;
    box-shadow: var(--shadow);
}

.staff-details h4 {
    margin: 0 0 4px;
    font-weight: 600;
}

.staff-details p {
    margin: 0;
    color: var(--text-muted);
    font-size: 0.9rem;
}

.period-badge {
    background: var(--primary);
    color: white;
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 500;
    margin-left: auto;
}

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
.star-rating label:hover {
    transform: scale(1.2);
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
.criteria-card .criteria-name {
    font-weight: 600;
    margin-bottom: 8px;
}
.criteria-card .criteria-desc {
    font-size: 0.85rem;
    color: var(--gray-500);
    margin-bottom: 12px;
}
</style>
@endpush

@section('content')
<div class="row justify-center">
    <div class="col-12 col-lg-8">
        <!-- Staff Info -->
        <div class="staff-info-card animate-fadeIn">
            <img src="{{ $staff->avatar_url }}" alt="{{ $staff->name }}" class="staff-avatar-lg">
            <div class="staff-details">
                <h4>{{ $staff->name }}</h4>
                <p>{{ $staff->department?->name ?? '-' }} • {{ $staff->email }}</p>
            </div>
            <div class="period-badge">
                <i class="fas fa-calendar"></i>
                {{ \App\Models\Evaluation::getMonthLabel($month) }}
            </div>
        </div>

        <div class="card animate-fadeIn">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-star text-warning"></i>
                    Form Evaluasi ({{ strtoupper($evaluatorType) }})
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('evaluations.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $staff->id }}">
                    <input type="hidden" name="period" value="{{ $month }}">
                    
                    <h5 class="mb-3">
                        <i class="fas fa-clipboard-check text-primary"></i>
                        Penilaian (1-5)
                    </h5>
                    
                    <!-- Grade Legend -->
                    <div class="d-flex gap-3 flex-wrap mb-4">
                        @foreach($gradeParams as $grade)
                        <span class="badge fs-xs" style="background: {{ $grade->color }};">{{ $grade->min_score }}-{{ $grade->max_score }} = {{ $grade->label }}</span>
                        @endforeach
                    </div>
                    
                    @php
                        $criteria = [
                            'kehadiran' => ['name' => 'Kehadiran', 'desc' => 'Tingkat kehadiran di rapat dan kegiatan organisasi'],
                            'kedisiplinan' => ['name' => 'Kedisiplinan', 'desc' => 'Ketepatan waktu dan kepatuhan terhadap aturan'],
                            'tanggung_jawab' => ['name' => 'Tanggung Jawab', 'desc' => 'Penyelesaian tugas yang diberikan dengan baik'],
                            'kerjasama' => ['name' => 'Kerjasama', 'desc' => 'Kemampuan bekerja sama dalam tim'],
                            'inisiatif' => ['name' => 'Inisiatif', 'desc' => 'Proaktif dan memberikan ide kreatif'],
                            'komunikasi' => ['name' => 'Komunikasi', 'desc' => 'Skill komunikasi dan koordinasi dengan tim'],
                        ];
                    @endphp
                    
                    <div class="row">
                        @foreach($criteria as $key => $crit)
                        <div class="col-12 col-md-6">
                            <div class="criteria-card">
                                <div class="criteria-name">{{ $crit['name'] }}</div>
                                <div class="criteria-desc">{{ $crit['desc'] }}</div>
                                <div class="star-rating" data-input="{{ $key }}">
                                    @for($i = 5; $i >= 1; $i--)
                                    <input type="radio" name="{{ $key }}" id="{{ $key }}_{{ $i }}" value="{{ $i }}" {{ old($key, 3) == $i ? 'checked' : '' }} required>
                                    <label for="{{ $key }}_{{ $i }}" title="{{ $i }}">
                                        <i class="fas fa-circle"></i>
                                    </label>
                                    @endfor
                                    <span class="star-value" id="{{ $key }}_value">{{ old($key, 3) }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="form-group mt-3">
                        <label for="notes" class="form-label">Catatan / Feedback</label>
                        <textarea id="notes" name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" placeholder="Tuliskan feedback atau catatan untuk staff...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Simpan Evaluasi
                        </button>
                        <a href="{{ route('evaluations.department', ['department' => $staff->department_id, 'month' => $month]) }}" class="btn btn-secondary">
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
        const name = this.name;
        document.getElementById(name + '_value').textContent = this.value;
    });
});
</script>
@endpush
