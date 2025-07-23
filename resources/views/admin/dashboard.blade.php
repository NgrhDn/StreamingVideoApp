@extends('layouts.admin_app')

@section('page_title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h4>Welcome Back, <strong>{{ Auth::user()->name }}</strong></h4>
            <p>Anda login sebagai <strong>Super Admin</strong>.</p>
        </div>
        
    </div>
        <div class="row mt-4">
        <!-- Total Video -->
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total Video</h5>
                    <p class="card-text fs-3 fw-bold">{{ $totalVideo ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Video yang Aktif</h5>
                    <p class="card-text fs-3 fw-bold">{{ $totalVideoAktif ?? 0 }}</p>
                </div>
            </div>
        </div>


        <!-- Total Pengguna -->
        <div class="col-md-4">
            <div class="card text-white bg-info mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total Pengguna</h5>
                    <p class="card-text fs-3 fw-bold">{{ $totalUser ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>


    {{-- Navigasi Cepat / Statistik --}}
    <div class="row">
        <!-- Card Multimedia -->
        <div class="col-md-4 mb-3">
            <a href="{{ route('multimedia.index') }}" class="text-decoration-none text-dark">
                <div class="card card-hover shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-film fa-2x text-primary"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Data Multimedia</h6>
                            <small>Lihat semua video yang ditambahkan</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Tambahkan lebih banyak kartu di sini jika ada fitur lain -->
    </div>
</div>
@endsection
