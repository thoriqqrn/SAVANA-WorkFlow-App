@extends('layouts.app')

@section('title', 'Tambah Task')
@section('page-title', 'Tambah Task')

@section('content')
<div class="row justify-center">
    <div class="col-12 col-lg-8">
        <div class="card animate-fadeIn">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-plus text-primary"></i>
                    Form Tambah Task
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('tasks.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="type" value="{{ $type }}">
                    @if($type === 'program' && $typeId)
                        <input type="hidden" name="program_id" value="{{ $typeId }}">
                    @elseif($type === 'department' && $typeId)
                        <input type="hidden" name="department_id" value="{{ $typeId }}">
                    @endif
                    
                    <div class="form-group">
                        <label for="title" class="form-label">Judul Task <span class="text-danger">*</span></label>
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
                    
                    @if(!$typeId)
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="type_select" class="form-label">Tipe Task <span class="text-danger">*</span></label>
                                <select id="type_select" class="form-control form-select" onchange="toggleTypeFields(this.value)">
                                    <option value="program" {{ $type === 'program' ? 'selected' : '' }}>Program</option>
                                    <option value="department" {{ $type === 'department' ? 'selected' : '' }}>Departemen</option>
                                    <option value="global" {{ $type === 'global' ? 'selected' : '' }}>Global</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-12 col-md-6" id="program_field">
                            <div class="form-group">
                                <label for="program_id" class="form-label">Program <span class="text-danger">*</span></label>
                                <select id="program_id" name="program_id" class="form-control form-select @error('program_id') is-invalid @enderror">
                                    <option value="">-- Pilih Program --</option>
                                    @foreach($programs as $program)
                                        <option value="{{ $program->id }}" {{ old('program_id') == $program->id ? 'selected' : '' }}>
                                            {{ $program->name }} ({{ $program->department?->name }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('program_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-12 col-md-6" id="department_field" style="display: none;">
                            <div class="form-group">
                                <label for="department_id" class="form-label">Departemen <span class="text-danger">*</span></label>
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
                        </div>
                    </div>
                    @endif
                    
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="assigned_to" class="form-label">Ditugaskan Kepada</label>
                                <select id="assigned_to" name="assigned_to" class="form-control form-select @error('assigned_to') is-invalid @enderror">
                                    <option value="">-- Pilih Staff --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ ucfirst($user->role?->name ?? '-') }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('assigned_to')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="priority" class="form-label">Prioritas <span class="text-danger">*</span></label>
                                <select id="priority" name="priority" class="form-control form-select @error('priority') is-invalid @enderror" required>
                                    <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Rendah</option>
                                    <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>Sedang</option>
                                    <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>Tinggi</option>
                                </select>
                                @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="deadline" class="form-label">Deadline</label>
                        <input type="date" id="deadline" name="deadline" class="form-control @error('deadline') is-invalid @enderror" value="{{ old('deadline') }}">
                        @error('deadline')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Simpan
                        </button>
                        <a href="{{ route('tasks.index') }}" class="btn btn-secondary">
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
function toggleTypeFields(type) {
    document.getElementById('program_field').style.display = type === 'program' ? 'block' : 'none';
    document.getElementById('department_field').style.display = type === 'department' ? 'block' : 'none';
    
    // Update hidden type field
    document.querySelector('input[name="type"]').value = type;
}

// Initialize on load
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type_select');
    if (typeSelect) {
        toggleTypeFields(typeSelect.value);
    }
});
</script>
@endpush
