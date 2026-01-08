@extends('layouts.app')

@section('title', 'Kabinet')
@section('page-title', 'Kabinet')

@section('content')
<div class="card animate-fadeIn">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-landmark text-primary"></i>
            Daftar Kabinet
        </h3>
        <a href="{{ route('cabinets.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Tambah Kabinet
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-container">
            <table class="table datatable">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Tahun</th>
                        <th>Departemen</th>
                        <th>Status</th>
                        <th class="no-sort" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cabinets as $cabinet)
                    <tr>
                        <td class="fw-semibold">{{ $cabinet->name }}</td>
                        <td>{{ $cabinet->year }}</td>
                        <td>
                            <span class="badge badge-info">{{ $cabinet->departments_count }} departemen</span>
                        </td>
                        <td>
                            @if($cabinet->status === 'active')
                                <span class="badge badge-success">
                                    <i class="fas fa-star"></i> Active
                                </span>
                            @else
                                <span class="badge badge-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('cabinets.show', $cabinet) }}" class="btn btn-sm btn-secondary btn-icon" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('cabinets.edit', $cabinet) }}" class="btn btn-sm btn-primary btn-icon" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('cabinets.destroy', $cabinet) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger btn-icon" data-confirm-delete="{{ $cabinet->name }}" title="Hapus">
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
