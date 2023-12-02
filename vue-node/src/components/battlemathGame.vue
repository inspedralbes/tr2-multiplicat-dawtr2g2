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
      layers: {
        walls: null,
        furniture: null,
      },
      objects: {
        doors: null,
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
      const router = useRouter();

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
            this.load.image('door', 'public/objects/door.png')
            self.rescaleCamera(this);
          },
          create: function () {
            self.createPlayerHouse(this);
            self.createPlayerAnims(this);
            self.cursors = this.input.keyboard.createCursorKeys();

            ///Create player
            self.knight = this.physics.add.sprite(780, 774, 'knight', 'knight_walk_right_1.png');
            self.knight.anims.play('knight_idle_right');
            self.knight.body.setSize(self.knight.width * 0.5, self.knight.height * 0.7);
            self.knight.body.setOffset(self.knight.width * 0.25, self.knight.height * 0.3);
            this.physics.world.enable(self.knight);
            this.physics.add.collider(self.knight, self.layers.walls);
            this.physics.add.collider(self.knight, self.layers.furniture);
            this.physics.add.collider(self.knight, self.objects.doors);
            // this.cameras.main.startFollow(this.knight, true);
            this.cameras.main.centerOn(self.knight.x, self.knight.y);
            self.createPlayerHouse_foreground(this);
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
              console.log('left');
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


            this.physics.add.overlap(self.knight, self.objects.doors, () => {
              if (self.tecla(this, 'E')) {
                this.scene.stop();
                router.push({ name: 'lobby' });
              }
            });
          }
        }
      };

      this.game = new Phaser.Game(config);
    },
    rescaleCamera() {
      const screenWidth = window.innerWidth;
      const screenHeight = window.innerHeight;

      if (screenWidth < 768 && screenHeight < 1024) {
        return 2;
      } else if (screenWidth < 1024 && screenHeight < 1366) {
        return 3;
      } else {
        return 4;
      }
    },
    preloadPlayerHouse(scene) {
      scene.load.image('pHouse_Furniture', 'tiles/TilesetElement.png');
      scene.load.image('pHouse_Walls', 'tiles/player_house/TilesetWallSimple.png');
      scene.load.image('pHouse_Floor', 'tiles/player_house/TilesetInteriorFloor.png');
      scene.load.tilemapTiledJSON('playerHouse', 'tiles/player_house/playerHouse.json');
    },
    createPlayerHouse(scene) {
      const map = scene.make.tilemap({ key: 'playerHouse' });
      const floorTileset = map.addTilesetImage('phFloor', 'pHouse_Floor');
      const wallTileset = map.addTilesetImage('phWall', 'pHouse_Walls');
      const furnitureTileset = map.addTilesetImage('phFurniture', 'pHouse_Furniture');
      const doorTileset = map.addTilesetImage('phDoor', 'pHouse_Furniture');

      map.createLayer('Background', wallTileset);
      map.createLayer('FloorGroup/Floor', floorTileset);
      map.createLayer('FloorGroup/Carpet', furnitureTileset);
      this.layers.walls = map.createLayer('Walls', wallTileset);
      this.layers.furniture = map.createLayer('Furniture', furnitureTileset);

      this.layers.walls.setCollisionByProperty({ collides: true });
      this.layers.furniture.setCollisionByProperty({ collides: true });

      this.objects.doors = scene.physics.add.staticGroup();
      const doorLayer = map.getObjectLayer('Doors');
      doorLayer.objects.forEach(doorsObj => {
        this.objects.doors.create(doorsObj.x + doorsObj.width / 2, doorsObj.y - doorsObj.height / 3.5, 'door');
      });


      // this.debugCollision(scene);
    },
    createPlayerHouse_foreground(scene) {
      const map = scene.make.tilemap({ key: 'playerHouse' });
      const furnitureTileset = map.addTilesetImage('phFurniture', 'pHouse_Furniture');
      map.createLayer('foreground', furnitureTileset);
    },
    debugCollision(scene) {
      const debugGraphics = scene.add.graphics().setAlpha(0.75);
      this.layers.walls.renderDebug(debugGraphics, {
        tileColor: null,
        collidingTileColor: new Phaser.Display.Color(243, 134, 48, 255),
      });
      this.layers.furniture.renderDebug(debugGraphics, {
        tileColor: null,
        collidingTileColor: new Phaser.Display.Color(100, 134, 48, 255),
      });
      this.objects.doors.children.iterate(door => {
        debugGraphics.fillRect(door.x - door.width / 2, door.y - door.height / 2, door.width, door.height);
      });
    },
    createPlayerAnims(scene) {
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

<style scoped>
.game {
  width: 100vw;
  height: 100vh;
}
</style>