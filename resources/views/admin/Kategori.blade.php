@extends('layouts.admin_app')

@section('page_title', 'Kategori')

@section('content')

<div class="container-fluid">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            Daftar Kategori
        </div>
        <div class="card-body">
            {{-- Form Tambah --}}
            <form action="{{ route('kategori.store') }}" method="POST" class="row g-3 mb-4">
                @csrf
                <div class="col-md-6">
                    <input type="text" name="nama_kategori" class="form-control" placeholder="Nama kategori baru" required>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-success" type="submit"><i class="fas fa-plus"></i> Tambah</button>
                </div>
            </form>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($kategoris->isEmpty())
                <div class="alert alert-warning">Belum ada kategori.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="bg-secondary text-white">
                            <tr>
                                <th>No</th>
                                <th>Nama Kategori</th>
                                <th>Status</th>
                                <th>Jumlah Video</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kategoris as $kategori)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $kategori->nama_kategori }}</td>
                                    <td>
                                        <label class="switch">
                                            <input type="checkbox"
                                                class="kategori-status-toggle"
                                                data-id="{{ $kategori->id }}"
                                                data-nama="{{ $kategori->nama_kategori }}"
                                                {{ $kategori->status == 'Aktif' ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                    <td>{{ $kategori->multimedia()->count() }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-warning btn-edit" 
                                            data-id="{{ $kategori->id }}" 
                                            data-nama="{{ $kategori->nama_kategori }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger btn-delete" 
                                            data-id="{{ $kategori->id }}" 
                                            data-nama="{{ $kategori->nama_kategori }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Ubah Status -->
<div class="modal fade" id="modalUbahStatus" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center p-3">
            <div class="my-3">
                <div class="bg-info rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                    <i class="fas fa-sync-alt fa-2x text-white"></i>
                </div>
            </div>
            <h5 class="fw-bold">Ubah Status Kategori?</h5>
            <p class="mb-4 text-muted">Status kategori <span id="statusKategoriNama">...</span> akan diubah menjadi <span id="statusBaru">...</span>.</p>
            <div class="d-flex justify-content-center gap-2 mb-2">
                <button type="button" class="btn btn-info px-4 text-white" id="btnConfirmUbahStatus">Ya, ubah!</button>
                <button type="button" class="btn btn-secondary px-4" id="btnBatalUbahStatus" data-bs-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Kategori -->
<div class="modal fade" id="editKategoriModal" tabindex="-1" aria-labelledby="editKategoriModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editKategoriForm">
            @csrf
            <input type="hidden" id="editKategoriId">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editKategoriModalLabel">Edit Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="text" id="editKategoriNama" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="modalHapus" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center p-3">
        <div class="my-3">
            <div class="bg-warning rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
            <i class="fas fa-exclamation fa-2x text-white"></i>
            </div>
        </div>
        <h5 class="fw-bold">Yakin ingin menghapus?</h5>
        <p class="mb-4 text-muted">Anda yakin ingin menghapus <span id="hapusJumlah">data ini</span>?</p>
        <div class="d-flex justify-content-center gap-2 mb-2">
            <button type="button" class="btn btn-danger px-4" id="btnConfirmHapus">Ya, hapus!</button>
            <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
        </div>
        </div>
    </div>
</div>


<!-- Modal Notifikasi (disamakan dengan yang versi video) -->
<div class="modal fade" id="notifKategoriModal" tabindex="-1" aria-labelledby="notifKategoriModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-black">
                <h5 class="modal-title" id="notifKategoriModalLabel">Sukses</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body" id="notifKategoriModalBody">
                Pesan notifikasi akan muncul di sini.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Oke</button>
            </div>
        </div>
    </div>
</div>

@endsection
@push('js')
<script src="{{ asset('js/kategori.js') }}"></script>
@endpush