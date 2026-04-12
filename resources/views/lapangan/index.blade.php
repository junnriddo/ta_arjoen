@extends('layouts.app')

@section('title', 'Daftar Lapangan')

@section('content')

<h2 class="page-title mb-4">
    <i class="bi bi-grid-3x3-gap text-success me-2"></i> Daftar Lapangan
</h2>

<div class="row g-4">
    @foreach($lapangans as $lapangan)
    <div class="col-md-6">
        <div class="lapangan-card card">
            <div class="lapangan-header {{ $loop->first ? 'lapangan-header-a' : 'lapangan-header-b' }}">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0 fw-bold">
                            <i class="bi bi-dribbble me-2"></i>{{ $lapangan->nama_lapangan }}
                        </h4>
                    </div>
                    <span class="badge bg-white text-dark px-3 py-2 rounded-pill">
                        {{ $lapangan->jenis_lapangan }}
                    </span>
                </div>
            </div>
            <div class="lapangan-body">
                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <div class="p-3 rounded-3 text-center" style="background: #fefce8;">
                            <i class="bi bi-sun text-warning" style="font-size: 1.5rem;"></i>
                            <div class="small text-muted mt-1">Pagi (08:00–17:00)</div>
                            <div class="fw-bold text-dark" style="font-size: 1.15rem;">
                                Rp {{ number_format($lapangan->harga_pagi, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded-3 text-center" style="background: #eff6ff;">
                            <i class="bi bi-moon-stars text-primary" style="font-size: 1.5rem;"></i>
                            <div class="small text-muted mt-1">Malam (18:00–23:00)</div>
                            <div class="fw-bold text-dark" style="font-size: 1.15rem;">
                                Rp {{ number_format($lapangan->harga_malam, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted small">
                        <i class="bi bi-calendar-check me-1"></i>
                        {{ $lapangan->bookings_count }} booking
                    </span>
                    <a href="/booking?lapangan_id={{ $lapangan->id }}" class="btn btn-june btn-sm">
                        <i class="bi bi-calendar-plus me-1"></i> Booking Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

@endsection