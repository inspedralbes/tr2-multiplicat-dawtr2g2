<template>
    <div class="game-container">
        <div class="modal-overlay" v-if="navigation_menus.showCharSelectModal">
            <div class="modal nes-container is-rounded">
                <p class="title">Selecciona el personatge</p>
                <char_select @selectedCharacter="selectSkin" />
                <button class="nes-btn" @click=closeCharSelectModal>Tanca</button>
            </div>
        </div>
        <div class="npc-modal" v-if="npc.interactingWithNPC">
            <div class="textBox-container">
                <textBox :text="npc.npcText" :npcImage="npc.npcImage" @closeText="cerrarDialogo" />
            </div>
        </div>
        <div class="gameCanvas" ref="gameContainer"></div>
    </div>
</template>
  
<script>
import { defineComponent } from 'vue';
import char_select from '@/components/char_select.vue';
import textBox from '@/components/textBox.vue';
import Phaser from 'phaser';

export default defineComponent({
    name: 'battlemathGame',
    components: {
        char_select,
        textBox
    },
    data() {
        return {
            player: null,
            playerSprite: '',
            canMove: true,
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
            navigation_menus: {
                showCharSelectModal: false,
            },
            npc: {
                interactingWithNPC: false,
                npcText: '',
                npcImage: '',
            },
            firstTime: true,
        }
    },
    mounted() {
        this.playerSprite = this.randomStartSkin("eggBoy", "eggGirl");
        this.initializeGame();
    },
    watch: {
        playerSprite(newSkin) {
            if (this.player) {
                this.createPlayerAnims(this.game.scene.scenes[0], newSkin);
                this.player.setTexture(newSkin);
                const currentAnimKey = this.player.anims.currentAnim.key; // Obtiene el nombre de la animación actual
                const parts = currentAnimKey.split('_'); // Divide el nombre actual por el guión bajo

                parts[0] = newSkin;
                this.player.anims.play(parts.join('_'));
            }
        }
    },
    methods: {
        initializeGame() {
            const self = this;

            const playerHouseConfig = {
                key: 'playerHouse',
                preload: function () {
                    self.player = null;
                    self.preloadPlayerHouse(this);
                    self.preloadNPC(this);
                    self.preloadSkins(this);
                    this.load.image('door', 'public/objects/door.png')
                    this.load.image('dialogBox', 'public/img/DialogBoxFaceset.png')
                },
                create: function () {
                    self.createPlayerHouse(this);
                    self.createParticleHouse(this, 664, 723);
                    self.createParticleHouse(this, 856, 723);
                    self.createParticleHouse(this, 920, 723);

                    // self.createPlayerAnims(this, self.playerSprite);


                    ///Create player
                    if (self.firstTime) {
                        self.firstTime = false;
                        self.playerCreate(this, 720, 774, self.playerSprite);
                    } else {
                        self.playerCreate(this, 793, 856, self.playerSprite);
                    }

                    self.npcCreate(this, 700, 774, 'npcWoman', 0);
                    self.npcCreate(this, 700, 800, 'npcSamurai', 0);
                    self.addHouseCollisions(this);

                    this.cameras.main.centerOn(780, 774);
                    self.createPlayerHouse_foreground(this);
                    self.createParticleHouse(this, 664, 851);
                    self.createParticleHouse(this, 856, 851);
                    self.createParticleHouse(this, 920, 851);

                },
                update: function () {
                    self.playerMovement(this, self.playerSprite);
                }

            };

            const lobbyConfig = {
                key: 'lobby',
                preload: function () {
                    self.player = null;
                    self.preloadLobby(this);
                    this.load.spritesheet('player', `characters/${self.playerSprite}.png`, { frameWidth: 16, frameHeight: 16 });
                },
                create: function () {
                    self.createLobby(this);

                    ///Create player
                    if (!self.player) {
                        self.playerCreate(this, 888, 390, self.playerSprite);
                    } else {
                        this.player.setVelocity(0, 0);
                    }
                    self.addLobbyCollisions(this);
                    this.cameras.main.startFollow(self.player, true);
                    self.createLobby_foreground(this);
                    this.physics.add.collider(self.player, self.lobby_layers.fg);


                },
                update: function () {
                    self.playerMovement(this, self.playerSprite);
                }
            }

            const config = {
                type: Phaser.AUTO,
                width: (window.innerWidth - self.cameraConfig.offset) / self.cameraConfig.zoom,
                height: (window.innerHeight - self.cameraConfig.offset) / self.cameraConfig.zoom,
                zoom: self.cameraConfig.zoom,
                // zoom: 1,
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

            const sceneStart = 1;

            if (sceneStart === 1) {
                this.game.scene.start('playerHouse');
            } else {
                this.game.scene.start('lobby');
            }

        },
        randomStartSkin(skin1, skin2) {
            const randomIndex = Math.random() < 0.5 ? 0 : 1;
            return randomIndex === 0 ? skin1 : skin2;
        },
        selectSkin(character) {
            this.playerSprite = character;
        },
        closeCharSelectModal() {
            this.navigation_menus.showCharSelectModal = false;
            this.canMove = true;
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
        preloadNPC(scene) {

            ///Preload spritesheets
            scene.load.spritesheet('npcWoman', 'npc/ss_Woman.png', { frameWidth: 16, frameHeight: 16 });
            scene.load.spritesheet('npcVillager1', 'npc/ss_Villager1.png', { frameWidth: 16, frameHeight: 16 });
            scene.load.spritesheet('npcVillager2', 'npc/ss_Villager2.png', { frameWidth: 16, frameHeight: 16 });
            scene.load.spritesheet('npcVillager3', 'npc/ss_Villager3.png', { frameWidth: 16, frameHeight: 16 });
            scene.load.spritesheet('npcVillager4', 'npc/ss_Villager4.png', { frameWidth: 16, frameHeight: 16 });
            scene.load.spritesheet('npcMaster', 'npc/ss_Master.png', { frameWidth: 16, frameHeight: 16 });
            scene.load.spritesheet('npcRyu', 'npc/ss_Ryu.png', { frameWidth: 16, frameHeight: 16 });
            scene.load.spritesheet('npcSamurai', 'npc/ss_Samurai.png', { frameWidth: 16, frameHeight: 16 });
            scene.load.spritesheet('npcBlueSamurai', 'npc/ss_BlueSamurai.png', { frameWidth: 16, frameHeight: 16 });

            ///Preload facesets
            scene.load.image('npcWoman_face', 'npc/face_Woman.png');
            scene.load.image('npcVillager1_face', 'npc/face_Villager1.png');
            scene.load.image('npcVillager2_face', 'npc/face_Villager2.png');
            scene.load.image('npcVillager3_face', 'npc/face_Villager3.png');
            scene.load.image('npcVillager4_face', 'npc/face_Villager4.png');
            scene.load.image('npcMaster_face', 'npc/face_Master.png');
            scene.load.image('npcRyu_face', 'npc/face_Ryu.png');
            scene.load.image('npcSamurai_face', 'npc/face_Samurai.png');
            scene.load.image('npcBlueSamurai_face', 'npc/face_BlueSamurai.png');
        },
        preloadSkins(scene) {
            scene.load.spritesheet('eggBoy', 'characters/eggBoy.png', { frameWidth: 16, frameHeight: 16 });
            scene.load.spritesheet('eggGirl', 'characters/eggGirl.png', { frameWidth: 16, frameHeight: 16 });
            scene.load.spritesheet('blackMage', 'characters/blackMage.png', { frameWidth: 16, frameHeight: 16 });
            scene.load.spritesheet('blueNinja', 'characters/blueNinja.png', { frameWidth: 16, frameHeight: 16 });
            scene.load.spritesheet('darkNinja', 'characters/darkNinja.png', { frameWidth: 16, frameHeight: 16 });
            scene.load.spritesheet('eskimoNinja', 'characters/eskimoNinja.png', { frameWidth: 16, frameHeight: 16 });
            scene.load.spritesheet('goldKnight', 'characters/goldKnight.png', { frameWidth: 16, frameHeight: 16 });
            scene.load.spritesheet('grayNinja', 'characters/grayNinja.png', { frameWidth: 16, frameHeight: 16 });
            scene.load.spritesheet('greenNinja', 'characters/greenNinja.png', { frameWidth: 16, frameHeight: 16 });
            scene.load.spritesheet('knight', 'characters/knight.png', { frameWidth: 16, frameHeight: 16 });
            scene.load.spritesheet('maskedNinja', 'characters/maskedNinja.png', { frameWidth: 16, frameHeight: 16 });
            scene.load.spritesheet('maskFrog', 'characters/maskFrog.png', { frameWidth: 16, frameHeight: 16 });
            scene.load.spritesheet('orangeMage', 'characters/orangeMage.png', { frameWidth: 16, frameHeight: 16 });
            scene.load.spritesheet('redNinja', 'characters/redNinja.png', { frameWidth: 16, frameHeight: 16 });
            scene.load.spritesheet('yellowNinja', 'characters/yellowNinja.png', { frameWidth: 16, frameHeight: 16 });
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
            scene.physics.add.collider(this.player, this.pHouse_layers.walls);
            scene.physics.add.collider(this.player, this.pHouse_layers.furniture);
            scene.physics.add.collider(this.player, this.objects.doors);

            scene.physics.add.overlap(this.player, this.objects.doors, () => {
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
            scene.physics.add.collider(this.player, this.lobby_layers.bg);
            scene.physics.add.collider(this.player, this.lobby_layers.floor);
            scene.physics.add.collider(this.player, this.lobby_layers.buildBottom2);
            scene.physics.add.collider(this.player, this.lobby_layers.buildBottom);
            scene.physics.add.collider(this.player, this.lobby_layers.build);
            scene.physics.add.collider(this.player, this.lobby_layers.buildTop);
            scene.physics.add.collider(this.player, this.lobby_layers.furnTop);


            scene.physics.add.overlap(this.player, this.objects.doors, (player, door) => {
                if (door.name === 'player_door') {
                    this.cambiarEscena(scene, 'playerHouse', 793, 856);
                } else {
                    console.log('aun nada')
                }
            });
        },
        npcCreate(scene, x, y, npc, frame) {
            let accionEnEspera = false;
            let overlapped = false;
            let overlappedNPC = null;

            const NPC = scene.physics.add.sprite(x, y, npc, frame);
            // const NPC_face = '';
            scene.physics.add.collider(NPC, this.player);
            NPC.body.setSize(this.player.width, this.player.height * .5);
            NPC.body.setOffset(this.player.width * 0, this.player.height * .5);
            NPC.setVelocity(0, 0);
            NPC.body.immovable = true;

            scene.physics.add.overlap(this.player, NPC, (player, npc) => {
                overlapped = true;
                overlappedNPC = npc;
            });

            scene.input.keyboard.on('keydown-SPACE', () => {
                const playerCloseToNPC = overlappedNPC && Phaser.Math.Distance.Between(this.player.x, this.player.y, overlappedNPC.x, overlappedNPC.y) < 16;
                if (!this.interactingWithNPC && playerCloseToNPC && overlapped && this.canMove && !accionEnEspera) {
                    accionEnEspera = true;
                    overlapped = false;


                    const distX = this.player.x - NPC.x;
                    const distY = this.player.y - NPC.y;

                    if (Math.abs(distX) > Math.abs(distY)) {
                        if (distX > 0) {
                            NPC.setFrame(3);
                        } else {
                            NPC.setFrame(2);
                        }
                    } else {
                        if (distY > 0) {
                            NPC.setFrame(0);
                        } else if (distY < 0) {
                            NPC.setFrame(1);
                        } else {
                            NPC.setFrame(0);
                        }
                    }

                    this.dialogo(npc);

                    scene.time.delayedCall(1000, () => {
                        accionEnEspera = false;
                        overlapped = true;
                        overlappedNPC = null;
                    }, [], this);
                }
            });

            if (overlappedNPC) {
                const distanceToNPC = Phaser.Math.Distance.Between(this.player.x, this.player.y, overlappedNPC.x, overlappedNPC.y);
                if (distanceToNPC > 16) {
                    overlappedNPC = null;
                }
            }

        },
        dialogo(npc) {
            let parts = npc.split('npc');
            let splittedNPC = parts[1];
            this.canMove = false;
            this.npc.interactingWithNPC = true;
            this.npc.npcImage = splittedNPC;
            switch (splittedNPC) {
                case ('Woman'):
                    this.npc.npcText = ['hola, Pedro', 'Paco?', 'Joselito'];
                    break;
                case ('Samurai'):
                    this.npc.npcText = [`Que en vols canviar d'estil?`];
                    break;
            }

        },
        cerrarDialogo(newVal) {
            setTimeout(() => {
                this.npc.interactingWithNPC = newVal;
                this.navigation_menus.showCharSelectModal = false;
                this.canMove = true;
                if (this.npc.npcImage === 'Samurai') {
                    this.navigation_menus.showCharSelectModal = true;
                }
            }, 10);
        },
        playerCreate(scene, x, y, skin) {
            this.createPlayerAnims(scene, skin);
            this.player = scene.physics.add.sprite(x, y, skin);

            this.player.anims.play(`${skin}_idle_down`);
            this.player.body.setSize(this.player.width * 0.6, this.player.height * 0.2);
            this.player.body.setOffset(this.player.width * .2, this.player.height * .8);
            scene.physics.world.enable(this.player);

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
        playerMovement(scene, skin) {
            const speed = 30;
            const runSpeedMultiplier = 1.5;

            let currentSpeed = speed;

            if (this.canMove) {
                if (this.tecla(scene, 'LEFT') || this.tecla(scene, 'A')) {
                    this.player.setVelocity(-currentSpeed, 0);
                    this.player.anims.play(`${skin}_move_left`, true);
                } else if (this.tecla(scene, 'RIGHT') || this.tecla(scene, 'D')) {
                    this.player.setVelocity(currentSpeed, 0);
                    this.player.anims.play(`${skin}_move_right`, true);
                } else if (this.tecla(scene, 'UP') || this.tecla(scene, 'W')) {
                    this.player.setVelocity(0, -currentSpeed);
                    this.player.anims.play(`${skin}_move_up`, true);
                } else if (this.tecla(scene, 'DOWN') || this.tecla(scene, 'S')) {
                    this.player.setVelocity(0, currentSpeed);
                    this.player.anims.play(`${skin}_move_down`, true);
                } else {
                    const parts = this.player.anims.currentAnim.key.split('_');
                    parts[1] = 'idle';
                    this.player.anims.play(parts.join('_'));
                    this.player.setVelocity(0, 0);
                }
            }
        },
        createPlayerAnims(scene, skin) {
            const frameRate = 8;

            scene.anims.create({
                key: `${skin}_idle_down`,
                frames: scene.anims.generateFrameNumbers(skin, {
                    frames: [0], // Especifica los números de las columnas que quieres
                }),
                repeat: -1,
                frameRate: frameRate,
            });

            scene.anims.create({
                key: `${skin}_idle_up`,
                frames: scene.anims.generateFrameNumbers(skin, {
                    frames: [1],
                }),
            });

            scene.anims.create({
                key: `${skin}_idle_left`,
                frames: scene.anims.generateFrameNumbers(skin, {
                    frames: [2],
                }),
            });

            scene.anims.create({
                key: `${skin}_idle_right`,
                frames: scene.anims.generateFrameNumbers(skin, {
                    frames: [3],
                }),
            });

            scene.anims.create({
                key: `${skin}_move_up`,
                frames: scene.anims.generateFrameNumbers(skin, {
                    frames: [1, 5, 9, 13]
                }),
                repeat: -1,
                frameRate: frameRate,
            });
            scene.anims.create({
                key: `${skin}_move_down`,
                frames: scene.anims.generateFrameNumbers(skin, {
                    frames: [0, 4, 8, 12], // Especifica los números de las columnas que quieres
                }),
                repeat: -1,
                frameRate: frameRate,
            });
            scene.anims.create({
                key: `${skin}_move_left`,
                frames: scene.anims.generateFrameNumbers(skin, {
                    frames: [2, 6, 10, 14]
                }),
                repeat: -1,
                frameRate: frameRate,
            });
            scene.anims.create({
                key: `${skin}_move_right`,
                frames: scene.anims.generateFrameNumbers(skin, {
                    frames: [3, 7, 11, 15]
                }),
                repeat: -1,
                frameRate: frameRate,
            });

        },
        tecla(scene, key) {
            return scene.input.keyboard.addKey(Phaser.Input.Keyboard.KeyCodes[key]).isDown;
        },
        cambiarEscena(scene, escena, x, y) {
            this.player.setPosition(x, y);
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
        }
    }
});
</script>
  
<style scoped>
.title {
    background-color: #F2EAF1 !important;
}

button {
    border-image-repeat: stretch !important;
    background-color: #ffad5d !important;
}

button::after {
    box-shadow: inset -4px -4px #e46d3a !important;
}

button:hover {
    background-color: #ec9e50 !important;
}

button:hover::after {
    box-shadow: inset -6px -6px #e46d3a !important;
}

.nes-btn:active:not(.is-disabled)::after {
    box-shadow: inset 4px 4px #e46d3a !important;
}

.game-container {
    overflow: hidden;
    width: 100vw;
    height: 100vh;
    position: absolute;
    top: 0;
    left: 0;
    margin: auto;
    background-color: #141B1B !important;
}

.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.784);
    display: flex;
    justify-content: center;
    align-items: center;
}

.modal {
    display: flex;
    border-image-repeat: stretch !important;
    border-image-source: url('../../public/img/border.svg') !important;
    border-image-slice: 6 !important;
    border-image-width: 3 !important;
    background-color: #F2EAF1;
    /* width: 20%; */
    flex-direction: column;
    border-color: rgb(255, 173, 93);
}

.npc-modal {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 30%;
}
</style>