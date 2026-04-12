@extends('layouts.app')

@section('title', 'Edit Lapangan - Admin')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-7">

        <a href="{{ route('admin.lapangan') }}" class="btn btn-outline-secondary mb-3">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>

        <div class="card">
            <div class="card-header p-0">
                <div class="p-4 text-white" style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); border-radius: 16px 16px 0 0;">
                    <h4 class="fw-bold mb-1">
                        <i class="bi bi-pencil-square me-2"></i> Edit Lapangan
                    </h4>
                    <p class="mb-0 opacity-75">{{ $lapangan->nama_lapangan }}</p>
                </div>
            </div>

            <div class="card-body p-4">

                {{-- Error messages --}}
                @if($errors->any())
                    <div class="alert alert-june-error mb-3">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>Mohon periksa kembali:</strong>
                        <ul class="mb-0 mt-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.lapangan.update', $lapangan->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-dribbble me-1"></i> Nama Lapangan <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="nama_lapangan"
                               class="form-control form-control-lg @error('nama_lapangan') is-invalid @enderror"
                               value="{{ old('nama_lapangan', $lapangan->nama_lapangan) }}">
                        @error('nama_lapangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-tag me-1"></i> Jenis Lapangan <span class="text-danger">*</span>
                        </label>
                        <select name="jenis_lapangan" class="form-select form-select-lg @error('jenis_lapangan') is-invalid @enderror">
                            <option value="">-- Pilih Jenis --</option>
                            <option value="Sintetis" {{ old('jenis_lapangan', $lapangan->jenis_lapangan) == 'Sintetis' ? 'selected' : '' }}>Sintetis</option>
                            <option value="Vinyl" {{ old('jenis_lapangan', $lapangan->jenis_lapangan) == 'Vinyl' ? 'selected' : '' }}>Vinyl</option>
                            <option value="Parquette" {{ old('jenis_lapangan', $lapangan->jenis_lapangan) == 'Parquette' ? 'selected' : '' }}>Parquette</option>
                            <option value="Semen" {{ old('jenis_lapangan', $lapangan->jenis_lapangan) == 'Semen' ? 'selected' : '' }}>Semen</option>
                        </select>
                        @error('jenis_lapangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-sun me-1"></i> Harga Pagi (Rp) <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="harga_pagi"
                                   class="form-control form-control-lg @error('harga_pagi') is-invalid @enderror"
                                   value="{{ old('harga_pagi', $lapangan->harga_pagi) }}" min="0">
                            @error('harga_pagi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-moon-stars me-1"></i> Harga Malam (Rp) <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="harga_malam"
                                   class="form-control form-control-lg @error('harga_malam') is-invalid @enderror"
                                   value="{{ old('harga_malam', $lapangan->harga_malam) }}" min="0">
                            @error('harga_malam')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-june btn-lg">
                            <i class="bi bi-check-circle me-2"></i> Update Lapangan
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection
