<aside id="left-panel" class="left-panel">
    <nav class="navbar navbar-expand-sm navbar-default">
        <div id="main-menu" class="main-menu collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <!-- Dashboard -->
                <li class="active">
                    <a href="{{ route('dashboard') }}">
                        <i class="menu-icon fa fa-laptop"></i>Dashboard
                    </a>
                </li>

                <!-- Master Data -->
                <li class="menu-title">Master Data</li>
                <li>
                    <a href="{{ route('diseases.index') }}">
                        <i class="menu-icon fa fa-bug"></i>Data Penyakit
                    </a>
                </li>
                <li>
                    <a href="{{ route('symptoms.index') }}">
                        <i class="menu-icon fa fa-list-alt"></i>Data Gejala
                    </a>
                </li>

                <!-- Kelola Rule (Sistem Pakar) -->
                <li>
                    <a href="{{ route('rules.index') }}">
                        <i class="menu-icon fa fa-cogs"></i>Kelola Rule (Sistem Pakar)
                    </a>
                </li>

                <!-- Riwayat Diagnosa -->
                <li>
                    <a href="{{ route('history.index') }}">
                        <i class="menu-icon fa fa-history"></i>Riwayat Diagnosa
                    </a>
                </li>

                <!-- Manajemen Pengguna (Opsional) -->
                <li>
                    <a href="{{ route('users.index') }}">
                        <i class="menu-icon fa fa-users"></i>Manajemen Pengguna
                    </a>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </nav>
</aside>
