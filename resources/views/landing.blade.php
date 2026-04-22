@extends('layouts.app')

@section('title', 'Beranda - Booking Lapangan Futsal')

@push('styles')
<style>
    .hero-landing {
        background: linear-gradient(135deg, #1a6b3c 0%, #0f4d2a 50%, #1a1a2e 100%);
        border-radius: 24px;
        overflow: hidden;
        position: relative;
    }

    .hero-landing::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(245, 158, 11, 0.15) 0%, transparent 70%);
        border-radius: 50%;
    }

    .feature-card {
        border: none;
        border-radius: 16px;
        padding: 2rem 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
        background: #fff;
        box-shadow: 0 2px 15px rgba(0,0,0,0.06);
    }

    .feature-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 40px rgba(0,0,0,0.12);
    }

    .feature-icon {
        width: 70px;
        height: 70px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 1.8rem;
    }

    .step-number {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #1a6b3c, #2e8b57);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.1rem;
        flex-shrink: 0;
    }
</style>
@endpush

@section('content')

{{-- Hero Section --}}
<div class="hero-landing p-4 p-md-5 mb-5">
    <div class="row align-items-center">
        <div class="col-lg-7 text-white">
            <div class="mb-3">
                <span class="badge px-3 py-2" style="background: rgba(245, 158, 11, 0.2); color: #f59e0b; font-size: 0.85rem;">
                    <i class="bi bi-star-fill me-1"></i> Futsal Indoor Terbaik
                </span>
            </div>
            <h1 class="fw-bold mb-3" style="font-size: 2.5rem; line-height: 1.2;">
                Booking Lapangan Futsal <br>
                <span style="color: #f59e0b;">Mudah & Cepat</span>
            </h1>
            <p class="mb-4 opacity-75" style="font-size: 1.1rem; line-height: 1.7;">
                JuneFutsal menyediakan lapangan futsal indoor berkualitas.
                Booking online 24 jam, pilih jadwal, langsung main!
            </p>
            <div class="d-flex gap-3 flex-wrap">
                <a href="/booking" class="btn btn-accent btn-lg px-4">
                    <i class="bi bi-calendar-plus me-2"></i> Booking Sekarang
                </a>
                <a href="/lapangan" class="btn btn-outline-light btn-lg px-4">
                    <i class="bi bi-grid-3x3-gap me-2"></i> Lihat Lapangan
                </a>
            </div>
            {{-- Quick stats --}}
            <div class="d-flex gap-4 mt-4">
                <div>
                    <h4 class="fw-bold mb-0" style="color: #f59e0b;">{{ $totalLapangan }}</h4>
                    <small class="opacity-50">Lapangan</small>
                </div>
                <div>
                    <h4 class="fw-bold mb-0" style="color: #f59e0b;">16</h4>
                    <small class="opacity-50">Slot/Hari</small>
                </div>
                <div>
                    <h4 class="fw-bold mb-0" style="color: #f59e0b;">08-23</h4>
                    <small class="opacity-50">Jam Buka</small>
                </div>
            </div>
        </div>
        <div class="col-lg-5 text-center d-none d-lg-block">
            <i class="bi bi-dribbble" style="font-size: 14rem; opacity: 0.1; color: #fff;"></i>
        </div>
    </div>
</div>

{{-- Fitur Unggulan --}}
<div class="text-center mb-4">
    <h2 class="fw-bold">Kenapa Pilih <span style="color: #1a6b3c;">JuneFutsal</span>?</h2>
    <p class="text-muted">Fasilitas & layanan terbaik untuk pengalaman bermain futsal Anda</p>
</div>

<div class="row g-4 mb-5">
    <div class="col-md-4">
        <div class="feature-card h-100">
            <div class="feature-icon" style="background: linear-gradient(135deg, #ecfdf5, #d1fae5);">
                <i class="bi bi-calendar-check" style="color: #1a6b3c;"></i>
            </div>
            <h5 class="fw-bold">Booking Online</h5>
            <p class="text-muted small mb-0">
                Pilih lapangan, tanggal, dan jam langsung dari HP. Tidak perlu telepon atau datang ke tempat.
            </p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="feature-card h-100">
            <div class="feature-icon" style="background: linear-gradient(135deg, #eff6ff, #bfdbfe);">
                <i class="bi bi-credit-card" style="color: #2563eb;"></i>
            </div>
            <h5 class="fw-bold">Bayar Online</h5>
            <p class="text-muted small mb-0">
                Pembayaran mudah via Midtrans — GoPay, QRIS, Transfer Bank, dan metode lainnya.
            </p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="feature-card h-100">
            <div class="feature-icon" style="background: linear-gradient(135deg, #fef3c7, #fde68a);">
                <i class="bi bi-whatsapp" style="color: #25d366;"></i>
            </div>
            <h5 class="fw-bold">Konfirmasi WhatsApp</h5>
            <p class="text-muted small mb-0">
                Konfirmasi booking langsung via WhatsApp ke admin. Respon cepat, proses mudah.
            </p>
        </div>
    </div>
</div>

{{-- Daftar Lapangan --}}
<div class="text-center mb-4">
    <h2 class="fw-bold">Lapangan <span style="color: #1a6b3c;">Kami</span></h2>
    <p class="text-muted">Pilih lapangan sesuai kebutuhan tim Anda</p>
</div>

<div class="row g-4 mb-5">
    @foreach($lapangans as $index => $l)
    <div class="col-md-6">
        <div class="lapangan-card">
            <div class="lapangan-header {{ $index % 2 == 0 ? 'lapangan-header-a' : 'lapangan-header-b' }}">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <i class="bi bi-dribbble me-2"></i>
                        <span style="font-size: 1.2rem;">{{ $l->nama_lapangan }}</span>
                    </div>
                    <span class="badge bg-white bg-opacity-25 px-3 py-2">{{ $l->jenis_lapangan }}</span>
                </div>
            </div>
            <div class="lapangan-body">
                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <div class="p-3 rounded-3 text-center" style="background: #f0fdf4;">
                            <i class="bi bi-sun text-warning"></i>
                            <div class="small text-muted mt-1">Pagi (08-16)</div>
                            <div class="fw-bold text-success">Rp {{ number_format($l->harga_pagi, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded-3 text-center" style="background: #eff6ff;">
                            <i class="bi bi-moon-stars text-primary"></i>
                            <div class="small text-muted mt-1">Malam (17-23)</div>
                            <div class="fw-bold text-primary">Rp {{ number_format($l->harga_malam, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
                <a href="/booking" class="btn btn-june w-100">
                    <i class="bi bi-calendar-plus me-1"></i> Booking Sekarang
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Cara Booking --}}
<div class="text-center mb-4">
    <h2 class="fw-bold">Cara <span style="color: #1a6b3c;">Booking</span></h2>
    <p class="text-muted">3 langkah mudah untuk booking lapangan</p>
</div>

<div class="row g-4 mb-5">
    <div class="col-md-4">
        <div class="d-flex align-items-start gap-3">
            <div class="step-number">1</div>
            <div>
                <h6 class="fw-bold mb-1">Pilih Jadwal</h6>
                <p class="text-muted small mb-0">Pilih lapangan, tanggal, dan jam yang tersedia pada halaman booking.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="d-flex align-items-start gap-3">
            <div class="step-number">2</div>
            <div>
                <h6 class="fw-bold mb-1">Isi Data & Bayar</h6>
                <p class="text-muted small mb-0">Masukkan nama dan nomor HP, lalu bayar online atau konfirmasi via WhatsApp.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="d-flex align-items-start gap-3">
            <div class="step-number">3</div>
            <div>
                <h6 class="fw-bold mb-1">Datang & Main!</h6>
                <p class="text-muted small mb-0">Setelah booking di-approve, datang sesuai jadwal dan nikmati permainan.</p>
            </div>
        </div>
    </div>
</div>

{{-- CTA --}}
<div class="text-center p-5 rounded-4 mb-4" style="background: linear-gradient(135deg, #1a6b3c 0%, #0f4d2a 60%, #1a1a2e 100%);">
    <h3 class="fw-bold text-white mb-2">Siap Bermain Futsal?</h3>
    <p class="text-white opacity-75 mb-4">Booking sekarang dan ajak tim Anda untuk bermain!</p>
    <a href="/booking" class="btn btn-accent btn-lg px-5">
        <i class="bi bi-calendar-plus me-2"></i> Booking Sekarang
    </a>
</div>

@endsection
