@extends('layouts.app')

@section('title', 'Tambah Link')
@section('page-title', 'Tambah Link')

@section('content')
<div class="row justify-center">
    <div class="col-12 col-lg-6">
        <div class="card animate-fadeIn">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-link text-primary"></i>
                    Form Tambah Link
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('links.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="title" class="form-label">Judul Link <span class="text-danger">*</span></label>
                        <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="contoh: Template SOP Medfo" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea id="description" name="description" class="form-control" rows="2" placeholder="Deskripsi singkat tentang link ini">{{ old('description') }}</textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="url" class="form-label">URL <span class="text-danger">*</span></label>
                        <input type="url" id="url" name="url" class="form-control @error('url') is-invalid @enderror" value="{{ old('url') }}" placeholder="https://..." required>
                        @error('url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="category" class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select id="category" name="category" class="form-control form-select" required>
                            @foreach($categories as $key => $cat)
                                <option value="{{ $key }}" {{ old('category') === $key ? 'selected' : '' }}>
                                    {{ $cat['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="icon" class="form-label">Icon (Font Awesome)</label>
                        <input type="text" id="icon" name="icon" class="form-control" value="{{ old('icon', 'fas fa-link') }}" placeholder="fas fa-link">
                        <small class="text-muted">Contoh: fas fa-file-alt, fas fa-chart-line, fas fa-gavel</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="sort_order" class="form-label">Urutan</label>
                        <input type="number" id="sort_order" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}" min="0">
                    </div>
                    
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Simpan
                        </button>
                        <a href="{{ route('links.index') }}" class="btn btn-secondary">
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
