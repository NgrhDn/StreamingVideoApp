@extends('layouts.admin_app')

@section('page_title', 'Multimedia')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            Daftar Data
        </div>
        <div class="card-body">
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <div>
                    <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#addMultimediaModal">
                        <i class="fas fa-plus"></i> Tambah baru
                    </button>
                    <button class="btn btn-danger" id="deleteSelectedButton">
                        <i class="fas fa-trash"></i> Hapus Terpilih
                    </button>
                </div>
            </div>
            
            <div id="tableContainer">
                @include('admin.partials.multimedia_table')
            </div>
            
            <div class="mb-3 d-flex align-items-center gap-2">
                <label for="per_page" class="form-label mb-0">Tampilkan</label>
                <select id="per_page" class="form-select w-auto">
                    <option value="10" {{ request('per_page', $perPage ?? 10) == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('per_page', $perPage ?? 10) == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page', $perPage ?? 10) == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('per_page', $perPage ?? 10) == 100 ? 'selected' : '' }}>100</option>
                </select>
                <span class="ms-1">data per halaman</span>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Baru -->
<div class="modal fade" id="addMultimediaModal" tabindex="-1" aria-labelledby="addMultimediaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="addMultimediaForm" action="{{ route('multimedia.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMultimediaModalLabel">Tambah Data Video Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="addJudul" class="form-label">Judul Video</label>
                        <input type="text" class="form-control" id="addJudul" name="judul" required>
                    </div>
                    <div class="mb-3">
                        <label for="addLink" class="form-label">Link</label>
                        <input type="text" class="form-control" id="addLink" name="link" required>
                    </div>
                    <div class="mb-3">
                        <select class="form-select" id="addKategori" name="kategori_id" required>
                            @if ($kategoriList->isEmpty())
                                <option selected disabled>Belum ada kategori aktif</option>
                            @else
                                <option value="" disabled {{ old('kategori_id') ? '' : 'selected' }}>Pilih Kategori</option>
                                @foreach ($kategoriList as $kategori)
                                    <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->nama_kategori }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label d-block">Status</label>
                        <input type="hidden" name="status" value="Nonaktif">
                        <label class="switch">
                            <input type="checkbox" id="addStatus" name="status" value="Aktif" checked>
                            <span class="slider round"></span>
                        </label>
                        <small class="text-muted ms-2">Centang untuk status Aktif</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Data -->
<div class="modal fade" id="editMultimediaModal" tabindex="-1" aria-labelledby="editMultimediaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editMultimediaForm">
            @csrf
            <input type="hidden" id="editId">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editMultimediaModalLabel">Edit Data Video</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editJudul" class="form-label">Judul Video</label>
                        <input type="text" class="form-control" id="editJudul" name="judul" required>
                    </div>
                    <div class="mb-3">
                        <label for="editLink" class="form-label">Link</label>
                        <input type="text" class="form-control" id="editLink" name="link" required>
                    </div>
                    <div class="mb-3">
                        <label for="editKategori" class="form-label">Kategori</label>
                        <select class="form-select" id="editKategori" name="kategori_id" required>
                            @foreach ($kategoriList as $kategori)
                                <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label d-block">Status</label>
                        <input type="hidden" name="status" value="Nonaktif">
                        <label class="switch">
                            <input type="checkbox" id="editStatus" name="status" value="Aktif">
                            <span class="slider round"></span>
                        </label>
                        <small class="text-muted ms-2">Centang untuk status Aktif</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Include modal tambahan -->
@include('components.delete-confirmation-modal')
@include('components.notification-modal')

@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if ($errors->any())
            var addModal = new bootstrap.Modal(document.getElementById('addMultimediaModal'));
            addModal.show();
        @endif

    
        // Handler tombol edit
        document.querySelectorAll('.btn-edit').forEach(function(button) {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const judul = this.getAttribute('data-judul');
                const link = this.getAttribute('data-link');
                // awal pembaruan
                const kategori = this.getAttribute('data-kategori');
                // akhir pembaruan
                const status = this.getAttribute('data-status');

                document.getElementById('editId').value = id;
                document.getElementById('editJudul').value = judul;
                document.getElementById('editLink').value = link;
                //awal pembaruan
                document.getElementById('editKategori').value = kategori;
                //akhir pembaruan
                document.getElementById('editStatus').checked = (status === 'Aktif');

                var editModal = new bootstrap.Modal(document.getElementById('editMultimediaModal'));
                editModal.show();
            });
        });
    });
</script>
@endpush
