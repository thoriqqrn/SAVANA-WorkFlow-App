@extends('layouts.app')

@section('title', 'Edit Profil')
@section('page-title', 'Edit Profil')

@push('styles')
<style>
.profile-card {
    max-width: 600px;
    margin: 0 auto;
}
.avatar-upload {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 16px;
    margin-bottom: 24px;
    padding: 24px;
    background: var(--gray-50);
    border-radius: 16px;
}
.avatar-preview {
    position: relative;
    width: 150px;
    height: 150px;
}
.avatar-preview img {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid var(--primary);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}
.avatar-preview .avatar-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s;
    cursor: pointer;
}
.avatar-preview:hover .avatar-overlay {
    opacity: 1;
}
.avatar-overlay i {
    color: white;
    font-size: 2rem;
}
.avatar-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    justify-content: center;
}
.file-input-wrapper {
    position: relative;
    overflow: hidden;
    display: inline-block;
}
.file-input-wrapper input[type=file] {
    position: absolute;
    left: 0;
    top: 0;
    opacity: 0;
    cursor: pointer;
    width: 100%;
    height: 100%;
}
.user-info-card {
    background: var(--gray-50);
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 24px;
}
.user-info-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 8px 0;
}
.user-info-item:not(:last-child) {
    border-bottom: 1px solid var(--border-color);
}
.user-info-item i {
    width: 24px;
    color: var(--primary);
}
.user-info-label {
    font-size: 0.8rem;
    color: var(--text-muted);
}
.user-info-value {
    font-weight: 500;
}
</style>
@endpush

@section('content')
<div class="profile-card">
    <div class="card animate-fadeIn">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-user-edit text-primary"></i>
                Edit Profil
            </h3>
        </div>
        <div class="card-body">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <!-- Avatar Upload -->
                <div class="avatar-upload">
                    <div class="avatar-preview">
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" id="avatarPreview">
                        <label for="avatarInput" class="avatar-overlay">
                            <i class="fas fa-camera"></i>
                        </label>
                    </div>
                    <div class="avatar-actions">
                        <div class="file-input-wrapper">
                            <button type="button" class="btn btn-primary btn-sm">
                                <i class="fas fa-upload"></i> Ganti Foto
                            </button>
                            <input type="file" name="avatar" id="avatarInput" accept="image/*" onchange="previewAvatar(this)">
                        </div>
                        @if($user->avatar)
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeAvatar()">
                            <i class="fas fa-trash"></i> Hapus Foto
                        </button>
                        @endif
                    </div>
                    <small class="text-muted">Format: JPG, PNG, GIF, WebP. Maks 2MB</small>
                </div>
                
                <!-- Name -->
                <div class="form-group">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Read-only Info -->
                <div class="user-info-card">
                    <div class="user-info-item">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <div class="user-info-label">Email</div>
                            <div class="user-info-value">{{ $user->email }}</div>
                        </div>
                    </div>
                    <div class="user-info-item">
                        <i class="fas fa-user-tag"></i>
                        <div>
                            <div class="user-info-label">Role</div>
                            <div class="user-info-value">{{ $user->role_name }}</div>
                        </div>
                    </div>
                    @if($user->department)
                    <div class="user-info-item">
                        <i class="fas fa-building"></i>
                        <div>
                            <div class="user-info-label">Departemen</div>
                            <div class="user-info-value">{{ $user->department->name }}</div>
                        </div>
                    </div>
                    @endif
                    <div class="user-info-item">
                        <i class="fas fa-calendar"></i>
                        <div>
                            <div class="user-info-label">Bergabung</div>
                            <div class="user-info-value">{{ $user->created_at->format('d M Y') }}</div>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Remove Avatar Form -->
<form id="removeAvatarForm" action="{{ route('profile.avatar.remove') }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatarPreview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function removeAvatar() {
    Swal.fire({
        title: 'Hapus Foto Profil?',
        text: 'Foto profil akan dihapus dan diganti dengan avatar default.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#EF4444',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('removeAvatarForm').submit();
        }
    });
}
</script>
@endpush
