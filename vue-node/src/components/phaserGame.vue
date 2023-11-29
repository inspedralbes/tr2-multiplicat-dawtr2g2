<template>
    <div ref="gameContainer"></div>
</template>
  
<script>

export default {
    data() {
        return {
            cursors: null,
            knight: null,
        }
    },
    mounted() {
        this.initPhaser();
    },
    methods: {
        initPhaser() {
            const config = {
                scale: {
                    zoom: 3,
                },
                scene: {
                    preload() {
                        ///LOAD MAP IMAGES
                        this.load.image('pHouse_Furniture', 'tiles/player_house/TilesetElement.png');
                        this.load.image('pHouse_Walls', 'tiles/player_house/TilesetWallSimple.png');
                        this.load.image('pHouse_Floor', 'tiles/player_house/TilesetInteriorFloor.png');
                        this.load.tilemapTiledJSON('playerHouse', 'tiles/player_house/playerHouse.json');

                        ///LOAD PLAYER SPRITE
                        this.load.atlas('knight', 'characters/knight/knight.png', 'characters/knight/knight.json');

                    },
                    create() {

                        this.cursors = this.input.keyboard.createCursorKeys();

                        const map = this.make.tilemap({ key: 'playerHouse' });
                        const floorTileset = map.addTilesetImage('phFloor', 'pHouse_Floor');
                        const wallTileset = map.addTilesetImage('phWall', 'pHouse_Walls');
                        const furnitureTileset = map.addTilesetImage('phFurniture', 'pHouse_Furniture');


                        map.createLayer('Background', wallTileset);
                        map.createLayer('FloorGroup/Floor', floorTileset);
                        map.createLayer('FloorGroup/Carpet', furnitureTileset);
                        const wallsLayer = map.createLayer('Walls', wallTileset);
                        const furnitureLayer = map.createLayer('Furniture', furnitureTileset);


                        wallsLayer.setCollisionByProperty({ collides: true });
                        furnitureLayer.setCollisionByProperty({ collides: true });


                        ///DEBUG DE COLISIONES
                        /*
                        const debugGraphics = this.add.graphics().setAlpha(0.75);
                        wallsLayer.renderDebug(debugGraphics, {
                            tileColor: null,
                        });
                        furnitureLayer.renderDebug(debugGraphics, {
                            tileColor: null,
                        });
                        */

                        // Ajusta el tamaño del canvas al tamaño del mapa
                        const width = map.widthInPixels;
                        const height = map.heightInPixels;
                        this.game.scale.resize(width, height);

                        ///PLAYER SPRITE
                        this.knight = this.add.sprite(128, 128, 'knight', 'knight_walk_down_1.png');
                        this.anims.create({
                            key: 'knight_idle_down',
                            frames: [{ key: 'knight', frame: 'knight_walk_down_1.png' }]
                        });

                        this.anims.create({
                            key: 'knight_idle_up',
                            frames: [{ key: 'knight', frame: 'knight_walk_up_1.png' }]
                        });

                        this.anims.create({
                            key: 'knight_idle_left',
                            frames: [{ key: 'knight', frame: 'knight_walk_left_1.png' }]
                        });

                        this.anims.create({
                            key: 'knight_idle_right',
                            frames: [{ key: 'knight', frame: 'knight_walk_right_1.png' }]
                        });

                        this.anims.create({
                            key: 'knight_move_up',
                            frames: this.anims.generateFrameNames('knight', {
                                prefix: 'knight_walk_up_',
                                start: 1,
                                end: 4,
                                suffix: '.png'
                            }),
                            repeat: -1,
                            frameRate: 10,
                        });
                        this.anims.create({
                            key: 'knight_move_down',
                            frames: this.anims.generateFrameNames('knight', {
                                prefix: 'knight_walk_down_',
                                start: 1,
                                end: 4,
                                suffix: '.png'
                            }),
                            repeat: -1,
                            frameRate: 10,
                        });
                        this.anims.create({
                            key: 'knight_move_left',
                            frames: this.anims.generateFrameNames('knight', {
                                prefix: 'knight_walk_left_',
                                start: 1,
                                end: 4,
                                suffix: '.png'
                            }),
                            repeat: -1,
                            frameRate: 10,
                        });
                        this.anims.create({
                            key: 'knight_move_right',
                            frames: this.anims.generateFrameNames('knight', {
                                prefix: 'knight_walk_right_',
                                start: 1,
                                end: 4,
                                suffix: '.png'
                            }),
                            repeat: -1,
                            frameRate: 10,
                        });
                        // knight.anims.play('knight_idle_up');
                    },
                    update(t, dt) {
                        if (!this.cursors || !this.knight) {
                            return;
                        }

                        const speed = 100;
                        if (this.cursors.left.isDown) {
                            this.knight.setVelocity(-speed, 0);
                            this.knight.anims.play('knight_move_left', true);
                        } else if (this.cursors.right.isDown) {
                            this.knight.setVelocity(speed, 0);
                            this.knight.anims.play('knight_move_right', true);
                        } else if (this.cursors.up.isDown) {
                            this.knight.setVelocity(0, -speed);
                            this.knight.anims.play('knight_move_up', true);
                        } else if (this.cursors.down.isDown) {
                            this.knight.setVelocity(0, speed);
                            this.knight.anims.play('knight_move_down', true);
                        } else {
                            this.knight.setVelocity(0, 0);
                            this.knight.anims.stop();
                        }
                    }
                }
            };

            this.game.canvas.parentElement.style.margin = 'auto';
        }
    }
};
</script>
  