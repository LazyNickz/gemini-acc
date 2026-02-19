<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #f8fafc;
            --bg-secondary: #ffffff;
            --bg-tertiary: #f1f5f9;
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-muted: #94a3b8;
            --border: #e2e8f0;
            --accent: #6366f1;
            --accent-hover: #4f46e5;
            --accent-light: #eef2ff;
            --success: #10b981;
            --success-bg: #ecfdf5;
            --warning: #f59e0b;
            --warning-bg: #fffbeb;
            --danger: #ef4444;
            --danger-bg: #fef2f2;
            --info: #3b82f6;
            --info-bg: #eff6ff;
            --sidebar-bg: #1e1b4b;
            --sidebar-text: #c7d2fe;
            --sidebar-active: #6366f1;
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --radius: 12px;
            --radius-sm: 8px;
        }

        [data-theme="dark"] {
            --bg-primary: #0f172a;
            --bg-secondary: #1e293b;
            --bg-tertiary: #334155;
            --text-primary: #f1f5f9;
            --text-secondary: #cbd5e1;
            --text-muted: #64748b;
            --border: #334155;
            --accent-light: #1e1b4b;
            --success-bg: #064e3b;
            --warning-bg: #78350f;
            --danger-bg: #7f1d1d;
            --info-bg: #1e3a5f;
            --sidebar-bg: #020617;
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.3);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.4);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.4);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            transition: background 0.3s, color 0.3s;
        }

        /* ========== SIDEBAR ========== */
        .sidebar {
            width: 260px;
            background: var(--sidebar-bg);
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 100;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
        }

        .sidebar-brand {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-brand h1 {
            font-size: 18px;
            font-weight: 700;
            color: #fff;
            letter-spacing: -0.5px;
        }

        .sidebar-brand span {
            font-size: 11px;
            color: var(--sidebar-text);
            opacity: 0.7;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .sidebar-nav {
            padding: 16px 12px;
            flex: 1;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            color: var(--sidebar-text);
            text-decoration: none;
            border-radius: var(--radius-sm);
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
            margin-bottom: 4px;
        }

        .sidebar-nav a:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .sidebar-nav a.active {
            background: var(--sidebar-active);
            color: #fff;
        }

        .sidebar-nav a .icon {
            width: 20px;
            text-align: center;
            font-size: 16px;
        }

        .sidebar-nav .nav-section {
            font-size: 11px;
            color: rgba(199, 210, 254, 0.4);
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 20px 16px 8px;
            font-weight: 600;
        }

        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-footer .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-footer .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--accent);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
        }

        .sidebar-footer .user-name {
            color: #fff;
            font-size: 13px;
            font-weight: 500;
        }

        .sidebar-footer .user-role {
            color: var(--sidebar-text);
            font-size: 11px;
            text-transform: capitalize;
        }

        /* ========== MAIN CONTENT ========== */
        .main-content {
            margin-left: 260px;
            flex: 1;
            min-height: 100vh;
        }

        .topbar {
            background: var(--bg-secondary);
            border-bottom: 1px solid var(--border);
            padding: 16px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .topbar-left h2 {
            font-size: 20px;
            font-weight: 600;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .hamburger {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 24px;
            color: var(--text-primary);
        }

        .theme-toggle {
            background: var(--bg-tertiary);
            border: 1px solid var(--border);
            padding: 8px 12px;
            border-radius: var(--radius-sm);
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s;
            color: var(--text-primary);
        }

        .theme-toggle:hover {
            background: var(--accent);
            color: #fff;
            border-color: var(--accent);
        }

        .content-area {
            padding: 32px;
        }

        /* ========== ALERTS BANNER ========== */
        .flash-alert {
            padding: 14px 20px;
            border-radius: var(--radius-sm);
            margin-bottom: 24px;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideDown 0.3s ease;
        }

        .flash-alert.success {
            background: var(--success-bg);
            color: #065f46;
            border-left: 4px solid var(--success);
        }

        .flash-alert.error {
            background: var(--danger-bg);
            color: #991b1b;
            border-left: 4px solid var(--danger);
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ========== CARDS ========== */
        .card {
            background: var(--bg-secondary);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            transition: box-shadow 0.2s;
        }

        .card:hover {
            box-shadow: var(--shadow-md);
        }

        .card-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-header h3 {
            font-size: 16px;
            font-weight: 600;
        }

        .card-body {
            padding: 24px;
        }

        /* ========== STAT CARDS ========== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px 24px;
            transition: all 0.2s;
        }

        .stat-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .stat-card .stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            margin-bottom: 12px;
        }

        .stat-card .stat-value {
            font-size: 28px;
            font-weight: 700;
            line-height: 1;
        }

        .stat-card .stat-label {
            font-size: 13px;
            color: var(--text-secondary);
            margin-top: 6px;
        }

        .stat-icon.purple {
            background: #eef2ff;
            color: #6366f1;
        }

        .stat-icon.green {
            background: #ecfdf5;
            color: #10b981;
        }

        .stat-icon.orange {
            background: #fffbeb;
            color: #f59e0b;
        }

        .stat-icon.red {
            background: #fef2f2;
            color: #ef4444;
        }

        .stat-icon.blue {
            background: #eff6ff;
            color: #3b82f6;
        }

        [data-theme="dark"] .stat-icon.purple {
            background: #1e1b4b;
        }

        [data-theme="dark"] .stat-icon.green {
            background: #064e3b;
        }

        [data-theme="dark"] .stat-icon.orange {
            background: #78350f;
        }

        [data-theme="dark"] .stat-icon.red {
            background: #7f1d1d;
        }

        [data-theme="dark"] .stat-icon.blue {
            background: #1e3a5f;
        }

        /* ========== MOTHER CARDS ========== */
        .mother-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }

        .mother-card {
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 24px;
            transition: all 0.2s;
            position: relative;
            overflow: hidden;
        }

        .mother-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--accent);
        }

        .mother-card.expiring::before {
            background: var(--warning);
        }

        .mother-card.expired::before {
            background: var(--danger);
        }

        .mother-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
        }

        .mother-card-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .mother-card-email {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
            word-break: break-all;
        }

        .mother-card-status {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .mother-card .countdown {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
            font-size: 13px;
            color: var(--text-secondary);
        }

        .mother-card .countdown strong {
            font-size: 22px;
            font-weight: 700;
            color: var(--text-primary);
        }

        .mother-card .countdown.warning strong {
            color: var(--warning);
        }

        .mother-card .countdown.danger strong {
            color: var(--danger);
        }

        .seat-bar {
            background: var(--bg-tertiary);
            border-radius: 6px;
            height: 8px;
            margin-bottom: 8px;
            overflow: hidden;
        }

        .seat-bar-fill {
            height: 100%;
            border-radius: 6px;
            background: var(--accent);
            transition: width 0.5s ease;
        }

        .seat-bar-fill.high {
            background: var(--warning);
        }

        .seat-bar-fill.full {
            background: var(--danger);
        }

        .seat-label {
            font-size: 12px;
            color: var(--text-muted);
            margin-bottom: 16px;
        }

        .assigned-emails {
            list-style: none;
        }

        .assigned-emails li {
            padding: 6px 10px;
            font-size: 12px;
            background: var(--bg-tertiary);
            border-radius: 6px;
            margin-bottom: 4px;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .assigned-emails li::before {
            content: '📧';
            font-size: 10px;
        }

        /* ========== BADGES ========== */
        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
        }

        .badge-active {
            background: var(--success-bg);
            color: #065f46;
        }

        .badge-expired {
            background: var(--danger-bg);
            color: #991b1b;
        }

        .badge-archived {
            background: var(--bg-tertiary);
            color: var(--text-muted);
        }

        .badge-unassigned {
            background: var(--warning-bg);
            color: #92400e;
        }

        .badge-cooldown {
            background: var(--info-bg);
            color: #1e40af;
        }

        .badge-deleted {
            background: var(--bg-tertiary);
            color: var(--text-muted);
        }

        .badge-warning {
            background: var(--warning-bg);
            color: #92400e;
        }

        .badge-critical {
            background: var(--danger-bg);
            color: #991b1b;
        }

        /* ========== BUTTONS ========== */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 20px;
            border-radius: var(--radius-sm);
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            font-family: inherit;
        }

        .btn-primary {
            background: var(--accent);
            color: #fff;
        }

        .btn-primary:hover {
            background: var(--accent-hover);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-success {
            background: var(--success);
            color: #fff;
        }

        .btn-success:hover {
            background: #059669;
        }

        .btn-warning {
            background: var(--warning);
            color: #fff;
        }

        .btn-warning:hover {
            background: #d97706;
        }

        .btn-danger {
            background: var(--danger);
            color: #fff;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        .btn-outline {
            background: transparent;
            color: var(--text-primary);
            border: 1px solid var(--border);
        }

        .btn-outline:hover {
            background: var(--bg-tertiary);
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
        }

        .btn-lg {
            padding: 14px 28px;
            font-size: 16px;
        }

        /* ========== FORMS ========== */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 6px;
        }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 14px;
            font-family: inherit;
            background: var(--bg-primary);
            color: var(--text-primary);
            transition: border-color 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .form-control.error {
            border-color: var(--danger);
        }

        .error-text {
            color: var(--danger);
            font-size: 12px;
            margin-top: 4px;
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 10px center;
            background-repeat: no-repeat;
            background-size: 20px;
            padding-right: 36px;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 80px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        /* ========== TABLE ========== */
        .table-wrapper {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th {
            text-align: left;
            padding: 12px 16px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-muted);
            border-bottom: 2px solid var(--border);
        }

        .table td {
            padding: 14px 16px;
            font-size: 14px;
            border-bottom: 1px solid var(--border);
            color: var(--text-primary);
        }

        .table tr:hover td {
            background: var(--bg-tertiary);
        }

        .table-actions {
            display: flex;
            gap: 8px;
        }

        /* ========== PAGINATION ========== */
        .pagination-wrapper {
            display: flex;
            justify-content: center;
            padding: 20px 0;
        }

        .pagination-wrapper nav span,
        .pagination-wrapper nav a {
            padding: 8px 14px;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 13px;
            margin: 0 2px;
            text-decoration: none;
            color: var(--text-primary);
            background: var(--bg-secondary);
            transition: all 0.2s;
        }

        .pagination-wrapper nav span[aria-current] {
            background: var(--accent);
            color: #fff;
            border-color: var(--accent);
        }

        .pagination-wrapper nav a:hover {
            background: var(--accent-light);
        }

        /* ========== SEARCH & FILTER BAR ========== */
        .filter-bar {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
            flex-wrap: wrap;
            align-items: center;
        }

        .filter-bar .form-control {
            max-width: 280px;
        }

        /* ========== ALERT ITEMS ========== */
        .alert-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 14px 20px;
            border-radius: var(--radius-sm);
            margin-bottom: 8px;
            transition: all 0.2s;
        }

        .alert-item.warning {
            background: var(--warning-bg);
            border-left: 4px solid var(--warning);
        }

        .alert-item.critical {
            background: var(--danger-bg);
            border-left: 4px solid var(--danger);
        }

        .alert-item .alert-icon {
            font-size: 20px;
            flex-shrink: 0;
        }

        .alert-item .alert-message {
            flex: 1;
            font-size: 14px;
        }

        .alert-item .alert-time {
            font-size: 12px;
            color: var(--text-muted);
            white-space: nowrap;
        }

        /* ========== EMPTY STATE ========== */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-muted);
        }

        .empty-state .empty-icon {
            font-size: 48px;
            margin-bottom: 16px;
        }

        .empty-state h3 {
            font-size: 18px;
            margin-bottom: 8px;
            color: var(--text-secondary);
        }

        /* ========== MOBILE ========== */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 99;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .sidebar-overlay.open {
                display: block;
            }

            .main-content {
                margin-left: 0;
            }

            .hamburger {
                display: block;
            }

            .content-area {
                padding: 20px;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .mother-grid {
                grid-template-columns: 1fr;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .topbar {
                padding: 12px 16px;
            }

            .topbar-left h2 {
                font-size: 16px;
            }

            .filter-bar {
                flex-direction: column;
            }

            .filter-bar .form-control {
                max-width: 100%;
            }
        }

        /* ========== UTILITY ========== */
        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .mb-0 {
            margin-bottom: 0;
        }

        .mb-2 {
            margin-bottom: 8px;
        }

        .mb-4 {
            margin-bottom: 16px;
        }

        .mb-6 {
            margin-bottom: 24px;
        }

        .mt-4 {
            margin-top: 16px;
        }

        .mt-6 {
            margin-top: 24px;
        }

        .flex {
            display: flex;
        }

        .items-center {
            align-items: center;
        }

        .justify-between {
            justify-content: space-between;
        }

        .gap-2 {
            gap: 8px;
        }

        .gap-3 {
            gap: 12px;
        }
    </style>
</head>

<body>
    <!-- Sidebar Overlay (mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <h1>⚡ MAM System</h1>
            <span>Mother Account Manager</span>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">Main</div>
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="icon">📊</span> Dashboard
            </a>

            <div class="nav-section">Management</div>
            <a href="{{ route('mothers.index') }}" class="{{ request()->routeIs('mothers.*') ? 'active' : '' }}">
                <span class="icon">👩</span> Mother Accounts
            </a>
            <a href="{{ route('accounts.index') }}" class="{{ request()->routeIs('accounts.*') ? 'active' : '' }}">
                <span class="icon">📧</span> Accounts
            </a>
            <a href="{{ route('buyers.index') }}" class="{{ request()->routeIs('buyers.*') ? 'active' : '' }}">
                <span class="icon">🛒</span> Buyers
            </a>
            <a href="{{ route('orders.index') }}" class="{{ request()->routeIs('orders.*') ? 'active' : '' }}">
                <span class="icon">📦</span> Orders
            </a>

            <div class="nav-section">System</div>
            <a href="{{ route('alerts.index') }}" class="{{ request()->routeIs('alerts.*') ? 'active' : '' }}">
                <span class="icon">🔔</span> Alerts
                @php $unresolvedCount = \App\Models\Alert::unresolved()->count(); @endphp
                @if($unresolvedCount > 0)
                    <span class="badge badge-critical" style="margin-left:auto;">{{ $unresolvedCount }}</span>
                @endif
            </a>
            <a href="{{ route('exports.index') }}" class="{{ request()->routeIs('exports.*') ? 'active' : '' }}">
                <span class="icon">📥</span> Exports
            </a>
        </nav>

        @auth
            <div class="sidebar-footer">
                <div class="user-info">
                    <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    <div>
                        <div class="user-name">{{ auth()->user()->name }}</div>
                        <div class="user-role">{{ auth()->user()->role }}</div>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" style="margin-top:12px;">
                    @csrf
                    <button type="submit" class="btn btn-outline btn-sm"
                        style="width:100%;justify-content:center;">Logout</button>
                </form>
            </div>
        @endauth
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <div class="topbar">
            <div class="topbar-left">
                <button class="hamburger" onclick="toggleSidebar()">☰</button>
                <h2>@yield('title', 'Dashboard')</h2>
            </div>
            <div class="topbar-right">
                <button class="theme-toggle" onclick="toggleTheme()" title="Toggle Dark/Light Mode"
                    id="themeBtn">🌙</button>
            </div>
        </div>

        <div class="content-area">
            @if(session('success'))
                <div class="flash-alert success">✅ {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="flash-alert error">❌ {{ session('error') }}</div>
            @endif

            @yield('content')
        </div>
    </div>

    <script>
        // Theme Toggle
        function toggleTheme() {
            const html = document.documentElement;
            const current = html.getAttribute('data-theme');
            const next = current === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', next);
            localStorage.setItem('theme', next);
            document.getElementById('themeBtn').textContent = next === 'dark' ? '☀️' : '🌙';
        }

        // Load saved theme
        (function () {
            const saved = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', saved);
            const btn = document.getElementById('themeBtn');
            if (btn) btn.textContent = saved === 'dark' ? '☀️' : '🌙';
        })();

        // Mobile Sidebar
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('sidebarOverlay').classList.toggle('open');
        }

        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('sidebarOverlay').classList.remove('open');
        }

        // Auto-dismiss flash alerts
        setTimeout(() => {
            document.querySelectorAll('.flash-alert').forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(-10px)';
                setTimeout(() => el.remove(), 300);
            });
        }, 5000);
    </script>

    @stack('scripts')
</body>

</html>