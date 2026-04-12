<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use App\Models\Booking;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();
        $startOfWeek = Carbon::now()->startOfWeek()->toDateString();
        $endOfWeek = Carbon::now()->endOfWeek()->toDateString();
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $totalLapangan = Lapangan::count();
        $totalBooking = Booking::count();

        // Booking menunggu approval
        $bookingPending = Booking::where('status', Booking::STATUS_PENDING)->count();

        // Total pendapatan keseluruhan (approved saja)
        $totalPendapatan = Booking::where('status', Booking::STATUS_APPROVED)->sum('harga');

        // ==========================================
        // REPORT: Booking (hanya status = approved)
        // ==========================================

        // Booking Hari Ini (approved)
        $bookingHariIni = Booking::whereDate('tanggal', $today)
            ->where('status', Booking::STATUS_APPROVED)
            ->count();

        // Booking Minggu Ini (approved)
        $bookingMingguIni = Booking::whereBetween('tanggal', [$startOfWeek, $endOfWeek])
            ->where('status', Booking::STATUS_APPROVED)
            ->count();

        // Booking Bulan Ini (approved)
        $bookingBulanIni = Booking::whereMonth('tanggal', $currentMonth)
            ->whereYear('tanggal', $currentYear)
            ->where('status', Booking::STATUS_APPROVED)
            ->count();

        // ==========================================
        // REPORT: Pendapatan (hanya status = approved)
        // ==========================================

        // Pendapatan Hari Ini
        $pendapatanHariIni = Booking::whereDate('tanggal', $today)
            ->where('status', Booking::STATUS_APPROVED)
            ->sum('harga');

        // Pendapatan Minggu Ini
        $pendapatanMingguIni = Booking::whereBetween('tanggal', [$startOfWeek, $endOfWeek])
            ->where('status', Booking::STATUS_APPROVED)
            ->sum('harga');

        // Pendapatan Bulan Ini
        $pendapatanBulanIni = Booking::whereMonth('tanggal', $currentMonth)
            ->whereYear('tanggal', $currentYear)
            ->where('status', Booking::STATUS_APPROVED)
            ->sum('harga');

        // Booking terbaru 5
        $bookingTerbaru = Booking::with('lapangan')->latest()->take(5)->get();

        return view('dashboard.index', compact(
            'totalLapangan',
            'totalBooking',
            'bookingPending',
            'totalPendapatan',
            'bookingHariIni',
            'bookingMingguIni',
            'bookingBulanIni',
            'pendapatanHariIni',
            'pendapatanMingguIni',
            'pendapatanBulanIni',
            'bookingTerbaru'
        ));
    }
}
