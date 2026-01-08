@extends('layouts.app')

@section('title', 'Edit Link')
@section('page-title', 'Edit Link')

@section('content')
<div class="row justify-center">
    <div class="col-12 col-lg-6">
        <div class="card animate-fadeIn">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-link text-primary"></i>
                    Edit: {{ $link->title }}
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('links.update', $link) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label for="title" class="form-label">Judul Link <span class="text-danger">*</span></label>
                        <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $link->title) }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea id="description" name="description" class="form-control" rows="2">{{ old('description', $link->description) }}</textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="url" class="form-label">URL <span class="text-danger">*</span></label>
                        <input type="url" id="url" name="url" class="form-control" value="{{ old('url', $link->url) }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="category" class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select id="category" name="category" class="form-control form-select" required>
                            @foreach($categories as $key => $cat)
                                <option value="{{ $key }}" {{ old('category', $link->category) === $key ? 'selected' : '' }}>
                                    {{ $cat['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="icon" class="form-label">Icon (Font Awesome)</label>
                        <input type="text" id="icon" name="icon" class="form-control" value="{{ old('icon', $link->icon) }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="sort_order" class="form-label">Urutan</label>
                        <input type="number" id="sort_order" name="sort_order" class="form-control" value="{{ old('sort_order', $link->sort_order) }}" min="0">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-check">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', $link->is_active) ? 'checked' : '' }}>
                            <span>Aktif</span>
                        </label>
                    </div>
                    
                    <div class="d-flex justify-between mt-4">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                Update
                            </button>
                            <a href="{{ route('links.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i>
                                Kembali
                            </a>
                        </div>
                        
                        <form action="{{ route('links.destroy', $link) }}" method="POST" onsubmit="return confirm('Hapus link ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
