import axios from 'axios';

const api = axios.create({
  baseURL: 'http://tenanta.localhost:8000/api/v1',
  headers: { 'Accept': 'application/json' },
});

export const processPayment = (data: { order_id: string; amount: number; payment_method: string }) => {
  return api.post('/payments', data);
};
