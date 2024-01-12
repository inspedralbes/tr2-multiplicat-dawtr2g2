# Documentació bàsica del projecte
## Instruccions per crear un entorn de desenvolupament
  - Eines + Plugins:
    - VUE3:
      - Express
      - Nes.css
      - Pinia
      - Pinia-plugin-persistedstate
      - Socket.io-client
      - uid
      - vue-router
      - vue3-toastify
    - NODE.js
      - Axios
      - Express
      - socket.io
    - LARAVEL
      - Sanctum
    - Phaser3

## Instruccions per desplegar el projecte a producció
1. Agafar les carpetes Laravel, Node i vue-node al nostre servidor
2. Primer farem la part de Laravel, anirem a la carpeta de laravel i duplicarem el .env.example amb el nom .env
3. Una vegada duplicat l'arxiu l'editarem i posarem les dades de la nostre base de dades per que Laravel pogui accedir sense cap problema
4. Una vegada finalitzat el pas anterior podrem executar dins de la carpeta de Laravel la següent comanda : **composer install**. Aixo instalara tot el que necesita Laravel per poder executar-se correctament
5. Executarem la següent comanda per importar dades a la base dades: **php artisan migrate::fresh --seed**
6. Una vegada acabada la part de laravel anirem a la carpeta Node on entrarem al arxiu comsManager.js i si es necesari cambiarem el valor de la variable **url** per la que realment sigui en el vostre cas.
7. Ara editarem l'arxiu server.js per si el port que ve predefinit esta ocupat el cambiarem per un altre. Aixo ho farem a la variable **PORT** i posarem un altre que no estigui ocupat.
8. Una vegada fet els 2 pasos anterior executarem la següent comanda: **npm install** perquè instali les dependencies que necesiti
9. Ara executarem l'arxiu **server.js** amb **screen** per que es quedi en segon pla i poder tancar la conexio ssh que estem executant. Això ho podrem fer executant la següent comanda: **screen node server.js** i per poder sortir haurem de fer **CTRL+A+D** i despres podrem tancar sense cap problema la conexio ssh
10. L'ultim que queda per fer seria anar a la carpeta vue-node i primer editar l'arxiu src/socket.js la variable **URL** per el domini del servidor i el port que hem posat en l'arxiu **server.js** perquè es pugui comunicar amb el node
11. Ara dins de la carpeta vue executarem la següent comanda: **npm run build** que ens donara com ha resultat la carpeta **dist**
12. Crearem una carpeta que es digui **vue** en la carpeta publica del server on ha d'estar com a minim la carpeta de Laravel. Dins de la carpeta de **vue** ficarem tots els arxius **menys index.html que el ficarem a la mateixa carpeta que Laravel i vue**
13. Una vegada el pas anterior ja podrem eliminar la carpeta **vue-node**


## Instruccions per seguir codificant el projecte
eines necessaries i com es crea l'entorn per que algú us ajudi en el vostre projecte.

## API / Endpoints / punts de comunicació

Get '/preguntes' -> (retorna JSON de totes les preguntes)
Post '/preguntes/crear' -> (retorna JSON de la pregunta creada)
Get '/preguntes/mostrar/{id}' -> (retorna JSON de la pregunta filtrada per id)
Get '/preguntes/dificultat/{dif}' -> (retorna JSON de preguntes filtrades per dificultat + comptador de preguntes que retorna)
Put '/preguntes/modificar/{id}' -> (retorna JSON de la pregunta modificada)
Delete '/preguntes/eliminar/{id}' -> (retorna JSON amb un missatge amb "Pregunta eliminada")

Get '/respostes' -> (retorna JSON de totes les respostes)
Post '/respostes/crear' -> (retorna JSON de la resposta creada)
Get '/respostes/mostrar/{id}' -> (retorna JSON de la resposta filtrada per id)
Put '/respostes/modificar/{id}' -> (retorna JSON de la resposta modificada)
Delete '/respostes/eliminar/{id}' -> (retorna JSON amb un missatge amb "Resposta eliminada")

Post '/registre' -> (retorna JSON amb la informació de l'usuari que s'ha registrat + token + 'success' 'Usuari registrat correctament' o 'message' 'Errors com a credencials, correu electrònic ja utilitzat...')
Post '/login' -> (retorna JSON amb la informació de l'usuari que ha iniciat sessió + token + 'success' 'Usuari ha iniciat sessió correctament' o 'message' 'Credencials incorrectes')
Put '/perfil/modificar/{id}' -> (retorna JSON amb 'success' 'Usuari modificat correctament' o 'message' 'Usuari no trobat' en el cas que estiguis intentant modificar un usuari que no existeix)
Post '/logout' -> (retorna JSON amb missatge de que s'ha tancat la sessió)

Get '/skins' (retorna JSON amb totes les skins)
Get '/getDamage/{id}' (retorna el número que ha de restar la vida)
