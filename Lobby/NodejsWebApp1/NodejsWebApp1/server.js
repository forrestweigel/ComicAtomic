'use strict';
//var http = require('http');
//var port = process.env.PORT || 1337;



const express = require("express");
const socket = require("socket.io");

// App setup
const PORT = 1337;
const app = express();
const server = app.listen(PORT, function () {
    console.log(`Listening on port ${PORT}`);
    console.log(`http://localhost:${PORT}`);
});

// Static files
app.use(express.static("public"));

// Socket setup
const io = socket(server);

const activeUsers = new Set();

io.on("connection", function (socket) {
    console.log("Made socket connection");

    socket.on("new user", function (data) {
        socket.userId = data;
        activeUsers.add(data);
        io.emit("new user", [...activeUsers]);
       

        
    });

    socket.on("disconnect", () => {
        activeUsers.delete(socket.userId);
        io.emit("user disconnected", socket.userId);
    });

    socket.on("chat message", function (data) {
        io.emit("chat message", data);
    });

    socket.on("create", () => {
        io.emit("create", socket.userId);

    });

    socket.on("join",function (data)  {

       
        io.emit("join",data,socket.userId);

    });


});



//const prompt = require('prompt');

//prompt.start();

//prompt.get("enter name", function (name) {
//    console.log(name);
   

//});


//http.createServer(function (req, res) {
//    res.writeHead(200, { 'Content-Type': 'text/plain' });
//    res.end('Hello World\n');
//}).listen(port);