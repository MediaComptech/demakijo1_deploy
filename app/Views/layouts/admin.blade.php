<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') | Admin SDN Demakijo 1</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AdminLTE v4 (Bootstrap 5) CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-alpha2/dist/css/adminlte.min.css">
    
    @stack('styles')
    
    <style>
        .sidebar-dark-primary {
            background-color: #003366 !important; /* Biru korporat sekolah */
        }
        .brand-link {
            border-bottom: 1px solid #ffcc00 !important; /* Kuning sekolah */
        }
        .nav-sidebar .nav-item > .nav-link.active {
            background-color: #ffcc00 !important;
            color: #003366 !important;
            font-weight: bold;
        }
    </style>
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
<div class="app-wrapper">
    <!-- Navbar -->
    <nav class="app-header navbar navbar-expand bg-body">
        <div class="container-fluid">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-md-block">
                    <a href="/" class="nav-link" target="_blank">Lihat Website</a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown user-menu">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=7F9CF5&background=EBF4FF" class="user-image rounded-circle shadow" alt="User Image">
                        <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                        <li class="user-header text-bg-primary">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=7F9CF5&background=EBF4FF" class="rounded-circle shadow" alt="User Image">
                            <p>
                                {{ Auth::user()->name }}
                                <small>Role: {{ Auth::user()->role ?? 'Admin' }}</small>
                            </p>
                        </li>
                        <li class="user-footer">
                            <a href="#" class="btn btn-default btn-flat">Profile</a>
                            <form method="POST" action="{{ url('/logout') }}" class="d-inline">
                                {!! csrf_field() !!}
                                <button type="submit" class="btn btn-default btn-flat float-end">Sign out</button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="app-sidebar shadow" data-bs-theme="dark" style="background-color: #003366;">
        <!-- Brand Logo -->
        <a href="{{ url('/dashboard') }}" class="brand-link text-center">
            <span class="brand-text font-weight-light fw-bold text-white">SDN DEMAKIJO 1</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar-wrapper">
            <nav class="mt-2">
                <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <a href="{{ url('/dashboard') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <li class="nav-header">KONTEN WEBSITE</li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-newspaper"></i>
                            <p>Berita & Artikel <i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('/admin/berita') }}" class="nav-link {{ request()->is('admin/berita*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i><p>Daftar Berita</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/admin/kategori-berita') }}" class="nav-link {{ request()->is('admin/kategori-berita*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i><p>Kategori</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-images"></i>
                            <p>Galeri & Album <i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('/admin/galeri') }}" class="nav-link {{ request()->is('admin/galeri*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i><p>Daftar Galeri</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/admin/album') }}" class="nav-link {{ request()->is('admin/album*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i><p>Album</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/admin/pengumuman') }}" class="nav-link {{ request()->is('admin/pengumuman*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-bullhorn"></i>
                            <p>Pengumuman</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/admin/agenda') }}" class="nav-link {{ request()->is('admin/agenda*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p>Agenda</p>
                        </a>
                    </li>

                    <li class="nav-header">MANAJEMEN SEKOLAH</li>
                    <li class="nav-item">
                        <a href="{{ url('/admin/guru') }}" class="nav-link {{ request()->is('admin/guru*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chalkboard-teacher"></i>
                            <p>Data Guru</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/admin/siswa') }}" class="nav-link {{ request()->is('admin/siswa*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-graduate"></i>
                            <p>Data Siswa</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/admin/prestasi') }}" class="nav-link {{ request()->is('admin/prestasi*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-trophy"></i>
                            <p>Prestasi</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/admin/komite') }}" class="nav-link {{ request()->is('admin/komite*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Komite Sekolah</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/admin/alumni') }}" class="nav-link {{ request()->is('admin/alumni*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-graduate"></i>
                            <p>Alumni
                            @php $unv = \App\Models\Alumni::where('is_verified', false)->count(); @endphp
                            @if($unv > 0)
                                <span class="badge bg-danger ms-1">{{ $unv }}</span>
                            @endif
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/admin/fasilitas') }}" class="nav-link {{ request()->is('admin/fasilitas*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-building"></i>
                            <p>Fasilitas</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/admin/keunggulan') }}" class="nav-link {{ request()->is('admin/keunggulan*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-star"></i>
                            <p>Kenapa Memilih Kami</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/admin/ekstrakurikuler') }}" class="nav-link {{ request()->is('admin/ekstrakurikuler*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-futbol"></i>
                            <p>Ekstrakurikuler</p>
                        </a>
                    </li>

                    <li class="nav-header">SISTEM</li>
                    <li class="nav-item">
                        <a href="{{ url('/admin/ppdb') }}" class="nav-link {{ request()->is('admin/ppdb*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-plus"></i>
                            <p>PPDB Online</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/admin/notifikasi') }}" class="nav-link {{ request()->is('admin/notifikasi*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-bell"></i>
                            <p>Push Notifications</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/admin/user') }}" class="nav-link {{ request()->is('admin/user*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users-cog"></i>
                            <p>Manajemen Pengguna</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/admin/pengaturan') }}" class="nav-link {{ request()->is('admin/pengaturan*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cogs"></i>
                            <p>Pengaturan Website</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <main class="app-main">
        <!-- Content Header (Page header) -->
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">@yield('title')</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">@yield('title')</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="app-content">
            <div class="container-fluid">
                @yield('content')
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </main>
    <!-- /.content-wrapper -->

    <footer class="app-footer">
        <strong>Copyright &copy; {{ date('Y') }} <a href="#">SDN Demakijo 1</a>.</strong>
        All rights reserved.
        <div class="float-end d-none d-sm-inline-block">
            <b>Version</b> 1.0.0
        </div>
    </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<!-- Bootstrap 5 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-alpha2/dist/js/adminlte.min.js"></script>

@stack('scripts')
</body>
</html>
