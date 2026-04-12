<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lapangan;
use App\Models\Booking;

class BookingController extends Controller
{
    /**
     * Slot jam operasional futsal (08:00 - 23:00)
     */
    private function getSlotJam()
    {
        return [
            '08:00', '09:00', '10:00', '11:00', '12:00',
            '13:00', '14:00', '15:00', '16:00', '17:00',
            '18:00', '19:00', '20:00', '21:00', '22:00', '23:00',
        ];
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
            $bookedSlots[$booking->lapangan_id][$booking->jam] = $booking->nama_pelanggan;
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

        // Hitung harga berdasarkan jam
        $jamAngka = (int) substr($jam, 0, 2);
        $harga = ($jamAngka <= 17) ? $lapangan->harga_pagi : $lapangan->harga_malam;

        return view('booking.create', compact('lapangan', 'tanggal', 'jam', 'harga'));
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
            'jam'            => 'required|string',
        ], [
            'nama_pelanggan.required' => 'Nama pelanggan wajib diisi.',
            'no_hp.required'          => 'Nomor HP wajib diisi.',
            'tanggal.required'        => 'Tanggal wajib diisi.',
            'jam.required'            => 'Jam wajib diisi.',
            'lapangan_id.required'    => 'Lapangan wajib dipilih.',
        ]);

        // Cek apakah slot sudah dibooking (approved)
        $exists = Booking::where('lapangan_id', $request->lapangan_id)
            ->where('tanggal', $request->tanggal)
            ->where('jam', $request->jam)
            ->where('status', Booking::STATUS_APPROVED)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Slot jam ini sudah dibooking. Silakan pilih jam lain.');
        }

        // Hitung harga otomatis
        $lapangan = Lapangan::findOrFail($request->lapangan_id);
        $jamAngka = (int) substr($request->jam, 0, 2);
        $harga = ($jamAngka <= 17) ? $lapangan->harga_pagi : $lapangan->harga_malam;

        $booking = Booking::create([
            'lapangan_id'    => $request->lapangan_id,
            'nama_pelanggan' => $request->nama_pelanggan,
            'no_hp'          => $request->no_hp,
            'tanggal'        => $request->tanggal,
            'jam'            => $request->jam,
            'harga'          => $harga,
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
            . "Harga: Rp " . number_format($booking->harga, 0, ',', '.') . "%0A%0A"
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
        // Cek apakah slot sudah ada yang approved (hindari double booking)
        $exists = Booking::where('lapangan_id', $booking->lapangan_id)
            ->where('tanggal', $booking->tanggal)
            ->where('jam', $booking->jam)
            ->where('status', Booking::STATUS_APPROVED)
            ->where('id', '!=', $booking->id)
            ->exists();

        if ($exists) {
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
            $exists = Booking::where('lapangan_id', $booking->lapangan_id)
                ->where('tanggal', $booking->tanggal)
                ->where('jam', $booking->jam)
                ->where('status', Booking::STATUS_APPROVED)
                ->where('id', '!=', $booking->id)
                ->exists();

            if ($exists) {
                return back()->with('error', 'Slot ini sudah ada booking lain yang di-approve. Tidak bisa approve.');
            }
        }

        $statusLabel = ['pending' => 'Pending', 'approved' => 'Approved', 'cancelled' => 'Cancelled'];

        $booking->update(['status' => $newStatus]);

        return back()->with('success', 'Status booking berhasil diubah menjadi ' . $statusLabel[$newStatus] . '!');
    }
}