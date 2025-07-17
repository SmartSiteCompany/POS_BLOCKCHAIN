<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h2>Bienvenido, {{ $user->name }}</h2>
    <p>Tu correo: {{ $user->email }}</p>
    <p>Tu tipo de usuario: {{ $user->kind }}</p>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Cerrar sesión</button>
    </form>
</body>
</html>
