<?php
session_start();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Sistema Turnos</title>
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
            --shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
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
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
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

        .register-box {
            width: 800px;
            max-width: 100%;
            z-index: 100;
            position: relative;
        }

        .register-logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .register-logo a {
            color: white;
            font-size: 28px;
            font-weight: 600;
            text-decoration: none;
        }

        .register-logo img {
            width: 80px;
            margin-bottom: 10px;
        }

        .register-card {
            background: white;
            border-radius: 5px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .register-card-header {
            padding: 15px;
            text-align: center;
            background: var(--primary);
            color: white;
        }

        .register-card-header h3 {
            margin: 0;
            font-weight: 600;
        }

        .register-card-body {
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

        .form-group.required label::after {
            content: " *";
            color: var(--danger);
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

        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px;
        }

        .form-col {
            flex: 1;
            padding: 0 10px;
            min-width: 250px;
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

        .btn-success {
            background: var(--success);
            color: white;
        }

        .btn-success:hover {
            background: #008d4c;
        }

        .register-card-footer {
            padding: 15px;
            text-align: center;
            background: #f9f9f9;
            border-top: 1px solid var(--border);
        }

        .register-card-footer a {
            color: var(--primary);
            text-decoration: none;
        }

        .register-card-footer a:hover {
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

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .progress {
            height: 10px;
            background: #f4f4f4;
            border-radius: 5px;
            overflow: hidden;
            margin: 10px 0;
        }

        .progress-bar {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 10px;
            transition: width 0.5s;
        }

        .password-strength {
            margin-top: 5px;
            font-size: 12px;
            color: #777;
        }

        .pulse {
            display: inline-block;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(60, 141, 188, 0.7);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(60, 141, 188, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(60, 141, 188, 0);
            }
        }

        .fade-in {
            animation: fadeIn 0.5s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .form-col {
                flex: 100%;
            }

            .register-box {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="register-box">
        <div class="register-logo">
            <a href="#"><i class="fas fa-tachometer-alt"></i> Sistema <b>Turnos</b></a>
        </div>

        <div class="register-card fade-in">
            <div class="register-card-header">
                <h3>Registro de Nuevo Usuario</h3>
            </div>

            <div class="register-card-body">
                <form id="registerForm" action="controladores/registroUsuario.php" method="POST">
                    <?php if (isset($_SESSION['errores'])) : ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php foreach ($_SESSION['errores'] as $error) : ?>
                                    <li><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php unset($_SESSION['errores']); ?>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['exito'])) : ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> <?= $_SESSION['exito']; ?>
                        </div>
                        <?php unset($_SESSION['exito']); ?>
                    <?php endif; ?>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group required">
                                <label for="firstName">Nombre</label>
                                <div class="input-group">
                                    <span class="input-group-prepend">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input type="text" class="form-control" id="firstName" name="nombre" placeholder="Ingresa tus nombres" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group required">
                                <label for="email">Correo Electrónico</label>
                                <div class="input-group">
                                    <span class="input-group-prepend">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" name="email" class="form-control" id="email" placeholder="ejemplo@correo.com" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group required">
                                <label for="password">Contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-prepend">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" name="password" class="form-control" id="password" placeholder="Crea una contraseña segura" required>
                                </div>
                                <div class="password-strength">
                                    <div>Seguridad de la contraseña: <span id="passwordStrength">Débil</span></div>
                                    <div class="progress">
                                        <div class="progress-bar" id="passwordStrengthBar" style="width: 20%; background: #dd4b39;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group required">
                                <label for="username">Nombre de Usuario</label>
                                <div class="input-group">
                                    <span class="input-group-prepend">
                                        <i class="fas fa-at"></i>
                                    </span>
                                    <input type="text" name="usuario" class="form-control" id="username" placeholder="Elige un nombre de usuario" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success btn-block">
                            <i class="fas fa-user-plus"></i> Crear Cuenta
                        </button>
                    </div>
                </form>
            </div>

            <div class="register-card-footer">
                <p>¿Ya tienes una cuenta? <a href="index.php">Inicia sesión aquí</a></p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const registerForm = document.getElementById('registerForm');
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('confirmPassword');
            const passwordMatch = document.getElementById('passwordMatch');
            const passwordStrength = document.getElementById('passwordStrength');
            const passwordStrengthBar = document.getElementById('passwordStrengthBar');

            // Validar fortaleza de contraseña
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                let strength = 0;

                // Si está vacío
                if (password.length === 0) {
                    passwordStrength.textContent = 'Débil';
                    passwordStrengthBar.style.width = '0%';
                    passwordStrengthBar.style.background = '#ddd';
                    return;
                }

                // Longitud
                if (password.length >= 8) strength += 20;

                // Contiene números
                if (/\d/.test(password)) strength += 20;

                // Contiene minúsculas y mayúsculas
                if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength += 20;

                // Contiene caracteres especiales
                if (/[^A-Za-z0-9]/.test(password)) strength += 20;

                // Contiene patrones complejos
                if (password.length > 10) strength += 20;

                // Actualizar barra de fortaleza
                passwordStrengthBar.style.width = strength + '%';

                // Actualizar texto según fortaleza
                if (strength < 40) {
                    passwordStrength.textContent = 'Débil';
                    passwordStrengthBar.style.background = '#dd4b39';
                } else if (strength < 70) {
                    passwordStrength.textContent = 'Media';
                    passwordStrengthBar.style.background = '#f39c12';
                } else {
                    passwordStrength.textContent = 'Fuerte';
                    passwordStrengthBar.style.background = '#00a65a';
                }
            });





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