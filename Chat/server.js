var express = require('express');
var app = express();
var server = require('http').createServer(app);
var io = require('socket.io').listen(server);
var mysql = require('mysql');
var util = require('util');
users = [];
connections = [];

server.listen(process.env.PORT || 3000); // Puerto en el que va a escuchar el servidor
console.log('Server Runing...');

var con = mysql.createConnection({ // Constructor para las conexiones a la base de datos.
    host:       "localhost",
    user:       "root",
    password:   "",
    database:   "proyecto"
});

if(con.connect(function(err){ // Intento de conexión a la base de datos ^
    if(err) throw err;
    console.log("MySQL Connected Successfuly...");
}));


io.sockets.on('connection', function(socket){ // Cuando se conecta un socket...
    // Conexion
    connections.push(socket);
    console.log('Connected: %s sockets connected', connections.length); // Unicamente muestra cuantas conexiones hay activas en el momento (por consola)

    // Desconexion
    socket.on('disconnect', function(data){
        
        users.splice(users.indexOf(socket.username), 1); // Si un usuario cierra su socket, desaparece de la lista de usuarios conectados
        updateUsernames();

        connections.splice(connections.indexOf(socket),1);
        console.log('Disconected: %s sockets connected', connections.length); // De nuevo, registro de conexiones en consola.
    });

    // Enviar mensaje
    socket.on('send message', function(data){
        var query_insert = util.format('INSERT INTO webchat_lines (name, message) VALUES ("%s", "%s")', socket.username, data); // Ingreso del mensaje de texto a la base de datos
        con.query(query_insert, function(err, result){
            if(err) throw err;
            console.log("Mensaje insertado..."); // Si no hay errores, muestra por consola "Mensaje insertado"
        });

        io.sockets.emit('new message', {msg: data, user: socket.username}); // Envío de la información obtenida aquí, al lado del cliente.
    });

    // Usuario nuevo
    socket.on('new user', function(data, callback){
        callback(true);
        socket.username = data;
        users.push(socket.username); // Usuarios conectados

        // Obtener mensajes almacenados cuando el usuario se conecta.
        var query = 'SELECT * FROM webchat_lines ORDER BY id';
        var msgs = con.query(query, function(err, result){
            if(err) throw err;
            
            socket.emit('load messages', result); // Si la solicitud no devuelve errores, envía todos los mensajes guardados en forma de array al cliente.

            // Testeando salida de datos, para usarla en el chat cliente
            /*for(var x = 0; x < result.length; x++){
                console.log(result[x].name + ' ' + result[x].message);
            }*/
        });
        
        updateUsernames();
    });

    // Comando /clear
    socket.on('clear messages', function(callback){
        var query_delete = "DELETE FROM webchat_lines"; // Elimina todos los mensajes de la base de datos.
        con.query(query_delete, function(err, result){
            if(err) throw err;
            console.log('Mensajes eliminados...');
            io.sockets.emit('cleared');
        })
    });

    function updateUsernames(){
        io.sockets.emit('get users', users);
    }
});