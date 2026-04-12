<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JuneFutsal - @yield('title', 'Booking Lapangan Futsal')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #1a6b3c;
            --primary-dark: #0f4d2a;
            --primary-light: #2e8b57;
            --accent: #f59e0b;
            --accent-dark: #d97706;
            --dark: #1a1a2e;
            --darker: #16213e;
            --light-bg: #f0fdf4;
        }

        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #f8fafc;
            min-height: 100vh;
        }

        /* Navbar */
        .navbar-june {
            background: linear-gradient(135deg, var(--dark) 0%, var(--primary-dark) 100%);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            padding: 0.8rem 0;
        }

        .navbar-june .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            color: #fff;
            letter-spacing: 1px;
        }

        .navbar-june .navbar-brand .brand-icon {
            color: var(--accent);
        }

        .navbar-june .nav-link {
            color: rgba(255, 255, 255, 0.85);
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            margin: 0 2px;
        }

        .navbar-june .nav-link:hover,
        .navbar-june .nav-link.active {
            color: #fff;
            background: rgba(255, 255, 255, 0.15);
        }

        .navbar-june .nav-link i {
            margin-right: 6px;
        }

        /* Hero pattern on content area */
        .content-wrapper {
            padding-top: 2rem;
            padding-bottom: 3rem;
            min-height: calc(100vh - 70px);
        }

        /* Cards */
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        /* Buttons */
        .btn-june {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            padding: 0.6rem 1.5rem;
            transition: all 0.3s ease;
        }

        .btn-june:hover {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(26, 107, 60, 0.4);
        }

        .btn-accent {
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dark) 100%);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            padding: 0.6rem 1.5rem;
            transition: all 0.3s ease;
        }

        .btn-accent:hover {
            background: linear-gradient(135deg, var(--accent-dark) 0%, #b45309 100%);
            color: #fff;
            transform: translateY(-1px);
        }

        /* Footer */
        .footer-june {
            background: linear-gradient(135deg, var(--dark) 0%, var(--primary-dark) 100%);
            color: rgba(255, 255, 255, 0.7);
            padding: 1.5rem 0;
            text-align: center;
            font-size: 0.9rem;
        }

        /* Slot grid styles */
        .slot-available {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 12px 8px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: block;
            text-align: center;
        }

        .slot-available:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(34, 197, 94, 0.5);
            color: #fff;
        }

        .slot-booked {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 12px 8px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: not-allowed;
            display: block;
            text-align: center;
            opacity: 0.85;
        }

        .slot-pending {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 12px 8px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: not-allowed;
            display: block;
            text-align: center;
            opacity: 0.85;
        }

        /* WhatsApp button */
        .btn-wa {
            background: linear-gradient(135deg, #25d366 0%, #128c7e 100%);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            padding: 0.6rem 1.5rem;
            transition: all 0.3s ease;
        }

        .btn-wa:hover {
            background: linear-gradient(135deg, #128c7e 0%, #075e54 100%);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(37, 211, 102, 0.4);
        }

        /* Status badges */
        .badge-pending {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #fff;
        }

        .badge-approved {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: #fff;
        }

        .badge-cancelled {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: #fff;
        }

        /* Page title */
        .page-title {
            font-weight: 700;
            color: var(--dark);
            position: relative;
            padding-bottom: 10px;
        }

        .page-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
            border-radius: 2px;
        }

        /* Stat cards */
        .stat-card {
            border-radius: 16px;
            padding: 1.5rem;
            color: #fff;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: -20px;
            right: -20px;
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .stat-card .stat-icon {
            font-size: 2.5rem;
            opacity: 0.3;
            position: absolute;
            right: 20px;
            top: 20px;
        }

        /* Table styling */
        .table-june thead {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: #fff;
        }

        .table-june thead th {
            border: none;
            padding: 14px 16px;
            font-weight: 600;
        }

        .table-june thead th:first-child {
            border-radius: 12px 0 0 0;
        }

        .table-june thead th:last-child {
            border-radius: 0 12px 0 0;
        }

        .table-june tbody td {
            padding: 12px 16px;
            vertical-align: middle;
        }

        /* Alert styles */
        .alert-june-success {
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            border-left: 4px solid #22c55e;
            color: #166534;
            border-radius: 12px;
        }

        .alert-june-error {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            border-left: 4px solid #ef4444;
            color: #991b1b;
            border-radius: 12px;
        }

        /* Lapangan card */
        .lapangan-card {
            border-radius: 20px;
            overflow: hidden;
            border: 2px solid #e2e8f0;
        }

        .lapangan-card .lapangan-header {
            padding: 1.2rem 1.5rem;
            color: #fff;
            font-weight: 700;
        }

        .lapangan-header-a {
            background: linear-gradient(135deg, #1a6b3c, #2e8b57);
        }

        .lapangan-header-b {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
        }

        .lapangan-card .lapangan-body {
            padding: 1.5rem;
        }
    </style>

    @stack('styles')
</head>
<body>

    @include('includes.navbar')

    <div class="content-wrapper">
        <div class="container">
            @yield('content')
        </div>
    </div>

    <footer class="footer-june">
        <div class="container">
            <p class="mb-0">
                <i class="bi bi-dribbble"></i>
                &copy; {{ date('Y') }} <strong>JuneFutsal</strong> — Sistem Booking Lapangan Futsal
            </p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>