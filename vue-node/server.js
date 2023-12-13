const express = require('express');
const cors = require('cors');
const app = express();
const axios = require('axios');



const http = require('http'); // Add this line to import the 'http' module
const { emit } = require('process');

const server = http.createServer(app); // Create an HTTP server using the 'app' instance


app.use(cors());

var rooms = [];

const io = require('socket.io')(server, {
  cors: {
    origin: "*", // Reemplaza con la URL de tu cliente
    methods: ["GET", "POST"]
  }
});

io.on('connection', (socket) => {
  console.log("connected");

  socket.on('login', async (email, password) => {
    try {
      const response = await axios.post('http://127.0.0.1:8000/api/login', {
        email: email,
        password: password
      });
      console.log('Respuesta del servidor:', response.data);
      const user = {
        username: response.data.user.user.username,
        token: response.data.user.token,
        skin: response.data.user.skin
      };
      console.log(user);
      socket.emit('loginParameters', user);
      socket.emit('success', response.data);
    } catch (error) {
      if (error.response.status === 400) {

        console.error('Error al realizar la solicitud:', error.response.data.message);
        socket.emit('error400', error.response.data.message);

      }
    }

  });

  socket.on('register', async (username, email, password, password_confirmation, skin_id) => {
    try {
      const response = await axios.post('http://127.0.0.1:8000/api/registre', {
        username: username,
        email: email,
        password: password,
        password_confirmation: password_confirmation,
        skin_id: skin_id
      });

      console.log('Respuesta del servidor:', response.data);
      socket.emit('success', response.data.success);

    } catch (error) {
      if (error.response.status === 400) {

        console.error('Error al realizar la solicitud:', error.response.data.message);
        socket.emit('error400', error.response.data.message);

      }

    }
  });

  socket.on('genQuest', (id) => {
    var randomNumber = Math.floor(Math.random() * (40 - 1 + 1)) + 1;
    const url = `http://127.0.0.1:8000/api/preguntes/mostrar/${randomNumber}`;
    var resp = [];

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
            io.to(id).emit('viewQuest', quest);
            socket.to(id).emit('viewResp', resp);
          })
          .catch(error => {
            console.error(error);
          });
      })
      .catch(error => {
        console.error(error);
      });
  });

  socket.on('compAns', (quest, resp, id) => {
    var url = `http://127.0.0.1:8000/api/preguntes/mostrar/${quest}`

    axios.get(url)
      .then(response => {
        var data = response.data.resposta_correcta_id;
        console.log(data);

        if (data == resp) {
          io.to(id).emit('correct');
          console.log('Correcte');
        } else {
          io.to(id).emit('incorrect');
          console.log('Incorrecte');
        }
      })
      .catch(error => {
        console.error(error);
      });
  });

  socket.on('getRooms', () => {
    io.emit('viewRooms', rooms);
  });

  socket.on('createRoom', (name, id) => {
    var room = {
      name: name,
      id: id,
      players: []
    };
    var player = {
      name: "player1",
      id: 1,
      life: 100,
    }
    room.players.push(player);
    rooms.push(room);

    socket.join(id);
    socket.emit('roomCreated', room);
    io.emit('viewRooms', rooms);
  });

  socket.on('joinRoom', (id) => {
    var exist = false;
    var i = 0;
    var room = {};

    while (i < rooms.length && !exist) {
      const element = rooms[i];
      if (id === element.id) {
        var player = {
          name: "player2",
          id: 2,
          life: 100,
        }
        exist = true;
        element.players.push(player);
        room = element;
      }
      i++;
    }

    if (exist) {
      socket.join(id);
      socket.emit("joiningGame", room);
      socket.to(id).emit('playerJoined', room);
      io.emit('viewRooms', rooms);
    }
  });

  socket.on('disconnect', () => {
    console.log('Cliente desconectado');
  });
});

const PORT = process.env.PORT || 3000;

server.listen(PORT, () => {
  console.log(`Servidor escuchando en el puerto ${PORT}`);
});