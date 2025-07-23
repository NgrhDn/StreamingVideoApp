<!-- resources/views/components/delete-confirmation-modal.blade.php -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-lg shadow-lg">
            <div class="modal-body text-center p-5">
                <i class="fas fa-exclamation-circle text-warning mb-4" style="font-size: 4em;"></i>
                <h4 class="mb-3">Yakin ingin menghapus?</h4>
                <p id="deleteConfirmationModalBody" class="text-muted">Data yang dihapus tidak bisa dikembalikan!</p>
                <div class="d-flex justify-content-center gap-3 mt-4">
                    <button type="button" class="btn btn-danger px-4 py-2 rounded-md" id="confirmDeleteButton">Ya, hapus!</button>
                    <button type="button" class="btn btn-secondary px-4 py-2 rounded-md" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>
</div>
