<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Theme Initialization Script -->
    <script>
        const storedTheme = localStorage.getItem('theme') || (window.matchMedia("(prefers-color-scheme: dark)").matches ?
            "dark" : "light");
        if (storedTheme) {
            document.documentElement.setAttribute('data-bs-theme', storedTheme);
        }
    </script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts (Bootstrap CDN Fallback) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        :root,
        [data-bs-theme="light"] {
            --primary-blue: #1e3a8a;
            /* Deep blue */
            --secondary-blue: #3b82f6;
            /* Bright blue */
            --accent-blue: #bfdbfe;
            /* Light blue accent */
            --bg-light: #f4f7fb;
            /* Very light blue-grey background */
            --text-dark: #1e293b;
            --card-bg: rgba(255, 255, 255, 0.95);
        }

        [data-bs-theme="dark"] {
            --primary-blue: #60a5fa;
            /* Lighter blue for dark mode */
            --secondary-blue: #3b82f6;
            --accent-blue: #1e3a8a;
            --bg-light: #0f172a;
            /* Slate 900 */
            --text-dark: #f8fafc;
            --card-bg: rgba(30, 41, 59, 0.95);
            /* Slate 800 */
        }

        body {
            background-color: var(--bg-light);
            color: var(--text-dark);
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue)) !important;
            padding: 1rem 0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15) !important;
        }

        .navbar-brand {
            color: #ffffff !important;
            font-weight: 700;
            letter-spacing: -0.5px;
            font-size: 1.5rem;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.85) !important;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .nav-link:hover,
        .nav-link:focus {
            color: #ffffff !important;
        }

        .navbar-toggler {
            border-color: rgba(255, 255, 255, 0.5);
        }

        .navbar-toggler-icon {
            filter: invert(1);
        }

        /* Dropdown styling */
        .dropdown-menu {
            border: none;
            border-radius: 12px;
            background-color: var(--card-bg);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .dropdown-item {
            color: var(--text-dark);
        }

        .dropdown-item:hover {
            background-color: var(--bg-light);
            color: var(--secondary-blue);
        }

        /* Cards & Glassmorphism */
        .card {
            border: none;
            border-radius: 20px;
            background: var(--card-bg);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
            color: var(--text-dark);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            background-color: transparent;
            border-bottom: 1px solid rgba(128, 128, 128, 0.15);
            padding: 1.5rem;
        }

        .card-body {
            padding: 2rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--secondary-blue), var(--primary-blue));
            border: none;
            border-radius: 10px;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
            transition: all 0.3s ease;
            color: white !important;
        }

        .btn-primary:hover,
        .btn-primary:focus {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
            background: linear-gradient(135deg, #2563eb, #1e40af);
            color: white !important;
        }

        .btn-outline-primary {
            color: var(--secondary-blue);
            border-color: var(--secondary-blue);
            border-radius: 10px;
            font-weight: 600;
            padding: 0.6rem 1.5rem;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background-color: var(--secondary-blue);
            color: white;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-weight: 700;
            color: var(--primary-blue);
            letter-spacing: -0.5px;
        }

        [data-bs-theme="dark"] h1,
        [data-bs-theme="dark"] h2,
        [data-bs-theme="dark"] h3,
        [data-bs-theme="dark"] h4,
        [data-bs-theme="dark"] h5,
        [data-bs-theme="dark"] h6 {
            color: var(--text-dark);
            /* White headers in dark mode */
        }

        .text-success {
            color: #10b981 !important;
            /* Premium green */
        }

        .text-danger {
            color: #ef4444 !important;
            /* Premium red */
        }

        .form-control,
        .form-select,
        .input-group-text {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border: 1px solid #cbd5e1;
            transition: all 0.2s ease;
            background-color: var(--card-bg) !important;
            color: var(--text-dark) !important;
        }

        [data-bs-theme="dark"] .form-control,
        [data-bs-theme="dark"] .form-select {
            border-color: #334155;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--secondary-blue);
            box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
        }

        .table {
            color: var(--text-dark);
            --bs-table-bg: transparent;
            --bs-table-color: var(--text-dark);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(59, 130, 246, 0.05);
        }

        .badge {
            padding: 0.5em 0.8em;
            border-radius: 6px;
            font-weight: 500;
        }

        .bg-secondary {
            background-color: #94a3b8 !important;
        }

        .bg-light {
            background-color: rgba(128, 128, 128, 0.05) !important;
        }
    </style>
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('accounts.index') }}">Cuentas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('predictions.index') }}">Proyecciones</a>
                            </li>
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">

                                    <a class="dropdown-item" href="{{ route('settings.index') }}">
                                        <i class="bi bi-gear me-2 text-secondary"></i>Configuración
                                    </a>

                                    <div class="dropdown-divider"></div>

                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right me-2 text-danger"></i>{{ __('Cerrar Sesión') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <!-- Dark Mode Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const getStoredTheme = () => localStorage.getItem('theme')
            const setStoredTheme = theme => localStorage.setItem('theme', theme)

            const setTheme = theme => {
                document.documentElement.setAttribute('data-bs-theme', theme)
                const darkModeSwitch = document.getElementById('darkModeSwitch');
                if (darkModeSwitch) {
                    darkModeSwitch.checked = theme === 'dark';
                }
            }

            setTheme(getStoredTheme() || (window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" :
                "light"));

            const darkModeSwitch = document.getElementById('darkModeSwitch');
            if (darkModeSwitch) {
                darkModeSwitch.addEventListener('change', () => {
                    const theme = darkModeSwitch.checked ? 'dark' : 'light';
                    setStoredTheme(theme);
                    setTheme(theme);
                });
            }
        });
    </script>
</body>

</html>
