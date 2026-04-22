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
                        <div class="col-6 mt-2">
                            <small class="text-muted">Rentang Jam</small>
                            <div class="fw-semibold" id="ringkasan-jam">{{ $jamMulai }} - {{ $jamSelesai }}</div>
                        </div>
                        <div class="col-6 mt-2">
                            <small class="text-muted">Total Harga</small>
                            <div class="fw-bold text-success" style="font-size: 1.1rem;">
                                <span id="ringkasan-total">Rp {{ number_format($harga, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <div class="col-6 mt-2">
                            <small class="text-muted">Tipe Pembayaran</small>
                            <div class="fw-semibold" id="ringkasan-payment-type">Lunas</div>
                        </div>
                        <div class="col-6 mt-2">
                            <small class="text-muted">Nominal Bayar Sekarang</small>
                            <div class="fw-bold text-primary" style="font-size: 1.1rem;">
                                <span id="ringkasan-payable">Rp {{ number_format($harga, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-warning d-flex align-items-start mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2 mt-1"></i>
                    <div>
                        <strong>Perhatian:</strong> Booking yang sudah dibuat tidak dapat dibatalkan.
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

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-clock me-1"></i> Jam Mulai <span class="text-danger">*</span>
                            </label>
                            <select name="jam_mulai" id="jam_mulai" class="form-select form-select-lg @error('jam_mulai') is-invalid @enderror" required>
                                @for($hour = 8; $hour <= 22; $hour++)
                                    @php $slot = sprintf('%02d:00', $hour); @endphp
                                    <option value="{{ $slot }}" {{ old('jam_mulai', $jamMulai) === $slot ? 'selected' : '' }}>{{ $slot }}</option>
                                @endfor
                            </select>
                            @error('jam_mulai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-clock-history me-1"></i> Jam Selesai <span class="text-danger">*</span>
                            </label>
                            <select name="jam_selesai" id="jam_selesai" class="form-select form-select-lg @error('jam_selesai') is-invalid @enderror" required>
                                {{-- diisi via JS sesuai jam mulai --}}
                            </select>
                            @error('jam_selesai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-wallet2 me-1"></i> Tipe Pembayaran <span class="text-danger">*</span>
                        </label>
                        <select name="payment_type" id="payment_type" class="form-select form-select-lg @error('payment_type') is-invalid @enderror" required>
                            <option value="lunas" {{ old('payment_type', 'lunas') === 'lunas' ? 'selected' : '' }}>Lunas (100%)</option>
                            <option value="dp" {{ old('payment_type') === 'dp' ? 'selected' : '' }}>DP (30%)</option>
                        </select>
                        @error('payment_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

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

@php
    $hargaPagi = (int) $lapangan->harga_pagi;
    $hargaMalam = (int) $lapangan->harga_malam;
@endphp

<script>
    (function () {
        const jamMulai = document.getElementById('jam_mulai');
        const jamSelesai = document.getElementById('jam_selesai');
        const paymentType = document.getElementById('payment_type');

        const ringkasanJam = document.getElementById('ringkasan-jam');
        const ringkasanTotal = document.getElementById('ringkasan-total');
        const ringkasanPayable = document.getElementById('ringkasan-payable');
        const ringkasanPaymentType = document.getElementById('ringkasan-payment-type');

        const hargaPagi = {{ $hargaPagi }};
        const hargaMalam = {{ $hargaMalam }};
        const oldJamSelesai = @json(old('jam_selesai', $jamSelesai));

        function toHour(time) {
            return parseInt(time.substring(0, 2), 10);
        }

        function formatRupiah(number) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
        }

        function refillJamSelesai() {
            const start = toHour(jamMulai.value);
            jamSelesai.innerHTML = '';

            for (let hour = start + 1; hour <= 23; hour++) {
                const value = String(hour).padStart(2, '0') + ':00';
                const option = document.createElement('option');
                option.value = value;
                option.textContent = value;
                jamSelesai.appendChild(option);
            }

            const canUseOld = Array.from(jamSelesai.options).some(opt => opt.value === oldJamSelesai);
            jamSelesai.value = canUseOld ? oldJamSelesai : jamSelesai.options[0].value;
        }

        function calculateTotal(startHour, endHour) {
            let total = 0;
            for (let hour = startHour; hour < endHour; hour++) {
                total += (hour < 17) ? hargaPagi : hargaMalam;
            }
            return total;
        }

        function updateRingkasan() {
            const start = toHour(jamMulai.value);
            const end = toHour(jamSelesai.value);
            const total = calculateTotal(start, end);
            const payable = paymentType.value === 'dp' ? Math.round(total * 0.3) : total;

            ringkasanJam.textContent = jamMulai.value + ' - ' + jamSelesai.value;
            ringkasanTotal.textContent = formatRupiah(total);
            ringkasanPayable.textContent = formatRupiah(payable);
            ringkasanPaymentType.textContent = paymentType.value === 'dp' ? 'DP (30%)' : 'Lunas (100%)';
        }

        refillJamSelesai();
        updateRingkasan();

        jamMulai.addEventListener('change', function () {
            refillJamSelesai();
            updateRingkasan();
        });

        jamSelesai.addEventListener('change', updateRingkasan);
        paymentType.addEventListener('change', updateRingkasan);
    })();
</script>

@endsection