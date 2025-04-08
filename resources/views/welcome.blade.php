<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>EICHSYS</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <style>
        /* Reset básico */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background-color: #e3f2fd; /* Fondo azul claro */
            color: #2d3748;
            line-height: 1.6;
        }

        .welcome-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 2rem;
            text-align: center;
        }

        .welcome-header {
            margin-bottom: 2rem;
        }

        .welcome-header h1 {
            font-size: 2.5rem;
            color: #1a73e8; /* Azul brillante para el título */
            margin-bottom: 0.5rem;
        }

        .welcome-header p {
            font-size: 1.25rem;
            color: #4a5568; /* Gris oscuro para el subtítulo */
        }

        .welcome-logo {
            width: 150px;
            margin-bottom: 2rem;
        }

        .login-options {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .login-options a {
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .login-options a.patient {
            background-color: #1a73e8; /* Azul brillante para pacientes */
            color: white;
        }

        .login-options a.doctor {
            background-color: #34a853; /* Verde para médicos */
            color: white;
        }

        .login-options a:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        .auth-links {
            position: absolute;
            top: 1rem;
            right: 1rem;
            display: flex;
            gap: 1rem;
        }

        .auth-links a {
            color: #1a73e8; /* Azul brillante para enlaces */
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .auth-links a:hover {
            color: #1557b3; /* Azul más oscuro al hacer hover */
        }

        .footer {
            margin-top: 3rem;
            font-size: 0.875rem;
            color: #718096; /* Gris para el pie de página */
        }
    </style>
</head>
<body class="antialiased">

<div class="welcome-container">
    <!-- Enlaces de autenticación en la esquina superior derecha -->
    <div class="auth-links">
        @if (Route::has('login'))
            @auth
                <a href="{{ url('/dashboard') }}">Dashboard</a>
            @else
                <a href="{{ route('login') }}">Acceder</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}">Registrarse</a>
                @endif
                @if (Route::has('register-medico'))
                    <a href="{{ route('register-medico') }}">Registrarse como Médico</a>
                @endif
            @endauth
        @endif
    </div>

    <!-- Contenido principal -->
    <div class="welcome-header">
        <img src="https://cdn-icons-png.flaticon.com/512/2967/2967178.png" alt="Logo EICHSYS" class="welcome-logo">
        <h1>Bienvenido a EICHSYS</h1>
        <p>Inicie sesión con su perfil</p>
    </div>

    <!-- Opciones de inicio de sesión -->
    <div class="login-options">
        <a href="{{ route('login') }}" class="patient">Acceder como Paciente</a>
        <a href="{{ route('login') }}" class="doctor">Acceder como Médico</a>
    </div>

    <!-- Pie de página -->
    <div class="footer">
        &copy; {{ date('Y') }} EICHSYS. Todos los derechos reservados.
    </div>
</div>

</body>
</html>