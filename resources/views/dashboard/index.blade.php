@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

{{-- Notifikasi Booking Pending --}}
@if($bookingPending > 0)
    <div class="alert d-flex align-items-center mb-4" role="alert"
         style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-left: 4px solid #f59e0b; border-radius: 12px; color: #92400e;">
        <i class="bi bi-bell-fill me-3" style="font-size: 1.5rem; color: #f59e0b;"></i>
        <div class="flex-grow-1">
            <strong>{{ $bookingPending }} Booking Menunggu Konfirmasi!</strong>
            <div class="small">Segera approve atau cancel booking yang masuk.</div>
        </div>
        <a href="/admin/booking?status=pending" class="btn btn-sm btn-warning fw-semibold ms-3">
            <i class="bi bi-arrow-right me-1"></i> Lihat
        </a>
    </div>
@endif

<!-- Hero Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0" style="background: linear-gradient(135deg, #1a6b3c 0%, #0f4d2a 60%, #1a1a2e 100%); border-radius: 20px; overflow: hidden;">
            <div class="card-body p-4 p-md-5 text-white position-relative">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="fw-bold mb-2" style="font-size: 2rem;">
                            <i class="bi bi-dribbble me-2" style="color: #f59e0b;"></i>
                            Selamat Datang di JuneFutsal
                        </h1>
                        <p class="mb-3 opacity-75" style="font-size: 1.05rem;">
                            Sistem Booking Lapangan Futsal Indoor — Booking mudah, main seru!
                        </p>
                        <a href="/booking" class="btn btn-accent btn-lg">
                            <i class="bi bi-calendar-plus me-2"></i> Booking Sekarang
                        </a>
                    </div>
                    <div class="col-md-4 text-center d-none d-md-block">
                        <i class="bi bi-trophy" style="font-size: 8rem; opacity: 0.15;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistik Utama -->
<div class="row g-4 mb-4">
    <div class="col-md-3 col-6">
        <div class="stat-card" style="background: linear-gradient(135deg, #1a6b3c, #2e8b57);">
            <i class="bi bi-grid-3x3-gap stat-icon"></i>
            <p class="mb-1 small opacity-75">Total Lapangan</p>
            <h2 class="fw-bold mb-0">{{ $totalLapangan }}</h2>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card" style="background: linear-gradient(135deg, #2563eb, #1d4ed8);">
            <i class="bi bi-calendar-check stat-icon"></i>
            <p class="mb-1 small opacity-75">Total Booking</p>
            <h2 class="fw-bold mb-0">{{ $totalBooking }}</h2>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
            <i class="bi bi-hourglass-split stat-icon"></i>
            <p class="mb-1 small opacity-75">Menunggu Approve</p>
            <h2 class="fw-bold mb-0">{{ $bookingPending }}</h2>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
            <i class="bi bi-cash-stack stat-icon"></i>
            <p class="mb-1 small opacity-75">Total Pendapatan</p>
            <h2 class="fw-bold mb-0">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h2>
        </div>
    </div>
</div>

<!-- Report Booking (Approved Only) -->
<div class="row mb-3">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">
            <i class="bi bi-bar-chart-line text-success me-2"></i> Report Booking
            <span class="badge bg-success ms-2" style="font-size: 0.7rem;">Approved Only</span>
        </h5>
        <div class="d-flex gap-2">
            <a href="/admin/export/harian" class="btn btn-sm btn-outline-success" title="Export Harian">
                <i class="bi bi-file-earmark-pdf me-1"></i> Harian
            </a>
            <a href="/admin/export/mingguan" class="btn btn-sm btn-outline-primary" title="Export Mingguan">
                <i class="bi bi-file-earmark-pdf me-1"></i> Mingguan
            </a>
            <a href="/admin/export/bulanan" class="btn btn-sm btn-outline-danger" title="Export Bulanan">
                <i class="bi bi-file-earmark-pdf me-1"></i> Bulanan
            </a>
        </div>
    </div>
</div>
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="stat-card" style="background: linear-gradient(135deg, #06b6d4, #0891b2);">
            <i class="bi bi-calendar-day stat-icon"></i>
            <p class="mb-1 small opacity-75">Booking Hari Ini</p>
            <h2 class="fw-bold mb-0">{{ $bookingHariIni }}</h2>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card" style="background: linear-gradient(135deg, #ec4899, #db2777);">
            <i class="bi bi-calendar-week stat-icon"></i>
            <p class="mb-1 small opacity-75">Booking Minggu Ini</p>
            <h2 class="fw-bold mb-0">{{ $bookingMingguIni }}</h2>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card" style="background: linear-gradient(135deg, #14b8a6, #0d9488);">
            <i class="bi bi-calendar-month stat-icon"></i>
            <p class="mb-1 small opacity-75">Booking Bulan Ini</p>
            <h2 class="fw-bold mb-0">{{ $bookingBulanIni }}</h2>
        </div>
    </div>
</div>

<!-- Report Pendapatan (Approved Only) -->
<div class="row mb-3">
    <div class="col-12">
        <h5 class="fw-bold">
            <i class="bi bi-wallet2 text-success me-2"></i> Report Pendapatan
            <span class="badge bg-success ms-2" style="font-size: 0.7rem;">Approved Only</span>
        </h5>
    </div>
</div>
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="stat-card" style="background: linear-gradient(135deg, #0ea5e9, #0284c7);">
            <i class="bi bi-cash stat-icon"></i>
            <p class="mb-1 small opacity-75">Pendapatan Hari Ini</p>
            <h2 class="fw-bold mb-0">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</h2>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card" style="background: linear-gradient(135deg, #d946ef, #a855f7);">
            <i class="bi bi-cash-coin stat-icon"></i>
            <p class="mb-1 small opacity-75">Pendapatan Minggu Ini</p>
            <h2 class="fw-bold mb-0">Rp {{ number_format($pendapatanMingguIni, 0, ',', '.') }}</h2>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card" style="background: linear-gradient(135deg, #f97316, #ea580c);">
            <i class="bi bi-cash-stack stat-icon"></i>
            <p class="mb-1 small opacity-75">Pendapatan Bulan Ini</p>
            <h2 class="fw-bold mb-0">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</h2>
        </div>
    </div>
</div>

<!-- Info Lapangan & Booking Terbaru -->
<div class="row g-4">
    <!-- Info Jam Operasional -->
    <div class="col-md-5">
        <div class="card h-100">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">
                    <i class="bi bi-clock text-success me-2"></i> Jam Operasional
                </h5>
                <div class="d-flex align-items-center mb-3 p-3 rounded-3" style="background: #f0fdf4;">
                    <i class="bi bi-sun text-warning me-3" style="font-size: 1.5rem;"></i>
                    <div>
                        <strong>Sesi Pagi</strong>
                        <div class="text-muted small">08:00 - 16:00</div>
                    </div>
                </div>
                <div class="d-flex align-items-center mb-3 p-3 rounded-3" style="background: #eff6ff;">
                    <i class="bi bi-moon-stars text-primary me-3" style="font-size: 1.5rem;"></i>
                    <div>
                        <strong>Sesi Malam</strong>
                        <div class="text-muted small">17:00 - 23:00</div>
                    </div>
                </div>
                <hr>
                <h6 class="fw-bold mb-2">
                    <i class="bi bi-info-circle text-primary me-1"></i> Keterangan Warna Slot
                </h6>
                <div class="d-flex align-items-center mb-2">
                    <span class="badge me-2" style="background: linear-gradient(135deg, #22c55e, #16a34a); width: 50px;">&nbsp;</span>
                    <span class="small">Tersedia — Bisa dibooking</span>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <span class="badge me-2" style="background: linear-gradient(135deg, #ef4444, #dc2626); width: 50px;">&nbsp;</span>
                    <span class="small">Tidak Tersedia — Sudah di-approve</span>
                </div>
                <div class="d-flex align-items-center">
                    <span class="badge me-2" style="background: linear-gradient(135deg, #f59e0b, #d97706); width: 50px;">&nbsp;</span>
                    <span class="small">Pending — Menunggu konfirmasi</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Terbaru -->
    <div class="col-md-7">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-clock-history text-primary me-2"></i> Booking Terbaru
                    </h5>
                    <a href="/admin/booking" class="btn btn-sm btn-outline-success">
                        <i class="bi bi-arrow-right me-1"></i> Lihat Semua
                    </a>
                </div>

                @if($bookingTerbaru->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-june mb-0">
                            <thead>
                                <tr>
                                    <th>Pelanggan</th>
                                    <th>Lapangan</th>
                                    <th>Tanggal</th>
                                    <th>Jam</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bookingTerbaru as $b)
                                <tr>
                                    <td>
                                        <i class="bi bi-person-circle text-muted me-1"></i>
                                        {{ $b->nama_pelanggan }}
                                    </td>
                                    <td>{{ $b->lapangan->nama_lapangan }}</td>
                                    <td>{{ \Carbon\Carbon::parse($b->tanggal)->format('d M Y') }}</td>
                                    <td>
                                        <span class="badge bg-dark">{{ $b->jam }}</span>
                                    </td>
                                    <td>
                                        @if($b->status == 'pending')
                                            <span class="badge badge-pending px-2 py-1">
                                                <i class="bi bi-hourglass-split me-1"></i>Pending
                                            </span>
                                        @elseif($b->status == 'approved')
                                            <span class="badge badge-approved px-2 py-1">
                                                <i class="bi bi-check-circle me-1"></i>Approved
                                            </span>
                                        @else
                                            <span class="badge badge-cancelled px-2 py-1">
                                                <i class="bi bi-x-circle me-1"></i>Cancelled
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-calendar-x" style="font-size: 3rem; opacity: 0.3;"></i>
                        <p class="mt-2">Belum ada booking</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
