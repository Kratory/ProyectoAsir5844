<?php
    session_start();
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Proyecto</title>

        <link href="//fonts.googleapis.com/css?family=Roboto:400,100,300,500,700" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="css/login.css" />
        <link rel="stylesheet" type="text/css" href="css/page.css" />
    </head>

    <body>
            <div class="container">
                <div class="form">
                    <div class="text-center">
                        <p class="titulo">ProyectChat</p>
                    </div>
                    <form id="loginForm" method="post" action="php/checkUser.php">
                        <!-- Si checkUser.php determina que el usuario no existe, substituye el color de fondo -->
                        <input type="text" class="input" name="name" placeholder="@Usuario" required="true" maxlength="16" <?php
                                  if(isset($_SESSION['notfound'])) echo 'style="background-color: #C54747;"'; else echo'"background-color: #fff;"';
                                ?> />
                        <!-- Si checkUser.php determina que la contraseña es incorrecta, substituye el color de fondo -->
                        <input type="password" class="input" name="password" placeholder="Contraseña" required="true" maxlength="32" <?php
                                  if(isset($_SESSION['badpass'])) echo 'style="background-color: #C54747;"'; else echo'"background-color: #fff;"';  
                                ?> />
                        <!-- Si checkUser.php determina que la contraseña es incorrecta, substituye "Entrar" por "Contraseña Incorrecta" -->
                        <!-- Si checkUser.php determina que el usuario no existe, substituye "Entrar" por "Usuario Inexistente" -->
                        <!-- Si ambas cosas suceden, prevalece la segunda -->
                        <input type="submit" name="login" class="btn" <?php
                                    if(isset($_SESSION['badpass']) && isset($_SESSION['notfound'])) echo 'value="Usuario Inexistente"'; else if(isset($_SESSION['badpass'])) echo 'value="Contraseña Incorrecta"'; else if(isset($_SESSION['notfound'])) echo 'value="Usuario Inexistente"'; else echo 'value="Entrar"';
                                    unset($_SESSION['badpass']); unset($_SESSION['notfound']);?> />
                    </form>
                    <form id="loginForm" method="post" action="php/regUser.php">
                        <input type="submit" class="btn" <?php if(isset($_SESSION['welcome'])) echo 'style="background-color: #66CA5F;" value="¡Bienvenido!"'; else echo 'value="¿No tienes cuenta?"'; unset($_SESSION['welcome']);?> />
                    </form> 
                
                </div>
                <div class="text-center">
                        <p class="firma">Proyecto ASIR APG 5488</p>
                </div>
            </div>
        <script src="js/script.js"></script>
    </body>
</html>