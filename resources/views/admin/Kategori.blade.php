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
                                        <button class="btn btn-sm btn-warning btn-edit" data-id="{{ $kategori->id }}">
                                            <i class="fas fa-edit"></i>
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

<!-- Modal Notifikasi (disamakan dengan yang versi video) -->
<div class="modal fade" id="notifKategoriModal" tabindex="-1" aria-labelledby="notifKategoriModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
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
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalElement = document.getElementById('notifKategoriModal');
    const modalBody = document.getElementById('notifKategoriModalBody');
    const modal = new bootstrap.Modal(modalElement);

    // 🟩 Fungsi showNotif diperbarui agar bisa ganti judul modal juga
    function showNotif(message, title = 'Sukses') {
        document.getElementById('notifKategoriModalLabel').textContent = title;
        modalBody.textContent = message;
        modal.show();
    }

    document.querySelectorAll('.kategori-status-toggle').forEach(toggle => {
        toggle.addEventListener('change', function () {
            const kategoriId = this.dataset.id;
            const namaKategori = this.dataset.nama;
            const originalState = this.checked;

            fetch(`/kategori/${kategoriId}/toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ status: this.checked ? 'Aktif' : 'Nonaktif' }) // <== Tambahan ini
            })

            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showNotif(`✅ Kategori "${namaKategori}" berhasil diubah menjadi ${data.new_status}.`);
                } else {
                    this.checked = !originalState;
                    showNotif(`❌ Gagal mengubah status kategori "${namaKategori}".`);
                }
            })
            .catch(err => {
                this.checked = !originalState;
                console.error(err);
                showNotif(`🚫 Terjadi kesalahan saat memproses kategori "${namaKategori}".`);
            });
        });
    });
});
</script>
@endpush


