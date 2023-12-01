const express = require('express');
const cors = require('cors');
const app = express();
const axios = require('axios');

const http = require('http'); // Add this line to import the 'http' module

const server = http.createServer(app); // Create an HTTP server using the 'app' instance

app.use(cors());

const io = require('socket.io')(server, {
    cors: {
        origin: "*", // Reemplaza con la URL de tu cliente
        methods: ["GET", "POST"]
    }
});

const connectedUsers = [];
var quest = '';
io.on('connection', (socket) => {
  console.log("connected");

  socket.emit('long', connectedUsers.length);

  socket.on('user', (userId) => {
    if (connectedUsers.length != 2){
      connectedUsers.push(userId);
      socket.emit('waiting',userId,connectedUsers.length);    
    }
  });

  socket.on('startGame', () => {
    io.emit('GameStart');
  });
  
  socket.on('genQuest', () => {
    const randomNumber = Math.floor(Math.random() * (40 - 1 + 1)) + 1;
    const url = `http://127.0.0.1:8000/api/veurePregunta/${randomNumber}`;
    
    axios.get(url)
      .then(response => {
        const data = response.data;        
        io.emit('viewQuest', data);
      })
      .catch(error => {
        console.error(error);
      });
  });
    
  socket.on('disconnect', () => {
    console.log('Cliente desconectado');
  });
});

const PORT = process.env.PORT || 3000;

server.listen(PORT, () => {
    console.log(`Servidor escuchando en el puerto ${PORT}`);
});