import axios from "axios";

const url = "http://localhost:8000/api/";

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

async function getRandomQuestion() {
    try {
        const randomNumber = Math.floor(Math.random() * (40 - 1 + 1)) + 1;
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
        for (let i = 0; i < 4; i++) {
            if (respCorr === i) {
                urlResp = `${url}respostes/mostrar/${data.resposta_correcta_id}`;
            } else {
                let randomNumber = Math.floor(Math.random() * (120 - 1 + 1)) + 1;
                urlResp = `${url}respostes/mostrar/${randomNumber}`;
            }
            const response = await axios.get(urlResp);
            responses.push({
                id: response.data.id,
                resposta: response.data.resposta
            });
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
    updateSkin
}

export default comsManager;