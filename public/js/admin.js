
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content'); // Ambil elemen content
            const sidebarToggle = document.getElementById('sidebarToggle');

            if (sidebarToggle && sidebar && content) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    content.classList.toggle('shifted'); // Tambah/hapus kelas 'shifted' pada content
                });
            } else {
                console.warn('Elemen sidebar, content, atau sidebarToggle tidak ditemukan.');
            }

            // Notifikasi Modal Logic
            const notificationModalElement = document.getElementById('notificationModal');
            let notificationModal;
            if (notificationModalElement) {
                notificationModal = new bootstrap.Modal(notificationModalElement);
            }
            const notificationModalTitle = document.getElementById('notificationModalLabel');
            const notificationModalBody = document.getElementById('notificationModalBody');

            window.showNotification = function(title, message) {
                if (notificationModalTitle && notificationModalBody && notificationModal) {
                    notificationModalTitle.textContent = title;
                    notificationModalBody.textContent = message;
                    notificationModal.show();
                } else {
                    alert(title + ": " + message); // Fallback jika modal tidak ditemukan
                }
            };

            // Konfirmasi Hapus Modal Logic (untuk tombol hapus terpilih)
            const deleteConfirmationModalElement = document.getElementById('deleteConfirmationModal');
            let deleteConfirmationModal;
            if (deleteConfirmationModalElement) {
                deleteConfirmationModal = new bootstrap.Modal(deleteConfirmationModalElement);
            }
            const confirmDeleteButton = document.getElementById('confirmDeleteButton');
            let deleteActionCallback = null;

            window.showDeleteConfirmation = function(message, callback) {
                if (document.getElementById('deleteConfirmationModalBody') && deleteConfirmationModal) {
                    document.getElementById('deleteConfirmationModalBody').textContent = message;
                    deleteActionCallback = callback;
                    deleteConfirmationModal.show();
                } else {
                    if (confirm(message)) { // Fallback konfirmasi browser
                        callback();
                    }
                }
            };

            if (confirmDeleteButton) {
                confirmDeleteButton.addEventListener('click', function() {
                    if (deleteActionCallback) {
                        deleteActionCallback();
                    }
                    if (deleteConfirmationModal) {
                        deleteConfirmationModal.hide();
                    }
                });
            }

            // Event listener untuk tombol toggle status (di halaman multimedia)
            document.querySelectorAll('.status-toggle').forEach(toggle => {
                toggle.addEventListener('change', function() {
                    const itemId = this.dataset.id;
                    const judul = this.dataset.judul;
                    const newStatus = this.checked ? 'Aktif' : 'Nonaktif';


                    fetch(`/multimedia/${itemId}/toggle-status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ status: newStatus })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification('Sukses', `Status video "${judul}" berhasil diubah menjadi ${newStatus}.`);
                        } else {
                            showNotification('Gagal', `Gagal mengubah status video "${judul}".`);
                            this.checked = !this.checked; // Kembalikan toggle jika gagal
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('Error', `Terjadi kesalahan saat mengubah status video "${judul}".`);
                        this.checked = !this.checked; // Kembalikan toggle jika error
                    });
                });
            });

            // Toggle Status Kategori
            document.querySelectorAll('.kategori-status-toggle').forEach(function (checkbox) {
                checkbox.addEventListener('change', function () {
                    const kategoriId = this.dataset.id;
                    const namaKategori = this.dataset.nama;
                    const statusBaru = this.checked ? 'Aktif' : 'Nonaktif';

                    if (confirm(`Apakah kamu yakin ingin mengubah status kategori "${namaKategori}" menjadi ${statusBaru}?`)) {
                        fetch(`/kategori/${kategoriId}/toggle`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ status: statusBaru })
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    alert(data.message);
                                } else {
                                    alert('Gagal mengubah status kategori.');
                                    checkbox.checked = !checkbox.checked;
                                }
                            })
                            .catch(error => {
                                alert('Terjadi kesalahan. Silakan coba lagi.');
                                checkbox.checked = !checkbox.checked;
                            });
                    } else {
                        // Batalkan toggle
                        this.checked = !this.checked;
                    }
                });
            });


            // Logika Select All Checkbox (di halaman multimedia)
            const selectAllCheckbox = document.getElementById('selectAll');
            const itemCheckboxes = document.querySelectorAll('.item-checkbox');

            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    itemCheckboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                });
            }

            if (itemCheckboxes.length > 0) {
                itemCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        if (!this.checked) {
                            if (selectAllCheckbox) selectAllCheckbox.checked = false;
                        } else {
                            const allChecked = Array.from(itemCheckboxes).every(cb => cb.checked);
                            if (selectAllCheckbox) selectAllCheckbox.checked = allChecked;
                        }
                    });
                });
            }

            // Logika Hapus Terpilih (di halaman multimedia)
            const deleteSelectedButton = document.getElementById('deleteSelectedButton');
            if (deleteSelectedButton) {
                deleteSelectedButton.addEventListener('click', function() {
                    const selectedIds = Array.from(itemCheckboxes)
                                        .filter(checkbox => checkbox.checked)
                                        .map(checkbox => checkbox.value);

                    if (selectedIds.length === 0) {
                        window.showNotification('Peringatan', 'Pilih setidaknya satu data untuk dihapus.');
                        return;
                    }

                    window.showDeleteConfirmation('Anda yakin ingin menghapus ' + selectedIds.length + ' data yang terpilih?', function() {
                        fetch('/multimedia/delete-selected', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ ids: selectedIds })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                window.showNotification('Sukses', data.message || 'Data terpilih berhasil dihapus.');
                                location.reload(); // Untuk demo, kita reload halaman
                            } else {
                                window.showNotification('Gagal', data.message || 'Gagal menghapus data terpilih.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            window.showNotification('Error', 'Terjadi kesalahan saat menghapus data.');
                        });
                    });
                });
            }

            // Logika Tambah Data Multimedia (Modal) (di halaman multimedia)
            const addMultimediaForm = document.getElementById('addMultimediaForm');
            const addLinkInput = document.getElementById('addLink');
            const linkFeedback = document.getElementById('linkFeedback');

            if (addMultimediaForm) {
                addMultimediaForm.addEventListener('submit', function(event) {
                    event.preventDefault();

                    const judul = document.getElementById('addJudul').value;
                    const link = document.getElementById('addLink').value;
                    const status = document.getElementById('addStatus').checked ? 'Aktif' : 'Nonaktif';
                    const kategoriId = document.getElementById('addKategori').value;

                    fetch('/multimedia/check-unique', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: new URLSearchParams({ link: link, judul: judul })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.link_exists) {
                            window.showNotification('Peringatan', 'Link ini sudah digunakan, silakan gunakan link lain.');
                        } else if (data.judul_exists) {
                            window.showNotification('Peringatan', 'Judul ini sudah digunakan, silakan gunakan judul lain.');
                        } else {
                            // Simpan ke server
                            fetch('/multimedia', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: new URLSearchParams({
                                    judul: judul,
                                    link: link,
                                    status: status,
                                    kategori_id: kategoriId
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    window.showNotification('Sukses', data.message);
                                    const addModal = bootstrap.Modal.getInstance(document.getElementById('addMultimediaModal'));
                                    if (addModal) addModal.hide();
                                    addMultimediaForm.reset();
                                    location.reload();
                                } else {
                                    window.showNotification('Gagal', data.message);
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                window.showNotification('Error', 'Terjadi kesalahan saat menambahkan data.');
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error checking uniqueness:', error);
                        window.showNotification('Error', 'Terjadi kesalahan saat memeriksa keunikan data.');
                    });

                });
            }
            
            // Logika untuk tombol Edit
            document.querySelectorAll('.btn-edit').forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.dataset.id;
                    const judul = this.dataset.judul;
                    const link = this.dataset.link;
                    const status = this.dataset.status;
                    const kategori_id = this.dataset.kategoriId;

                    document.getElementById('editId').value = id;
                    document.getElementById('editJudul').value = judul;
                    document.getElementById('editLink').value = link;
                    document.getElementById('editStatus').checked = (status === 'Aktif');
                    
                    const kategoriSelect = document.getElementById('editKategori');
                    if (kategoriSelect) {
                        kategoriSelect.value = kategori_id;
                    }

                    const editModal = new bootstrap.Modal(document.getElementById('editMultimediaModal'));
                    editModal.show();
                });
            });


            // Submit form edit
            document.getElementById('editMultimediaForm').addEventListener('submit', function (e) {
                e.preventDefault();

                const id = document.getElementById('editId').value;
                const judul = document.getElementById('editJudul').value;
                const link = document.getElementById('editLink').value;
                const status = document.getElementById('editStatus').checked ? 'Aktif' : 'Nonaktif';
                const kategoriId = document.getElementById('editKategori').value;

                fetch(`/multimedia/${id}/update`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: new URLSearchParams({
                        judul: judul,
                        link: link,
                        status: status,
                        kategori_id: kategoriId
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        window.showNotification('Sukses', data.message);
                        const editModal = bootstrap.Modal.getInstance(document.getElementById('editMultimediaModal'));
                        editModal.hide();
                        location.reload();
                    } else {
                        window.showNotification('Gagal', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    window.showNotification('Error', 'Terjadi kesalahan saat menyimpan perubahan.');
                });
            });

        });
const perPageSelect = document.getElementById('per_page');
const tableContainer = document.getElementById('tableContainer');

function loadTable(perPage, page = 1) {
    console.log('Load Table => perPage:', perPage, 'page:', page);

    fetch(`/multimedia/ajax?per_page=${perPage}&page=${page}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        tableContainer.innerHTML = html;
        attachPaginationLinks(); // untuk tombol pagination
        attachEventListeners();  // untuk tombol toggle/edit dsb

    })
    .catch(error => {
        console.error('Gagal memuat data:', error);
    });
}

function attachPaginationLinks() {
    const links = tableContainer.querySelectorAll('.pagination a');

    links.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const url = new URL(this.href);
            const page = url.searchParams.get('page');
            const perPage = document.getElementById('per_page').value;
            loadTable(perPage, page);
        });
    });
}

function attachEventListeners() {
    // Toggle status
    document.querySelectorAll('.status-toggle').forEach(toggle => {
        toggle.addEventListener('change', function () {
            const itemId = this.dataset.id;
            const newStatus = this.checked ? 'Aktif' : 'Nonaktif';

            fetch(`/multimedia/${itemId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ status: newStatus })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Sukses', data.message);
                } else {
                    showNotification('Gagal', data.message);
                    this.checked = !this.checked;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error', 'Terjadi kesalahan saat mengubah status.');
                this.checked = !this.checked;
            });
        });
    });

    // Tombol Edit
    document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;
            const judul = this.dataset.judul;
            const link = this.dataset.link;
            const status = this.dataset.status;
            const kategori_id = this.dataset.kategoriId;

            document.getElementById('editId').value = id;
            document.getElementById('editJudul').value = judul;
            document.getElementById('editLink').value = link;
            document.getElementById('editStatus').checked = (status === 'Aktif');

            const kategoriSelect = document.getElementById('editKategori');
            if (kategoriSelect) {
                kategoriSelect.value = kategori_id;
            }

            const editModal = new bootstrap.Modal(document.getElementById('editMultimediaModal'));
            editModal.show();
        });
    });

    // Checkbox logic: Select All
    const selectAllCheckbox = document.getElementById('selectAll');
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function () {
            itemCheckboxes.forEach(cb => cb.checked = this.checked);
        });
    }

    itemCheckboxes.forEach(cb => {
        cb.addEventListener('change', function () {
            if (!this.checked && selectAllCheckbox) {
                selectAllCheckbox.checked = false;
            } else if (Array.from(itemCheckboxes).every(cb => cb.checked)) {
                if (selectAllCheckbox) selectAllCheckbox.checked = true;
            }
        });
    });
}

if (perPageSelect) {
    perPageSelect.addEventListener('change', function () {
        const perPage = this.value;
        loadTable(perPage, 1);
    });

    attachPaginationLinks(); // Jalankan pertama kali
}
