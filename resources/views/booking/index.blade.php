@extends('layouts.app')

@section('title', 'Booking Lapangan')

@section('content')

<h2 class="page-title mb-4">
    <i class="bi bi-calendar-check text-success me-2"></i> Booking Lapangan
</h2>

{{-- Alert success --}}
@if(session('success'))
    <div class="alert alert-june-success d-flex align-items-center mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2" style="font-size: 1.2rem;"></i>
        {{ session('success') }}
    </div>
@endif

{{-- Alert error --}}
@if(session('error'))
    <div class="alert alert-june-error d-flex align-items-center mb-4" role="alert">
        <i class="bi bi-exclamation-circle-fill me-2" style="font-size: 1.2rem;"></i>
        {{ session('error') }}
    </div>
@endif

{{-- Pilih Tanggal --}}
<div class="card mb-4">
    <div class="card-body p-4">
        <form method="GET" action="/booking" class="row align-items-end g-3">
            <div class="col-md-4">
                <label class="form-label fw-semibold">
                    <i class="bi bi-calendar3 me-1"></i> Pilih Tanggal
                </label>
                <input type="date" name="tanggal" class="form-control form-control-lg"
                       value="{{ $tanggal }}" min="{{ now()->toDateString() }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-june btn-lg w-100">
                    <i class="bi bi-search me-1"></i> Lihat Jadwal
                </button>
            </div>
            <div class="col-md-5 text-md-end">
                <p class="mb-0 text-muted">
                    <i class="bi bi-info-circle me-1"></i>
                    Tanggal dipilih: <strong>{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}</strong>
                </p>
            </div>
        </form>
    </div>
</div>

{{-- Slot Jam per Lapangan --}}
@foreach($lapangans as $lapangan)
<div class="card mb-4 lapangan-card">
    <div class="lapangan-header {{ $loop->first ? 'lapangan-header-a' : 'lapangan-header-b' }}">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-dribbble me-2"></i>{{ $lapangan->nama_lapangan }}
                </h5>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-white bg-opacity-25 px-3 py-2">{{ $lapangan->jenis_lapangan }}</span>
                <span class="small opacity-75">
                    <i class="bi bi-sun me-1"></i>Rp {{ number_format($lapangan->harga_pagi, 0, ',', '.') }} |
                    <i class="bi bi-moon-stars me-1"></i>Rp {{ number_format($lapangan->harga_malam, 0, ',', '.') }}
                </span>
            </div>
        </div>
    </div>
    <div class="lapangan-body">
        <div class="row g-2">
            @foreach($slotJam as $jam)
                @php
                    $isBooked = isset($bookedSlots[$lapangan->id][$jam]);
                    $namaPelanggan = $isBooked ? $bookedSlots[$lapangan->id][$jam] : null;
                    $jamAngka = (int) substr($jam, 0, 2);
                    $harga = ($jamAngka <= 17) ? $lapangan->harga_pagi : $lapangan->harga_malam;
                    $sesi = ($jamAngka <= 17) ? 'Pagi' : 'Malam';
                @endphp
                <div class="col-xl-2 col-lg-3 col-md-3 col-4">
                    @if($isBooked)
                        <div class="slot-booked">
                            <div class="fw-bold">{{ $jam }}</div>
                            <small class="d-block"><i class="bi bi-person-fill me-1"></i>{{ $namaPelanggan }}</small>
                        </div>
                    @else
                        <a href="/booking/create?lapangan_id={{ $lapangan->id }}&tanggal={{ $tanggal }}&jam={{ $jam }}"
                           class="slot-available">
                            <div class="fw-bold">{{ $jam }}</div>
                            <small><i class="bi bi-check-circle me-1"></i>Tersedia</small>
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>
@endforeach

@endsection