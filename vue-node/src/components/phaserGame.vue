<template>
    <div ref="gameContainer"></div>
</template>
  
<script>
import Phaser from 'phaser';

export default {
    mounted() {
        this.initPhaser();
    },
    methods: {
        initPhaser() {
            const config = {
                type: Phaser.AUTO,
                scale: {
                    mode: Phaser.Scale.FIT,
                    width: '100%',
                    height: '100%'
                },
                scene: {
                    preload() {
                        this.load.image('pHouse_Furniture', 'tiles/player_house/TilesetElement.png');
                        this.load.image('pHouse_Walls', 'tiles/player_house/TilesetWallSimple.png');
                        this.load.image('pHouse_Floor', 'tiles/player_house/TilesetInteriorFloor.png');
                        this.load.tilemapTiledJSON('playerHouse', 'tiles/player_house/playerHouse.json');
                    },
                    create() {
                        const map = this.make.tilemap({ key: 'playerHouse' });
                        const floorTileset = map.addTilesetImage('phFloor', 'pHouse_Floor');
                        const wallTileset = map.addTilesetImage('phWall', 'pHouse_Walls');
                        const furnitureTileset = map.addTilesetImage('phFurniture', 'pHouse_Furniture');

                        map.createLayer('FloorGroup/Floor', floorTileset);
                        map.createLayer('Walls', wallTileset);
                        map.createLayer('FloorGroup/Carpet', furnitureTileset);
                    }
                }
            };

            this.game = new Phaser.Game(config);
            this.game.canvas.parentElement.style.margin = 'auto';
        }
    }
};
</script>
  