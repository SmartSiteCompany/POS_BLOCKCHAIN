<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>POS Blockchain</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/tabla_users.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/json.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}" />
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
        <!-- Botón hamburguesa -->
        <button id="menu-toggle" class="toggle toggle2" aria-label="Toggle menu">
            <div id="bar4" class="bars"></div>
            <div id="bar5" class="bars"></div>
            <div id="bar6" class="bars"></div>
        </button>

        <a class="navbar-brand" href="/users">Cryptotrade</a>

        <div class="ms-auto">
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button class="btn btn-outline-light">Cerrar sesión</button>
            </form>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar">
        <ul>
            <li><a href="{{ route('register') }}" class="action-button btn-primary text-decoration-none">
            <div class="svg-wrapper-1">
                <div class="svg-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 12c2.7 0 4.5-1.8 4.5-4.5S14.7 3 12 3 7.5 4.8 7.5 7.5 9.3 12 12 12zm0 1.5c-3 0-9 1.5-9 4.5V21h18v-3c0-3-6-4.5-9-4.5z"/>
                    </svg>
                </div>
            </div>
            <span>Registro</span>
        </a></li>
            <li><a href="/users">Usuarios</a></li>
            <li><a href="/pagos">Pagos</a></li>
        </ul>
    </div>

    <!-- Contenido principal -->
    <main class="p-4">
        @yield('content')
    </main>

    <!-- Script para toggle -->
    <script>
        const toggleBtn = document.getElementById('menu-toggle');
        const sidebar = document.querySelector('.sidebar');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('sidebar-open');

            // Animar las barras del botón (opcional)
            toggleBtn.classList.toggle('active');
        });
    </script>
</body>
</html>
