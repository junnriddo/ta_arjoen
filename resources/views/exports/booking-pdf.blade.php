<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Report Booking - JuneFutsal</title>
    <style>
        * {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-size: 11.5px;
            color: #111827;
            padding: 22px 26px;
        }

        .header {
            display: table;
            width: 100%;
            margin-bottom: 14px;
            padding-bottom: 12px;
            border-bottom: 2px solid #1a6b3c;
        }

        .header-left {
            display: table-cell;
            vertical-align: middle;
        }

        .header-right {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
        }

        .header h1 {
            font-size: 20px;
            color: #1a6b3c;
            margin-bottom: 2px;
        }

        .header p {
            font-size: 10.5px;
            color: #6b7280;
        }

        .periode {
            margin: 12px 0 14px;
            padding: 10px 12px;
            background: #f0fdf4;
            border-radius: 6px;
            border: 1px solid #bbf7d0;
        }

        .periode h3 {
            font-size: 13px;
            color: #166534;
            text-align: center;
        }

        .summary {
            margin-bottom: 12px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 10px 12px;
            background: #f9fafb;
        }

        .summary-row {
            display: table;
            width: 100%;
        }

        .summary-item {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .summary-label {
            font-size: 10px;
            color: #6b7280;
            margin-bottom: 4px;
        }

        .summary-value {
            font-size: 12px;
            font-weight: bold;
            color: #111827;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
            border: 1px solid #e5e7eb;
        }

        table thead th {
            background: #1a6b3c;
            color: #fff;
            padding: 8px 7px;
            font-size: 10.5px;
            text-align: left;
            border-right: 1px solid rgba(255, 255, 255, 0.2);
        }

        table thead th:last-child {
            border-right: none;
        }

        table tbody td {
            padding: 7px 7px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 10.5px;
        }

        table tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        .total-row {
            background: #f0fdf4 !important;
        }

        .total-row td {
            font-weight: bold;
            font-size: 11px;
            border-top: 2px solid #1a6b3c;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .badge {
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 9.5px;
            color: #fff;
            display: inline-block;
        }

        .badge-approved {
            background: #22c55e;
        }

        .footer {
            text-align: center;
            margin-top: 18px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
            font-size: 9.5px;
            color: #9ca3af;
        }

        .empty-msg {
            text-align: center;
            padding: 30px;
            color: #9ca3af;
        }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="header">
        <div class="header-left">
            <h1>JuneFutsal</h1>
            <p>Sistem Booking Lapangan Futsal Indoor</p>
        </div>
        <div class="header-right">
            <p>Report Booking</p>
            <p>{{ now()->translatedFormat('d F Y') }}</p>
        </div>
    </div>

    {{-- Periode --}}
    <div class="periode">
        <h3>Report Booking — {{ $periode }}</h3>
    </div>

    <div class="summary">
        <div class="summary-row">
            <div class="summary-item">
                <div class="summary-label">Total Booking (Approved)</div>
                <div class="summary-value">{{ $bookings->count() }} Booking</div>
            </div>
            <div class="summary-item text-right">
                <div class="summary-label">Total Pendapatan</div>
                <div class="summary-value">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    {{-- Tabel --}}
    @if($bookings->count() > 0)
        <table>
            <thead>
                <tr>
                    <th class="text-center" style="width: 32px;">No</th>
                    <th>Nama Pelanggan</th>
                    <th>No HP</th>
                    <th>Lapangan</th>
                    <th class="text-center" style="width: 80px;">Tanggal</th>
                    <th class="text-center" style="width: 55px;">Jam</th>
                    <th class="text-right" style="width: 95px;">Harga</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $index => $b)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $b->nama_pelanggan }}</td>
                    <td>{{ $b->no_hp }}</td>
                    <td>{{ $b->lapangan->nama_lapangan }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($b->tanggal)->format('d/m/Y') }}</td>
                    <td class="text-center">{{ $b->jam }}</td>
                    <td class="text-right">Rp {{ number_format($b->harga, 0, ',', '.') }}</td>
                    <td class="text-center">
                        <span class="badge badge-approved">Approved</span>
                    </td>
                </tr>
                @endforeach
                {{-- Total --}}
                <tr class="total-row">
                    <td colspan="6" class="text-right">Total Pendapatan:</td>
                    <td class="text-right">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $bookings->count() }} booking</td>
                </tr>
            </tbody>
        </table>
    @else
        <div class="empty-msg">
            <p>Tidak ada data booking approved pada periode ini.</p>
        </div>
    @endif

    {{-- Footer --}}
    <div class="footer">
        <p>Dicetak pada: {{ now()->translatedFormat('d F Y, H:i') }} WIB</p>
        <p>© {{ date('Y') }} JuneFutsal — Sistem Booking Lapangan Futsal</p>
    </div>

</body>
</html>
