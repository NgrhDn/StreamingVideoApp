<style>
.custom-navbar {
    background-color: #2b6cb0 !important;
}

.custom-navbar .nav-link,
.custom-navbar .navbar-brand {
    color: #fff !important;
}

.custom-navbar .nav-link.active {
    font-weight: bold;
}

/* Dropdown fix for white text */
.custom-navbar .dropdown-menu {
    background-color: #2b6cb0;
    border: none;
}

.custom-navbar .dropdown-item {
    color: #fff;
}

.custom-navbar .dropdown-item:hover {
    background-color: #2c5282;
}

.sticky-top {
    z-index: 1030; /* biar tetap di atas konten lain */
}

</style>

<nav class="navbar navbar-expand-lg custom-navbar sticky-top mb-4 shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{ asset('images/lifemedia_logo.png') }}" alt="Lifemedia" height="40">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarMain" aria-controls="navbarMain"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{ url('/') }}">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('https://lifemedia.id/company') }}">Tentang Kami</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
