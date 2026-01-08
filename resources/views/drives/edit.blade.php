@extends('layouts.app')

@section('title', 'Edit Drive')
@section('page-title', 'Edit Akun Drive')

@section('content')
<div class="row justify-center">
    <div class="col-12 col-lg-6">
        <div class="card animate-fadeIn">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fab fa-google-drive text-primary"></i>
                    Edit: {{ $drive->name }}
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('drives.update', $drive) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label for="name" class="form-label">Nama Drive <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $drive->name) }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="department_id" class="form-label">Departemen</label>
                        <select id="department_id" name="department_id" class="form-control form-select">
                            <option value="">-- Umum (Semua Departemen) --</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ old('department_id', $drive->department_id) == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="email" class="form-label">Email Google <span class="text-danger">*</span></label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $drive->email) }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="text" id="password" name="password" class="form-control" value="{{ old('password', $drive->password) }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="drive_url" class="form-label">URL Google Drive <span class="text-danger">*</span></label>
                        <input type="url" id="drive_url" name="drive_url" class="form-control" value="{{ old('drive_url', $drive->drive_url) }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-check">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', $drive->is_active) ? 'checked' : '' }}>
                            <span>Aktif</span>
                        </label>
                    </div>
                    
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Update
                        </button>
                        <a href="{{ route('drives.index') }}" class="btn btn-secondary">
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
