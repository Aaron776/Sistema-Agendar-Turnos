<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema Turnos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #3c8dbc;
            --secondary: #222d32;
            --light: #ecf0f5;
            --success: #00a65a;
            --info: #00c0ef;
            --warning: #f39c12;
            --danger: #dd4b39;
            --text: #444;
            --text-light: #b8c7ce;
            --border: #d2d6de;
            --shadow: 0 3px 6px rgba(0,0,0,0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--light);
            color: var(--text);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: url('https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1500&q=80');
            background-size: cover;
            background-position: center;
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, rgba(60, 141, 188, 0.8), rgba(34, 45, 50, 0.9));
        }

        .login-box {
            width: 360px;
            z-index: 100;
            position: relative;
        }

        .login-logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .login-logo a {
            color: white;
            font-size: 28px;
            font-weight: 600;
            text-decoration: none;
        }

        .login-logo img {
            width: 80px;
            margin-bottom: 10px;
        }

        .login-card {
            background: white;
            border-radius: 5px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .login-card-header {
            padding: 15px;
            text-align: center;
            background: var(--primary);
            color: white;
        }

        .login-card-header h3 {
            margin: 0;
            font-weight: 600;
        }

        .login-card-body {
            padding: 20px;
        }

        .form-group {
            margin-bottom: 15px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: var(--text);
        }

        .input-group {
            position: relative;
        }

        .input-group-prepend {
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eee;
            border: 1px solid var(--border);
            border-right: none;
            border-radius: 3px 0 0 3px;
            color: #777;
        }

        .form-control {
            width: 100%;
            padding: 10px 10px 10px 45px;
            border: 1px solid var(--border);
            border-radius: 3px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(60, 141, 188, 0.2);
        }

        .checkbox {
            display: flex;
            align-items: center;
        }

        .checkbox input {
            margin-right: 5px;
        }

        .checkbox label {
            margin: 0;
            font-weight: normal;
        }

        .btn-block {
            display: block;
            width: 100%;
        }

        .btn {
            padding: 10px 15px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: #367fa9;
        }

        .social-auth-links {
            margin: 15px 0;
            text-align: center;
        }

        .social-auth-links p {
            margin-bottom: 10px;
            color: #777;
            font-size: 14px;
        }

        .btn-google {
            background: #dd4b39;
            color: white;
            margin-bottom: 10px;
        }

        .btn-facebook {
            background: #3b5998;
            color: white;
        }

        .login-card-footer {
            padding: 15px;
            text-align: center;
            background: #f9f9f9;
            border-top: 1px solid var(--border);
        }

        .login-card-footer a {
            color: var(--primary);
            text-decoration: none;
        }

        .login-card-footer a:hover {
            text-decoration: underline;
        }

        .alert {
            padding: 10px 15px;
            margin-bottom: 15px;
            border-radius: 3px;
            font-size: 14px;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .pulse {
            display: inline-block;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(60, 141, 188, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(60, 141, 188, 0); }
            100% { box-shadow: 0 0 0 0 rgba(60, 141, 188, 0); }
        }

        .fade-in {
            animation: fadeIn 0.5s;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 576px) {
            .login-box {
                width: 90%;
            }
        }
    </style>
</head>
<body>
    <div class="login-box">
        <div class="login-logo">
            <a href="#"><i class="fas fa-tachometer-alt"></i> Sistema <b>Turnos</b></a>
        </div>

        <div class="login-card fade-in">
            <div class="login-card-header">
                <h3>Iniciar Sesión</h3>
            </div>

            <div class="login-card-body">
                <form id="loginForm" action="controladores/login.php" method="POST">
                    <div class="form-group">
                        <label for="username">Usuario</label>
                        <div class="input-group">
                            <span class="input-group-prepend">
                                <i class="fas fa-user"></i>
                            </span>
                            <input type="text" name="usuario" class="form-control" id="username" placeholder="Ingresa tu usuario" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-prepend">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" name="password" class="form-control" id="password" placeholder="Ingresa tu contraseña" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="checkbox">
                            <input type="checkbox" id="remember">
                            <label for="remember">Recordar mi sesión</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                        </button>
                    </div>
                </form>
            </div>

            <div class="login-card-footer">
                <a href="codigo_cambio_password.php"><i class="fas fa-key"></i> Olvidé mi contraseña</a><br>
                <a href="registro.php"><i class="fas fa-user-plus"></i> Crear una cuenta nueva</a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Efecto de enfoque en los campos
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });
                
                input.addEventListener('blur', function() {
                    if (this.value === '') {
                        this.parentElement.classList.remove('focused');
                    }
                });
            });
        });
    </script>
</body>
</html>