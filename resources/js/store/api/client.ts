import axios from 'axios';

const httpClient = axios.create({
    baseURL: '/api',
    withCredentials: true,
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
    }
})

export default httpClient;
