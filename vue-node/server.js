import express from "express";
import cors from "cors";
import http from "http";
import { Server } from "socket.io";
import comsManager from "./comsManager.js";
import { useAppStore } from "./src/stores/app.js";

const app = express();
const server = http.createServer(app);

app.use(cors());

var rooms = [];
var players = [];

const io = new Server(server, {
  cors: {
    origin: "*",
    methods: ["GET", "POST"],
  },
});

io.on("connection", (socket) => {
  console.log("Client connectat");

  socket.on("login", async (email, password) => {
    comsManager
      .login(email, password)
      .then((response) => {
        const user = {
          userId: response.data.user.id,
          username: response.data.user.username,
          token: response.data.token,
          skin: response.data.skin,
        };
        socket.emit("loginParameters", user);
        socket.emit("success", response);
      })
      .catch((error) => {
        if (error.response.status === 400) {
          console.error(
            "Error al realizar la solicitud:",
            error.response.data.message
          );
          socket.emit("error400", error.response.data.message);
        }
      });
  });

  socket.on(
    "register",
    async (username, email, password, password_confirmation, skin_id) => {
      comsManager
        .register(username, email, password, password_confirmation, skin_id)
        .then((response) => {
          socket.emit("success", response);
        })
        .catch((error) => {
          if (error.response.status === 400) {
            socket.emit("error400", error.response.data.message);
          }
        });
    }
  );

  socket.on("logout", (token) => {
    comsManager
      .logout(token)
      .then((response) => {
        socket.emit("successLogout", response);
        socket.emit("logoutEliminarInfo");
      })
      .catch((error) => {
        if (error.response.status === 400) {
          socket.emit("error400", error.response.data.message);
        }
      });
  });

  socket.on("genQuest", async (id) => {
    var i = 0;
    const room = rooms.find((room) => room.id === id);
    var exist = false;
    var questData = {};
    try {
      while (!exist) {
        questData = await comsManager.getRandomQuestion();
        if (!room.quests.some((q) => q.id === questData.id)) {
          exist = true;
          room.quests.push(questData);
        }
      }
      const respData = await comsManager.getRandomAnswers(questData);

      const quest = {
        id: questData.id,
        pregunta: questData.pregunta,
      };

      io.to(id).emit("viewQuest", quest);
      socket.to(id).emit("viewResp", respData);

      room.timer = 10;

      return { questData, respData };
    } catch (error) {
      console.error(error);
      return { error: error.message };
    }
  });

  socket.on("compAns", (quest, resp, id, user) => {
    var a = 0;
    comsManager
      .checkAnswer(quest)
      .then((response) => {
        var data = response.resposta_correcta_id;
        if (data == resp) {
          io.to(id).emit("correct");
          console.log("Correcte");
        } else {
          let x = 0;
          while (x < rooms.length) {
            const element = rooms[x];
            if (id === element.id) {
              let y = 0;
              while (y < element.players.length) {
                const player = element.players[y];
                if (player.name === user) {
                  comsManager
                    .getDamage(response.dificultat_id)
                    .then((response) => {
                      player.life = player.life - response;
                      console.log(player.life);
                      io.to(id).emit("life", player);
                      if (player.life <= 0) {
                        io.to(id).emit("gameOver", player);
                        clearInterval(element.timerId);

                        io.to(id).emit("disconnectRoom", id);
                        const socketsInRoom = io.sockets.adapter.rooms.get(id);

                        for (const socketId of socketsInRoom) {
                          const socket = io.sockets.sockets.get(socketId);
                          socket.leave(id);
                        }

                        const roomIndex = rooms.findIndex((room) => room.id === id);
                        if (roomIndex !== -1) {
                          rooms.splice(roomIndex, 1);
                        }
                        io.emit('viewRooms', rooms);
                      }
                    })
                    .catch((error) => {
                      console.error(error);
                    });
                }
                y++;
              }
            }
            x++;
          }

          io.to(id).emit("incorrect");
          console.log("Incorrecte");
        }

        let a = 0;
        while (a < rooms.length) {
          const element = rooms[a];
          if (id === element.id) {
            element.timer = 10;
          }
          a++;
        }
      })
      .catch((error) => {
        console.error(error);
      });
  });

  socket.on("getRooms", () => {
    socket.emit("viewRooms", rooms);
  });

  socket.on("createRoom", (name, id, user) => {
    var room = {
      name: name,
      id: id,
      players: [],
      timer: 10,
      timerId: null,
      timeUp: false,
      quests: [],
    };

    var player = {
      name: user.name,
      id: 1,
      life: 100,
      skin: user.skin,
    };
    room.players.push(player);
    rooms.push(room);

    socket.join(id);
    socket.emit("roomCreated", room);
    io.emit("viewRooms", rooms);
  });

  socket.on("joinRoom", (id, user) => {
    var exist = false;
    var i = 0;
    var room = {};

    while (i < rooms.length && !exist) {
      const element = rooms[i];
      if (id === element.id) {
        var player = {
          name: user.name,
          id: 2,
          life: 100,
          skin: user.skin,
        };
        exist = true;
        element.players.push(player);
        room = element;
      }
      i++;
    }

    // function startTimer(room, id) {
    //   room.timerId = setInterval(() => {
    //     if (room.timer > 0) {
    //       room.timer--;
    //       io.to(id).emit("timer", room.timer);
    //     } else {
    //       io.to(id).emit("timeUp");
    //       clearInterval(room.timerId);
    //       console.log("Time's up!");
    //       setTimeout(() => {
    //         room.timer = 10;
    //         io.to(id).emit("startTimer");
    //         startTimer(room, id);
    //       }, 3000);
    //     }
    //   }, 1000);
    // }

    if (exist) {
      socket.join(id);
      socket.emit("joiningGame", room);
      socket.to(id).emit("playerJoined", room);
      io.emit("viewRooms", rooms);
      // console.log("Emit Rooms 3");
      // startTimer(room, id);
    }
  });

  socket.on("getSkins", () => {
    comsManager
      .getSkins()
      .then((response) => {
        socket.emit("viewSkins", response);
      })
      .catch((error) => {
        console.error(error);
      });
  });

  socket.on("newSkin", (playerID, newSkin) => {
    comsManager
      .updateSkin(playerID, newSkin)
      .then((response) => {
        console.log(response);
        socket.emit("skinUpdated", response);
      })
      .catch((error) => {
        console.error(error);
      });
  });

  socket.on("exitRoom", (id) => {

    io.to(id).emit("disconnectRoom", id);
    const socketsInRoom = io.sockets.adapter.rooms.get(id);

    for (const socketId of socketsInRoom) {
      const socket = io.sockets.sockets.get(socketId);
      socket.leave(id);
    }

    const roomIndex = rooms.findIndex((room) => room.id === id);
    if (roomIndex !== -1) {
      rooms.splice(roomIndex, 1);
    }

    io.emit("viewRooms", rooms);
  });

  socket.on("addPlayer", (playerInfo) => {
    if (players.length === 0) {
      players.push(playerInfo);
    } else {
      var i = 0;
      while (i < players.length && !exist) {
        var exist = false;
        if (players[i].id === playerInfo.id) {
          players[i] = playerInfo;
          exist = true;
        }
        i++;
      }
      if (!exist) {
        players.push(playerInfo);
      }
    }

    console.log(players);
    io.emit("viewPlayers", players);
  });

  socket.on("disconnect", () => {
    console.log("Client desconectat");
  });


});

const PORT = process.env.PORT || 3817;

server.listen(PORT, () => {
  console.log(`Servidor escuchando en el puerto ${PORT}`);
});
