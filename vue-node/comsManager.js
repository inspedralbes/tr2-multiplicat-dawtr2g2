import axios from "axios";

const url = "http://localhost:8000/api/";

async function getQuestion(id) {
    return axios.get(`${url}preguntes/mostrar/${id}`)
        .then(response => {
            const pregunta = response.data;
            return pregunta;
        })
        .catch(error => {
            console.error(error);
        });
};

async function login(email, password) {
    try {
        const response = await axios.post(`${url}login`, { email, password });
        return response.data;
    } catch (error) {
        console.error(error);
        throw error; // Puedes manejar el error aquí o relanzarlo para que lo manejen en el lugar donde se llame a la función
    }
}

async function register(username, email, password, password_confirmation, skin_id) {
    try {
        const response = await axios.post(`${url}registre`, {
            username: username,
            email: email,
            password: password,
            password_confirmation: password_confirmation,
            skin_id: skin_id
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
        const promises = [];
        for (let i = 0; i < 4; i++) {
            if (respCorr == i) {
                const urlResp = `${url}respostes/mostrar/${data.resposta_correcta_id}`;
            } else {
                const randomNumber = Math.floor(Math.random() * (120 - 1 + 1)) + 1;
                const urlResp = `${url}respostes/mostrar/${randomNumber}`;
            }
            promises.push(axios.get(urlResp));
        }
        const responses = await Promise.all(promises);
        const resp = responses.map(response => ({
            id: response.data.id,
            resposta: response.data.resposta
        }));
        return resp;
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

const comsManager = {
    getQuestion,
    login,
    register,
    getRandomQuestion,
    getRandomAnswers,
    checkAnswer,
    getSkins
}

export default comsManager;