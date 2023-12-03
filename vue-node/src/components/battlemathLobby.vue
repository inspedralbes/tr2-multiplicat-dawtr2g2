<template>
  <div class="game" ref="gameContainer"></div>
</template>

<script scoped>
import { defineComponent } from 'vue';
import Phaser from 'phaser';
import { useRouter } from 'vue-router';

export default defineComponent({
  name: 'battlemathGame',
  data() {
    return {
      cursorsLoaded: false,
      cursors: null,
      knight: null,
      lobby_layers: {
        furn: null,
        furnTop: null,
        buildBottom2: null,
        buildBottom: null,
        build: null,
        buildTop: null,
        floor: null,
      },
      cameraConfig: {
        zoom: this.rescaleCamera(),
        offset: 4.8
      }
    }
  },
  mounted() {
    this.initializeGame();
  },
  methods: {
    initializeGame() {
      const self = this;

      const config = {
        type: Phaser.AUTO,
        width: (window.innerWidth - self.cameraConfig.offset) / self.cameraConfig.zoom,
        height: (window.innerHeight - self.cameraConfig.offset) / self.cameraConfig.zoom,
        zoom: self.cameraConfig.zoom,
        scale: {
          autoCenter: Phaser.Scale.RESIZE,
        },
        parent: this.$refs.gameContainer,
        physics: {
          default: 'arcade',
          arcade: {
            gravity: { y: 0 },
            debug: false
          }
        },
        scene: {
          preload: function () {
            self.preloadPlayerHouse(this);
            this.load.atlas('knight', 'characters/knight/knight.png', 'characters/knight/knight.json');
            self.rescaleCamera(this);
          },
          create: function () {
            self.createLobby(this);
            self.createPlayer(this);
            self.cursors = this.input.keyboard.createCursorKeys();

            ///Create player
            self.knight = this.physics.add.sprite(888, 390, 'knight', 'knight_walk_down_1.png');
            self.knight.anims.play('knight_idle_down');
            self.knight.body.setSize(self.knight.width * 0.5, self.knight.height * 0.7);
            self.knight.body.setOffset(self.knight.width * 0.25, self.knight.height * 0.3);
            this.physics.world.enable(self.knight);
            this.physics.add.collider(self.knight, self.lobby_layers.buildBottom2);
            this.physics.add.collider(self.knight, self.lobby_layers.buildBottom);
            this.physics.add.collider(self.knight, self.lobby_layers.build);
            this.physics.add.collider(self.knight, self.lobby_layers.buildTop);
            this.physics.add.collider(self.knight, self.lobby_layers.furnTop);
            this.physics.add.collider(self.knight, self.lobby_layers.floor);
            this.cameras.main.startFollow(self.knight, true);
            // this.cameras.main.centerOn(this.knight.x, this.knight.y);
            self.createLobby_foreground(this);
          },
          update: function () {
            const speed = 50;
            const runSpeedMultiplier = 1.5;

            let currentSpeed = speed;

            // if (self.tecla(this, 'SHIFT')) {
            //   currentSpeed *= runSpeedMultiplier;
            // } else {
            //   currentSpeed = speed;
            // }

            if ((self.cursors.left?.isDown || self.tecla(this, 'A'))) {
              self.knight.setVelocity(-currentSpeed, 0);
              self.knight.anims.play('knight_move_left', true);
            } else if (self.cursors.right?.isDown || self.tecla(this, 'D')) {
              self.knight.setVelocity(currentSpeed, 0);
              self.knight.anims.play('knight_move_right', true);
            } else if (self.cursors.up?.isDown || self.tecla(this, 'W')) {
              self.knight.setVelocity(0, -currentSpeed);
              self.knight.anims.play('knight_move_up', true);
            } else if (self.cursors.down?.isDown || self.tecla(this, 'S')) {
              self.knight.setVelocity(0, currentSpeed);
              self.knight.anims.play('knight_move_down', true);
            } else {
              const parts = self.knight.anims.currentAnim.key.split('_');
              parts[1] = 'idle';
              self.knight.anims.play(parts.join('_'));
              self.knight.setVelocity(0, 0);
            }
            // console.log(this.knight.x, this.knight.y);
          }
        },
        render: {
          antialias: true,
          pixelArt: true,
          roundPixels: true,
          fps: {
            min: 30,
            target: 60,
            forceSetTimeOut: true
          },
          // ...
        }
      };

      this.game = new Phaser.Game(config);

    },
    rescaleCamera() {
      const screenWidth = window.innerWidth;
      const screenHeight = window.innerHeight;

      if (screenWidth < 768 && screenHeight < 1024) {
        return 1.5;
      } else if (screenWidth < 1024 && screenHeight < 1366) {
        return 2;
      } else {
        return 3;
      }
    },
    preloadPlayerHouse(scene) {
      scene.load.image('TilesetLobby', 'tiles/lobby_map/TilesetLobby.png');
      scene.load.image('TilesetElement', 'tiles/TilesetElement.png')
      scene.load.tilemapTiledJSON('lobby', 'tiles/lobby_map/lobbyMap.json');
    },
    createLobby(scene) {
      const map = scene.make.tilemap({ key: 'lobby' });
      const tileset = map.addTilesetImage('TilesetLobby', 'TilesetLobby');
      const furniture = map.addTilesetImage('TilesetElement', 'TilesetElement');

      map.createLayer('bg', tileset);
      this.lobby_layers.floor = map.createLayer('floor', tileset);
      this.lobby_layers.buildBottom2 = map.createLayer('buildings-bottom_2', tileset);
      this.lobby_layers.buildBottom = map.createLayer('buildings-bottom', tileset);
      this.lobby_layers.build = map.createLayer('buildings', tileset);
      this.lobby_layers.buildTop = map.createLayer('buildings-top', tileset);
      this.lobby_layers.furnTop = map.createLayer('furniture-top', tileset);

      this.lobby_layers.buildBottom2.setCollisionByProperty({ collides: true });
      this.lobby_layers.buildBottom.setCollisionByProperty({ collides: true });
      this.lobby_layers.build.setCollisionByProperty({ collides: true });
      this.lobby_layers.buildTop.setCollisionByProperty({ collides: true });
      this.lobby_layers.furnTop.setCollisionByProperty({ collides: true });
      this.lobby_layers.floor.setCollisionByProperty({ collides: true });


      // this.debugCollision(scene);
    },
    createLobby_foreground(scene) {
      const map = scene.make.tilemap({ key: 'lobby' });
      const tileset = map.addTilesetImage('TilesetLobby', 'TilesetLobby');

      map.createLayer('foreground', tileset);

    },
    debugCollision(scene) {
      const debugGraphics = scene.add.graphics().setAlpha(0.75);
      this.lobby_layers.buildBottom2.renderDebug(debugGraphics, {
        tileColor: null,
        collidingTileColor: new Phaser.Display.Color(120, 134, 48, 255),
      });
      this.lobby_layers.buildBottom.renderDebug(debugGraphics, {
        tileColor: null,
        collidingTileColor: new Phaser.Display.Color(140, 134, 48, 255),
      });
      this.lobby_layers.build.renderDebug(debugGraphics, {
        tileColor: null,
        collidingTileColor: new Phaser.Display.Color(160, 134, 48, 255),
      });
      this.lobby_layers.buildTop.renderDebug(debugGraphics, {
        tileColor: null,
        collidingTileColor: new Phaser.Display.Color(180, 134, 48, 255),
      });
      this.lobby_layers.furnTop.renderDebug(debugGraphics, {
        tileColor: null,
        collidingTileColor: new Phaser.Display.Color(200, 134, 48, 255),
      });
      this.lobby_layers.floor.renderDebug(debugGraphics, {
        tileColor: null,
        collidingTileColor: new Phaser.Display.Color(220, 134, 48, 255),
      });
    },
    async loadCursor(scene) {
      await new Promise(resolve => setTimeout(resolve, 1000));
      this.cursors = scene.input.keyboard.createCursorKeys();
      this.cursorsLoaded = true;
    },
    createPlayer(scene) {
      const frameRate = 7;

      scene.anims.create({
        key: 'knight_idle_down',
        frames: [{ key: 'knight', frame: 'knight_walk_down_1.png' }]
      });

      scene.anims.create({
        key: 'knight_idle_up',
        frames: [{ key: 'knight', frame: 'knight_walk_up_1.png' }]
      });

      scene.anims.create({
        key: 'knight_idle_left',
        frames: [{ key: 'knight', frame: 'knight_walk_left_1.png' }]
      });

      scene.anims.create({
        key: 'knight_idle_right',
        frames: [{ key: 'knight', frame: 'knight_walk_right_1.png' }]
      });

      scene.anims.create({
        key: 'knight_move_up',
        frames: scene.anims.generateFrameNames('knight', {
          prefix: 'knight_walk_up_',
          start: 1,
          end: 4,
          suffix: '.png'
        }),
        repeat: -1,
        frameRate: frameRate,
      });
      scene.anims.create({
        key: 'knight_move_down',
        frames: scene.anims.generateFrameNames('knight', {
          prefix: 'knight_walk_down_',
          start: 1,
          end: 4,
          suffix: '.png'
        }),
        repeat: -1,
        frameRate: frameRate,
      });
      scene.anims.create({
        key: 'knight_move_left',
        frames: scene.anims.generateFrameNames('knight', {
          prefix: 'knight_walk_left_',
          start: 1,
          end: 4,
          suffix: '.png'
        }),
        repeat: -1,
        frameRate: frameRate,
      });
      scene.anims.create({
        key: 'knight_move_right',
        frames: scene.anims.generateFrameNames('knight', {
          prefix: 'knight_walk_right_',
          start: 1,
          end: 4,
          suffix: '.png'
        }),
        repeat: -1,
        frameRate: frameRate,
      });

    },
    tecla(scene, key) {
      return scene.input.keyboard.addKey(Phaser.Input.Keyboard.KeyCodes[key]).isDown;
    }
  }
});
</script>