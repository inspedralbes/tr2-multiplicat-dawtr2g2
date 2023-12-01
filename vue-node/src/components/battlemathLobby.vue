<template>
  <!-- <div ref="gameContainer"></div> -->
</template>

<script scoped>
import { defineComponent } from 'vue';
import Phaser from 'phaser';

export default defineComponent({
  name: 'battlemathGame',
  data() {
    return {
      cursorsLoaded: false,
      cursors: null,
      knight: null,
      layers: {
        furn: null,
        furnTop: null,
        buildBottom2: null,
        buildBottom: null,
        build: null,
        buildTop: null,
        floor: null,
      },
      cameraConfig: {
        zoom: 3,
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
          autoCenter: Phaser.Scale.CENTER_BOTH
        },
        // parent: this.$refs.gameContainer,
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
          },
          create: function () {
            this.game.canvas.focus();
            self.createPlayerHouse(this);
            self.createPlayer(this);
            self.cursors = this.input.keyboard.createCursorKeys();

            ///Funciones-variables que en en metodos no funcionan pero aqui si
            this.knight = this.physics.add.sprite(888, 390, 'knight', 'knight_walk_down_1.png');
            this.knight.anims.play('knight_idle_down');
            this.knight.body.setSize(this.knight.width * 0.5, this.knight.height * 0.7);
            this.physics.world.enable(this.knight);
            this.physics.add.collider(this.knight, self.layers.walls);
            this.physics.add.collider(this.knight, self.layers.furniture);
            this.cameras.main.startFollow(this.knight, true);
            this.cameras.main.scrollX = Math.round(this.cameras.main.scrollX);
            this.cameras.main.scrollY = Math.round(this.cameras.main.scrollY);
            // this.cameras.main.centerOn(this.knight.x, this.knight.y);
          },
          update: function () {
            if (!this.knight) {
              this.knight = this.physics.add.sprite(888, 390, 'knight', 'knight_walk_down_1.png');
            }

            if (!this.cursorsLoaded) {
              this.cursors = this.input.keyboard.createCursorKeys();
              this.cursorsLoaded = true;
            } else {
              const speed = 100;
              if (this.cursors.left?.isDown) {
                this.knight.setVelocity(-speed, 0);
                this.knight.anims.play('knight_move_left', true);
              } else if (this.cursors.right?.isDown) {
                this.knight.setVelocity(speed, 0);
                this.knight.anims.play('knight_move_right', true);
              } else if (this.cursors.up?.isDown) {
                this.knight.setVelocity(0, -speed);
                this.knight.anims.play('knight_move_up', true);
              } else if (this.cursors.down?.isDown) {
                this.knight.setVelocity(0, speed);
                this.knight.anims.play('knight_move_down', true);
              } else {
                const parts = this.knight.anims.currentAnim.key.split('_');
                parts[1] = 'idle';
                this.knight.anims.play(parts.join('_'));
                this.knight.setVelocity(0, 0);
              }
            }
            // console.log(this.knight.x, this.knight.y);
          }
        },
        render: {
          antialias: true, // Activar anti-aliasing para suavizar bordes
          pixelArt: true, // Si estás usando gráficos pixelados, configúralo en 'true'
          roundPixels: true, // Redondear píxeles para evitar el desenfoque
          fps: {
            min: 30, // Framerate mínimo
            target: 60, // Objetivo de framerate
            forceSetTimeOut: true // Forzar el uso de setTimeout en lugar de requestAnimationFrame
          },
          // ...
        }
      };

      this.game = new Phaser.Game(config);

    },
    preloadPlayerHouse(scene) {
      scene.load.image('TilesetLobby', 'tiles/lobby_map/TilesetLobby.png');
      scene.load.image('TilesetElement', 'tiles/TilesetElement.png')
      scene.load.tilemapTiledJSON('lobby', 'tiles/lobby_map/lobbyMap.json');
    },
    createPlayerHouse(scene) {
      const map = scene.make.tilemap({ key: 'lobby' });
      const tileset = map.addTilesetImage('TilesetLobby', 'TilesetLobby');
      const furniture = map.addTilesetImage('TilesetElement', 'TilesetElement');

      map.createLayer('bg', tileset);
      this.layers.floor = map.createLayer('floor', tileset);
      this.layers.furn = map.createLayer('furniture', furniture);
      this.layers.buildBottom2 = map.createLayer('buildings-bottom_2', tileset);
      this.layers.buildBottom = map.createLayer('buildings-bottom', tileset);
      this.layers.build = map.createLayer('buildings', tileset);
      this.layers.buildTop = map.createLayer('buildings-top', tileset);
      this.layers.furnTop = map.createLayer('furniture-top', tileset);

      this.layers.furn.setCollisionByProperty({ collides: true });
      this.layers.buildBottom2.setCollisionByProperty({ collides: true });
      this.layers.buildBottom.setCollisionByProperty({ collides: true });
      this.layers.build.setCollisionByProperty({ collides: true });
      this.layers.buildTop.setCollisionByProperty({ collides: true });
      this.layers.furnTop.setCollisionByProperty({ collides: true });
      this.layers.floor.setCollisionByProperty({ collides: true });


      this.debugCollision(scene);
    },
    debugCollision(scene) {
      const debugGraphics = scene.add.graphics().setAlpha(0.75);
      this.layers.furn.renderDebug(debugGraphics, {
        tileColor: null,
        collidingTileColor: new Phaser.Display.Color(100, 134, 48, 255),
      });
      this.layers.buildBottom2.renderDebug(debugGraphics, {
        tileColor: null,
        collidingTileColor: new Phaser.Display.Color(120, 134, 48, 255),
      });
      this.layers.buildBottom.renderDebug(debugGraphics, {
        tileColor: null,
        collidingTileColor: new Phaser.Display.Color(140, 134, 48, 255),
      });
      this.layers.build.renderDebug(debugGraphics, {
        tileColor: null,
        collidingTileColor: new Phaser.Display.Color(160, 134, 48, 255),
      });
      this.layers.buildTop.renderDebug(debugGraphics, {
        tileColor: null,
        collidingTileColor: new Phaser.Display.Color(180, 134, 48, 255),
      });
      this.layers.furnTop.renderDebug(debugGraphics, {
        tileColor: null,
        collidingTileColor: new Phaser.Display.Color(200, 134, 48, 255),
      });
      this.layers.floor.renderDebug(debugGraphics, {
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
  }
});
</script>