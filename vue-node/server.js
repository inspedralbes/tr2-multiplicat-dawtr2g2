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
var resp = [];

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
    var randomNumber = Math.floor(Math.random() * (40 - 1 + 1)) + 1;
    const url = `http://127.0.0.1:8000/api/preguntes/mostrar/${randomNumber}`;
    
    axios.get(url)
      .then(response => {
        var data = response.data;
        var respCorr = Math.floor(Math.random() * 4);

        var promises = [];
        for (let i = 0; i < 4; i++) {
          if (respCorr == i) {
            var urlResp = `http://127.0.0.1:8000/api/respostes/mostrar/${data.resposta_correcta_id}`;
          } else {
            randomNumber = Math.floor(Math.random() * (120 - 1 + 1)) + 1;
            urlResp = `http://127.0.0.1:8000/api/respostes/mostrar/${randomNumber}`;    
          }
          promises.push(axios.get(urlResp));
        }
        
        Promise.all(promises)
          .then(responses => {
            resp = responses.map(response => ({
              id: response.data.id,
              resposta: response.data.resposta
            }));
            console.log(resp);
            var quest = {
              id: data.id,
              pregunta: data.pregunta
            };
            io.emit('viewQuest', quest);
            socket.broadcast.emit('viewResp', resp);
          })
          .catch(error => {
            console.error(error);
          });
      })
      .catch(error => {
        console.error(error);
      });
  });

  socket.on('compAns', (quest,resp) => { 
    var url = `http://127.0.0.1:8000/api/preguntes/mostrar/${quest}`
    
    axios.get(url)
      .then(response => {
        var data = response.data.resposta_correcta_id;
        console.log(data);
        if (data == resp) {
          console.log('Correcte');
        }else{
          console.log('Incorrecte');
        }
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