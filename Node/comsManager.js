import axios from "axios";

const url = "http://battlemath.daw.inspedralbes.cat/Laravel/public/api/";

async function login(email, password) {
    try {
        const response = await axios.post(`${url}login`, { email, password });
        return response.data;
    } catch (error) {
        console.error(error);
        throw error;
    }
}

async function register(username, email, password, password_confirmation, skin_id) {
    try {
        const response = await axios.post(`${url}registre`, {
            username: username,
            email: email,
            password: password,
            password_confirmation: password_confirmation,
            skin_id: skin_id.id
        });
        return response.data;
    } catch (error) {
        console.error(error);
        throw error;
    }
}

async function logout(token) {
    try {
        const response = await axios.post(`${url}logout`, {}, {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        });
        return response.data;
    } catch (error) {
        console.error(error);
        throw error;
    }
}

async function getRandomQuestion() {
    try {
        const randomNumber = Math.floor(Math.random() * (70 - 1 + 1)) + 1;
        const response = await axios.get(`${url}preguntes/mostrar/${randomNumber}`);
        return response.data;
    } catch (error) {
        console.error(error);
        throw error;
    }
}

async function getRandomAnswers(data) {
    try {
        const respCorr = Math.floor(Math.random() * 4);
        const responses = [];
        let urlResp = '';
        while (responses.length < 4) {
            if (respCorr === responses.length) {
                urlResp = `${url}respostes/mostrar/${data.resposta_correcta_id}`;
            } else {
                let randomNumber = Math.floor(Math.random() * (120 - 1 + 1)) + 1;
                urlResp = `${url}respostes/mostrar/${randomNumber}`;
            }
            const response = await axios.get(urlResp);
            if (response.data.resposta != null) {
                const newResponse = {
                    id: response.data.id,
                    resposta: response.data.resposta
                };
                if (!responses.some(r => r.id === newResponse.id)) {
                    responses.push(newResponse);
                }
            }
        }

        return responses;
    } catch (error) {
        console.error(error);
        throw error;
    }
}

async function checkAnswer(questionId) {
    try {
        const response = await axios.get(`${url}preguntes/mostrar/${questionId}`);
        return response.data;
    } catch (error) {
        console.error(error);
        throw error;
    }
}

async function getSkins() {
    try {
        const response = await axios.get(`${url}skins`);
        return response.data;
    } catch (error) {
        console.error(error);
        throw error;
    }
}

async function getDamage(id) {
    try {
        const response = await axios.get(`${url}getDamage/${id}`);
        return response.data;
    } catch (error) {
        console.error(error);
        throw error;
    }

}

async function updateSkin(playerID, skinID) {
    try {
        const response = await axios.put(`${url}perfil/modificar/${playerID}`, {
            skin_id: skinID
        });
        return response.data;
    } catch (error) {
        console.error(error);
        throw error;
    }
}

const comsManager = {
    login,
    register,
    getRandomQuestion,
    getRandomAnswers,
    checkAnswer,
    getSkins,
    getDamage,
    updateSkin,
    logout
}

export default comsManager;