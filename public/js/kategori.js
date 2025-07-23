document.addEventListener('DOMContentLoaded', function () {
    const modalElement = document.getElementById('notifKategoriModal');
    const modalBody = document.getElementById('notifKategoriModalBody');
    const modal = new bootstrap.Modal(modalElement);

    function showNotif(message, title = 'Sukses') {
        document.getElementById('notifKategoriModalLabel').textContent = title;
        modalBody.textContent = message;
        modal.show();
    }

    // Toggle Status
    let selectedStatusId = null;
    let selectedStatusNama = '';
    let selectedStatusValue = '';
    let selectedToggleEl = null;

    document.querySelectorAll('.kategori-status-toggle').forEach(toggle => {
        toggle.addEventListener('click', function (e) {
            // Simpan info yang dibutuhkan
            selectedToggleEl = this;
            selectedStatusId = this.dataset.id;
            selectedStatusNama = this.dataset.nama;
            selectedStatusValue = this.checked ? 'Aktif' : 'Nonaktif';

            // Cegah toggle aktif sekarang
            e.preventDefault();

            // Isi teks di modal konfirmasi
            document.getElementById('statusKategoriNama').textContent = selectedStatusNama;
            document.getElementById('statusBaru').textContent = selectedStatusValue;

            // Tampilkan modal konfirmasi ubah status
            const modal = new bootstrap.Modal(document.getElementById('modalUbahStatus'));
            modal.show();
        });
    });

    document.getElementById('btnConfirmUbahStatus').addEventListener('click', function () {
        fetch(`/kategori/${selectedStatusId}/toggle`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: selectedStatusValue })
        })
        .then(res => res.json())
        .then(data => {
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalUbahStatus'));
            modal.hide();

            if (data.success) {
                // Setelah berhasil ubah status, toggle switch baru aktif
                selectedToggleEl.checked = (selectedStatusValue === 'Aktif');
                showNotif(data.message);
            } else {
                showNotif('❌ ' + data.message, 'Gagal');
            }
        })
        .catch(() => {
            showNotif('Terjadi kesalahan.', 'Error');
        });
    });


    document.getElementById('btnBatalUbahStatus').addEventListener('click', function () {
        // Jangan ubah apapun, biarkan toggle tetap pada posisi sebelumnya
        const modal = bootstrap.Modal.getInstance(document.getElementById('modalUbahStatus'));
        modal.hide();
    });


    // Tombol Edit
    document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;
            const nama = this.dataset.nama;

            document.getElementById('editKategoriId').value = id;
            document.getElementById('editKategoriNama').value = nama;

            const modal = new bootstrap.Modal(document.getElementById('editKategoriModal'));
            modal.show();
        });
    });

    // Form Edit
    document.getElementById('editKategoriForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const id = document.getElementById('editKategoriId').value;
        const nama = document.getElementById('editKategoriNama').value;

        fetch(`/kategori/${id}/update`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ nama_kategori: nama })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showNotif(data.message);
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotif('❌ ' + data.message, 'Gagal');
            }
        })
        .catch(() => {
            showNotif('Terjadi kesalahan saat menyimpan.', 'Error');
        });
    });

    // Tombol Hapus
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;
            const nama = this.dataset.nama;

            // Ubah isi teks konfirmasi
            document.getElementById('hapusJumlah').textContent = `kategori "${nama}"`;

            // Simpan ID sementara untuk aksi konfirmasi
            document.getElementById('btnConfirmHapus').setAttribute('data-id', id);

            // Tampilkan modal
            const modal = new bootstrap.Modal(document.getElementById('modalHapus'));
            modal.show();
        });
    });

    document.getElementById('btnConfirmHapus').addEventListener('click', function () {
        const id = this.getAttribute('data-id');

        fetch(`/kategori/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const modal = bootstrap.Modal.getInstance(document.getElementById('modalHapus'));
                modal.hide();

                showNotif(data.message);
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotif('❌ ' + data.message, 'Gagal');
            }
        })
        .catch(() => {
            showNotif('Terjadi kesalahan saat menghapus.', 'Error');
        });
    });
});
