<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lapangan;
use App\Models\Booking;

class BookingController extends Controller
{
    /**
     * Slot jam mulai operasional futsal (08:00 - 22:00)
     * Tutup jam 23:00, jadi start terakhir 22:00.
     */
    private function getSlotJam()
    {
        return [
            '08:00', '09:00', '10:00', '11:00', '12:00',
            '13:00', '14:00', '15:00', '16:00', '17:00',
            '18:00', '19:00', '20:00', '21:00', '22:00',
        ];
    }

    private function hourFromTime(string $time): int
    {
        return (int) substr($time, 0, 2);
    }

    private function parseJamRange(string $jam): array
    {
        if (str_contains($jam, '-')) {
            [$start, $end] = explode('-', $jam);

            return [$this->hourFromTime(trim($start)), $this->hourFromTime(trim($end))];
        }

        $start = $this->hourFromTime($jam);

        return [$start, $start + 1];
    }

    private function formatJamRange(int $startHour, int $endHour): string
    {
        return sprintf('%02d:00-%02d:00', $startHour, $endHour);
    }

    private function calculateTotalPrice(Lapangan $lapangan, int $startHour, int $endHour): int
    {
        $total = 0;

        for ($hour = $startHour; $hour < $endHour; $hour++) {
            $total += ($hour < 17) ? (int) $lapangan->harga_pagi : (int) $lapangan->harga_malam;
        }

        return $total;
    }

    private function hasApprovedOverlap(int $lapanganId, string $tanggal, int $startHour, int $endHour, ?int $excludeBookingId = null): bool
    {
        $query = Booking::where('lapangan_id', $lapanganId)
            ->where('tanggal', $tanggal)
            ->where('status', Booking::STATUS_APPROVED);

        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }

        foreach ($query->get(['id', 'jam']) as $existingBooking) {
            [$existingStart, $existingEnd] = $this->parseJamRange($existingBooking->jam);

            // Overlap: start < existingEnd && end > existingStart
            if ($startHour < $existingEnd && $endHour > $existingStart) {
                return true;
            }
        }

        return false;
    }

    /**
     * Halaman utama booking - menampilkan slot jam per lapangan
     * Slot dianggap penuh jika status approved
     * Slot menampilkan nama pelanggan jika approved
     */
    public function index(Request $request)
    {
        $tanggal = $request->get('tanggal', now()->toDateString());
        $lapangans = Lapangan::all();
        $slotJam = $this->getSlotJam();

        // Ambil booking yang approved pada tanggal yang dipilih
        $bookings = Booking::where('tanggal', $tanggal)
            ->where('status', Booking::STATUS_APPROVED)
            ->get();

        // Buat array: [lapangan_id => ['08:00' => 'Budi', '09:00' => 'Andi', ...]]
        $bookedSlots = [];
        foreach ($bookings as $booking) {
            [$startHour, $endHour] = $this->parseJamRange($booking->jam);

            for ($hour = $startHour; $hour < $endHour; $hour++) {
                $slot = sprintf('%02d:00', $hour);
                $bookedSlots[$booking->lapangan_id][$slot] = $booking->nama_pelanggan;
            }
        }

        return view('booking.index', compact('lapangans', 'slotJam', 'tanggal', 'bookedSlots'));
    }

    /**
     * Form booking - dipanggil saat user klik slot jam
     */
    public function create(Request $request)
    {
        $lapangan = Lapangan::findOrFail($request->lapangan_id);
        $tanggal = $request->tanggal;
        $jam = $request->jam;

        // Default 1 jam (untuk backward compatible)
        $jamMulai = $jam;
        $startHour = $this->hourFromTime($jamMulai);
        $endHour = min($startHour + 1, 23);
        $jamSelesai = sprintf('%02d:00', $endHour);
        $harga = $this->calculateTotalPrice($lapangan, $startHour, $endHour);

        return view('booking.create', compact('lapangan', 'tanggal', 'jamMulai', 'jamSelesai', 'harga'));
    }

    /**
     * Simpan data booking dengan validasi
     * Status default: pending
     */
    public function store(Request $request)
    {
        $request->validate([
            'lapangan_id'    => 'required|exists:lapangans,id',
            'nama_pelanggan' => 'required|string|max:255',
            'no_hp'          => 'required|string|max:20',
            'tanggal'        => 'required|date',
            'jam_mulai'      => 'nullable|string',
            'jam_selesai'    => 'nullable|string',
            'jam'            => 'nullable|string',
            'payment_type'   => 'required|in:dp,lunas',
        ], [
            'nama_pelanggan.required' => 'Nama pelanggan wajib diisi.',
            'no_hp.required'          => 'Nomor HP wajib diisi.',
            'tanggal.required'        => 'Tanggal wajib diisi.',
            'payment_type.required'   => 'Tipe pembayaran wajib dipilih.',
            'lapangan_id.required'    => 'Lapangan wajib dipilih.',
        ]);

        $jamMulai = $request->jam_mulai ?? $request->jam;

        if (empty($jamMulai)) {
            return back()->withInput()->with('error', 'Jam mulai wajib dipilih.');
        }

        $startHour = $this->hourFromTime($jamMulai);

        // Backward compatible: jika jam_selesai tidak ada, anggap durasi 1 jam
        $jamSelesai = $request->jam_selesai ?: sprintf('%02d:00', min($startHour + 1, 23));
        $endHour = $this->hourFromTime($jamSelesai);

        if ($endHour <= $startHour) {
            return back()->withInput()->with('error', 'Jam selesai harus lebih besar dari jam mulai.');
        }

        if ($startHour < 8 || $endHour > 23) {
            return back()->withInput()->with('error', 'Jam booking harus dalam rentang operasional 08:00 - 23:00.');
        }

        // Cek overlap slot booking approved
        if ($this->hasApprovedOverlap((int) $request->lapangan_id, $request->tanggal, $startHour, $endHour)) {
            return back()->with('error', 'Slot jam ini sudah dibooking. Silakan pilih jam lain.');
        }

        // Hitung harga otomatis berdasarkan durasi per jam
        $lapangan = Lapangan::findOrFail($request->lapangan_id);
        $totalPrice = $this->calculateTotalPrice($lapangan, $startHour, $endHour);
        $paymentType = $request->payment_type;
        $payableAmount = $paymentType === 'dp'
            ? (int) round($totalPrice * 0.3)
            : $totalPrice;

        $jamRange = $this->formatJamRange($startHour, $endHour);

        $booking = Booking::create([
            'lapangan_id'    => $request->lapangan_id,
            'nama_pelanggan' => $request->nama_pelanggan,
            'no_hp'          => $request->no_hp,
            'tanggal'        => $request->tanggal,
            'jam'            => $jamRange,
            'harga'          => $payableAmount,
            'total_price'    => $totalPrice,
            'payment_type'   => $paymentType,
            'status'         => Booking::STATUS_PENDING,
        ]);

        // Redirect ke halaman sukses dengan data untuk WhatsApp
        return redirect()->route('booking.sukses', $booking->id);
    }

    /**
     * Halaman sukses booking + tombol WhatsApp
     */
    public function sukses(Booking $booking)
    {
        $booking->load('lapangan');

        // Nomor WhatsApp admin dari config (aman untuk config:cache)
        $noAdmin = preg_replace('/\D+/', '', (string) config('services.fonnte.admin_number'));
        if (str_starts_with($noAdmin, '0')) {
            $noAdmin = '62' . substr($noAdmin, 1);
        }

        // Buat pesan WhatsApp otomatis
        $pesan = "Halo Admin JuneFutsal,%0A%0A"
            . "Saya ingin konfirmasi booking:%0A"
            . "Nama: {$booking->nama_pelanggan}%0A"
            . "Lapangan: {$booking->lapangan->nama_lapangan}%0A"
            . "Tanggal: " . \Carbon\Carbon::parse($booking->tanggal)->format('d-m-Y') . "%0A"
            . "Jam: {$booking->jam}%0A"
            . "Total: Rp " . number_format($booking->total_price ?? $booking->harga, 0, ',', '.') . "%0A"
            . "Pembayaran: " . strtoupper($booking->payment_type ?? 'lunas') . "%0A"
            . "Nominal Bayar: Rp " . number_format($booking->harga, 0, ',', '.') . "%0A%0A"
            . "Mohon segera di-approve. Terima kasih!";

        $waLink = !empty($noAdmin)
            ? "https://wa.me/{$noAdmin}?text={$pesan}"
            : '#';

        return view('booking.sukses', compact('booking', 'waLink'));
    }

    /**
     * Halaman admin - daftar semua data booking
     */
    public function data(Request $request)
    {
        $query = Booking::with('lapangan')->latest();

        // Filter tanggal
        if ($request->filled('tanggal')) {
            $query->where('tanggal', $request->tanggal);
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->paginate(5)->withQueryString();

        return view('booking.data', compact('bookings'));
    }

    /**
     * Admin: Approve booking
     */
    public function approve(Booking $booking)
    {
        [$startHour, $endHour] = $this->parseJamRange($booking->jam);

        if ($this->hasApprovedOverlap($booking->lapangan_id, $booking->tanggal, $startHour, $endHour, $booking->id)) {
            return back()->with('error', 'Slot ini sudah ada booking yang di-approve.');
        }

        $booking->update(['status' => Booking::STATUS_APPROVED]);

        return back()->with('success', 'Booking berhasil di-approve!');
    }

    /**
     * Admin: Cancel booking
     */
    public function cancel(Booking $booking)
    {
        $booking->update(['status' => Booking::STATUS_CANCELLED]);

        return back()->with('success', 'Booking berhasil di-cancel.');
    }

    /**
     * Admin: Update status booking secara fleksibel
     * Bisa ubah ke pending, approved, atau cancelled kapan saja
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,cancelled',
        ]);

        $newStatus = $request->status;

        // Jika ubah ke approved, cek dulu apakah slot sudah ada yang approved (hindari double booking)
        if ($newStatus === Booking::STATUS_APPROVED) {
            [$startHour, $endHour] = $this->parseJamRange($booking->jam);

            if ($this->hasApprovedOverlap($booking->lapangan_id, $booking->tanggal, $startHour, $endHour, $booking->id)) {
                return back()->with('error', 'Slot ini sudah ada booking lain yang di-approve. Tidak bisa approve.');
            }
        }

        $statusLabel = ['pending' => 'Pending', 'approved' => 'Approved', 'cancelled' => 'Cancelled'];

        $booking->update(['status' => $newStatus]);

        return back()->with('success', 'Status booking berhasil diubah menjadi ' . $statusLabel[$newStatus] . '!');
    }
}