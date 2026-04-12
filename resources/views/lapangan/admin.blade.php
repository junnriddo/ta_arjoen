@extends('layouts.app')

@section('title', 'Kelola Lapangan - Admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="page-title mb-0">
        <i class="bi bi-building text-success me-2"></i> Kelola Lapangan
    </h2>
    <a href="{{ route('admin.lapangan.create') }}" class="btn btn-june">
        <i class="bi bi-plus-circle me-1"></i> Tambah Lapangan
    </a>
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

{{-- Tabel Lapangan --}}
<div class="card">
    <div class="card-body p-0">
        @if($lapangans->count() > 0)
            <div class="table-responsive">
                <table class="table table-june table-hover mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Lapangan</th>
                            <th>Jenis Lapangan</th>
                            <th>Harga Pagi</th>
                            <th>Harga Malam</th>
                            <th>Jumlah Booking</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lapangans as $index => $l)
                        <tr>
                            <td>
                                <span class="badge bg-secondary rounded-pill">{{ $lapangans->firstItem() + $index }}</span>
                            </td>
                            <td>
                                <i class="bi bi-dribbble text-success me-1"></i>
                                <strong>{{ $l->nama_lapangan }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-info px-3 py-2">{{ $l->jenis_lapangan }}</span>
                            </td>
                            <td>
                                <strong class="text-success">Rp {{ number_format($l->harga_pagi, 0, ',', '.') }}</strong>
                            </td>
                            <td>
                                <strong class="text-primary">Rp {{ number_format($l->harga_malam, 0, ',', '.') }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-dark px-3 py-2">
                                    <i class="bi bi-calendar-check me-1"></i>{{ $l->bookings_count }} booking
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.lapangan.edit', $l->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.lapangan.destroy', $l->id) }}" method="POST"
                                          onsubmit="return confirm('Yakin hapus lapangan {{ $l->nama_lapangan }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-building" style="font-size: 4rem; color: #d1d5db;"></i>
                <h5 class="mt-3 text-muted">Belum ada lapangan</h5>
                <p class="text-muted">Tambahkan lapangan baru untuk memulai.</p>
                <a href="{{ route('admin.lapangan.create') }}" class="btn btn-june">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Lapangan
                </a>
            </div>
        @endif
    </div>
</div>

{{-- Pagination --}}
@if($lapangans->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $lapangans->links() }}
    </div>
@endif

@endsection
