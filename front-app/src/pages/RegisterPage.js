import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import axios from 'axios';
import './Login.css';

const RegisterPage = () => {
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const navigate = useNavigate();

  const handleRegister = async () => {
    try {
      const response = await axios.post('http://localhost:8000/api/register', {
        name,
        email,
        password,
      });

      if (response.status === 200 || response.status === 201) {
        alert('Conta criada com sucesso!');
        navigate('/login');
      }
    } catch (error) {
      console.error('Erro ao criar conta:', error.response?.data || error.message);
      alert('Erro ao criar conta. Verifique os dados e tente novamente.');
    }
  };

  return (
    <div className="login-container d-flex justify-content-center align-items-center vh-100">
      <div className="login-box p-4 rounded shadow">
        <h2 className="text-white mb-4">Create Account</h2>
        <div className="mb-3">
          <input
            type="text"
            className="form-control"
            placeholder="Name"
            value={name}
            onChange={(e) => setName(e.target.value)}
          />
        </div>
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
        <button
          className="btn btn-primary w-100"
          onClick={handleRegister}
        >
          Create Account
        </button>
        <div className="text-center mt-3">
          <a href="/login" className="text-white-50">Back to Login</a>
        </div>
      </div>
    </div>
  );
};

export default RegisterPage;
