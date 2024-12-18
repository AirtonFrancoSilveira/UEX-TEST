import React, { useState, useEffect } from 'react';
import api from '../services/api';
import { GoogleMap, LoadScript, Marker } from '@react-google-maps/api';
import '../styles/Dashboard.css';

const DashboardPage = () => {
  const [contacts, setContacts] = useState([]);
  const [filteredContacts, setFilteredContacts] = useState([]);
  const [searchTerm, setSearchTerm] = useState('');
  const [newContact, setNewContact] = useState({
    name: '',
    cpf: '',
    phone: '',
    cep: '',
    address: '',
  });
  const [markers, setMarkers] = useState([]);

  const fetchContacts = async (term = '') => {
    try {
      const response = await api.get(`/contacts?name=${term}`, {
        headers: { Authorization: `Bearer ${localStorage.getItem('token')}` },
      });
      setContacts(response.data);
      setFilteredContacts(response.data);
      updateMarkers(response.data);
    } catch (error) {
      console.error('Erro ao buscar contatos:', error);
    }
  };

  const updateMarkers = (contacts) => {
    const newMarkers = contacts.map((contact) => ({
      lat: parseFloat(contact.latitude),
      lng: parseFloat(contact.longitude),
    }));
    setMarkers(newMarkers);
  };

  const handleAddContact = async () => {
    try {
      await api.post('/contacts', newContact, {
        headers: { Authorization: `Bearer ${localStorage.getItem('token')}` },
      });
      fetchContacts();
    } catch (error) {
      console.error('Erro ao adicionar contato:', error);
    }
  };

  const handleDeleteContact = async (id) => {
    try {
      await api.delete(`/contacts/${id}`, {
        headers: { Authorization: `Bearer ${localStorage.getItem('token')}` },
      });
      fetchContacts();
    } catch (error) {
      console.error('Erro ao excluir contato:', error);
    }
  };

  const handleSearch = (e) => {
    const term = e.target.value;
    setSearchTerm(term);
    fetchContacts(term);
  };

  useEffect(() => {
    fetchContacts();
  }, []);

  return (
    <div className="dashboard-container">
      <div className="row">
        {/* Lista de Contatos e Formulário */}
        <div className="col-md-4 contact-list">
          <h4>Contatos</h4>
          <input
            type="text"
            placeholder="Buscar contato"
            className="form-control mb-2"
            value={searchTerm}
            onChange={handleSearch}
          />

          {/* Formulário de Cadastro */}
          <div>
            <input
              type="text"
              placeholder="Nome"
              className="form-control mb-2"
              onChange={(e) => setNewContact({ ...newContact, name: e.target.value })}
            />
            <input
              type="text"
              placeholder="CPF"
              className="form-control mb-2"
              onChange={(e) => setNewContact({ ...newContact, cpf: e.target.value })}
            />
            <input
              type="text"
              placeholder="Telefone"
              className="form-control mb-2"
              onChange={(e) => setNewContact({ ...newContact, phone: e.target.value })}
            />
            <input
              type="text"
              placeholder="CEP"
              className="form-control mb-2"
              onChange={(e) => setNewContact({ ...newContact, cep: e.target.value })}
            />
            <input
              type="text"
              placeholder="Endereço"
              className="form-control mb-2"
              onChange={(e) => setNewContact({ ...newContact, address: e.target.value })}
            />
            <button className="btn btn-primary w-100 mb-3" onClick={handleAddContact}>
              Adicionar Contato
            </button>
          </div>

          {/* Lista de Contatos */}
          <ul className="list-group">
            {filteredContacts.map((contact) => (
              <li key={contact.id} className="list-group-item d-flex justify-content-between align-items-center">
                <div>
                  <strong>{contact.name}</strong>
                  <p className="small mb-0">{contact.address}</p>
                </div>
                <button className="btn btn-danger btn-sm" onClick={() => handleDeleteContact(contact.id)}>
                  Excluir
                </button>
              </li>
            ))}
          </ul>
        </div>

        {/* Mapa */}
        <div className="col-md-8">
          <LoadScript googleMapsApiKey="AIzaSyDwGVqaa7SZIjvWFFNCm8Y7GvDrKIcDA3Y">
            <GoogleMap mapContainerStyle={{ height: '100vh', width: '100%' }} center={{ lat: -23.55052, lng: -46.633308 }} zoom={6}>
              {markers.map((marker, index) => (
                <Marker key={index} position={marker} />
              ))}
            </GoogleMap>
          </LoadScript>
        </div>
      </div>
    </div>
  );
};

export default DashboardPage;
