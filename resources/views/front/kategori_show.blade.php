@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold text-dark mb-4">{{ $kategori->nama_kategori }}</h2>
    <div class="row g-4">
        @foreach ($kategori->multimedia as $video)
        <div class="col-sm-6 col-md-4">
            <div class="card h-100">
                <div class="position-relative overflow-hidden" style="aspect-ratio: 16 / 9; background-color: #000;">
                    <div class="lazy-video-wrapper w-100 h-100" data-url="{{ $video->link }}">
                        <img src="/images/video-thumb.png" class="w-100 h-100 object-fit-cover" alt="Video thumbnail">
                        <div class="play-button-overlay position-absolute top-50 start-50 translate-middle">
                            <i class="fas fa-play-circle fa-3x text-white"></i>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="card-title marquee-container">
                        <div class="marquee-text">{{ $video->judul }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Marquee jalan
    document.querySelectorAll('.marquee-container').forEach(container => {
        const text = container.querySelector('.marquee-text');
        if (text.scrollWidth > container.clientWidth) {
            text.classList.add('marquee-active');
        }
    });

    // Lazy-load video
    document.querySelectorAll('.lazy-video-wrapper').forEach(wrapper => {
        wrapper.addEventListener('click', function () {
            const url = wrapper.dataset.url;
            const container = wrapper.parentElement;
            const id = 'video_' + Math.random().toString(36).substr(2, 9);
            const videoHtml = `<video id="${id}" class="w-100 h-100" controls preload="none" poster="/images/video-thumb.png"></video>`;
            container.innerHTML = videoHtml;

            const video = document.getElementById(id);

            // Deteksi YouTube
            if (url.includes('youtube.com') || url.includes('youtu.be')) {
                const ytId = (url.match(/(youtu\.be\/|v=)([A-Za-z0-9_\-]+)/) || [])[2];
                if (ytId) {
                    container.innerHTML = `
                        <iframe class="w-100 h-100"
                            src="https://www.youtube.com/embed/${ytId}"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
                        </iframe>
                    `;
                } else {
                    container.innerHTML = "<p class='text-danger'>Video YouTube tidak valid.</p>";
                }
            } else if (Hls.isSupported() && url.endsWith('.m3u8')) {
                const hls = new Hls();
                hls.loadSource(url);
                hls.attachMedia(video);
            } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                video.src = url;
            } else if (url.endsWith('.mp4')) {
                video.innerHTML = `<source src="${url}" type="video/mp4">`;
            } else {
                container.innerHTML = "<p class='text-danger'>Browser tidak mendukung video ini.</p>";
            }
        });
    });
});
</script>
@endpush
