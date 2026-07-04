<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foodinfo - Premium Food Database</title>
    
    <!-- Bootstrap 5 & Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Outfit:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --bg-color: #FAFCFE;
            --card-bg: #FFFFFF;
            --border-color: #E2E8F0;
            --primary-color: #059669;    /* Rich Emerald */
            --primary-light: #10B981;   /* Emerald */
            --text-color: #0F172A;      
            --text-muted: #64748B;      
            --glass-bg: rgba(255, 255, 255, 0.75);
        }
        
        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            font-family: 'Inter', sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        h1, h2, h3, h4, h5, h6, .navbar-brand {
            font-family: 'Outfit', sans-serif;
        }

        /* Glass Navbar */
        .navbar-custom {
            background: var(--glass-bg);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.6);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .navbar-brand {
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary-color), #0EA5E9);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 1.75rem;
        }
        .nav-link {
            font-weight: 500;
            color: var(--text-muted) !important;
            padding: 0.5rem 1rem !important;
            border-radius: 8px;
        }
        .nav-link:hover {
            color: var(--primary-color) !important;
            background-color: rgba(16, 185, 129, 0.08);
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-custom py-3">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">Foodinfo</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">Home</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    @auth
                        <a href="{{ url('/home') }}" class="btn btn-outline-secondary me-2">Dashboard</a>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary me-2">Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow-1">
        @yield('content')
    </main>

    <footer class="py-5 mt-auto bg-white border-top text-center">
        <h5 class="fw-bold" style="font-family: 'Outfit', sans-serif; color: var(--primary-color);">Foodinfo</h5>
        <p class="text-muted small">&copy; {{ date('Y') }} Foodinfo. All rights reserved.</p>
    </footer>

</body>
</html>
