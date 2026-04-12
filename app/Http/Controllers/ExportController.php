<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{
    /**
     * Export Report Booking Harian (PDF)
     */
    public function harian()
    {
        $tanggal = now()->toDateString();

        $bookings = Booking::with('lapangan')
            ->whereDate('tanggal', $tanggal)
            ->where('status', Booking::STATUS_APPROVED)
            ->latest()
            ->get();

        $totalPendapatan = $bookings->sum('harga');

        $periode = 'Hari Ini (' . Carbon::parse($tanggal)->translatedFormat('d F Y') . ')';

        $pdf = Pdf::loadView('exports.booking-pdf', compact('bookings', 'totalPendapatan', 'periode'));

        return $pdf->download('report-booking-harian-' . $tanggal . '.pdf');
    }

    /**
     * Export Report Booking Mingguan (PDF)
     */
    public function mingguan()
    {
        $startOfWeek = Carbon::now()->startOfWeek()->toDateString();
        $endOfWeek = Carbon::now()->endOfWeek()->toDateString();

        $bookings = Booking::with('lapangan')
            ->whereBetween('tanggal', [$startOfWeek, $endOfWeek])
            ->where('status', Booking::STATUS_APPROVED)
            ->latest()
            ->get();

        $totalPendapatan = $bookings->sum('harga');

        $periode = 'Minggu Ini (' . Carbon::parse($startOfWeek)->translatedFormat('d M Y') . ' - ' . Carbon::parse($endOfWeek)->translatedFormat('d M Y') . ')';

        $pdf = Pdf::loadView('exports.booking-pdf', compact('bookings', 'totalPendapatan', 'periode'));

        return $pdf->download('report-booking-mingguan-' . $startOfWeek . '.pdf');
    }

    /**
     * Export Report Booking Bulanan (PDF)
     */
    public function bulanan()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $bookings = Booking::with('lapangan')
            ->whereMonth('tanggal', $currentMonth)
            ->whereYear('tanggal', $currentYear)
            ->where('status', Booking::STATUS_APPROVED)
            ->latest()
            ->get();

        $totalPendapatan = $bookings->sum('harga');

        $periode = 'Bulan ' . Carbon::now()->translatedFormat('F Y');

        $pdf = Pdf::loadView('exports.booking-pdf', compact('bookings', 'totalPendapatan', 'periode'));

        return $pdf->download('report-booking-bulanan-' . now()->format('Y-m') . '.pdf');
    }
}
