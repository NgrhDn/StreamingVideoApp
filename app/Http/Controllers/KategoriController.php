<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Multimedia;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::orderBy('created_at', 'desc')->get();
        return view('admin.Kategori', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori,nama_kategori',
        ]);

        Kategori::create([
            'nama_kategori' => $request->nama_kategori,
            'status' => 'Aktif', // default aktif saat buat
        ]);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function toggleStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Aktif,Nonaktif',
        ]);

        $kategori = Kategori::find($id);
        if (!$kategori) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak ditemukan.'
            ], 404);
        }

        // Simpan status baru ke kategori
        $kategori->status = $request->status;
        $kategori->save();

        // Update juga semua video yang punya kategori ini
        Multimedia::where('kategori_id', $kategori->id)->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'new_status' => $request->status,
            'message' => "Kategori dan semua video terkait berhasil diubah menjadi {$request->status}."
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori,nama_kategori,' . $id,
        ]);

        $kategori = Kategori::find($id);
        if (!$kategori) {
            return response()->json(['success' => false, 'message' => 'Kategori tidak ditemukan.']);
        }

        $kategori->nama_kategori = $request->nama_kategori;
        $kategori->save();

        return response()->json(['success' => true, 'message' => 'Kategori berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $kategori = Kategori::find($id);
        if (!$kategori) {
            return response()->json(['success' => false, 'message' => 'Kategori tidak ditemukan.']);
        }

        // Jika ingin juga menghapus multimedia-nya, tambahkan:
        // Multimedia::where('kategori_id', $id)->delete();

        $kategori->delete();

        return response()->json(['success' => true, 'message' => 'Kategori berhasil dihapus.']);
    }


}


