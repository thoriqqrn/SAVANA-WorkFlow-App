@extends('layouts.app')

@section('title', 'Import User')
@section('page-title', 'Import User dari CSV')

@push('styles')
<style>
.import-page {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

.format-table {
    width: 100%;
    font-size: 0.85rem;
}

.format-table th, .format-table td {
    padding: 8px 12px;
    border: 1px solid var(--border-color);
    text-align: left;
}

.format-table th {
    background: var(--gray-50);
    font-weight: 600;
}

.format-table code {
    background: var(--primary-light);
    color: var(--primary);
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 0.8rem;
}

.csv-example {
    background: var(--gray-50);
    padding: 12px 16px;
    border-radius: 8px;
    font-family: monospace;
    font-size: 0.8rem;
    overflow-x: auto;
    white-space: pre;
}

.result-card {
    margin-top: 1.5rem;
}

.result-item {
    display: flex;
    gap: 12px;
    padding: 10px 0;
    border-bottom: 1px solid var(--border-color);
    font-size: 0.85rem;
}

.result-item:last-child {
    border-bottom: none;
}

.result-item .row-num {
    min-width: 40px;
    color: var(--text-muted);
}

.result-item.success .status-icon {
    color: var(--success);
}

.result-item.error .status-icon {
    color: var(--danger);
}

.upload-zone {
    border: 2px dashed var(--border-color);
    border-radius: 12px;
    padding: 2rem;
    text-align: center;
    transition: all 0.3s;
    cursor: pointer;
}

.upload-zone:hover {
    border-color: var(--primary);
    background: var(--primary-light);
}

.upload-zone input[type="file"] {
    display: none;
}

.upload-zone i {
    font-size: 2.5rem;
    color: var(--primary);
    margin-bottom: 1rem;
}

@media (max-width: 992px) {
    .import-page {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .import-page {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .upload-zone {
        padding: 1.5rem 1rem;
    }
    
    .upload-zone i {
        font-size: 2rem;
    }
    
    .d-flex.gap-2 {
        flex-direction: column;
    }
    
    .d-flex.gap-2 .btn {
        width: 100%;
    }
    
    .format-table {
        display: block;
        overflow-x: auto;
        white-space: nowrap;
    }
    
    .format-table th, .format-table td {
        padding: 6px 10px;
        font-size: 0.8rem;
    }
    
    .csv-example {
        font-size: 0.7rem;
        padding: 10px 12px;
    }
    
    .result-item {
        flex-wrap: wrap;
        gap: 8px;
    }
    
    .result-item span:last-child {
        flex: 1 1 100%;
        word-break: break-word;
    }
    
    .card-header h3.card-title,
    .card-header h5.card-title {
        font-size: 0.95rem;
    }
    
    .alert-info {
        font-size: 0.85rem;
        padding: 10px;
    }
}

@media (max-width: 480px) {
    .format-table th, .format-table td {
        padding: 5px 8px;
        font-size: 0.75rem;
    }
    
    .csv-example {
        font-size: 0.65rem;
    }
}
</style>
@endpush

@section('content')
<div class="import-page">
    <!-- Upload Form -->
    <div class="card animate-fadeIn">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-upload text-primary"></i>
                Upload File CSV
            </h3>
        </div>
        <div class="card-body">
            <form action="{{ route('users.import.process') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <label class="upload-zone" for="csvFile">
                    <i class="fas fa-file-csv"></i>
                    <div class="fw-semibold mb-2">Klik untuk upload file CSV</div>
                    <div class="text-muted fs-sm">Maksimal 2MB, format .csv</div>
                    <input type="file" name="csv_file" id="csvFile" accept=".csv,.txt" required onchange="showFileName(this)">
                    <div id="fileName" class="mt-2 text-primary fw-semibold"></div>
                </label>
                
                @error('csv_file')
                    <div class="text-danger mt-2 fs-sm">{{ $message }}</div>
                @enderror
                
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload"></i> Import Users
                    </button>
                    <a href="{{ route('users.import.template') }}" class="btn btn-secondary">
                        <i class="fas fa-download"></i> Download Template
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Format Guide -->
    <div class="card animate-fadeIn">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-info-circle text-info"></i>
                Panduan Format CSV
            </h3>
        </div>
        <div class="card-body">
            <h6 class="mb-3">Kolom yang Dibutuhkan:</h6>
            <table class="format-table mb-4">
                <thead>
                    <tr>
                        <th>Kolom</th>
                        <th>Keterangan</th>
                        <th>Wajib</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><code>name</code></td>
                        <td>Nama lengkap user</td>
                        <td>✅ Ya</td>
                    </tr>
                    <tr>
                        <td><code>email</code></td>
                        <td>Email unik (belum terdaftar)</td>
                        <td>✅ Ya</td>
                    </tr>
                    <tr>
                        <td><code>password</code></td>
                        <td>Password (min. 6 karakter)</td>
                        <td>✅ Ya</td>
                    </tr>
                    <tr>
                        <td><code>role</code></td>
                        <td>admin, bph, kabinet, atau staff</td>
                        <td>✅ Ya</td>
                    </tr>
                    <tr>
                        <td><code>department</code></td>
                        <td>Nama departemen (untuk staff/kabinet)</td>
                        <td>❌ Tidak</td>
                    </tr>
                </tbody>
            </table>
            
            <h6 class="mb-3">Contoh Format CSV:</h6>
            <div class="csv-example">name,email,password,role,department
John Doe,john@example.com,password123,staff,Divisi IT
Jane Doe,jane@example.com,password456,kabinet,Divisi Humas
Admin User,admin@example.com,securepass,bph,</div>

            <div class="alert alert-info mt-4">
                <i class="fas fa-lightbulb"></i>
                <strong>Tips:</strong> Gunakan Excel/Google Sheets untuk membuat data, lalu export ke CSV.
            </div>
        </div>
    </div>
</div>

@if(session('import_results'))
<div class="result-card">
    <div class="row">
        @if(count(session('import_results')['success']) > 0)
        <div class="col-12 col-md-6 mb-3">
            <div class="card">
                <div class="card-header" style="background: var(--success-light);">
                    <h5 class="card-title mb-0 text-success">
                        <i class="fas fa-check-circle"></i>
                        Berhasil ({{ count(session('import_results')['success']) }})
                    </h5>
                </div>
                <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                    @foreach(session('import_results')['success'] as $item)
                    <div class="result-item success">
                        <span class="row-num">Row {{ $item['row'] }}</span>
                        <i class="fas fa-check-circle status-icon"></i>
                        <span><strong>{{ $item['data'] }}</strong> - {{ $item['message'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
        
        @if(count(session('import_results')['errors']) > 0)
        <div class="col-12 col-md-6 mb-3">
            <div class="card">
                <div class="card-header" style="background: var(--danger-light);">
                    <h5 class="card-title mb-0 text-danger">
                        <i class="fas fa-times-circle"></i>
                        Gagal ({{ count(session('import_results')['errors']) }})
                    </h5>
                </div>
                <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                    @foreach(session('import_results')['errors'] as $item)
                    <div class="result-item error">
                        <span class="row-num">Row {{ $item['row'] }}</span>
                        <i class="fas fa-times-circle status-icon"></i>
                        <span><strong>{{ $item['data'] }}</strong> - {{ $item['message'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endif

<div class="mt-3">
    <a href="{{ route('users.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar User
    </a>
</div>
@endsection

@push('scripts')
<script>
function showFileName(input) {
    const fileName = input.files[0]?.name || '';
    document.getElementById('fileName').textContent = fileName;
}
</script>
@endpush
