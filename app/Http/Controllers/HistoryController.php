<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\History;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    /**
     * Menampilkan daftar riwayat diagnosa pengguna.
     */
    public function index()
    {
        // Ambil riwayat terbaru (dengan relasi animal dan disease)
        $latestHistory = History::where('user_id', Auth::id())
            ->with(['disease', 'animal'])
            ->latest()
            ->first();

        // Ambil daftar riwayat dengan paginasi (dengan relasi animal dan disease)
        $histories = History::where('user_id', Auth::id())
            ->with(['disease', 'animal'])
            ->latest()
            ->paginate(10);

        return view('history.index', compact('latestHistory', 'histories'));
    }


    /**
     * Menampilkan detail riwayat diagnosa tertentu.
     */
    public function show($id)
    {
        // Ambil riwayat diagnosa berdasarkan ID dan pastikan hanya milik user yang sedang login
        $history = History::where('user_id', Auth::id())->findOrFail($id);

        return view('history.show', compact('history'));
    }

    /**
     * Menghapus riwayat diagnosa tertentu.
     */
    public function destroy($id)
    {
        try {
            // Dekripsi ID untuk keamanan
            $decryptedId = decrypt($id);

            // Cari riwayat diagnosa yang akan dihapus hanya jika milik user yang sedang login
            $history = History::where('user_id', Auth::id())->findOrFail($decryptedId);

            // Hapus data
            $history->delete();

            return redirect()->route('history.index')->with('success', 'Riwayat diagnosa berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('history.index')->with('error', 'Gagal menghapus riwayat diagnosa. Error: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus semua riwayat diagnosa pengguna.
     */
    public function destroyAll()
    {
        try {
            // Ambil semua riwayat milik user yang sedang login
            $deletedCount = History::where('user_id', Auth::id())->delete();

            if ($deletedCount > 0) {
                return redirect()->route('history.index')->with('success', "Berhasil menghapus {$deletedCount} riwayat diagnosa.");
            } else {
                return redirect()->route('history.index')->with('info', 'Tidak ada riwayat diagnosa yang dihapus.');
            }
        } catch (\Exception $e) {
            return redirect()->route('history.index')->with('error', 'Gagal menghapus semua riwayat diagnosa. Error: ' . $e->getMessage());
        }
    }
}
