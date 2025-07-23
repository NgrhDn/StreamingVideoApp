<!-- resources/views/components/admin-sidebar.blade.php -->
<div id="sidebar">
    <div class="sidebar-brand">
        <img src="{{ asset('images/lifemedia_logo.png') }}" alt="Lifemedia Logo">
    </div>

    <div class="user-profile text-center">
        @auth
            <i class="fas fa-user-circle" style="font-size: 40px; color: #2b6cb0;"></i>
            <div class="name">{{ Auth::user()->name ?? 'Guest' }}</div>
            <div class="role">Superadmin</div>
        @else
            <i class="fas fa-user-circle" style="font-size: 80px; color: #666;"></i>
            <div class="name">Guest</div>
            <div class="role">Pengguna</div>
        @endauth
        <div class="user-actions mt-2">
            <button title="Pengaturan Profil"><i class="fas fa-cog"></i></button>
            @auth
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" title="Logout"><i class="fas fa-power-off"></i></button>
                </form>
            @endauth
        </div>
    </div>

    <nav class="sidebar-menu mt-4">
        <ul class="list-unstyled">
            <li>
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('multimedia.index') }}" class="{{ request()->routeIs('multimedia.*') ? 'active' : '' }}">
                    <i class="fas fa-film"></i> <span>Multimedia</span>
                </a>
            </li>
            <li>
                <a href="{{ route('kategori.index') }}" class="{{ request()->routeIs('kategori.*') ? 'active' : '' }}">
                    <i class="fas fa-folder-open"></i> <span>Kategori</span>
                </a>
            </li>
            <li>
                <a class="nav-link" href="{{ route('pengaturan') }}">
                        <i class="fas fa-cog"></i>
                        <span>Pengaturan</span>
                </a>
            </li>
        </ul>
    </nav>
</div>
