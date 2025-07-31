<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>POS Blockchain</title>
   <link rel="stylesheet" href="{{ asset('css/app.css') }}">
   <link rel="stylesheet" href="{{ asset('css/tabla_users.css') }}">
   <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
   <link rel="stylesheet" href="{{ asset('css/json.css') }}">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
        <a class="navbar-brand" href="users">Cryptotrade</a>
        <div class="ms-auto">
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button class="btn btn-outline-light">Cerrar sesión</button>
            </form>
        </div>
    </nav>


    @yield('content')

   
</html>
