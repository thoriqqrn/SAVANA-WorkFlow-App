@extends('layouts.app')

@section('title', 'Tambah Timeline')
@section('page-title', 'Tambah Timeline')

@section('content')
<div class="row justify-center">
    <div class="col-12 col-lg-8">
        <div class="card animate-fadeIn">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-calendar-plus text-primary"></i>
                    Form Tambah Timeline
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('timelines.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="title" class="form-label">Judul <span class="text-danger">*</span></label>
                        <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="type" class="form-label">Tipe <span class="text-danger">*</span></label>
                                <select id="type" name="type" class="form-control form-select @error('type') is-invalid @enderror" required>
                                    <option value="global" {{ old('type') === 'global' ? 'selected' : '' }}>Global</option>
                                    <option value="department" {{ old('type') === 'department' ? 'selected' : '' }}>Department</option>
                                    <option value="program" {{ old('type') === 'program' ? 'selected' : '' }}>Program</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="color" class="form-label">Warna</label>
                                <input type="color" id="color" name="color" class="form-control" value="{{ old('color', '#7C3AED') }}" style="height: 42px;">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group" id="departmentField" style="display: none;">
                        <label for="department_id" class="form-label">Departemen</label>
                        <select id="department_id" name="department_id" class="form-control form-select @error('department_id') is-invalid @enderror">
                            <option value="">-- Pilih Departemen --</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('department_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group" id="programField" style="display: none;">
                        <label for="program_id" class="form-label">Program</label>
                        <select id="program_id" name="program_id" class="form-control form-select @error('program_id') is-invalid @enderror">
                            <option value="">-- Pilih Program --</option>
                            @foreach($programs as $program)
                                <option value="{{ $program->id }}" {{ old('program_id') == $program->id ? 'selected' : '' }}>
                                    {{ $program->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('program_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="start_date" class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                                <input type="date" id="start_date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}" required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="end_date" class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                                <input type="date" id="end_date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}" required>
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Simpan
                        </button>
                        <a href="{{ route('timelines.index') }}" class="btn btn-secondary">
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
document.getElementById('type').addEventListener('change', function() {
    const departmentField = document.getElementById('departmentField');
    const programField = document.getElementById('programField');
    
    departmentField.style.display = this.value === 'department' ? 'block' : 'none';
    programField.style.display = this.value === 'program' ? 'block' : 'none';
});

// Trigger on load
document.getElementById('type').dispatchEvent(new Event('change'));
</script>
@endpush
