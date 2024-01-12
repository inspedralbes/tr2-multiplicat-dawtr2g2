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
Quins fitxers s'han d'editar i com (típicament per connectar la BD etc...)

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
