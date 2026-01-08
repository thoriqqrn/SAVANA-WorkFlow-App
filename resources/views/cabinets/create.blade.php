@extends('layouts.app')

@section('title', 'Tambah Kabinet')
@section('page-title', 'Tambah Kabinet')

@section('content')
<div class="row justify-center">
    <div class="col-12 col-lg-6">
        <div class="card animate-fadeIn">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-landmark text-primary"></i>
                    Form Tambah Kabinet
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('cabinets.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="name" class="form-label">Nama Kabinet <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="contoh: Kabinet Harmoni" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="year" class="form-label">Tahun Kepengurusan <span class="text-danger">*</span></label>
                        <input type="text" id="year" name="year" class="form-control @error('year') is-invalid @enderror" value="{{ old('year') }}" placeholder="contoh: 2025/2026" required>
                        @error('year')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select id="status" name="status" class="form-control form-select @error('status') is-invalid @enderror" required>
                            <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active (Periode Berjalan)</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        <small class="text-muted">Hanya satu kabinet yang bisa active pada satu waktu</small>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Simpan
                        </button>
                        <a href="{{ route('cabinets.index') }}" class="btn btn-secondary">
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
