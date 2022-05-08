import axios from 'axios';

const instance = axios.create({
    baseURL: 'https://pivot.visitmarche.be/'
});

export default instance;
