<template>
    <div ref="gameContainer"></div>
</template>
  
<script scoped>
import { defineComponent } from 'vue';
import Phaser from 'phaser';

export default defineComponent({
    name: 'battlemathLobby',
    data() {
        return {
            cursorsLoaded: false,
            cursors: null,
            knight: null,
            layers: {
                elements: null,

            },
            cameraConfig: {
                zoom: 1,
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
                        self.preloadLobby(this);
                        this.load.atlas('knight', 'characters/knight/knight.png', 'characters/knight/knight.json');
                    },
                    create: function () {
                        self.createLobby(this);
                        self.createPlayer(this);
                        self.cursors = this.input.keyboard.createCursorKeys();

                        ///Funciones-variables que en en metodos no funcionan pero aqui si
                        this.knight = this.physics.add.sprite(0, 0, 'knight', 'knight_walk_right_1.png');
                        this.knight.anims.play('knight_idle_right');
                        this.knight.body.setSize(this.knight.width * 0.5, this.knight.height * 0.7);
                        this.physics.world.enable(this.knight);
                        this.physics.add.collider(this.knight, self.layers.walls);
                        this.physics.add.collider(this.knight, self.layers.furniture);
                        // this.cameras.main.startFollow(this.knight, true);
                        this.cameras.main.centerOn(this.knight.x, this.knight.y);
                    },
                    update: function () {
                        if (!this.knight) {
                            this.knight = this.physics.add.sprite(0, 0, 'knight', 'knight_walk_right_1.png');
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

                                this.knight.scaleX = 1;
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
                }
            };

            this.game = new Phaser.Game(config);
        },
        preloadLobby(scene) {
            scene.load.image('lobby', 'tiles/lobby_map/TilesetLobby.png');
            scene.load.image('pHouse_Furniture', 'tiles/TilesetElement.png');
            scene.load.tilemapTiledJSON('lobbyMap', 'tiles/lobby_map/lobbyMap.json');
        },
        createLobby(scene) {
            const map = scene.make.tilemap({ key: 'lobby' });
            const lobbyTileset = map.addTilesetImage('TilesetLobby', 'lobby');
            const furnitureTileset = map.addTilesetImage('TilesetElement', 'pHouse_Furniture');
            // const floorTileset = map.addTilesetImage('phFloor', 'pHouse_Floor');
            // const wallTileset = map.addTilesetImage('phWall', 'pHouse_Walls');

            map.createLayer('background', lobbyTileset);
            map.createLayer('furniture', furnitureTileset);

            // this.layers.walls = map.createLayer('Walls', wallTileset);
            // this.layers.furniture = map.createLayer('Furniture', furnitureTileset);

            // this.layers.walls.setCollisionByProperty({ collides: true });
            // this.layers.furniture.setCollisionByProperty({ collides: true });

            // this.debugCollision(scene);
        },
        debugCollision(scene) {
            const debugGraphics = scene.add.graphics().setAlpha(0.75);
            // this.layers.walls.renderDebug(debugGraphics, {
            //     tileColor: null,
            //     collidingTileColor: new Phaser.Display.Color(243, 134, 48, 255),
            // });
            // this.layers.furniture.renderDebug(debugGraphics, {
            //     tileColor: null,
            //     collidingTileColor: new Phaser.Display.Color(100, 134, 48, 255),
            // });
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