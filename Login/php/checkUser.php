<?php
    session_start();
    header("Content-Type: text/html;charset=utf-8");
    include 'db.php';
    
    if($_POST['login']){ // Intento de login
        $user   = $_POST['name'];
        $passwd = $_POST['password'];

        $query = "SELECT * FROM webchat_users WHERE name='$user'";
        $run = $connection->query($query);
        

        if(mysqli_num_rows($run) != 0){ // Si existe
            while($row = mysqli_fetch_assoc($run)){
                $dbuser         = $row['name'];
                $dbpassword     = $row['password'];
            }

            if($passwd == $dbpassword){
                $_SESSION['user'] = $dbuser;
                header('Location: ../../chat/index.php'); // ENTRADA AL CHAT
                $_SESSION['name'] = $user;
            }
            else{
                $_SESSION['badpass'] = 1;
                header('Location: ../index.php'); // Contraseña incorrecta, vuelta a index.
            }
        }
        else{ // Si NO existe
            $_SESSION['notfound'] = 1;
            header('Location: ../index.php'); // No existe, vuelta a index.
        }
    }
    else if($_POST['register']){ // Intento de registro
        $user = $_POST['name'];
        $email = $_POST['email'];
        $passwd = $_POST['password'];

        $exists = "SELECT * FROM webchat_users WHERE name='$user' OR email='$email'"; // Comprueba si existe alguna cuenta con ese nombre o ese email.
        $check  = $connection->query($exists);

        if(mysqli_num_rows($check) != 0){ // Si ya existe, prepara el aviso y regresa a regUser
            while($row = mysqli_fetch_assoc($check)){
                $dbuser     = $row['name'];
                $dbmail     = $row['email'];
            }
            // Errores dependiendo de qué campo ya está en uso
            if($user == $dbuser) $_SESSION['usertaken'] = 1;
            if($email == $dbmail) $_SESSION['mailtaken'] = 1;
            header('Location: regUser.php');
        }
        else{ // Si no existe lo añadimos a la base de datos, y lo mandamos a index.
            $query = "INSERT INTO webchat_users (name, password, email) VALUES ('$user', '$passwd', '$email')";
            $insert = $connection->query($query);
            if($insert){
                $_SESSION['welcome'] = 1;
                header('Location: ../index.php');
            }
            else{
                echo 'Error: ' . mysqli_error($connection);
            }
        }
    }
?>