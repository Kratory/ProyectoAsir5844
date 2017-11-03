<?php
    session_start();
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Proyecto</title>

        <link href="//fonts.googleapis.com/css?family=Roboto:400,100,300,500,700" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="../css/login.css" />
        <link rel="stylesheet" type="text/css" href="../css/page.css" />
    </head>

    <body>
            <div class="container">
                <div class="form">
                    <div class="text-center">
                        <p class="titulo">ProyectChat</p>
                    </div>
                    <form id="loginForm" method="post" action="checkUser.php">
                        <!-- Si checkUser determina que el usuario ya existe, cambia el color de fondo -->
                        <input type="text" class="input" name="name" placeholder="@Usuario" required="true" maxlength="16" <?php 
                            if(isset($_SESSION['usertaken'])){ echo 'style="background-color: #C54747";';} else echo 'style="background-color: #fff";';
                        ?>/>
                        <!-- Si checkUser determina que el email ya existe, cambia el color de fondo -->
                        <input type="email" class="input" name="email" placeholder="email@email.com" required="true" maxlength="64" <?php 
                            if(isset($_SESSION['mailtaken'])){ echo 'style="background-color: #C54747";';} else echo 'style="background-color: #fff";';
                        ?>/>
                        <input type="password" class="input" name="password" placeholder="Contraseña" required="true" maxlength="32" />
                        
                        <!-- Si checkUser  determina que el usuario o el correo ya existen, cambia el texto del boton -->
                        <input type="submit" name="register" class="btn" <?php 
                            if(isset($_SESSION['usertaken']) && isset($_SESSION['mailtaken'])){
                                echo 'value="Usuario y Email en uso.!"';
                                unset($_SESSION['usertaken']); unset($_SESSION['mailtaken']);
                            }
                            else if(isset($_SESSION['usertaken'])){
                                echo 'value="Usuario en uso!"';
                                unset($_SESSION['usertaken']);
                            }
                            else if(isset($_SESSION['mailtaken'])){
                                echo 'value="Email en uso!"';
                                unset($_SESSION['mailtaken']);
                            }
                            else{
                                echo 'value="¡Vamos!"';
                            }
                        ?>/>
                    </form>
                
                </div>
            </div>
    </body>
</html>