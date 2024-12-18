import axios from 'axios';

const api = axios.create({
  baseURL: 'http://localhost:8000/api',
  headers: { Accept: 'application/json', 'Content-Type': 'application/json' },
});

// Adiciona o token a todas as requisições
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
    console.log('Token enviado:', token); // Log para depuração
  } else {
    console.warn('Token JWT não encontrado');
  }
  return config;
});

export default api;
