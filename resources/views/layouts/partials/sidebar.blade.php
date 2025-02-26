<aside id="left-panel" class="left-panel">
    <nav class="navbar navbar-expand-sm navbar-default">
        <div id="main-menu" class="main-menu collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <!-- Dashboard -->
                <li class="{{ Request::routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}">
                        <i class="menu-icon fa fa-laptop"></i>Dashboard
                    </a>
                </li>

                <!-- Master Data -->
                <li class="menu-title">Master Data</li>
                <li class="{{ Request::routeIs('diseases.index') ? 'active' : '' }}">
                    <a href="{{ route('diseases.index') }}">
                        <i class="menu-icon fa fa-bug"></i>Data Penyakit
                    </a>
                </li>
                <li class="{{ Request::routeIs('symptoms.index') ? 'active' : '' }}">
                    <a href="{{ route('symptoms.index') }}">
                        <i class="menu-icon fa fa-list-alt"></i>Data Gejala
                    </a>
                </li>

                <!-- Kelola Rule (Sistem Pakar) -->
                <li class="{{ Request::routeIs('rules.index') ? 'active' : '' }}">
                    <a href="{{ route('rules.index') }}">
                        <i class="menu-icon fa fa-cogs"></i>Kelola Aturan 
                    </a>
                </li>

                <!-- Riwayat Diagnosa -->
                <li class="{{ Request::routeIs('history.index') ? 'active' : '' }}">
                    <a href="{{ route('history.index') }}">
                        <i class="menu-icon fa fa-history"></i>Riwayat Diagnosa
                    </a>
                </li>

                <!-- Manajemen Pengguna (Opsional) -->
                <li class="{{ Request::routeIs('users.index') ? 'active' : '' }}">
                    <a href="{{ route('users.index') }}">
                        <i class="menu-icon fa fa-users"></i>Manajemen Pengguna
                    </a>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </nav>
</aside>
