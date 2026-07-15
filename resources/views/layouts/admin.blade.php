<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Food Information System</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #F5F7FA;
            --card-bg: #FFFFFF;
            --border-color: #E5E7EB;
            --primary-color: #10B981; /* Emerald Green */
            --secondary-color: #F97316; /* Orange */
            --text-color: #1F2937; /* Dark Gray */
            --text-muted: #6B7280;
            --radius-md: 12px;
            --radius-lg: 16px;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
        }
        .sidebar {
            width: 260px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            background: var(--card-bg);
            border-right: 1px solid var(--border-color);
            padding-top: 1.5rem;
            z-index: 100;
        }
        .sidebar-brand {
            font-weight: 700;
            font-size: 1.25rem;
            color: var(--primary-color);
            padding: 0 1.5rem;
            margin-bottom: 2rem;
            display: block;
            text-decoration: none;
        }
        .nav-link {
            color: var(--text-color);
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.2s;
        }
        .nav-link:hover, .nav-link.active {
            color: var(--primary-color);
            background-color: rgba(16, 185, 129, 0.1);
        }
        .main-content {
            margin-left: 260px;
            padding: 2rem;
        }
        .card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            margin-bottom: 1.5rem;
        }
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 8px;
        }
        .btn-primary:hover {
            background-color: #059669;
            border-color: #059669;
        }
        .btn-secondary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            border-radius: 8px;
        }
        .btn-secondary:hover {
            background-color: #EA580C;
            border-color: #EA580C;
        }
        .table {
            color: var(--text-color);
        }
        .table th {
            font-weight: 600;
            color: var(--text-muted);
            border-bottom-width: 1px;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">Foodinfo Admin</a>
        <nav class="nav flex-column">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a class="nav-link {{ request()->routeIs('admin.reports') || request()->routeIs('admin.audit-log') ? 'active' : '' }}" href="{{ route('admin.reports') }}">Reports</a>
            <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">Categories</a>
            <a class="nav-link {{ request()->routeIs('admin.foods.*') ? 'active' : '' }}" href="{{ route('admin.foods.index') }}">Foods</a>
            <a class="nav-link {{ request()->routeIs('admin.nutrients.*') ? 'active' : '' }}" href="{{ route('admin.nutrients.index') }}">Nutrients</a>
            <a class="nav-link {{ request()->routeIs('admin.health_statuses.*') ? 'active' : '' }}" href="{{ route('admin.health_statuses.index') }}">Health Statuses</a>
            
            <hr>
            
            <form action="{{ route('logout') }}" method="POST" class="px-3">
                @csrf
                <button type="submit" class="btn btn-outline-danger w-100">Logout</button>
            </form>
            
            <div class="px-3 mt-3">
                <a href="{{ route('home') }}" class="btn btn-outline-secondary w-100" target="_blank">View Site</a>
            </div>
        </nav>
    </div>

    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
