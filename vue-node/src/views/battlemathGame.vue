<template>
    <div class="game-container">
        <!-- <div class="profile">
            <button class="nes-btn profile-button" @click="toggleProfile">
                <img src="../../public/icons/profile.svg" alt="">
            </button>
            <div class="modal nes-container is-rounded" :class="{ 'not-visible': !this.navigation_menus.profile }">
                <button v-if="!isLogged" class="nes-btn" @click="navigation_menus.loginModal = true">Login</button>
                <button v-if="!isLogged" class="nes-btn" @click="navigation_menus.registerModal = true">Registra't</button>
                <button v-if="isLogged" class="nes-btn" @click="navigation_menus.registerModal = true">Surt</button>
            </div>
        </div> -->

        <div class="modal-overlay" v-if="navigation_menus.showCharSelectModal">
            <div class="modal nes-container is-rounded">
                <p class="title">Selecciona el personatge</p>
                <char_select @selectedCharacter="selectSkin" />
                <button class="nes-btn" @click="closeCharSelectModal">
                    Tanca
                </button>
            </div>
        </div>
        <div class="npc-modal" v-if="npc.interactingWithNPC">
            <div class="npcFace-container" v-if="npc.npcImage != 'doorPHouse'">
                <img class="npcFace" :src="`/npc/face_${npc.npcImage}.png`" alt="" />
            </div>
            <div class="modal nes-container is-rounded textBox">
                <button @click="closeNPCModal" class="nes-btn boton-cerrar boton-cerrar-npc">
                    <img src="../../public/icons/cross.svg" alt="" />
                </button>
                <textBox :text="npc.npcText" @closeText="cerrarDialogo" />
                <div class="woman-btn" v-if="npc.npcImage === 'Woman' &&
                    !this.npc.interactingWithDoor
                    ">
                    <button v-if="!this.isLogged" class="nes-btn" @click="navigation_menus.loginModal = true">
                        Login
                    </button>
                    <button v-if="this.isLogged" class="nes-btn" @click="logout">
                        Surt
                    </button>
                    <button v-if="!this.isLogged" class="nes-btn" @click="navigation_menus.registerModal = true">
                        Registra't
                    </button>
                </div>
                <div class="woman-btn" v-if="npc.npcImage === 'Samurai' && isLogged">
                    <button class="npc-btn nes-btn" @click="openCharSelectModal">
                        Si
                    </button>
                    <button class="npc-btn nes-btn" @click="closeNPCModal">
                        No
                    </button>
                </div>
            </div>
        </div>

        <div v-if="navigation_menus.loginModal" class="login-modal">
            <div class="modal nes-container is-rounded">
                <button @click="navigation_menus.loginModal = false" class="nes-btn boton-cerrar">
                    <img src="../../public/icons/cross.svg" alt="" />
                </button>
                <login @user="loginUser" />
            </div>
        </div>

        <div v-if="navigation_menus.registerModal" class="register-modal">
            <div class="modal nes-container is-rounded">
                <button @click="navigation_menus.registerModal = false" class="nes-btn boton-cerrar">
                    <img src="../../public/icons/cross.svg" alt="" />
                </button>
                <register @user="registerUser" />
            </div>
        </div>

        <div v-if="!isLogged" :class="{ 'controls': !controlsHidden, 'controlsHide': controlsHidden }">
            <img src="/img/Tuto.png" alt="">
        </div>

        <div class="gameCanvas" ref="gameContainer"></div>
    </div>
</template>

<script>
import { computed, defineComponent } from 'vue';
import char_select from '@/components/char_select.vue';
import login from '@/components/Login.vue';
import register from '@/components/Register.vue';
import textBox from '@/components/textBox.vue';
import Phaser from 'phaser';
import Router from '../router';
import { socket } from '@/socket';
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import { useAppStore } from "../stores/app";

export default defineComponent({
    name: "battlemathGame",
    components: {
        char_select,
        textBox,
        login,
        register,
    },
    data() {
        return {
            player: {
                skinID: "",
                playerID: "",
            },
            game: null,
            playerInfoInterval: 0,
            player: null,
            username: "",
            token: "",
            success: false,
            message: "",
            isLogged: false,
            playerSprite: "",
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
                offset: 4.8,
            },
            navigation_menus: {
                showCharSelectModal: false,
                loginModal: false,
                registerModal: false,
                sceneStart: 1,
            },
            npc: {
                npcs: [],
                interactingWithNPC: false,
                interactingWithDoor: false,
                npcText: "",
                npcImage: "",
                playerInTrigger: false,
                profile: false,
            },
            controlsHidden: false,
            playerInfo: {
                id: null,
                username: "",
                skin: "",
                x: "",
                y: "",
            },
            playerSprites: {},
        };
    },
    mounted() {
        const store = useAppStore();
        this.isLogged = store.getIsLogged();
        if (this.isLogged) {
            this.username = store.getUsername();
            this.token = store.getToken();
            this.playerSprite = store.getSkin();
        } else {
            this.playerSprite = this.randomStartSkin("eggBoy", "eggGirl");
        }

        this.recibirsuccessLogout();
        if (store.getLastRoute() === '/rooms') {
            this.navigation_menus.sceneStart = 2;
            setTimeout(() => {
                this.navigation_menus.sceneStart = 1;
            }, 1000);
        } else {
            this.navigation_menus.sceneStart = 1;
        }
        this.initializeGame();
        store.setLastRoute("/game");
    },
    watch: {
        playerSprite(newSkin) {
            if (this.player) {
                this.createPlayerAnims(this.game.scene.scenes[0], newSkin);
                this.player.setTexture(newSkin);
                const currentAnimKey = this.player.anims.currentAnim.key;
                const parts = currentAnimKey.split("_");

                parts[0] = newSkin;
                this.player.anims.play(parts.join("_"));
            }
        },
        success() {
            if (this.success) {
                this.toastNotification();
            }
        },
    },
    methods: {
        initializeGame() {
            const self = this;
            const store = useAppStore();

            const playerHouseConfig = {
                key: "playerHouse",
                preload: function () {
                    self.player = null;
                    self.preloadPlayerHouse(this);
                    self.preloadNPC(this);
                    self.preloadSkins(this);
                    this.load.image("door", "/objects/door.png");
                    this.load.image("dialogBox", "/img/DialogBoxFaceset.png");
                },
                create: function () {
                    ///Create map
                    self.createPlayerHouse(this);
                    self.createParticleHouse(this, 664, 723);
                    self.createParticleHouse(this, 856, 723);
                    self.createParticleHouse(this, 920, 723);

                    self.createNPC(this, 700, 800, "npcWoman", 0);
                    self.createNPC(this, 712, 724, "npcSamurai", 0);

                    ///Create player
                    if (!self.isLogged) {
                        self.playerCreate(this, 720, 774, self.playerSprite);
                    } else {
                        self.playerCreate(this, 793, 840, self.playerSprite);
                    }

                    self.createNPC(this, 792, 870, "npcdoorPHouse", 0);
                    self.triggerWithNPC(this);

                    self.addHouseCollisions(this);

                    this.cameras.main.centerOn(780, 774);
                    self.createPlayerHouse_foreground(this);
                    self.createParticleHouse(this, 664, 851);
                    self.createParticleHouse(this, 856, 851);
                    self.createParticleHouse(this, 920, 851);

                    self.playerMovement(this, self.playerSprite);
                },
                update: function () { },
            };

            const lobbyConfig = {
                key: "lobby",
                preload: function () {
                    self.player = null;
                    self.preloadLobby(this);
                    self.preloadNPC(this);
                    self.preloadSkins(this);
                },
                create: function () {
                    self.createLobby(this);

                    self.createNPC(this, 910, 420, 'npcRyu', 0);
                    ///Create player
                    if (self.navigation_menus.sceneStart === 1) {
                        self.playerCreate(this, 888, 390, self.playerSprite);
                    } else {
                        self.playerCreate(this, 687, 554, self.playerSprite);
                    }


                    self.triggerWithNPC(this);
                    self.addLobbyCollisions(this);
                    this.cameras.main.startFollow(self.player, true);
                    self.createLobby_foreground(this);
                    this.physics.add.collider(
                        self.player,
                        self.lobby_layers.fg
                    );

                    self.playerMovement(this, self.playerSprite);
                    if (store.firstTime) {
                        self.dialogo(this, 'npcRyu');
                        store.firstTime = false;
                    }
                },
                update: function () {
                },
            };

            const config = {
                type: Phaser.AUTO,
                width:
                    (window.innerWidth - self.cameraConfig.offset) /
                    self.cameraConfig.zoom,
                height:
                    (window.innerHeight - self.cameraConfig.offset) /
                    self.cameraConfig.zoom,
                zoom: self.cameraConfig.zoom,
                scale: {
                    autoCenter: Phaser.Scale.RESIZE,
                    mode: Phaser.Scale.NONE,
                },
                parent: this.$refs.gameContainer,
                physics: {
                    default: "arcade",
                    arcade: {
                        gravity: { y: 0 },
                        debug: false,
                    },
                },
            };

            self.game = this.game = new Phaser.Game(config);
            this.game.loop.targetFps = 30;
            this.game.scene.add("playerHouse", playerHouseConfig, false);
            this.game.scene.add("lobby", lobbyConfig, false);

            if (self.navigation_menus.sceneStart === 1) {
                this.game.scene.start("playerHouse");
            } else {
                this.game.scene.start("lobby");
            }
        },
        registerUser(user) {
            this.username = user.username;
            this.npc.interactingWithNPC = false;
            this.canMove = true;
            this.navigation_menus.registerModal = false;
            this.navigation_menus.loginModal = true;
        },
        loginUser(user) {
            this.username = user.user.username;
            this.player.playerID = user.user.id;
            this.token = user.token;
            this.playerSprite = user.skin;
            this.npc.interactingWithNPC = false;
            this.isLogged = true;
            this.canMove = true;
            this.navigation_menus.loginModal = false;
        },
        logout() {
            this.isLogged = false;
            this.username = "";
            this.playerSprite = this.randomStartSkin("eggBoy", "eggGirl");
            this.closeNPCModal();
            socket.emit("logout", this.token);
        },
        recibirsuccessLogout() {
            socket.on("successLogout", (response) => {
                this.success = true;
                this.message = response.success;
            });
        },
        toastNotification() {
            toast.success(this.message, {
                position: "top-right",
                timeout: 2000,
                closeOnClick: true,
                pauseOnHover: true,
                draggable: true,
                draggablePercent: 0.6,
                showCloseButtonOnHover: false,
                hideProgressBar: false,
                closeButton: "button",
                icon: true,
                rtl: false,
            });
        },
        randomStartSkin(skin1, skin2) {
            const randomIndex = Math.random() < 0.5 ? 0 : 1;
            return randomIndex === 0 ? skin1 : skin2;
        },
        selectSkin(character) {
            const store = useAppStore();

            this.player.skinID = character.id;
            this.playerSprite = character.name;
            store.setNewSkin(character.name);

        },
        openCharSelectModal() {
            this.npc.interactingWithNPC = false;
            this.navigation_menus.showCharSelectModal = true;
            this.canMove = false;
        },
        closeCharSelectModal() {
            this.navigation_menus.showCharSelectModal = false;
            this.canMove = true;
            socket.emit("newSkin", this.player.playerID, this.player.skinID);
        },
        closeNPCModal() {
            this.npc.interactingWithNPC = false;
            this.canMove = true;
        },
        toggleProfile() {
            this.navigation_menus.profile = !this.navigation_menus.profile;
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
            scene.load.spritesheet("npcWoman", "npc/ss_Woman.png", {
                frameWidth: 16,
                frameHeight: 16,
            });
            scene.load.spritesheet("npcVillager1", "npc/ss_Villager1.png", {
                frameWidth: 16,
                frameHeight: 16,
            });
            scene.load.spritesheet("npcVillager2", "npc/ss_Villager2.png", {
                frameWidth: 16,
                frameHeight: 16,
            });
            scene.load.spritesheet("npcVillager3", "npc/ss_Villager3.png", {
                frameWidth: 16,
                frameHeight: 16,
            });
            scene.load.spritesheet("npcVillager4", "npc/ss_Villager4.png", {
                frameWidth: 16,
                frameHeight: 16,
            });
            scene.load.spritesheet("npcMaster", "npc/ss_Master.png", {
                frameWidth: 16,
                frameHeight: 16,
            });
            scene.load.spritesheet("npcRyu", "npc/ss_Ryu.png", {
                frameWidth: 16,
                frameHeight: 16,
            });
            scene.load.spritesheet("npcSamurai", "npc/ss_Samurai.png", {
                frameWidth: 16,
                frameHeight: 16,
            });
            scene.load.spritesheet("npcBlueSamurai", "npc/ss_BlueSamurai.png", {
                frameWidth: 16,
                frameHeight: 16,
            });
            scene.load.spritesheet("DialogInfo", "npc/DialogInfo.png", {
                frameWidth: 20,
                frameHeight: 16,
            });

            ///Preload facesets
            scene.load.image("npcWoman_face", "npc/face_Woman.png");
            scene.load.image("npcVillager1_face", "npc/face_Villager1.png");
            scene.load.image("npcVillager2_face", "npc/face_Villager2.png");
            scene.load.image("npcVillager3_face", "npc/face_Villager3.png");
            scene.load.image("npcVillager4_face", "npc/face_Villager4.png");
            scene.load.image("npcMaster_face", "npc/face_Master.png");
            scene.load.image("npcRyu_face", "npc/face_Ryu.png");
            scene.load.image("npcSamurai_face", "npc/face_Samurai.png");
            scene.load.image("npcBlueSamurai_face", "npc/face_BlueSamurai.png");
        },
        preloadSkins(scene) {
            scene.load.spritesheet("eggBoy", "characters/eggBoy.png", {
                frameWidth: 16,
                frameHeight: 16,
            });
            scene.load.spritesheet("eggGirl", "characters/eggGirl.png", {
                frameWidth: 16,
                frameHeight: 16,
            });
            scene.load.spritesheet("blackMage", "characters/blackMage.png", {
                frameWidth: 16,
                frameHeight: 16,
            });
            scene.load.spritesheet("blueNinja", "characters/blueNinja.png", {
                frameWidth: 16,
                frameHeight: 16,
            });
            scene.load.spritesheet("darkNinja", "characters/darkNinja.png", {
                frameWidth: 16,
                frameHeight: 16,
            });
            scene.load.spritesheet(
                "eskimoNinja",
                "characters/eskimoNinja.png",
                { frameWidth: 16, frameHeight: 16 }
            );
            scene.load.spritesheet("goldKnight", "characters/goldKnight.png", {
                frameWidth: 16,
                frameHeight: 16,
            });
            scene.load.spritesheet("grayNinja", "characters/grayNinja.png", {
                frameWidth: 16,
                frameHeight: 16,
            });
            scene.load.spritesheet("greenNinja", "characters/greenNinja.png", {
                frameWidth: 16,
                frameHeight: 16,
            });
            scene.load.spritesheet("knight", "characters/knight.png", {
                frameWidth: 16,
                frameHeight: 16,
            });
            scene.load.spritesheet(
                "maskedNinja",
                "characters/maskedNinja.png",
                { frameWidth: 16, frameHeight: 16 }
            );
            scene.load.spritesheet("maskFrog", "characters/maskFrog.png", {
                frameWidth: 16,
                frameHeight: 16,
            });
            scene.load.spritesheet("orangeMage", "characters/orangeMage.png", {
                frameWidth: 16,
                frameHeight: 16,
            });
            scene.load.spritesheet("redNinja", "characters/redNinja.png", {
                frameWidth: 16,
                frameHeight: 16,
            });
            scene.load.spritesheet(
                "yellowNinja",
                "characters/yellowNinja.png",
                { frameWidth: 16, frameHeight: 16 }
            );
            scene.load.spritesheet("mario", "characters/mario.png", {
                frameWidth: 16,
                frameHeight: 16,
            });
            scene.load.spritesheet("yayo", "characters/yayo.png", {
                frameWidth: 16,
                frameHeight: 16,
            });
            scene.load.spritesheet("fueguito", "characters/fueguito.png", {
                frameWidth: 16,
                frameHeight: 16,
            });
        },
        preloadPlayerHouse(scene) {
            scene.load.spritesheet("npcdoorPHouse", "objects/door.png", {
                frameWidth: 16,
                frameHeight: 16,
            });
            scene.load.image("pHouse_Furniture", "tiles/TilesetElement.png");
            scene.load.image(
                "pHouse_Walls",
                "tiles/player_house/TilesetWallSimple.png"
            );
            scene.load.image(
                "pHouse_Floor",
                "tiles/player_house/TilesetInteriorFloor.png"
            );
            scene.load.image("fire", "particles/Fire.png");
            scene.load.tilemapTiledJSON(
                "playerHouse",
                "tiles/player_house/playerHouse.json"
            );
        },
        createPlayerHouse(scene) {
            const map = scene.make.tilemap({ key: "playerHouse" });
            const floorTileset = map.addTilesetImage("phFloor", "pHouse_Floor");
            const wallTileset = map.addTilesetImage("phWall", "pHouse_Walls");
            const furnitureTileset = map.addTilesetImage(
                "phFurniture",
                "pHouse_Furniture"
            );

            map.createLayer("Background", wallTileset);
            map.createLayer("FloorGroup/Floor", floorTileset);
            map.createLayer("FloorGroup/Carpet", furnitureTileset);
            this.pHouse_layers.walls = map.createLayer("Walls", wallTileset);
            this.pHouse_layers.furniture = map.createLayer(
                "Furniture",
                furnitureTileset
            );

            this.pHouse_layers.walls.setCollisionByProperty({ collides: true });
            this.pHouse_layers.furniture.setCollisionByProperty({
                collides: true,
            });

            // this.debugCollision(scene);
        },
        createPlayerHouse_foreground(scene) {
            const map = scene.make.tilemap({ key: "playerHouse" });
            const furnitureTileset = map.addTilesetImage(
                "phFurniture",
                "pHouse_Furniture"
            );
            map.createLayer("foreground", furnitureTileset);
        },
        addHouseCollisions(scene) {
            scene.physics.add.collider(this.player, this.pHouse_layers.walls);
            scene.physics.add.collider(
                this.player,
                this.pHouse_layers.furniture
            );
            scene.physics.add.collider(this.player, this.objects.doors);
        },
        preloadLobby(scene) {
            scene.load.image(
                "TilesetLobby",
                "tiles/lobby_map/TilesetLobby.png"
            );
            scene.load.image("TilesetElement", "tiles/TilesetElement.png");
            scene.load.image("dojo_door_left", "/objects/dojo_door_left.png");
            scene.load.image("dojo_door_right", "/objects/dojo_door_right.png");
            scene.load.image("phouse_door", "/objects/phouse_door.png");
            scene.load.spritesheet("leaves", "particles/Leaf.png", {
                frameWidth: 16,
                frameHeight: 16,
            });
            scene.load.tilemapTiledJSON(
                "lobby",
                "tiles/lobby_map/lobbyMap.json"
            );
        },
        createLobby(scene) {
            const map = scene.make.tilemap({ key: "lobby" });
            const tileset = map.addTilesetImage("TilesetLobby", "TilesetLobby");
            const furniture = map.addTilesetImage(
                "TilesetElement",
                "TilesetElement"
            );

            this.lobby_layers.bg = map.createLayer("bg", tileset);
            this.lobby_layers.floor = map.createLayer("floor", tileset);
            this.lobby_layers.buildBottom2 = map.createLayer(
                "buildings-bottom_2",
                tileset
            );
            this.lobby_layers.buildBottom = map.createLayer(
                "buildings-bottom",
                tileset
            );
            this.lobby_layers.build = map.createLayer("buildings", tileset);
            this.lobby_layers.buildTop = map.createLayer(
                "buildings-top",
                tileset
            );
            this.lobby_layers.furnTop = map.createLayer(
                "furniture-top",
                tileset
            );

            this.lobby_layers.buildBottom2.setCollisionByProperty({
                collides: true,
            });
            this.lobby_layers.buildBottom.setCollisionByProperty({
                collides: true,
            });
            this.lobby_layers.build.setCollisionByProperty({ collides: true });
            this.lobby_layers.buildTop.setCollisionByProperty({
                collides: true,
            });
            this.lobby_layers.furnTop.setCollisionByProperty({
                collides: true,
            });
            this.lobby_layers.floor.setCollisionByProperty({ collides: true });
            this.lobby_layers.bg.setCollisionByProperty({ collides: true });

            this.objects.doors = scene.physics.add.staticGroup();
            const doorLayer = map.getObjectLayer("Doors");
            let door = null;
            doorLayer.objects.forEach((doorsObj) => {
                if (doorsObj.name === "player_door") {
                    door = this.objects.doors.create(
                        doorsObj.x + doorsObj.width / 2,
                        doorsObj.y - doorsObj.height / 1.99,
                        "phouse_door"
                    );
                } else if (doorsObj.name === "dojo_door_right") {
                    door = this.objects.doors.create(
                        doorsObj.x + doorsObj.width / 2,
                        doorsObj.y - doorsObj.height / 1.99,
                        "dojo_door_right"
                    );
                } else {
                    door = this.objects.doors.create(
                        doorsObj.x + doorsObj.width / 2,
                        doorsObj.y - doorsObj.height / 1.99,
                        "dojo_door_left"
                    );
                }
                door.name = doorsObj.name;
            });
        },
        createLobby_foreground(scene) {
            const map = scene.make.tilemap({ key: "lobby" });
            const tileset = map.addTilesetImage("TilesetLobby", "TilesetLobby");

            this.lobby_layers.fg = map.createLayer("foreground", tileset);
            this.lobby_layers.fg.setCollisionByProperty({ collides: true });
        },
        addLobbyCollisions(scene) {
            scene.physics.add.collider(this.player, this.lobby_layers.bg);
            scene.physics.add.collider(this.player, this.lobby_layers.floor);
            scene.physics.add.collider(
                this.player,
                this.lobby_layers.buildBottom2
            );
            scene.physics.add.collider(
                this.player,
                this.lobby_layers.buildBottom
            );
            scene.physics.add.collider(this.player, this.lobby_layers.build);
            scene.physics.add.collider(this.player, this.lobby_layers.buildTop);
            scene.physics.add.collider(this.player, this.lobby_layers.furnTop);

            scene.physics.add.overlap(
                this.player,
                this.objects.doors,
                (player, door) => {
                    if (door.name === "player_door") {
                        this.cambiarEscena(scene, "playerHouse");
                    } else {
                        if (this.game) {
                            this.game.destroy(true);
                        }
                        Router.push("/rooms");
                    }
                }
            );
        },
        createNPC(scene, x, y, npc, frame) {
            this.npc.playerInTrigger = false;

            const NPC = scene.physics.add.sprite(x, y, npc, frame);
            this.npc.npcs.push(NPC);
        },
        triggerWithNPC(scene) {
            for (let i = 0; i < this.npc.npcs.length; i++) {
                let npc = this.npc.npcs[i];
                let npcName = npc.texture.key;
                let dialogInfo = "";

                scene.physics.add.collider(npc, this.player);
                npc.body.setSize(npc.width, npc.height);
                npc.body.setOffset(0, 0);
                npc.setVelocity(0, 0);
                npc.body.immovable = true;

                const trigger = scene.physics.add
                    .sprite(npc.x, npc.y, null)
                    .setAlpha(0);
                trigger.body.setSize(npc.width * 1.8, npc.height * 1.8);
                trigger.body.setAllowGravity(false);
                trigger.child = npc.texture.key;

                if (npcName != "npcdoorPHouse") {
                    dialogInfo = scene.physics.add.sprite(
                        npc.x,
                        npc.y - 20,
                        "DialogInfo",
                        0
                    );

                    dialogInfo.anims.create({
                        key: `DialogInfoAnim`,
                        frames: scene.anims.generateFrameNumbers("DialogInfo", {
                            frames: [0, 1, 2, 3],
                        }),
                        repeat: -1,
                        frameRate: 2,
                    });
                    dialogInfo.setAlpha(0);

                    dialogInfo.anims.play(`DialogInfoAnim`, true);
                }

                let dialogoMostrado = false;

                scene.physics.add.overlap(
                    this.player,
                    trigger,
                    (player, trigger) => {
                        if (trigger.body.touching.none) {
                            this.npc.playerInTrigger = true;
                            if (trigger.child != "npcdoorPHouse") {
                                dialogInfo.setAlpha(1);
                            }
                            if (!dialogoMostrado) {
                                if (
                                    this.isLogged &&
                                    trigger.child === "npcdoorPHouse"
                                ) {
                                    this.cambiarEscena(scene, "lobby");
                                } else if (
                                    !this.isLogged &&
                                    trigger.child === "npcdoorPHouse"
                                ) {
                                    this.dialogo(scene, trigger.child);
                                }
                                dialogoMostrado = true;
                                this.interactWithNPC(scene, npc);
                            }
                        } else {
                            if (trigger.child != "npcdoorPHouse") {
                                dialogInfo.setAlpha(0);
                            }
                            this.npc.playerInTrigger = false;
                            scene.input.keyboard.off("keydown-SPACE");
                            dialogoMostrado = false;
                        }
                    }
                );
            }
        },
        interactWithNPC(scene, npc) {
            scene.input.keyboard.on("keydown-SPACE", () => {
                if (
                    !this.interactingWithNPC &&
                    this.canMove &&
                    this.npc.playerInTrigger
                ) {
                    const distX = this.player.x - npc.x;
                    const distY = this.player.y - npc.y;

                    if (Math.abs(distX) > Math.abs(distY)) {
                        if (distX > 0) {
                            npc.setFrame(3);
                        } else {
                            npc.setFrame(2);
                        }
                    } else {
                        if (distY > 0) {
                            npc.setFrame(0);
                        } else if (distY < 0) {
                            npc.setFrame(1);
                        } else {
                            npc.setFrame(0);
                        }
                    }
                    this.dialogo(scene, npc.texture.key);
                }
            });
        },
        dialogo(scene, npc) {
            console.log(npc);
            this.npc.npcImage = "";
            this.npc.npcText = "";
            let parts = npc.split("npc");
            let splittedNPC = parts[1];
            this.canMove = false;
            this.npc.interactingWithNPC = true;
            this.npc.interactingWithDoor = false;
            this.npc.npcImage = splittedNPC;
            switch (splittedNPC) {
                case "Woman":
                    if (this.isLogged) {
                        this.npc.npcText = [
                            `Hola ${this.username}, com estas?`,
                        ];
                    } else {
                        this.npc.npcText = [`Ens coneixem d'abans?`];
                    }
                    break;
                case "Samurai":
                    if (!this.isLogged) {
                        this.npc.npcText = [
                            `Qui ets? Deuries parlar amb l'altra dona.`,
                        ];
                    } else {
                        this.npc.npcText = [
                            `Hola ${this.username}, que en vols canviar d'estil?`,
                        ];
                    }
                    break;
                case "doorPHouse":
                    this.npc.interactingWithDoor = true;
                    if (!this.isLogged) {
                        this.npc.npcImage = "Woman";
                        this.npc.npcText = [`Qui ets? Vine aquí.`];
                    } else {
                        this.closeNPCModal();
                    }
                    break;
                case "Ryu":
                    this.npc.npcText = [`Hola ${this.username}, hauries d'anar al Dojo.`,
                        `Allà podràs lluitar contra altres jugadors.`, `Es l'edifici amb el terrat vermell.`];
                    break;
                default:
                    break;
            }
        },
        cerrarDialogo(newVal) {
            setTimeout(() => {
                this.npc.interactingWithNPC = newVal;
                this.navigation_menus.showCharSelectModal = false;
                this.canMove = true;
            }, 10);
        },
        playerCreate(scene, x, y, skin) {
            const store = useAppStore();
            if (this.navigation_menus.sceneStart === 2) {
                const skin2 = store.getSkin();
                this.createPlayerAnims(scene, skin2);
                this.player = scene.physics.add.sprite(x, y, skin2);
                this.player.anims.play(`${skin2}_idle_down`);
            } else {
                this.createPlayerAnims(scene, skin);
                this.player = scene.physics.add.sprite(x, y, skin);
                this.player.anims.play(`${skin}_idle_down`);
            }

            this.player.body.setSize(
                this.player.width * 1,
                this.player.height * 0.2
            );
            this.player.body.setOffset(
                this.player.width * 0,
                this.player.height * 0.8
            );
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
        },
        playerMovement(scene, skin) {
            const speed = 30;
            const runSpeedMultiplier = 1.5;

            let currentSpeed = speed;
            scene.input.keyboard.on("keydown", (event) => {
                this.controlsHidden = true;
                if (this.canMove) {
                    if (skin != this.playerSprite) {
                        skin = this.playerSprite;
                    }


                    switch (event.code) {
                        case "ArrowLeft":
                            this.player.anims.play(`${skin}_move_left`, true);
                            this.player.setVelocity(-currentSpeed, 0);
                            break;
                        case "ArrowRight":
                            this.player.anims.play(`${skin}_move_right`, true);
                            this.player.setVelocity(currentSpeed, 0);
                            break;
                        case "ArrowUp":
                            this.player.anims.play(`${skin}_move_up`, true);
                            this.player.setVelocity(0, -currentSpeed);
                            break;
                        case "ArrowDown":
                            this.player.anims.play(`${skin}_move_down`, true);
                            this.player.setVelocity(0, currentSpeed);
                            break;
                        default:
                            break;
                    }
                }
            });

            scene.input.keyboard.on("keyup", (event) => {
                switch (event.code) {
                    default:
                        // clearInterval(this.playerInfoInterval);
                        if (scene.scene.isActive("lobby")) {
                            this.addPlayerInfo(scene);
                        }
                        const parts =
                            this.player.anims.currentAnim.key.split("_");
                        parts[1] = "idle";
                        this.player.anims.play(parts.join("_"));
                        this.player.setVelocity(0, 0);

                        break;
                }
            });
        },
        createPlayerAnims(scene, skin) {
            const frameRate = 8;
            const store = useAppStore();

            if (this.navigation_menus.sceneStart === 2) {
                skin = store.getSkin();
            }


            scene.anims.create({
                key: `${skin}_idle_down`,
                frames: scene.anims.generateFrameNumbers(skin, {
                    frames: [0],
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
                    frames: [1, 5, 9, 13],
                }),
                repeat: -1,
                frameRate: frameRate,
            });
            scene.anims.create({
                key: `${skin}_move_down`,
                frames: scene.anims.generateFrameNumbers(skin, {
                    frames: [0, 4, 8, 12],
                }),
                repeat: -1,
                frameRate: frameRate,
            });
            scene.anims.create({
                key: `${skin}_move_left`,
                frames: scene.anims.generateFrameNumbers(skin, {
                    frames: [2, 6, 10, 14],
                }),
                repeat: -1,
                frameRate: frameRate,
            });
            scene.anims.create({
                key: `${skin}_move_right`,
                frames: scene.anims.generateFrameNumbers(skin, {
                    frames: [3, 7, 11, 15],
                }),
                repeat: -1,
                frameRate: frameRate,
            });
        },
        tecla(scene, key) {
            return scene.input.keyboard.addKey(
                Phaser.Input.Keyboard.KeyCodes[key]
            ).isDown;
        },
        cambiarEscena(scene, escena) {
            if (escena === "lobby") {
                scene.scene.stop("playerHouse");
                scene.scene.start("lobby");
            } else if (escena === "playerHouse") {
                scene.scene.stop("lobby");
                scene.scene.start("playerHouse");
            }
            this.npc.npcs = [];
        },
        createParticleHouse(scene, x, y) {
            const emitter = scene.add.particles(x, y, "fire", {
                color: [0xfacc22, 0xf89800, 0xf83600, 0x9f0404],
                colorEase: "quad.out",
                lifespan: 1600,
                angle: { min: -100, max: -80 },
                scale: { start: 0.7, end: 0, ease: "sine.out" },
                speed: 10,
                advance: 2000,
            });

            return emitter;
        },
        addPlayerInfo(scene) {
            const store = useAppStore();
            this.playerInfo.id = store.getId();
            this.playerInfo.username = store.getUsername();
            this.playerInfo.skin = this.playerSprite;
            this.playerInfo.x = this.player.x;
            this.playerInfo.y = this.player.y;

            socket.emit("addPlayer", this.playerInfo);
            this.viewPlayers(scene);
        },

        viewPlayers(scene) {
            socket.on("viewPlayers", (players) => {
                for (let i = 0; i < players.length; i++) {
                    if (players[i].id !== this.playerInfo.id) {
                        // Si ya tenemos un sprite para este jugador, lo eliminamos
                        if (this.playerSprites[players[i].id]) {
                            this.playerSprites[players[i].id].sprite.destroy();
                            this.playerSprites[players[i].id].text.destroy();
                        }

                        //Añadimos el username encima del personaje
                        const text = scene.add.text(
                            players[i].x - 10,
                            players[i].y - 20,
                            players[i].username,
                            {
                                fontFamily: "Arial",
                                fontSize: 10,
                                color: "#fff",
                                // backgroundColor: "#00000069",
                                // padding: {
                                //     left: 2,
                                //     right: 2,
                                //     top: 1,
                                //     bottom: 1,
                                // },

                            }
                        );

                        // Creamos un nuevo sprite para el jugador
                        const jugador = scene.physics.add.sprite(
                            players[i].x,
                            players[i].y,
                            players[i].skin
                        );

                        // Almacenamos el sprite y el texto en nuestro objeto
                        this.playerSprites[players[i].id] = { sprite: jugador, text: text };
                    }
                }
            });
        }

    },
});
</script>

<style scoped>
.title {
    background-color: #f2eaf1 !important;
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

.npc-btn {
    width: 20%;
}

.profile {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    position: fixed;
    top: 20px;
    right: 20px;
}

.profile button {
    margin: 20px;
}

.profile div.not-visible {
    display: none;
}

.profile div {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-direction: column;
    padding: 20px;
    max-height: 100%;
}

.profile div button {
    width: 100%;
}

.profile-button img {
    width: 50px;
}

.profile-button {
    width: 200px;
}

.boton-cerrar {
    position: absolute;
    font-size: 25px;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    top: 10px;
    right: 10px;
}

.boton-cerrar>img {
    width: 30px;
}

.boton-cerrar-npc {
    position: absolute;
    font-size: 25px;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    top: 10px;
    right: 10px;
    z-index: 100;
}

.game-container {
    overflow: hidden;
    width: 100vw;
    height: 100vh;
    position: absolute;
    top: 0;
    left: 0;
    margin: auto;
    background-color: #141b1b !important;
}

.modal-overlay,
.login-modal,
.register-modal {
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
    border-image-source: url("/img/border.svg") !important;
    border-image-slice: 6 !important;
    border-image-width: 3 !important;
    background-color: #f2eaf1;
    /* width: 20%; */
    flex-direction: column;
    border-color: rgb(255, 173, 93);
}

.npc-modal {
    position: fixed;
    /* top: 0; */
    bottom: 0;
    left: 0;
    width: 100%;
    height: 30vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

.textBox {
    width: 40vw;
}

.npcFace-container {
    border-width: 10px;
    border-style: solid;
    border-image-source: url("/img/FacesetBox.png");
    border-image-slice: 5;
    border-image-repeat: stretch;
}

.npcFace {
    width: 120px;
    height: 120px;
}

.woman-btn {
    display: flex;
    width: 100%;
    padding-left: 20px;
    gap: 2rem;
}

/* -------------------------------------------------------------------------- */
/*                                  CONTROLS                                  */
/* -------------------------------------------------------------------------- */
.controls {
    width: 100%;
    text-align: center;
    position: absolute;
    bottom: -100px;
    animation: animControlsUp 1s ease-in-out 1s both;
    background-color: #141b1ba4;
}

.controlsHide {
    width: 100%;
    text-align: center;
    position: absolute;
    background-color: #141b1ba4;
    animation: animControlsDown 1s ease-in-out both;
}

.controls img,
.controlsHide img {
    width: 50%;
    height: auto;
}

@keyframes animControlsUp {
    100% {
        bottom: 100px;
    }
}

@keyframes animControlsDown {
    0% {
        bottom: 100px;
    }

    99.9% {
        bottom: -100px;
    }

    100% {
        visibility: hidden;
    }
}

@media screen and (min-width: 1150px) {

    .controls img,
    .controlsHide img {
        width: 40%;
    }
}

@media screen and (min-width: 1440px) {

    .controls img,
    .controlsHide img {
        width: 35%;
    }

    .controls {
        bottom: -150px;
    }
}
</style>
