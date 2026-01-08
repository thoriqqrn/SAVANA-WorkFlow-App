@extends('layouts.app')

@section('title', 'Departemen')
@section('page-title', 'Departemen')

@section('content')
<div class="card animate-fadeIn">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-building text-primary"></i>
            Daftar Departemen
        </h3>
        <a href="{{ route('departments.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Tambah Departemen
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-container">
            <table class="table datatable">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Deskripsi</th>
                        <th>Kabinet</th>
                        <th>Anggota</th>
                        <th>Proker</th>
                        <th>Status</th>
                        <th class="no-sort" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($departments as $department)
                    <tr>
                        <td class="fw-semibold">{{ $department->name }}</td>
                        <td class="text-muted fs-sm">{{ Str::limit($department->description, 50) ?? '-' }}</td>
                        <td>{{ $department->cabinet?->name ?? '-' }}</td>
                        <td>
                            <span class="badge badge-info">{{ $department->users_count }} orang</span>
                        </td>
                        <td>
                            <span class="badge badge-primary">{{ $department->programs_count }} proker</span>
                        </td>
                        <td>
                            <span class="badge badge-{{ $department->status === 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($department->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('departments.show', $department) }}" class="btn btn-sm btn-secondary btn-icon" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('departments.edit', $department) }}" class="btn btn-sm btn-primary btn-icon" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('departments.destroy', $department) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger btn-icon" data-confirm-delete="{{ $department->name }}" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
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
