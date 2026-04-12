@extends('layouts.app')

@section('title', 'Form Booking')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-7">

        <a href="/booking?tanggal={{ $tanggal }}" class="btn btn-outline-secondary mb-3">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Jadwal
        </a>

        <div class="card">
            <div class="card-header p-0">
                <div class="p-4 text-white" style="background: linear-gradient(135deg, #1a6b3c 0%, #2e8b57 100%); border-radius: 16px 16px 0 0;">
                    <h4 class="fw-bold mb-1">
                        <i class="bi bi-calendar-plus me-2"></i> Form Booking Lapangan
                    </h4>
                    <p class="mb-0 opacity-75">Lengkapi data berikut untuk konfirmasi booking</p>
                </div>
            </div>

            <div class="card-body p-4">

                {{-- Ringkasan Booking --}}
                <div class="p-3 rounded-3 mb-4" style="background: #f0fdf4; border: 1px solid #bbf7d0;">
                    <h6 class="fw-bold mb-2">
                        <i class="bi bi-receipt text-success me-1"></i> Ringkasan Booking
                    </h6>
                    <div class="row">
                        <div class="col-6">
                            <small class="text-muted">Lapangan</small>
                            <div class="fw-semibold">{{ $lapangan->nama_lapangan }}</div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Jenis</small>
                            <div class="fw-semibold">{{ $lapangan->jenis_lapangan }}</div>
                        </div>
                        <div class="col-6 mt-2">
                            <small class="text-muted">Tanggal</small>
                            <div class="fw-semibold">{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</div>
                        </div>
                        <div class="col-3 mt-2">
                            <small class="text-muted">Jam</small>
                            <div class="fw-semibold">{{ $jam }}</div>
                        </div>
                        <div class="col-3 mt-2">
                            <small class="text-muted">Harga</small>
                            <div class="fw-bold text-success" style="font-size: 1.1rem;">
                                Rp {{ number_format($harga, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>

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

                @if(session('error'))
                    <div class="alert alert-june-error mb-3">
                        <i class="bi bi-exclamation-circle-fill me-2"></i>
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Form --}}
                <form action="/booking/store" method="POST">
                    @csrf

                    <input type="hidden" name="lapangan_id" value="{{ $lapangan->id }}">
                    <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                    <input type="hidden" name="jam" value="{{ $jam }}">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-person me-1"></i> Nama Pelanggan <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="nama_pelanggan"
                               class="form-control form-control-lg @error('nama_pelanggan') is-invalid @enderror"
                               value="{{ old('nama_pelanggan') }}"
                               placeholder="Masukkan nama lengkap">
                        @error('nama_pelanggan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-phone me-1"></i> Nomor HP <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="no_hp"
                               class="form-control form-control-lg @error('no_hp') is-invalid @enderror"
                               value="{{ old('no_hp') }}"
                               placeholder="Contoh: 08123456789">
                        @error('no_hp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-june btn-lg">
                            <i class="bi bi-check-circle me-2"></i> Konfirmasi Booking
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection