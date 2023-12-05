<template>
    <div class="rooms_container">
        <div class="box">
            <div class="options">
                <button class="option">LISTA PARTIDAS</button>
                <button class="option">CREAR PARTIDAS</button>
            </div>
            <div class="rooms">
                <div class="room">
                    <h2 class="title">Partida Clase #1</h2>
                    <h3 class="user">Julian</h3>
                    <h4 class="capacity">1/2</h4>
                    <button class="join-btn">Unirse</button>
                </div>

                <div class="room">
                    <h2 class="title">Hikkimikki</h2>
                    <h3 class="user">Deividson</h3>
                    <h4 class="capacity">2/2</h4>
                    <p>Completa</p>
                </div>

                <div class="room">
                    <h2 class="title">Que plasta</h2>
                    <h3 class="user">Yoyo</h3>
                    <h4 class="capacity">2/2</h4>
                    <p>Completa</p>
                </div>

                <div class="room">
                    <h2 class="title">Partida Clase #2</h2>
                    <h3 class="user">Dabid Visval</h3>
                    <h4 class="capacity">1/2</h4>
                    <button class="join-btn">Unirse</button>
                </div>

                <div class="room">
                    <h2 class="title">La Ganga</h2>
                    <h3 class="user">ElBryan</h3>
                    <h4 class="capacity">1/2</h4>
                    <button class="join-btn">Unirse</button>
                </div>

                <div class="room">
                    <h2 class="title">Me gusta la perassss</h2>
                    <h3 class="user">MrPera</h3>
                    <h4 class="capacity">2/2</h4>
                    <p>Completa</p>
                </div>

            </div>
        </div>
    </div>
</template>

<style scoped>
.rooms_container {
    width: 100%;
    height: 100vh;
    background-image: url(../../public/img/pixelArt-background.jpeg);
    background-size: cover;
    display: flex;
    align-items: center;
}

.box{
    position: relative;
    width: 70%;
    height: 35%;
    border: 4px solid black;
    margin: 0 auto;
    border-radius: 20px;
    
}
.options{
    position: absolute;
    top: -30px;
    left: 40px;
    display: flex;

}
.option{
    background-color: #282828;
    text-transform: uppercase;
    color: white;
    font-weight: bold;
    padding: 15px 10px;
    border: 2px solid #E58D08;
    border-radius: 15px;
}

.rooms{
    margin-top: 40px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 5px;
    height: 270px;
    overflow-y: scroll;
}
.room{
    width: 90%;
    min-height: 58px;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    align-items: center;
    justify-items: center;
    margin: 0 auto;
    background-color: #282828;
    color: white;
    border-radius: 10px;
    border: 2px solid #E58D08;
}
.title{
    text-align: center;
}

.join-btn{
    padding: 5px 10px;
    background-color: #A1FB4F;
    color: black;
    border-radius: 10px;
    transition: all 0.3s ease;  
}
.join-btn:hover{
    background-color: #E58D08;
    color: white;
}

::-webkit-scrollbar {
  width: 10px;
}

/* Track */
::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 100px;
  margin-right: 5px;
}
 
/* Handle */
::-webkit-scrollbar-thumb {
  background: #E58D08;
  border-radius: 100px;
  margin-right: 5px;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
  background: #282828; 
}

</style>
