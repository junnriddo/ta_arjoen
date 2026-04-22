@extends('layouts.app')

@section('title', 'Pembayaran Booking')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-7">

        <div class="card">
            <div class="card-header p-0">
                <div class="p-4 text-white text-center" style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); border-radius: 16px 16px 0 0;">
                    <i class="bi bi-credit-card" style="font-size: 3.5rem;"></i>
                    <h3 class="fw-bold mt-2 mb-1">Pembayaran Online</h3>
                    <p class="mb-0 opacity-75">Bayar langsung untuk approve booking otomatis</p>
                </div>
            </div>

            <div class="card-body p-4">

                {{-- Detail Booking --}}
                <div class="p-3 rounded-3 mb-4" style="background: #eff6ff; border: 1px solid #bfdbfe;">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-receipt text-primary me-1"></i> Detail Booking
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
                            <div class="fw-bold text-primary" style="font-size: 1.2rem;">
                                Rp {{ number_format($booking->harga, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-warning d-flex align-items-start mb-3" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2 mt-1"></i>
                    <div>
                        <strong>Perhatian:</strong> Booking yang sudah dibuat tidak dapat dibatalkan.
                    </div>
                </div>

                <div class="alert alert-info d-flex align-items-start mb-4" role="alert">
                    <i class="bi bi-camera-fill me-2 mt-1"></i>
                    <div>
                        <strong>Wajib screenshot bukti pembayaran</strong> untuk berjaga-jaga jika sistem gagal mengirim notifikasi ke admin.
                    </div>
                </div>

                {{-- Tombol Bayar --}}
                <div class="d-grid gap-2 mb-3">
                    <button id="pay-button" class="btn btn-lg fw-bold"
                        style="background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff; border-radius: 12px; padding: 14px;"
                        data-snap-token="{{ $booking->snap_token }}"
                        data-booking-id="{{ $booking->id }}"
                        data-csrf-token="{{ csrf_token() }}">
                        <i class="bi bi-credit-card me-2"></i> Bayar Sekarang — Rp {{ number_format($booking->harga, 0, ',', '.') }}
                    </button>
                </div>

                <div class="text-center mb-3">
                    <small class="text-muted">Atau bayar nanti dan konfirmasi via WhatsApp</small>
                </div>

                <div class="d-grid gap-2">
                    <a href="{{ route('booking.sukses', $booking->id) }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Halaman Sukses
                    </a>
                </div>

            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
{{-- Midtrans Snap JS --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
    var payButton  = document.getElementById('pay-button');
    var snapToken  = payButton.getAttribute('data-snap-token');
    var bookingId  = payButton.getAttribute('data-booking-id');
    var csrfToken  = payButton.getAttribute('data-csrf-token');
    var suksesUrl  = '/booking/sukses/' + bookingId;

    payButton.addEventListener('click', function () {
        snap.pay(snapToken, {
            onSuccess: function(result) {
                fetch('/booking/payment/callback', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        booking_id: bookingId,
                        transaction_status: result.transaction_status
                    })
                }).then(function() {
                    window.location.href = suksesUrl;
                });
            },
            onPending: function(result) {
                fetch('/booking/payment/callback', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        booking_id: bookingId,
                        transaction_status: 'pending'
                    })
                }).then(function() {
                    window.location.href = suksesUrl;
                });
            },
            onError: function(result) {
                alert('Pembayaran gagal. Silakan coba lagi.');
            },
            onClose: function() {
                // User menutup popup tanpa menyelesaikan pembayaran
            }
        });
    });
</script>
@endpush
