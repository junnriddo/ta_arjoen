@extends('layouts.app')

@section('title', 'Data Booking - Admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="page-title mb-0">
        <i class="bi bi-clipboard-data text-success me-2"></i> Data Booking
    </h2>
    <div class="d-flex gap-2">
        <a href="/admin/export/harian" class="btn btn-sm btn-outline-success">
            <i class="bi bi-file-earmark-pdf me-1"></i> Export Harian
        </a>
        <a href="/admin/export/mingguan" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-file-earmark-pdf me-1"></i> Export Mingguan
        </a>
        <a href="/admin/export/bulanan" class="btn btn-sm btn-outline-danger">
            <i class="bi bi-file-earmark-pdf me-1"></i> Export Bulanan
        </a>
    </div>
</div>

{{-- Alert --}}
@if(session('success'))
    <div class="alert alert-june-success d-flex align-items-center mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2" style="font-size: 1.2rem;"></i>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-june-error d-flex align-items-center mb-4" role="alert">
        <i class="bi bi-exclamation-circle-fill me-2" style="font-size: 1.2rem;"></i>
        {{ session('error') }}
    </div>
@endif

{{-- Filter --}}
<div class="card mb-4">
    <div class="card-body p-3">
        <form method="GET" action="/admin/booking" class="row align-items-end g-3">
            <div class="col-md-3">
                <label class="form-label fw-semibold small">
                    <i class="bi bi-funnel me-1"></i> Filter Tanggal
                </label>
                <input type="date" name="tanggal" class="form-control"
                       value="{{ request('tanggal') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold small">
                    <i class="bi bi-filter me-1"></i> Status
                </label>
                <select name="status" class="form-select">
                    <option value="">Semua</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-june w-100">
                    <i class="bi bi-search me-1"></i> Filter
                </button>
            </div>
            <div class="col-md-2">
                <a href="/admin/booking" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                </a>
            </div>
            <div class="col-md-3 text-md-end">
                <span class="badge bg-success px-3 py-2" style="font-size: 0.9rem;">
                    <i class="bi bi-list-check me-1"></i>
                    Total: {{ $bookings->total() }} booking
                </span>
            </div>
        </form>
    </div>
</div>

{{-- Tabel Data Booking --}}
<div class="card">
    <div class="card-body p-0">
        @if($bookings->count() > 0)
            <div class="table-responsive">
                <table class="table table-june table-hover mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pelanggan</th>
                            <th>No HP</th>
                            <th>Nama Lapangan</th>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Harga</th>
                            <th>Status</th>
                            <th>Bayar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $index => $b)
                        <tr>
                            <td>
                                <span class="badge bg-secondary rounded-pill">{{ $bookings->firstItem() + $index }}</span>
                            </td>
                            <td>
                                <i class="bi bi-person-circle text-muted me-1"></i>
                                <strong>{{ $b->nama_pelanggan }}</strong>
                            </td>
                            <td>
                                <i class="bi bi-phone text-muted me-1"></i>
                                {{ $b->no_hp }}
                            </td>
                            <td>
                                <span class="badge {{ $b->lapangan->nama_lapangan == 'Lapangan A' ? 'bg-success' : 'bg-primary' }} px-3 py-2">
                                    <i class="bi bi-dribbble me-1"></i>{{ $b->lapangan->nama_lapangan }}
                                </span>
                            </td>
                            <td>
                                <i class="bi bi-calendar3 text-muted me-1"></i>
                                {{ \Carbon\Carbon::parse($b->tanggal)->format('d M Y') }}
                            </td>
                            <td>
                                <span class="badge bg-dark px-3 py-2">
                                    <i class="bi bi-clock me-1"></i>{{ $b->jam }}
                                </span>
                            </td>
                            <td>
                                <strong class="text-success">
                                    Rp {{ number_format($b->harga, 0, ',', '.') }}
                                </strong>
                            </td>
                            <td>
                                @if($b->status == 'pending')
                                    <span class="badge badge-pending px-3 py-2">
                                        <i class="bi bi-hourglass-split me-1"></i>Pending
                                    </span>
                                @elseif($b->status == 'approved')
                                    <span class="badge badge-approved px-3 py-2">
                                        <i class="bi bi-check-circle me-1"></i>Approved
                                    </span>
                                @else
                                    <span class="badge badge-cancelled px-3 py-2">
                                        <i class="bi bi-x-circle me-1"></i>Cancelled
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($b->payment_status === 'paid')
                                    <span class="badge bg-success px-2 py-1">
                                        <i class="bi bi-check-circle me-1"></i>Paid
                                    </span>
                                @else
                                    <span class="badge bg-secondary px-2 py-1">
                                        <i class="bi bi-dash-circle me-1"></i>Unpaid
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1 flex-wrap">
                                    {{-- Tombol Approve (muncul jika status BUKAN approved) --}}
                                    @if($b->status != 'approved')
                                        <form action="{{ route('admin.booking.status', $b->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="btn btn-success btn-sm" title="Approve"
                                                    onclick="return confirm('Approve booking ini?')">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Tombol Pending (muncul jika status BUKAN pending) --}}
                                    @if($b->status != 'pending')
                                        <form action="{{ route('admin.booking.status', $b->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="pending">
                                            <button type="submit" class="btn btn-warning btn-sm" title="Set Pending"
                                                    onclick="return confirm('Ubah status menjadi Pending?')">
                                                <i class="bi bi-hourglass-split"></i>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Tombol Cancel (muncul jika status BUKAN cancelled) --}}
                                    @if($b->status != 'cancelled')
                                        <form action="{{ route('admin.booking.status', $b->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="cancelled">
                                            <button type="submit" class="btn btn-danger btn-sm" title="Cancel"
                                                    onclick="return confirm('Cancel booking ini?')">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background: #f8fafc;">
                            <td colspan="9" class="text-end fw-bold pe-3">
                                <i class="bi bi-calculator me-1"></i> Total Pendapatan (Approved):
                            </td>
                            <td>
                                <strong class="text-success" style="font-size: 1.1rem;">
                                    Rp {{ number_format($bookings->where('status', 'approved')->sum('harga'), 0, ',', '.') }}
                                </strong>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-calendar-x" style="font-size: 4rem; color: #d1d5db;"></i>
                <h5 class="mt-3 text-muted">Belum ada data booking</h5>
                <p class="text-muted">Data booking akan muncul di sini setelah ada pelanggan yang melakukan booking.</p>
                <a href="/booking" class="btn btn-june">
                    <i class="bi bi-calendar-plus me-1"></i> Buat Booking
                </a>
            </div>
        @endif
    </div>
</div>

{{-- Pagination --}}
@if($bookings->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $bookings->links() }}
    </div>
@endif

@endsection
