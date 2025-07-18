<?php

namespace App\Http\Controllers;

use App\Models\Multimedia;

class FrontendController extends Controller
{
    public function index()
    {
        $videos = Multimedia::where('status', 'Aktif')
                    ->latest()
                    ->paginate(12);

        return view('front.videos', compact('videos'));
    }
}
