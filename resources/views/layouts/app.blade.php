<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foodinfo - Premium Food Database</title>
    <meta name="description" content="Discover detailed nutritional information and health benefits for thousands of foods.">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts: Outfit (Headings) & Inter (Body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Outfit:wght@400;500;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            /* Color Palette */
            --bg-color: #FAFCFE;
            --card-bg: #FFFFFF;
            --border-color: #E2E8F0;
            --primary-color: #059669;    /* Rich Emerald */
            --primary-light: #10B981;   /* Emerald */
            --secondary-color: #F59E0B; /* Amber */
            --accent-color: #0EA5E9;    /* Ocean Blue */
            
            --text-color: #0F172A;      /* Slate 900 */
            --text-muted: #64748B;      /* Slate 500 */
            
            --radius-md: 16px;
            --radius-lg: 24px;
            
            --shadow-sm: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            --shadow-md: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.04);
            --shadow-hover: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            
            --glass-bg: rgba(255, 255, 255, 0.75);
            --glass-border: 1px solid rgba(255, 255, 255, 0.5);
        }
        
        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4, h5, h6, .navbar-brand {
            font-family: 'Outfit', sans-serif;
            letter-spacing: -0.02em;
        }

        main {
            flex-grow: 1;
        }

        /* Glass Navbar */
        .navbar-custom {
            background: var(--glass-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.6);
            position: sticky;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }
        .navbar-custom.scrolled {
            box-shadow: var(--shadow-sm);
        }
        .navbar-brand {
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 1.75rem;
        }
        .nav-link {
            font-weight: 500;
            color: var(--text-muted) !important;
            padding: 0.5rem 1rem !important;
            margin: 0 0.25rem;
            border-radius: 8px;
            transition: all 0.2s ease;
        }
        .nav-link:hover, .nav-link.active {
            color: var(--primary-color) !important;
            background-color: rgba(16, 185, 129, 0.08);
        }
        
        /* Buttons */
        .btn {
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            padding: 0.6rem 1.5rem;
            border-radius: 12px;
            transition: all 0.2s ease;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
            border: none;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
        }
        .btn-outline-secondary {
            border-color: var(--border-color);
            color: var(--text-muted);
        }
        .btn-outline-secondary:hover {
            background-color: #F1F5F9;
            color: var(--text-color);
            border-color: #CBD5E1;
            transform: translateY(-1px);
        }

        /* Premium Cards */
        .card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card-hover:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-hover);
            border-color: rgba(16, 185, 129, 0.2);
        }

        /* Universal Badges */
        .badge {
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            padding: 0.4em 0.8em;
            border-radius: 6px;
        }
        
        footer {
            background-color: #FFFFFF;
            border-top: 1px solid var(--border-color);
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-custom py-3" id="mainNav">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">Foodinfo</a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('foods.search') ? 'active' : '' }}" href="{{ route('foods.search') }}">Search Foods</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary me-2">
                            <i class="fas fa-chart-line me-1"></i> Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary me-2">
                            <i class="fas fa-lock me-1"></i> Admin Login
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="py-5 mt-auto">
        <div class="container text-center">
            <h5 class="fw-bold mb-3" style="font-family: 'Outfit', sans-serif; color: var(--primary-color);">Foodinfo</h5>
            <p class="text-muted small mb-0">&copy; {{ date('Y') }} Foodinfo - Premium Food Database. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            if (window.scrollY > 10) {
                document.getElementById('mainNav').classList.add('scrolled');
            } else {
                document.getElementById('mainNav').classList.remove('scrolled');
            }
        });
    </script>
</body>
</html>
