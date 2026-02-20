<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts (Bootstrap CDN Fallback) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        :root {
            --primary-blue: #1e3a8a; /* Deep blue */
            --secondary-blue: #3b82f6; /* Bright blue */
            --accent-blue: #bfdbfe; /* Light blue accent */
            --bg-light: #f4f7fb; /* Very light blue-grey background */
            --text-dark: #1e293b;
            --card-bg: rgba(255, 255, 255, 0.95);
        }
        body {
            background-color: var(--bg-light);
            color: var(--text-dark);
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        .navbar {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue)) !important;
            padding: 1rem 0;
            box-shadow: 0 4px 20px rgba(30, 58, 138, 0.15) !important;
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
        .nav-link:hover, .nav-link:focus {
            color: #ffffff !important;
        }
        .navbar-toggler {
            border-color: rgba(255,255,255,0.5);
        }
        .navbar-toggler-icon {
            filter: invert(1);
        }
        /* Dropdown styling */
        .dropdown-menu {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(30, 58, 138, 0.1);
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
            box-shadow: 0 10px 30px rgba(30, 58, 138, 0.06);
            backdrop-filter: blur(10px);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(30, 58, 138, 0.12);
        }
        .card-header {
            background-color: transparent;
            border-bottom: 1px solid rgba(0,0,0,0.05);
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
        }
        .btn-primary:hover, .btn-primary:focus {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
            background: linear-gradient(135deg, #2563eb, #1e40af);
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
        h1, h2, h3, h4, h5, h6 {
            font-weight: 700;
            color: var(--primary-blue);
            letter-spacing: -0.5px;
        }
        .text-success {
            color: #10b981 !important; /* Premium green */
        }
        .text-danger {
            color: #ef4444 !important; /* Premium red */
        }
        .form-control, .form-select {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border: 1px solid #cbd5e1;
            transition: all 0.2s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--secondary-blue);
            box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
        }
        .table {
            color: var(--text-dark);
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
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
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
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
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
</body>
</html>
