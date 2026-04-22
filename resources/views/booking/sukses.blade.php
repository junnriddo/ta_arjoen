@extends('layouts.app')

@section('title', 'Booking Berhasil')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-7">

        <div class="card">
            <div class="card-header p-0">
                <div class="p-4 text-white text-center" style="background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); border-radius: 16px 16px 0 0;">
                    <i class="bi bi-check-circle" style="font-size: 4rem;"></i>
                    <h3 class="fw-bold mt-2 mb-1">Booking Berhasil Dikirim!</h3>
                    <p class="mb-0 opacity-75">Booking Anda sedang menunggu konfirmasi admin</p>
                </div>
            </div>

            <div class="card-body p-4">

                {{-- Detail Booking --}}
                <div class="p-3 rounded-3 mb-4" style="background: #f0fdf4; border: 1px solid #bbf7d0;">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-receipt text-success me-1"></i> Detail Booking
                    </h6>
                    <div class="row g-3">
                        <div class="col-6">
                            <small class="text-muted">Nama Pelanggan</small>
                            <div class="fw-semibold">{{ $booking->nama_pelanggan }}</div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">No HP</small>
                            <div class="fw-semibold">{{ $booking->no_hp }}</div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Lapangan</small>
                            <div class="fw-semibold">{{ $booking->lapangan->nama_lapangan }}</div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Jenis</small>
                            <div class="fw-semibold">{{ $booking->lapangan->jenis_lapangan }}</div>
                        </div>
                        <div class="col-4">
                            <small class="text-muted">Tanggal</small>
                            <div class="fw-semibold">{{ \Carbon\Carbon::parse($booking->tanggal)->translatedFormat('d F Y') }}</div>
                        </div>
                        <div class="col-4">
                            <small class="text-muted">Jam</small>
                            <div class="fw-semibold">{{ $booking->jam }}</div>
                        </div>
                        <div class="col-4">
                            <small class="text-muted">Total Harga</small>
                            <div class="fw-bold text-success" style="font-size: 1.1rem;">
                                Rp {{ number_format($booking->total_price ?? $booking->harga, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-4">
                            <small class="text-muted">Tipe Bayar</small>
                            <div class="fw-semibold text-uppercase">{{ $booking->payment_type ?? 'lunas' }}</div>
                        </div>
                        <div class="col-4">
                            <small class="text-muted">Nominal Bayar</small>
                            <div class="fw-bold text-primary" style="font-size: 1.1rem;">
                                Rp {{ number_format($booking->harga, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Status --}}
                <div class="p-3 rounded-3 mb-4 text-center" style="background: #fffbeb; border: 1px solid #fde68a;">
                    <i class="bi bi-hourglass-split text-warning" style="font-size: 1.5rem;"></i>
                    <h6 class="fw-bold mt-2 mb-1">Status: <span class="text-warning">PENDING</span></h6>
                    <p class="mb-0 small text-muted">
                        Admin akan meng-approve booking Anda. Silakan hubungi admin via WhatsApp untuk konfirmasi lebih cepat.
                    </p>
                </div>

                {{-- Tombol WhatsApp --}}
                <div class="d-grid gap-2 mb-3">
                    @if($waLink !== '#')
                        <a href="{{ $waLink }}" target="_blank" class="btn btn-wa btn-lg">
                            <i class="bi bi-whatsapp me-2"></i> Konfirmasi via WhatsApp
                        </a>
                    @else
                        <button type="button" class="btn btn-secondary btn-lg" disabled>
                            <i class="bi bi-whatsapp me-2"></i> Konfirmasi via WhatsApp
                        </button>
                        <small class="text-danger">Nomor WhatsApp admin belum dikonfigurasi. Hubungi admin untuk setting <code>WA_ADMIN_NUMBER</code>.</small>
                    @endif
                </div>

                {{-- Tombol Bayar Online (Midtrans) --}}
                @if($booking->payment_status !== 'paid')
                    <div class="d-grid gap-2 mb-3">
                        <a href="/booking/payment/{{ $booking->id }}" class="btn btn-lg fw-bold"
                           style="background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff; border-radius: 10px;">
                            <i class="bi bi-credit-card me-2"></i> Bayar Online (Midtrans)
                        </a>
                    </div>
                @else
                    <div class="p-3 rounded-3 mb-3 text-center" style="background: #f0fdf4; border: 1px solid #bbf7d0;">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 1.5rem;"></i>
                        <h6 class="fw-bold mt-2 mb-0 text-success">Pembayaran Berhasil!</h6>
                    </div>
                @endif

                <div class="d-grid gap-2">
                    <a href="/booking?tanggal={{ $booking->tanggal }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Jadwal
                    </a>
                </div>

            </div>
        </div>

    </div>
</div>

@endsection
