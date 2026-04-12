<nav class="navbar navbar-june navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="/">
            <i class="bi bi-dribbble brand-icon me-2"></i>
            June<span style="color: var(--accent);">Futsal</span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">

                {{-- Menu untuk semua pengunjung --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">
                        <i class="bi bi-house-door"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('lapangan*') ? 'active' : '' }}" href="/lapangan">
                        <i class="bi bi-grid-3x3-gap"></i> Lapangan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('booking*') ? 'active' : '' }}" href="/booking">
                        <i class="bi bi-calendar-check"></i> Booking
                    </a>
                </li>

                {{-- Menu khusus admin (hanya muncul jika sudah login) --}}
                @auth
                    @php
                        $pendingCount = \App\Models\Booking::where('status', 'pending')->count();
                    @endphp
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') || request()->is('dashboard') ? 'active' : '' }}" href="/dashboard">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/booking*') ? 'active' : '' }}" href="/admin/booking">
                            <i class="bi bi-clipboard-data"></i> Data Booking
                            @if($pendingCount > 0)
                                <span class="badge rounded-pill" style="background: #ef4444; font-size: 0.7rem;">{{ $pendingCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/lapangan*') ? 'active' : '' }}" href="/admin/lapangan">
                            <i class="bi bi-building"></i> Kelola Lapangan
                        </a>
                    </li>

                    {{-- Tombol Logout --}}
                    <li class="nav-item ms-lg-2">
                        <form action="/logout" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link text-white" style="border: 1px solid rgba(255,255,255,0.3); border-radius: 8px;">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </button>
                        </form>
                    </li>
                @else
                    {{-- Tombol Login (hanya muncul jika belum login) --}}
                    <li class="nav-item ms-lg-2">
                        <a class="nav-link {{ request()->is('login') ? 'active' : '' }}" href="/login"
                           style="border: 1px solid rgba(255,255,255,0.3); border-radius: 8px;">
                            <i class="bi bi-box-arrow-in-right"></i> Login Admin
                        </a>
                    </li>
                @endauth

            </ul>
        </div>
    </div>
</nav>