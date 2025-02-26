<header id="header" class="header">
    <div class="top-left">
        <div class="navbar-header">
            <a class="navbar-brand" href="{{ route('dashboard') }}">Ruminansia-Care</a>
            <a class="navbar-toggler" data-toggle="collapse" data-target="#main-menu">
                <i class="fa fa-bars"></i>
            </a>
        </div>
    </div>

    <div class="top-right">
        <div class="header-menu">
            <div class="user-area dropdown float-right">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    <!-- Ganti Avatar dengan Inisial Nama -->
                    <div class="user-avatar">
                        <span class="avatar-initials">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </span>
                    </div>
                </a>

                <div class="user-menu dropdown-menu">
                    <!-- Logout -->
                    <a class="nav-link" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fa fa-power-off"></i> Keluar
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
