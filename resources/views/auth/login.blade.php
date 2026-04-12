@extends('layouts.app')

@section('title', 'Login Admin')

@section('content')

<div class="row justify-content-center mt-5">
    <div class="col-md-5">

        {{-- Alert sukses --}}
        @if(session('success'))
            <div class="alert alert-june-success d-flex align-items-center mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2" style="font-size: 1.2rem;"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="card border-0 shadow">
            {{-- Header --}}
            <div class="card-header p-0">
                <div class="p-4 text-white text-center"
                     style="background: linear-gradient(135deg, #1a1a2e 0%, #0f4d2a 100%); border-radius: 16px 16px 0 0;">
                    <i class="bi bi-dribbble" style="font-size: 3rem; color: #f59e0b;"></i>
                    <h3 class="fw-bold mt-2 mb-1">
                        June<span style="color: #f59e0b;">Futsal</span>
                    </h3>
                    <p class="mb-0 opacity-75 small">Login untuk mengakses halaman admin</p>
                </div>
            </div>

            <div class="card-body p-4">

                {{-- Alert error --}}
                @if(session('error'))
                    <div class="alert alert-june-error d-flex align-items-center mb-3" role="alert">
                        <i class="bi bi-exclamation-circle-fill me-2"></i>
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Form Login --}}
                <form method="POST" action="/login">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-envelope me-1"></i> Email
                        </label>
                        <input type="email" name="email"
                               class="form-control form-control-lg @error('email') is-invalid @enderror"
                               value="{{ old('email') }}"
                               placeholder="admin@junefutsal.com" autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-lock me-1"></i> Password
                        </label>
                        <input type="password" name="password"
                               class="form-control form-control-lg @error('password') is-invalid @enderror"
                               placeholder="Masukkan password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-june btn-lg">
                            <i class="bi bi-box-arrow-in-right me-2"></i> Login
                        </button>
                    </div>
                </form>
            </div>

            <div class="card-footer text-center py-3 bg-transparent border-0">
                <a href="/" class="text-muted text-decoration-none small">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda
                </a>
            </div>
        </div>

    </div>
</div>

@endsection
