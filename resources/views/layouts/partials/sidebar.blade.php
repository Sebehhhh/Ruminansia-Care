<aside id="left-panel" class="left-panel">
    <nav class="navbar navbar-expand-sm navbar-default">
        <div id="main-menu" class="main-menu collapse navbar-collapse">
            <ul class="nav navbar-nav">

                <!-- Dashboard -->
                <li class="{{ Request::routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}">
                        <i class="menu-icon fa fa-laptop"></i> Dashboard
                    </a>
                </li>

                <!-- MASTER DATA (Hanya Admin) -->
                @if (Auth::check() && Auth::user()->is_admin)
                    <li class="menu-title">Master Data</li>
                   
                    <li class="{{ Request::routeIs('diseases.index') ? 'active' : '' }}">
                        <a href="{{ route('diseases.index') }}">
                            <i class="menu-icon fa fa-bug"></i> Penyakit
                        </a>
                    </li>
                    <li class="{{ Request::routeIs('symptoms.index') ? 'active' : '' }}">
                        <a href="{{ route('symptoms.index') }}">
                            <i class="menu-icon fa fa-list-alt"></i> Gejala
                        </a>
                    </li>
                    <li class="{{ Request::routeIs('animals.index') ? 'active' : '' }}">
                        <a href="{{ route('animals.index') }}">
                            <i class="menu-icon fa fa-paw"></i> Hewan
                        </a>
                    </li>

                    <!-- SISTEM PAKAR (Hanya Admin) -->
                    <li class="menu-title">Sistem Pakar</li>
                    <li class="{{ Request::routeIs('rules.index') ? 'active' : '' }}">
                        <a href="{{ route('rules.index') }}">
                            <i class="menu-icon fa fa-cogs"></i> Kelola Aturan
                        </a>
                    </li>
                @endif

                <!-- DIAGNOSA (Bisa Diakses Semua User) -->
                <li class="menu-title">Diagnosa</li>
                <li class="{{ Request::routeIs('history.index') ? 'active' : '' }}">
                    <a href="{{ route('history.index') }}">
                        <i class="menu-icon fa fa-history"></i> Riwayat Diagnosa
                    </a>
                </li>

                <!-- PENGGUNA & ADMIN (Hanya Admin) -->
                @if (Auth::check() && Auth::user()->is_admin)
                    <li class="menu-title">Manajemen</li>
                    <li class="{{ Request::routeIs('users.index') ? 'active' : '' }}">
                        <a href="{{ route('users.index') }}">
                            <i class="menu-icon fa fa-users"></i> Pengguna
                        </a>
                    </li>
                @endif

            </ul>
        </div><!-- /.navbar-collapse -->
    </nav>
</aside>
