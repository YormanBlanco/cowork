<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Cowork</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="fonts/iconic/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
    <link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
    <link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
    <link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
    <link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" type="text/css" href="css/util.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>

<div class="limiter">
    <div class="container-login100" style="background-image: url('images/bg-01.jpg');">
        <div class="wrap-login100">
            <form id="loginForm">
                @csrf
                <span class="login100-form-logo">
                    <i class="zmdi zmdi-landscape"></i>
                </span>

                <span class="login100-form-title p-b-34 p-t-27">
                    Log in
                </span>

                <div class="wrap-input100 validate-input" data-validate="Enter email">
                    <input class="input100" type="text" name="email" placeholder="Email" id="email">
                    <span class="focus-input100" data-placeholder="&#xf207;"></span>
                </div>

                <div class="wrap-input100 validate-input" data-validate="Enter contraseña">
                    <input class="input100" type="password" name="password" placeholder="Contraseña" id="password">
                    <span class="focus-input100" data-placeholder="&#xf191;"></span>
                </div>

                <div class="container-login100-form-btn">
                    <button type="submit" id="loginButton" class="login100-form-btn">
                        Iniciar Sesión
                    </button>
                </div>

                <div class="text-center p-t-90">
                    <a href="javascript:void(0);" id="registerLink" class="txt1">
                        ¿No tienes cuenta? Registrate
                    </a>
                </div>
            </form>

            <!-- Formulario de Registro (oculto inicialmente) -->
            <form id="registerForm" class="login100-form validate-form" style="display:none;">
                @csrf
                <span class="login100-form-logo">
                    <i class="zmdi zmdi-landscape"></i>
                </span>

                <span class="login100-form-title p-b-34 p-t-27">
                    Registro
                </span>

                <div class="wrap-input100 validate-input" data-validate="Enter email">
                    <input class="input100" type="email" name="email" placeholder="Email" id="email2" required>
                    <span class="focus-input100" data-placeholder="&#xf207;"></span>
                </div>

                <div class="wrap-input100 validate-input" data-validate="Enter nombre">
                    <input class="input100" type="text" name="name" id="name" placeholder="Nombre" required>
                    <span class="focus-input100" data-placeholder="&#xf191;"></span>
                </div>

                <div class="wrap-input100 validate-input" data-validate="Enter contraseña">
                    <input class="input100" type="password" name="password" placeholder="Contraseña" id="password2" required>
                    <span class="focus-input100" data-placeholder="&#xf191;"></span>
                </div>

                <div class="wrap-input100 validate-input" data-validate="Confirmar contraseña">
                    <input class="input100" type="password" name="password_confirmation" placeholder="Confirmar Contraseña" id="password_confirmation" required>
                    <span class="focus-input100" data-placeholder="&#xf191;"></span>
                </div>

                <div class="container-login100-form-btn">
                    <button type="submit" id="registerButton" class="login100-form-btn">
                        Registrarse
                    </button>
                </div>

                <div class="text-center p-t-90">
                    <a href="javascript:void(0);" id="loginLink" class="txt1">
                        ¿Ya tienes cuenta? Inicia sesión
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<script src="vendor/bootstrap/js/popper.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>

<script>
    // Cambiar entre el formulario de login y registro
    document.getElementById('registerLink').addEventListener('click', function() {
        document.getElementById('loginForm').style.display = 'none';
        document.getElementById('registerForm').style.display = 'block';
    });

    document.getElementById('loginLink').addEventListener('click', function() {
        document.getElementById('registerForm').style.display = 'none';
        document.getElementById('loginForm').style.display = 'block';
    });


    // Enviar el formulario de login usando fetch
    document.getElementById('loginForm').addEventListener('submit', async function(event) {
        event.preventDefault();

        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const data = {
            email: email,
            password: password,
        };

        try {
            const response = await fetch(`/auth/login`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const responseData = await response.json();

            // Verificar si la respuesta es exitosa
            if (response.ok) {  // response.ok es true si el código de estado está entre 200 y 299
                // Verificar si hay una URL de redirección
                if (responseData.redirect) {
                    // Redirigir al cliente
                    window.location.href = responseData.redirect;
                } else {
                    // Si no hay redirección, hacer algo más, como mostrar un mensaje o actualizar la UI
                    console.log('Inicio de sesión exitoso, pero no hay redirección.');
                }
            } else {
                // Si la respuesta no es exitosa, mostrar el mensaje de error
                alert(responseData.error || 'Error al iniciar sesión');
            }
        } catch (error) {
            // Capturar y mostrar errores de la red (problemas de conexión)
            alert('Error en la solicitud');
        }

    });

    // Enviar el formulario de register
    document.getElementById('registerForm').addEventListener('submit', async function(event) {
        event.preventDefault();

        const email = document.getElementById('email2').value;
        const name = document.getElementById('name').value;
        const password = document.getElementById('password2').value;
        const password_confirmation = document.getElementById('password_confirmation').value;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const data = {
            email,
            name,
            password,
            password_confirmation
        };

        try {
            const response = await fetch(`/auth/register`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const responseData = await response.json();
            console.log(responseData)

            // Verificar si la respuesta es exitosa
            if (response.ok) {  // response.ok es true si el código de estado está entre 200 y 299
                // Verificar si hay una URL de redirección
                if (responseData.redirect) {
                    // Redirigir al cliente
                    window.location.href = responseData.redirect;
                } else {
                    // Si no hay redirección, hacer algo más, como mostrar un mensaje o actualizar la UI
                    console.log('Registro exitoso, pero no hay redirección.');
                }
            } else {
                // Si la respuesta no es exitosa, mostrar el mensaje de error
                alert(responseData.error || 'Error al registrarse');
            }
        } catch (error) {
            // Capturar y mostrar errores de la red (problemas de conexión)
            alert('Error en la solicitud');
        }

    });
</script>

</body>
</html>