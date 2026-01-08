@extends('layouts.app')

@section('title', 'Data User')
@section('page-title', 'Data User')

@section('content')
<div class="card animate-fadeIn">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-users text-primary"></i>
            Daftar User
        </h3>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Tambah User
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-container">
            <table class="table datatable">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Departemen</th>
                        <th>Status</th>
                        <th class="no-sort" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-center gap-2">
                                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="avatar-sm">
                                <span class="fw-semibold">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge badge-{{ $user->role?->name === 'admin' ? 'danger' : ($user->role?->name === 'bph' ? 'warning' : ($user->role?->name === 'kabinet' ? 'info' : 'secondary')) }}">
                                {{ ucfirst($user->role?->name ?? '-') }}
                            </span>
                        </td>
                        <td>{{ $user->department?->name ?? '-' }}</td>
                        <td>
                            <span class="badge badge-{{ $user->status === 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-secondary btn-icon" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-primary btn-icon" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger btn-icon" data-confirm-delete="{{ $user->name }}" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
