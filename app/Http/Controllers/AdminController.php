<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use App\Models\Multimedia;
use App\Models\Kategori; // tambahkan ini di atas jika belum ada

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalVideoAktif = \App\Models\Multimedia::where('status', 'aktif')->count();
        $totalVideo = \App\Models\Multimedia::count();
        $totalUser = \App\Models\User::count();

        return view('admin.dashboard', compact('totalVideoAktif', 'totalVideo', 'totalUser'));
    }


    public function multimedia(Request $request)
    {
        $query = Multimedia::query();

        // Filter pencarian
        $searchText = $request->input('search_text');
        $searchBy = $request->input('search_by');
        $searchOperator = $request->input('search_operator');

        if ($searchText) {
            if ($searchBy === 'judul' && $searchOperator === 'contain') {
                $query->where('judul', 'like', '%' . $searchText . '%');
            } elseif ($searchBy === 'link' && $searchOperator === 'contain') {
                $query->where('link', 'like', '%' . $searchText . '%');
            }
        }

        $perPage = $request->input('per_page', 10);
        $multimediaItems = $query->orderByDesc('id')->paginate($perPage)->withQueryString();

        $kategoriList = Kategori::where('status', 'Aktif')->get();


        return view('admin.multimedia', compact('multimediaItems', 'perPage', 'kategoriList'));
    }



    public function storeMultimedia(Request $request)
    {
        $data = $request->only(['judul', 'link', 'status', 'kategori_id']);

        // Cek apakah link sudah ada
        if (Multimedia::where('link', $data['link'])->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Link ini sudah digunakan sebelumnya.'
            ]);
        }

        // Cek apakah judul sudah ada
        if (Multimedia::where('judul', $data['judul'])->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Judul ini sudah digunakan sebelumnya.'
            ]);
        }

        // Simpan jika lolos semua
        $multimedia = Multimedia::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Data multimedia berhasil ditambahkan.',
            'data' => $multimedia
        ]);
    }

    public function toggleMultimediaStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Aktif,Nonaktif',
        ]);

        $item = Multimedia::find($id);
        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.'], 404);
        }

        $item->status = $request->status;
        $item->save();

        // ✅ Jika video diaktifkan, aktifkan juga kategori-nya (jika belum aktif)
        if ($request->status === 'Aktif' && $item->kategori) {
            if ($item->kategori->status !== 'Aktif') {
                $item->kategori->status = 'Aktif';
                $item->kategori->save();
            }
        }

        return response()->json(['success' => true, 'message' => 'Status berhasil diubah.']);
    }


    public function deleteSelectedMultimedia(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer',
        ]);

        $deleted = Multimedia::whereIn('id', $request->ids)->delete();

        if ($deleted > 0) {
            return response()->json(['success' => true, 'message' => 'Data terpilih berhasil dihapus.']);
        }

        return response()->json(['success' => false, 'message' => 'Tidak ada data yang cocok untuk dihapus.'], 404);
    }

    public function updateMultimedia(Request $request, $id)
    {
        $multimedia = Multimedia::find($id);
        if (!$multimedia) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.']);
        }

        // Cek jika judul atau link sudah dipakai oleh item lain
        if (Multimedia::where('judul', $request->judul)->where('id', '!=', $id)->exists()) {
            return response()->json(['success' => false, 'message' => 'Judul sudah digunakan oleh data lain.']);
        }

        if (Multimedia::where('link', $request->link)->where('id', '!=', $id)->exists()) {
            return response()->json(['success' => false, 'message' => 'Link sudah digunakan oleh data lain.']);
        }

        $multimedia->update([
            'judul' => $request->judul,
            'link' => $request->link,
            'status' => $request->status,
            'kategori_id' => $request->kategori_id,
        ]);

        return response()->json(['success' => true, 'message' => 'Data berhasil diperbarui.']);
    }


    public function checkJudulDanLink(Request $request)
    {
        $link = $request->input('link');
        $judul = $request->input('judul');

        $linkExists = Multimedia::where('link', $link)->exists();
        $judulExists = Multimedia::where('judul', $judul)->exists();

        return response()->json([
            'link_exists' => $linkExists,
            'judul_exists' => $judulExists
        ]);
    }

    public function ajaxMultimedia(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $page = $request->get('page', 1);

        $multimediaItems = Multimedia::orderByDesc('id')->paginate($perPage, ['*'], 'page', $page);

        return view('admin.partials.multimedia_table', compact('multimediaItems', 'perPage'))->render();
    }

}
