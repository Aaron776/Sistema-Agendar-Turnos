<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - Sistema de Turnos</title>
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

        .recovery-box {
            width: 400px;
            z-index: 100;
            position: relative;
        }

        .recovery-logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .recovery-logo a {
            color: white;
            font-size: 28px;
            font-weight: 600;
            text-decoration: none;
        }

        .recovery-logo img {
            width: 80px;
            margin-bottom: 10px;
        }

        .recovery-card {
            background: white;
            border-radius: 5px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .recovery-card-header {
            padding: 15px;
            text-align: center;
            background: var(--primary);
            color: white;
        }

        .recovery-card-header h3 {
            margin: 0;
            font-weight: 600;
        }

        .recovery-card-body {
            padding: 30px;
        }

        .recovery-icon {
            text-align: center;
            font-size: 64px;
            color: var(--primary);
            margin-bottom: 20px;
        }

        .recovery-instructions {
            text-align: center;
            margin-bottom: 25px;
            color: #666;
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
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
            padding: 12px 12px 12px 45px;
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

        .btn {
            padding: 12px 15px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.2s;
            display: block;
            width: 100%;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: #367fa9;
        }

        .recovery-card-footer {
            padding: 20px;
            text-align: center;
            background: #f9f9f9;
            border-top: 1px solid var(--border);
        }

        .recovery-card-footer a {
            color: var(--primary);
            text-decoration: none;
        }

        .recovery-card-footer a:hover {
            text-decoration: underline;
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

        @media (max-width: 576px) {
            .recovery-box {
                width: 90%;
            }
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
    </style>
</head>

<body>
    <div class="recovery-box">
        <div class="recovery-logo">
            <a href="#"><i class="fas fa-calendar-alt"></i> Sistema de <b>Turnos</b></a>
        </div>

        <div class="recovery-card fade-in">
            <div class="recovery-card-header">
                <h3>Recuperar Contraseña</h3>
            </div>

            <div class="recovery-card-body">
                <div class="recovery-icon">
                    <i class="fas fa-key"></i>
                </div>

                <p class="recovery-instructions">
                    Ingresa tu dirección de correo electrónico y te enviaremos un enlace para restablecer tu contraseña.
                </p>

                <form id="recoveryForm" method="POST" action="controladores/codigoCambioPassword.php">
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
                    <div class="form-group">
                        <label for="email">Correo Electrónico</label>
                        <div class="input-group">
                            <span class="input-group-prepend">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" class="form-control" id="email" name="email" placeholder="ejemplo@correo.com" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Enviar Enlace de Recuperación
                        </button>
                    </div>
                </form>
            </div>

            <div class="recovery-card-footer">
                <a href="index.php"><i class="fas fa-arrow-left"></i> Volver al Inicio de Sesión</a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Efecto de enfoque en el campo
            const emailInput = document.getElementById('email');
            emailInput.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });

            emailInput.addEventListener('blur', function() {
                if (this.value === '') {
                    this.parentElement.classList.remove('focused');
                }
            });
        });
    </script>
</body>

</html>