<?php session_start();
$session_name = $_SESSION['name']; ?>
<html>
    <head> 
        <title>IO CHAT</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <script src="http://code.jquery.com/jquery-latest.min.js"></script>
        <script src="/socket.io/socket.io.js"></script>
        <style>
            body{
                margin-top: 30px;
            }
            #messageArea{
                display: none; /* La página entera (el contenedor del chat completo), está oculto por defecto*/
            }
        </style>
        <script>
            var session = '<?php echo $session_name ?>'; // Conversión de variable de sesion php a js.
        </script>
    </head>
    <body>
        <div class="container">
            <div id="userFormArea" class="row">
                <div class="col-md-12">
                    <form id="userForm">
                        <div class="form-group">
                            <label>Introduce tu nombre</label>
                            <input class="form-control" id="username" required="true"></input>
                            <br/>
                            <input type="submit" class="btn btn-primary" value="Entrar" />
                        </div>
                    </form>
                </div>
            </div>

            <div id="messageArea" class="row">
                <div class="col-md-4"> <!-- Bootstrap para barra lateral usuarios -->
                    <div class="well" style="overflow: auto; height: 500px;">
                        <h3>Usuarios Conectados</h3>
                        <ul class="list-group" id="users"></ul>
                    </div>
                </div>
                <div class="col-md-8"><!-- Boostrap para la zona de chat -->
                    <div class="chat" id="chat" style="overflow: auto; height: 500px;"></div>

                    <form id="messageForm">
                        <div class="form-group">
                            <label>Introduce un mensaje</label>
                            <input class="form-control" id="message" required="true"></input>
                            <br/>
                            <input type="submit" class="btn btn-primary" value="Enviar" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.3/socket.io.js"></script> <!-- include socket io -->

        <script>
            $(function(){
                var socket = io.connect('http://localhost:3000'); // Conexión al script

                // Definición de zonas del código HTML
                var $messageForm = $('#messageForm');
                var $message = $('#message');
                var $chat = $('#chat');

                var $messageArea = $('#messageArea');
                var $userFormArea = $('#userFormArea');
                var $userForm = $('#userForm');
                var $users = $('#users');
                var $username = $('#username');
                

                $messageForm.submit(function(e){ // Cada vez que se envía un mensaje desde el lado cliente.
                    e.preventDefault();
                    if($message.val() == '/clear'){
                        socket.emit('clear messages');
                        $message.val('Mensajes borrados por un administrador.');
                    }
                    socket.emit('send message', $message.val()); // La información se manda al script del lado del servidor.
                    $message.val(''); // Reset
                    
                });

                socket.on('load messages', function(data){ // Recibe información del script servidor para mostrar en pantalla todos los mensajes almacenados al conectarse.
                    if(data.length > 0){
                        for(var x = 0; x < data.length; x++) // Bucle que pasa por todas las iteraciones de data (Que viene siendo el resultado del query hecho en el servidor)
                        {
                            $chat.append('<div class="well"><strong>'+ data[x].name +'</strong>: '+ data[x].message + '</div>');
                        }
                    }
                    scrollDown(); // Baja la barra lateral automáticamente.
                });

                socket.on('new message', function(data){ // Procesa información del servidor para envíar los mensajes nuevos a los clientes.
                    $chat.append('<div class="well"><strong>'+data.user+'</strong>: '+data.msg+'</div>');
                    scrollDown(); // Baja la barra lateral automaticamente cada vez que se envía un mensaje.
                });

                $userForm.submit(function(e){ // Cada vez que entra un usuario nuevo.
                    e.preventDefault();
                    socket.emit('new user', $username.val() + ' (' + session + ')', function(data){ // Envio de información al script servidor, adjuntando al nombre además la variable de session php.
                        if(data){                                                                   // De manera que los usuarios se mostrarán: Nombre-escogido (Nombre real de registro)
                            $userFormArea.hide(); // Si se han introducido datos, oculta el formulario de nombre para el chat.
                            $messageArea.show(); // Y muestra el chat en sí.
                        }
                    });

                    $username.val(''); // Reset
                });

                socket.on('get users', function(data){
                    var html = '';
                    for(i = 0; i < data.length; i++){
                        html += '<li class="list-group-item">'+data[i]+'</li>'; // Añade los usuarios que se van conectando a la lista lateral, obteniendo la información del script servidor.
                    }
                    $users.html(html);
                });

                socket.on('cleared', function(callback){
                    while(chat.firstChild){
                        chat.removeChild(chat.firstChild); // Elimina todos los mensajes mostrados gráficamente, los hijos del contenedor chat.
                    }
                });l

                function scrollDown(){ // Funcion que únicamente fuerza a la barra de scroll vertical a bajar cada vez que se la llama.
                    var elem = document.getElementById('chat');
                    elem.scrollTop = elem.scrollHeight;
                }
            });
        </script>
    </body>
</html>