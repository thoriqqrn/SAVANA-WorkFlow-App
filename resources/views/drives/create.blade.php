@extends('layouts.app')

@section('title', 'Tambah Drive')
@section('page-title', 'Tambah Akun Drive')

@section('content')
<div class="row justify-center">
    <div class="col-12 col-lg-6">
        <div class="card animate-fadeIn">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fab fa-google-drive text-primary"></i>
                    Form Tambah Drive
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('drives.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="name" class="form-label">Nama Drive <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="contoh: Drive PSDM" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="department_id" class="form-label">Departemen</label>
                        <select id="department_id" name="department_id" class="form-control form-select">
                            <option value="">-- Umum (Semua Departemen) --</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="email" class="form-label">Email Google <span class="text-danger">*</span></label>
                        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="text" id="password" name="password" class="form-control @error('password') is-invalid @enderror" value="{{ old('password') }}" required>
                        <small class="text-muted">Password akan ditampilkan ke user untuk login</small>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="drive_url" class="form-label">URL Google Drive <span class="text-danger">*</span></label>
                        <input type="url" id="drive_url" name="drive_url" class="form-control @error('drive_url') is-invalid @enderror" value="{{ old('drive_url') }}" placeholder="https://drive.google.com/drive/folders/..." required>
                        @error('drive_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Simpan
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
