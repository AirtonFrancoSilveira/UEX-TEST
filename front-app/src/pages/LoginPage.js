import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import api from '../services/api';
import '../styles/Login.css';

const LoginPage = () => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const navigate = useNavigate();

  const handleLogin = async () => {
    try {
      // Chamada ao backend para login
      const response = await api.post('/login', { email, password });

      // Armazena o token no localStorage
      localStorage.setItem('token', response.data.token);

      // Redireciona para o Dashboard
      navigate('/dashboard');
    } catch (error) {
      alert('Credenciais inválidas. Verifique e tente novamente.');
      console.error(error.response?.data || error.message);
    }
  };

  return (
    <div className="login-container d-flex justify-content-center align-items-center vh-100">
      <div className="login-box p-4 rounded shadow">
        <h2 className="text-white mb-4">Login</h2>
        <div className="mb-3">
          <input
            type="email"
            className="form-control"
            placeholder="Email"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
          />
        </div>
        <div className="mb-3">
          <input
            type="password"
            className="form-control"
            placeholder="Password"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
          />
        </div>
        <button className="btn btn-primary w-100" onClick={handleLogin}>
          Login
        </button>
        <div className="text-center mt-3">
          <a href="/register" className="text-white-50">Create New Account</a>
        </div>
      </div>
    </div>
  );
};

export default LoginPage;
