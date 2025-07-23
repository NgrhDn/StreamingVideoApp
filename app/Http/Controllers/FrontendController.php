<?php

namespace App\Http\Controllers;

use App\Models\Multimedia;
use App\Models\Kategori;

class FrontendController extends Controller
{

    public function index()
    {
        $videosByKategori = Kategori::with(['multimedia' => function ($query) {
            $query->where('status', 'Aktif')->latest()->take(3); 
        }])->get();

        return view('front.videos', compact('videosByKategori'));
    }

    public function show($id)
    {
        $kategori = Kategori::with(['multimedia' => function ($query) {
            $query->where('status', 'Aktif')->latest(); // ambil semua, tidak dibatasi 3
        }])->findOrFail($id);

        return view('front.kategori_show', compact('kategori'));
    }


}
