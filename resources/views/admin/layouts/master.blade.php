<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — GoWayki Admin</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    @vite(['resources/css/app.css'])
    <style>
        :root {
            --color-bg-general: #F9F9F9;
            --color-sidebar:    #092634;
            --color-primary:    #004E72;
            --color-highlight:  #FF6E42;
            --color-text-light: #FFFFFF;
            --color-text-dark:  #1A1A1A;
            --color-text-muted: #8A94A6;
            --card-radius:      16px;
            --card-shadow:      0 4px 20px rgba(0, 0, 0, 0.06);
            --sidebar-hover:    rgba(255, 255, 255, 0.08);
            --sidebar-active:   rgba(255, 255, 255, 0.12);
            --border:           #EEF0F2;
            --danger:           #EF4444;
            --success:          #22C55E;
            --sidebar-w:        240px;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body {
            height: 100%;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            font-size: 14px;
            color: var(--color-text-dark);
            background: var(--color-bg-general);
        }
        body { overflow: hidden; }
        a { text-decoration: none; color: inherit; }

        .sidebar {
            position: fixed; top: 0; left: 0;
            width: var(--sidebar-w); height: 100vh;
            background: var(--color-sidebar);
            color: var(--color-text-light);
            z-index: 100;
            display: flex; flex-direction: column;
        }
        .sidebar-logo {
            padding: 24px 20px 20px;
            font-size: 1.2rem; font-weight: 800;
            letter-spacing: -0.5px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        .sidebar-logo span { color: var(--color-highlight); }
        .sidebar-nav { flex: 1; overflow-y: auto; padding: 12px 0; }
        .nav-item {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 20px;
            color: rgba(255,255,255,0.7);
            font-weight: 500; font-size: 0.9rem;
            transition: background 0.15s, color 0.15s;
            border-left: 3px solid transparent;
        }
        .nav-item:hover { background: var(--sidebar-hover); color: var(--color-text-light); }
        .nav-item.active {
            background: var(--sidebar-active); color: var(--color-text-light);
            border-left-color: var(--color-highlight);
        }
        .nav-item .icono { font-size: 1.1rem; width: 24px; text-align: center; }
        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid rgba(255,255,255,0.06);
            font-size: 0.8rem;
        }
        .sidebar-footer a { color: rgba(255,255,255,0.5); }
        .sidebar-footer a:hover { color: var(--color-text-light); }

        .main {
            margin-left: var(--sidebar-w);
            height: 100vh;
            display: flex;
            flex-direction: column;
            background: var(--color-bg-general);
            overflow: hidden;
        }
        .main-topbar {
            display: flex; align-items: center; justify-content: space-between;
            padding: 16px 28px;
            background: #FFF;
            border-bottom: 1px solid var(--border);
            flex-shrink: 0;
        }
        .main-topbar h2 { font-size: 1.25rem; font-weight: 700; }
        .main-topbar .user-badge {
            display: flex; align-items: center; gap: 8px;
            font-size: 0.85rem; color: var(--color-text-muted);
        }
        .main-topbar .user-badge .rol {
            background: var(--color-primary); color: #fff;
            padding: 2px 10px; border-radius: 12px;
            font-size: 0.7rem; font-weight: 600; text-transform: uppercase;
        }
        .content { flex: 1; overflow-y: auto; padding: 28px; }

        .card { background: #FFF; border-radius: var(--card-radius); box-shadow: var(--card-shadow); }
        .stat-card { background: #FFF; border-radius: var(--card-radius); box-shadow: var(--card-shadow); padding: 20px; }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px; margin-bottom: 28px;
        }
        .kpi-card--highlight { background: var(--color-highlight) !important; color: #FFF; }
        .kpi-card--highlight .stat-valor { color: #FFF; }
        .kpi-card--highlight .stat-label { color: rgba(255,255,255,0.8); }
        .stat-valor { font-size: 2rem; font-weight: 800; color: var(--color-text-dark); line-height: 1.2; }
        .stat-label { font-size: 0.8rem; color: var(--color-text-muted); font-weight: 500; margin-top: 4px; }

        .progress-bar {
            height: 8px; background: var(--border); border-radius: 4px; overflow: hidden; margin-top: 10px;
        }
        .progress-bar .fill {
            height: 100%; background: var(--color-primary); border-radius: 4px; transition: width 0.6s ease;
        }

        .table-wrap {
            overflow-x: auto; background: #FFF;
            border-radius: var(--card-radius); box-shadow: var(--card-shadow);
        }
        .table-wrap table { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
        .table-wrap th {
            background: #F4F6F8; color: var(--color-text-muted);
            font-weight: 600; text-transform: uppercase;
            font-size: 0.72rem; letter-spacing: 0.5px;
            padding: 12px 16px; text-align: left;
            border-bottom: 1px solid var(--border);
        }
        .table-wrap td {
            padding: 10px 16px; border-bottom: 1px solid var(--border);
            color: var(--color-text-dark);
        }
        .table-wrap tr:last-child td { border-bottom: none; }
        .table-wrap tr:hover td { background: #FAFBFC; }

        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 18px; border-radius: 10px;
            font-size: 0.85rem; font-weight: 600;
            border: none; cursor: pointer; transition: all 0.15s; line-height: 1.4;
        }
        .btn-primary { background: var(--color-primary); color: #FFF; }
        .btn-primary:hover { filter: brightness(1.1); }
        .btn-danger { background: var(--danger); color: #FFF; }
        .btn-danger:hover { filter: brightness(1.1); }
        .btn-outline { background: transparent; color: var(--color-primary); border: 1.5px solid var(--color-primary); }
        .btn-outline:hover { background: var(--color-primary); color: #FFF; }
        .btn-sm { padding: 5px 12px; font-size: 0.78rem; border-radius: 8px; }

        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-size: 0.82rem; font-weight: 600; margin-bottom: 4px; }
        .form-control {
            width: 100%; padding: 10px 14px;
            border: 1.5px solid var(--border); border-radius: 12px;
            font-size: 0.88rem; color: var(--color-text-dark);
            background: #FFF; transition: border-color 0.15s;
            font-family: inherit;
        }
        .form-control:focus { outline: none; border-color: var(--color-primary); box-shadow: 0 0 0 3px rgba(0,78,114,0.08); }
        textarea.form-control { min-height: 120px; resize: vertical; }

        .modal-bg {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,0.45); z-index: 1000;
            align-items: center; justify-content: center;
        }
        .modal-bg.open { display: flex; }
        .modal {
            background: #FFF; border-radius: var(--card-radius);
            box-shadow: 0 12px 48px rgba(0,0,0,0.2);
            width: 90%; max-width: 640px; max-height: 90vh; overflow-y: auto;
        }
        .modal-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 18px 24px; border-bottom: 1px solid var(--border);
        }
        .modal-header h3 { font-size: 1.05rem; font-weight: 700; }
        .modal-close { background: none; border: none; font-size: 1.3rem; cursor: pointer; color: var(--color-text-muted); }
        .modal-close:hover { color: var(--color-text-dark); }
        .modal-body { padding: 24px; }
        .modal-footer {
            display: flex; justify-content: flex-end; gap: 10px;
            padding: 16px 24px; border-top: 1px solid var(--border);
        }

        .tabs { display: flex; border-bottom: 1px solid var(--border); margin-bottom: 20px; }
        .tab-btn {
            padding: 10px 20px; font-size: 0.85rem; font-weight: 600;
            color: var(--color-text-muted); background: none; border: none;
            border-bottom: 2px solid transparent; cursor: pointer; transition: all 0.15s;
        }
        .tab-btn:hover { color: var(--color-text-dark); }
        .tab-btn.active { color: var(--color-primary); border-bottom-color: var(--color-primary); }
        .tab-panel { display: none; }
        .tab-panel.active { display: block; }

        .badge {
            display: inline-block; padding: 2px 10px; border-radius: 12px;
            font-size: 0.7rem; font-weight: 600;
        }
        .badge-success { background: #DCFCE7; color: #166534; }
        .badge-danger  { background: #FEE2E2; color: #991B1B; }
        .badge-warning { background: #FEF3C7; color: #92400E; }

        .alert {
            padding: 12px 18px; border-radius: 12px; font-size: 0.85rem; margin-bottom: 16px;
        }
        .alert-success { background: #DCFCE7; color: #166534; border-left: 4px solid var(--success); }
        .alert-danger  { background: #FEE2E2; color: #991B1B; border-left: 4px solid var(--danger); }
        .alert-info    { background: #E0F2FE; color: #075985; border-left: 4px solid var(--color-primary); }

        .empty-state { text-align: center; padding: 48px 20px; color: var(--color-text-muted); }
        .empty-state .icono { font-size: 3rem; margin-bottom: 12px; }
        .empty-state p { font-size: 0.95rem; }

        .mapa-editor { width: 100%; height: 380px; border-radius: 12px; border: 1.5px solid var(--border); }

        @media (max-width: 768px) {
            :root { --sidebar-w: 0px; }
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main { margin-left: 0; }
            .content { padding: 16px; overflow-y: auto; }
            .stats-grid { grid-template-columns: 1fr; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <aside class="sidebar" id="adminSidebar">
        <div class="sidebar-logo">Go<span>Wayki</span></div>
        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="icono">📊</span> Dashboard
            </a>
            <a href="{{ route('admin.rutas.index') }}" class="nav-item {{ request()->routeIs('admin.rutas.*') ? 'active' : '' }}">
                <span class="icono">🚌</span> Rutas
            </a>
            <a href="{{ route('admin.paraderos.index') }}" class="nav-item {{ request()->routeIs('admin.paraderos.*') ? 'active' : '' }}">
                <span class="icono">📍</span> Paraderos
            </a>
            <a href="{{ route('admin.usuarios') }}" class="nav-item {{ request()->routeIs('admin.usuarios') ? 'active' : '' }}">
                <span class="icono">👥</span> Usuarios
            </a>
            <a href="{{ route('admin.recorridos') }}" class="nav-item {{ request()->routeIs('admin.recorridos') ? 'active' : '' }}">
                <span class="icono">🗺️</span> Recorridos
            </a>
        </nav>
        <div class="sidebar-footer">
            <a href="{{ route('home') }}">← Volver al sitio</a>
        </div>
    </aside>

    <div class="main">
        <div class="main-topbar">
            <h2>@yield('title', 'Dashboard')</h2>
            <div class="user-badge">
                <span>{{ auth()->user()->name }}</span>
                <span class="rol">{{ auth()->user()->role }}</span>
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" style="background:none;border:none;color:var(--color-text-muted);font-size:0.8rem;cursor:pointer;margin-left:8px;">Salir</button>
                </form>
            </div>
        </div>
        <div class="content">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @yield('content')
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @stack('scripts')
</body>
</html>
