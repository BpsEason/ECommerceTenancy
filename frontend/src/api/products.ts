import axios from 'axios';

const api = axios.create({
  baseURL: 'http://tenanta.localhost:8000/api/v1',
  headers: {
    'Accept': 'application/json',
  },
});

export const getProducts = () => {
  return api.get('/products');
};
