<?php

namespace App\Http\Controllers;

use App\Models\OutcomingLetter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OutcomingMailController extends Controller
{
    public function index(Request $request)
    {
        $query = OutcomingLetter::query();

        // Filter by date range
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('tanggal_dikirim', [$request->from_date, $request->to_date]);
        }

        $letters = $query->orderBy('created_at', 'desc')->get();

        return view('outcoming-mail', compact('letters'));
    }

    public function store(Request $request)
    {
        // Validasi input termasuk file hanya boleh PDF
        $request->validate([
            'penerima' => 'required|string|max:255',
            'perihal' => 'required|string|max:255',
            'tanggal_dikirim' => 'required|date',
            'tanggal_dibuat' => 'required|date',
            'deskripsi' => 'nullable|string',
            'attachment' => 'required|mimes:pdf|max:10000', // Maksimal 2MB, hanya PDF
        ]);

        // Simpan file ke storage public dengan nama unik (UUID)
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');

            // Generate nama file dengan UUID dan tambahkan ekstensi .pdf
            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();

            // Simpan file dengan nama yang sudah diubah
            $path = $file->storeAs('public/surat_keluar', $fileName);
        }

        // Dapatkan bulan dan tahun dari 'tanggal_dikirim' atau 'tanggal_dibuat'
        $tahunBulan = date('Ym', strtotime($request->input('tanggal_dikirim')));

        // Hitung jumlah surat masuk di bulan dan tahun ini
        $jumlahSurat = OutcomingLetter::whereYear('tanggal_dikirim', date('Y', strtotime($request->input('tanggal_dikirim'))))
            ->whereMonth('tanggal_dikirim', date('m', strtotime($request->input('tanggal_dikirim'))))
            ->count();

        // Generate nomor surat, SM-yyyymmxxx (contoh: SM-202410001)
        $nomorSurat = 'SK-' . $tahunBulan . str_pad($jumlahSurat + 1, 3, '0', STR_PAD_LEFT);

        // Simpan data surat ke database
        OutcomingLetter::create([
            'nomor' => $nomorSurat,
            'penerima' => $request->input('penerima'),
            'perihal' => $request->input('perihal'),
            'tanggal_dikirim' => $request->input('tanggal_dikirim'),
            'tanggal_dibuat' => $request->input('tanggal_dibuat'),
            'deskripsi' => $request->input('deskripsi'),
            'attachment' => $path, // Simpan path dari file yang sudah di-upload
        ]);

        // Redirect ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Surat berhasil ditambahkan.');
    }

    // Fungsi Update
    public function update(Request $request, $id)
    {
        // Validasi input termasuk file hanya boleh PDF
        $request->validate([
            'penerima' => 'required|string|max:255',
            'perihal' => 'required|string|max:255',
            'tanggal_dikirim' => 'required|date',
            'tanggal_dibuat' => 'required|date',
            'deskripsi' => 'nullable|string',
            'attachment' => 'nullable|mimes:pdf|max:2048', // Maksimal 2MB, hanya PDF
        ]);

        // Cari data surat berdasarkan ID
        $letter = OutcomingLetter::findOrFail($id);

        // Proses file jika ada file baru yang di-upload
        if ($request->hasFile('attachment')) {
            // Hapus file lama jika ada
            if ($letter->attachment && Storage::exists($letter->attachment)) {
                Storage::delete($letter->attachment);
            }

            // Upload file baru dengan nama UUID
            $file = $request->file('attachment');
            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/surat_keluar', $fileName);
        } else {
            // Jika file tidak di-upload, gunakan file lama
            $path = $letter->attachment;
        }

        // Update data surat di database
        $letter->update([
            'penerima' => $request->input('penerima'),
            'perihal' => $request->input('perihal'),
            'tanggal_dikirim' => $request->input('tanggal_dikirim'),
            'tanggal_dibuat' => $request->input('tanggal_dibuat'),
            'deskripsi' => $request->input('deskripsi'),
            'attachment' => $path, // Path dari file yang di-upload atau yang lama
        ]);

        // Redirect ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Surat berhasil diperbarui.');
    }

    // Fungsi Delete
    public function destroy($id)
    {
        // Cari surat berdasarkan ID
        $letter = OutcomingLetter::findOrFail($id);

        // Hapus file attachment jika ada
        if ($letter->attachment && Storage::exists($letter->attachment)) {
            Storage::delete($letter->attachment);
        }

        // Hapus surat dari database
        $letter->delete();

        // Redirect ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Surat berhasil dihapus.');
    }
}
