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

        // Dekripsi ID untuk keamanan
        $decryptedId = decrypt($id);

        // Cari riwayat diagnosa yang akan dihapus hanya jika milik user yang sedang login
        $history = History::where('user_id', Auth::id())->findOrFail($decryptedId);

        // Hapus data
        $history->delete();

        return redirect()->route('history.index')->with('success', 'Riwayat diagnosa berhasil dihapus.');
    }
}
