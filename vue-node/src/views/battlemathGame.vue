<template>
    <div class="game-container">
        <div class="gameCanvas" ref="gameContainer"></div>
    </div>
</template>
  
<script scoped>
import { defineComponent } from 'vue';
import Phaser from 'phaser';

export default defineComponent({
    name: 'battlemathGame',

    data() {
        return {
            knight: null,
            speed: 30,
            pHouse_layers: {
                walls: null,
                furniture: null,
            },
            lobby_layers: {
                furn: null,
                furnTop: null,
                buildBottom2: null,
                buildBottom: null,
                build: null,
                buildTop: null,
                floor: null,
                bg: null,
                fg: null,
            },
            objects: {
                doors: null,
            },
            cameraConfig: {
                zoom: this.rescaleCamera(),
                offset: 4.8
            },
            firstTime: true,
        }
    },
    mounted() {
        this.initializeGame();
    },
    methods: {
        initializeGame() {
            const self = this;

            const playerHouseConfig = {
                key: 'playerHouse',
                preload: function () {
                    self.knight = null;
                    self.preloadPlayerHouse(this);
                    this.load.atlas('knight', 'characters/knight/knight.png', 'characters/knight/knight.json');
                    this.load.image('door', 'public/objects/door.png')
                },
                create: function () {
                    self.createPlayerHouse(this);
                    self.createParticleHouse(this, 664, 723);
                    self.createParticleHouse(this, 856, 723);
                    self.createParticleHouse(this, 920, 723);

                    self.createPlayerAnims(this);
                    ///Create player
                    if (self.firstTime) {
                        self.firstTime = false;
                        self.playerCreate(this, 780, 774);
                    } else {
                        self.playerCreate(this, 793, 856);
                    }
                    self.addHouseCollisions(this);

                    this.cameras.main.centerOn(780, 774);
                    self.createPlayerHouse_foreground(this);
                    self.createParticleHouse(this, 664, 851);
                    self.createParticleHouse(this, 856, 851);
                    self.createParticleHouse(this, 920, 851);

                },
                update: function () {
                    self.playerMovement(this);

                    // this.physics.add.overlap(self.knight, self.objects.doors, () => {
                    //     if (self.tecla(this, 'E')) {
                    //         self.cambiarEscena(this, 'lobby', 793, 856);
                    //     }
                    // });
                }

            };

            const lobbyConfig = {
                key: 'lobby',
                preload: function () {
                    self.knight = null;
                    self.preloadLobby(this);
                    this.load.atlas('knight', 'characters/knight/knight.png', 'characters/knight/knight.json');
                    this.load.spritesheet('Leaf', 'particles/Leaf.png', { frameWidth: 12, frameHeight: 7 });
                },
                create: function () {
                    self.createLobby(this);
                    self.createPlayerAnims(this);

                    ///Create player
                    if (!self.knight) {
                        self.playerCreate(this, 888, 390);
                    } else {
                        this.knight.setVelocity(0, 0);
                    }
                    self.addLobbyCollisions(this);
                    this.cameras.main.startFollow(self.knight, true);
                    self.createLobby_foreground(this);
                    this.physics.add.collider(self.knight, self.lobby_layers.fg);


                },
                update: function () {
                    self.playerMovement(this);
                }
            }

            const config = {
                type: Phaser.AUTO,
                width: (window.innerWidth - self.cameraConfig.offset) / self.cameraConfig.zoom,
                height: (window.innerHeight - self.cameraConfig.offset) / self.cameraConfig.zoom,
                zoom: self.cameraConfig.zoom,
                scale: {
                    autoCenter: Phaser.Scale.RESIZE,
                    mode: Phaser.Scale.NONE,
                },
                parent: this.$refs.gameContainer,
                physics: {
                    default: 'arcade',
                    arcade: {
                        gravity: { y: 0 },
                        debug: false
                    }
                },
            }


            this.game = new Phaser.Game(config);
            this.game.loop.targetFps = 30;
            this.game.scene.add('playerHouse', playerHouseConfig, false);
            this.game.scene.add('lobby', lobbyConfig, false);

            const sceneStart = 2;

            if (sceneStart === 1) {
                this.game.scene.start('playerHouse');
            } else {
                this.game.scene.start('lobby');
            }

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
            scene.load.image('pHouse_Furniture', 'tiles/TilesetElement.png');
            scene.load.image('pHouse_Walls', 'tiles/player_house/TilesetWallSimple.png');
            scene.load.image('pHouse_Floor', 'tiles/player_house/TilesetInteriorFloor.png');
            scene.load.image('fire', 'particles/Fire.png')
            scene.load.tilemapTiledJSON('playerHouse', 'tiles/player_house/playerHouse.json');
        },
        createPlayerHouse(scene) {
            const map = scene.make.tilemap({ key: 'playerHouse' });
            const floorTileset = map.addTilesetImage('phFloor', 'pHouse_Floor');
            const wallTileset = map.addTilesetImage('phWall', 'pHouse_Walls');
            const furnitureTileset = map.addTilesetImage('phFurniture', 'pHouse_Furniture');

            map.createLayer('Background', wallTileset);
            map.createLayer('FloorGroup/Floor', floorTileset);
            map.createLayer('FloorGroup/Carpet', furnitureTileset);
            this.pHouse_layers.walls = map.createLayer('Walls', wallTileset);
            this.pHouse_layers.furniture = map.createLayer('Furniture', furnitureTileset);

            this.pHouse_layers.walls.setCollisionByProperty({ collides: true });
            this.pHouse_layers.furniture.setCollisionByProperty({ collides: true });

            this.objects.doors = scene.physics.add.staticGroup();
            const doorLayer = map.getObjectLayer('Doors');
            doorLayer.objects.forEach(doorsObj => {
                this.objects.doors.create(doorsObj.x + doorsObj.width / 2, doorsObj.y - doorsObj.height / 1.99, 'door');
            });
            // this.debugCollision(scene);
        },
        createPlayerHouse_foreground(scene) {
            const map = scene.make.tilemap({ key: 'playerHouse' });
            const furnitureTileset = map.addTilesetImage('phFurniture', 'pHouse_Furniture');
            map.createLayer('foreground', furnitureTileset);
        },
        addHouseCollisions(scene) {
            scene.physics.add.collider(this.knight, this.pHouse_layers.walls);
            scene.physics.add.collider(this.knight, this.pHouse_layers.furniture);
            scene.physics.add.collider(this.knight, this.objects.doors);

            scene.physics.add.overlap(this.knight, this.objects.doors, () => {
                if (this.tecla(scene, 'E')) {
                    this.cambiarEscena(scene, 'lobby', 793, 856);
                }
            });
        },
        preloadLobby(scene) {
            scene.load.image('TilesetLobby', 'tiles/lobby_map/TilesetLobby.png');
            scene.load.image('TilesetElement', 'tiles/TilesetElement.png');
            scene.load.image('dojo_door_left', 'public/objects/dojo_door_left.png');
            scene.load.image('dojo_door_right', 'public/objects/dojo_door_right.png');
            scene.load.image('phouse_door', 'public/objects/phouse_door.png');
            scene.load.spritesheet('leaves', 'particles/Leaf.png', { frameWidth: 16, frameHeight: 16 });
            scene.load.tilemapTiledJSON('lobby', 'tiles/lobby_map/lobbyMap.json');
        },
        createLobby(scene) {
            const map = scene.make.tilemap({ key: 'lobby' });
            const tileset = map.addTilesetImage('TilesetLobby', 'TilesetLobby');
            const furniture = map.addTilesetImage('TilesetElement', 'TilesetElement');

            this.lobby_layers.bg = map.createLayer('bg', tileset);
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
            this.lobby_layers.bg.setCollisionByProperty({ collides: true });


            ///1
            ///241

            this.objects.doors = scene.physics.add.staticGroup();
            const doorLayer = map.getObjectLayer('Doors');
            let door = null;
            doorLayer.objects.forEach(doorsObj => {
                if (doorsObj.name === 'player_door') {
                    door = this.objects.doors.create(doorsObj.x + doorsObj.width / 2, doorsObj.y - doorsObj.height / 1.99, 'phouse_door');
                } else if (doorsObj.name === 'dojo_door_right') {
                    door = this.objects.doors.create(doorsObj.x + doorsObj.width / 2, doorsObj.y - doorsObj.height / 1.99, 'dojo_door_right');
                } else {
                    door = this.objects.doors.create(doorsObj.x + doorsObj.width / 2, doorsObj.y - doorsObj.height / 1.99, 'dojo_door_left');
                }
                door.name = doorsObj.name;
            });

        },
        createLobby_foreground(scene) {
            const map = scene.make.tilemap({ key: 'lobby' });
            const tileset = map.addTilesetImage('TilesetLobby', 'TilesetLobby');

            this.lobby_layers.fg = map.createLayer('foreground', tileset);
            this.lobby_layers.fg.setCollisionByProperty({ collides: true });

        },
        addLobbyCollisions(scene) {
            scene.physics.add.collider(this.knight, this.lobby_layers.bg);
            scene.physics.add.collider(this.knight, this.lobby_layers.floor);
            scene.physics.add.collider(this.knight, this.lobby_layers.buildBottom2);
            scene.physics.add.collider(this.knight, this.lobby_layers.buildBottom);
            scene.physics.add.collider(this.knight, this.lobby_layers.build);
            scene.physics.add.collider(this.knight, this.lobby_layers.buildTop);
            scene.physics.add.collider(this.knight, this.lobby_layers.furnTop);


            scene.physics.add.overlap(this.knight, this.objects.doors, (knight, door) => {
                if (door.name === 'player_door') {
                    this.cambiarEscena(scene, 'playerHouse', 793, 856);
                } else {
                    console.log('aun nada')
                }
            });
        },
        playerCreate(scene, x, y) {
            this.knight = scene.physics.add.sprite(x, y, 'knight', 'knight_walk_right_1.png');

            this.knight.anims.play('knight_idle_right');
            this.knight.body.setSize(this.knight.width * 0.7, this.knight.height * 0.2);
            this.knight.body.setOffset(this.knight.width * .15, this.knight.height * .8);
            this.knight.body.bounce.set(0);
            scene.physics.world.enable(this.knight);

        },
        debugCollision(scene) {
            const debugGraphics = scene.add.graphics().setAlpha(0.75);
            this.pHouse_layers.walls.renderDebug(debugGraphics, {
                tileColor: null,
                collidingTileColor: new Phaser.Display.Color(243, 134, 48, 255),
            });
            this.pHouse_layers.furniture.renderDebug(debugGraphics, {
                tileColor: null,
                collidingTileColor: new Phaser.Display.Color(100, 134, 48, 255),
            });
            this.objects.doors.children.iterate(door => {
                debugGraphics.fillRect(door.x - door.width / 2, door.y - door.height / 2, door.width, door.height);
            });
        },
        playerMovement(scene) {
            const speed = 30;
            const runSpeedMultiplier = 1.5;

            let currentSpeed = speed;

            // console.log(this.knight.x, this.knight.y)
            if (this.tecla(scene, 'M')) {
                if (scene.scene.isActive('lobby')) {
                    this.cambiarEscena(scene, 'playerHouse');
                } else {
                    this.cambiarEscena(scene, 'lobby');
                }
            }

            if (this.tecla(scene, 'LEFT') || this.tecla(scene, 'A')) {
                this.knight.setVelocity(-currentSpeed, 0);
                this.knight.anims.play('knight_move_left', true);
            } else if (this.tecla(scene, 'RIGHT') || this.tecla(scene, 'D')) {
                this.knight.setVelocity(currentSpeed, 0);
                this.knight.anims.play('knight_move_right', true);
            } else if (this.tecla(scene, 'UP') || this.tecla(scene, 'W')) {
                this.knight.setVelocity(0, -currentSpeed);
                this.knight.anims.play('knight_move_up', true);
            } else if (this.tecla(scene, 'DOWN') || this.tecla(scene, 'S')) {
                this.knight.setVelocity(0, currentSpeed);
                this.knight.anims.play('knight_move_down', true);
            } else {
                const parts = this.knight.anims.currentAnim.key.split('_');
                parts[1] = 'idle';
                this.knight.anims.play(parts.join('_'));
                this.knight.setVelocity(0, 0);
            }

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
        },
        cambiarEscena(scene, escena, x, y) {
            this.knight.setPosition(x, y);
            if (escena === 'lobby') {
                scene.scene.stop('playerHouse');
                scene.scene.start('lobby');
            } else if (escena === 'playerHouse') {
                scene.scene.stop('lobby');
                scene.scene.start('playerHouse');
            }
        },
        createParticleHouse(scene, x, y) {
            const emitter = scene.add.particles(x, y, 'fire',
                {
                    color: [0xfacc22, 0xf89800, 0xf83600, 0x9f0404],
                    colorEase: 'quad.out',
                    lifespan: 1600,
                    angle: { min: -100, max: -80 },
                    scale: { start: 0.70, end: 0, ease: 'sine.out' },
                    speed: 10,
                    advance: 2000,
                });

            return emitter;
        },
    }
});
</script>
  
<style scoped>
.game-container {
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
    margin: auto;
}

.game-container {
    /* Asegúrate de que el canvas ocupe todo el contenedor */
    width: 100%;
    height: 100%;
    background-color: #141B1B !important;
}
</style>