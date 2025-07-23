<!-- CDN untuk HLS -->
<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>

<div class="table-responsive">
    @if ($multimediaItems->isEmpty())
        <table class="table table-bordered table-hover">
            <thead class="bg-secondary text-white">
                <tr>
                    <th style="width: 50px;"><input type="checkbox" class="form-check-input" id="selectAll"></th>
                    <th>Judul</th>
                    <th>Link</th>
                    <th>Kategori</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="5" class="text-center text-danger py-4">Data kosong</td>
                </tr>
            </tbody>
        </table>
    @else
        <table class="table table-bordered table-hover" id="multimediaTable">
            <thead class="bg-secondary text-white">
                <tr>
                    <th style="width: 50px;"><input type="checkbox" class="form-check-input" id="selectAll"></th>
                    <th>Judul</th>
                    <th>Link</th>
                    <th>Kategori</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($multimediaItems as $item)
                    <tr>
                        <td><input type="checkbox" class="form-check-input item-checkbox" value="{{ $item->id }}"></td>
                        <td>{{ $item->judul }}</td>
                        <td>
                            <a href="{{ $item->link }}" target="_blank" class="text-decoration-none">
                                {{ \Illuminate\Support\Str::limit($item->link, 50) }}
                            </a>
                        </td>
                        <td>
                            {{ $item->kategori->nama_kategori ?? '-' }}
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="status-toggle" data-id="{{ $item->id }}" {{ $item->status == 'Aktif' ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="#" class="btn btn-sm btn-info btn-lihat" 
                                    title="Lihat"
                                    data-link="{{ $item->link }}"
                                    data-title="{{ $item->judul }}">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <a href="#" class="btn btn-sm btn-warning btn-edit"
                                    data-id="{{ $item->id }}"
                                    data-judul="{{ $item->judul }}"
                                    data-link="{{ $item->link }}"
                                    data-status="{{ $item->status }}"
                                    data-kategori-id="{{ $item->kategori_id }}">
                                    <i class="fas fa-edit"></i>
                                </a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Modal Video --}}
        <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="videoModalLabel">Lihat Video</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="ratio ratio-16x9" id="videoContainer">
                            <!-- Diisi oleh JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $multimediaItems->links('pagination::bootstrap-4') }}
        </div>
    @endif
</div>

{{-- Script Modal Video --}}
<script>
    // Delegated event listener agar tetap berfungsi di pagination
    document.addEventListener('click', function (event) {
        const target = event.target.closest('.btn-lihat');
        if (!target) return;

        event.preventDefault();
        const link = target.dataset.link;
        const title = target.dataset.title || 'Lihat Video';

        const videoContainer = document.getElementById('videoContainer');
        const videoModal = new bootstrap.Modal(document.getElementById('videoModal'));
        const modalTitle = document.getElementById('videoModalLabel');

        // Reset konten
        videoContainer.innerHTML = '';

        // Set judul modal
        modalTitle.textContent = title;

        if (link.includes('youtube.com') || link.includes('youtu.be')) {
            const embedLink = convertToEmbedYoutube(link);
            videoContainer.innerHTML = `<iframe class="w-100 h-100" src="${embedLink}" frameborder="0" allowfullscreen></iframe>`;
        } else if (link.endsWith('.m3u8')) {
            const videoHTML = `<video id="videoPlayer" class="w-100 h-100" controls autoplay></video>`;
            videoContainer.innerHTML = videoHTML;

            const video = document.getElementById('videoPlayer');

            if (Hls.isSupported()) {
                const hls = new Hls();
                hls.loadSource(link);
                hls.attachMedia(video);
                hls.on(Hls.Events.MANIFEST_PARSED, function () {
                    video.play();
                });
            } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                video.src = link;
                video.addEventListener('loadedmetadata', function () {
                    video.play();
                });
            } else {
                videoContainer.innerHTML = '<div class="text-danger">Browser tidak mendukung HLS (.m3u8)</div>';
            }
        } else {
            videoContainer.innerHTML = `<iframe class="w-100 h-100" src="${link}" frameborder="0" allowfullscreen></iframe>`;
        }

        videoModal.show();
    });

    function convertToEmbedYoutube(url) {
        if (url.includes('watch?v=')) {
            return url.replace('watch?v=', 'embed/');
        } else if (url.includes('youtu.be/')) {
            const id = url.split('youtu.be/')[1].split('?')[0];
            return 'https://www.youtube.com/embed/' + id;
        }
        return url;
    }

    // Hapus konten saat modal ditutup
    document.getElementById('videoModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('videoContainer').innerHTML = '';
    });
</script>
