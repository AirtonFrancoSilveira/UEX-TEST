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
  const [loading, setLoading] = useState(true);

  const fetchContacts = async (term = '') => {
    try {
      const token = localStorage.getItem('token');
      if (!token) {
        console.error('Token não encontrado!');
        setLoading(false);
        return;
      }

      const response = await api.get(`/contacts?name=${term}`, {
        headers: { Authorization: `Bearer ${token}` },
      });

      // Corrige o caminho dos dados paginados
      if (response.data && response.data.data && Array.isArray(response.data.data.data)) {
        const contactsList = response.data.data.data;
        setContacts(contactsList);
        setFilteredContacts(contactsList);
        updateMarkers(contactsList);
      } else {
        console.error('Formato inesperado de dados:', response.data);
      }
    } catch (error) {
      console.error('Erro ao buscar contatos:', error);
    } finally {
      setLoading(false);
    }
  };

  const updateMarkers = (contacts) => {
    // Filtra somente contatos com latitude e longitude válidas
    const newMarkers = contacts
      .filter((contact) => contact.latitude !== null && contact.longitude !== null)
      .map((contact) => ({
        lat: parseFloat(contact.latitude),
        lng: parseFloat(contact.longitude),
        name: contact.name,
        address: contact.address,
      }));
  
    setMarkers(newMarkers); // Atualiza os marcadores no estado
  };

  const formatPhoneNumber = (phone) => {
    // Remove tudo que não for número
    const cleaned = phone.replace(/\D/g, '');
    // Formata no padrão "(XX) XXXXX-XXXX"
    return cleaned.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
  };
  
  
  const handleAddContact = async () => {
    try {
      const token = localStorage.getItem('token');
      if (!token) {
        console.error('Token não encontrado!');
        return;
      }
  
      const payload = {
        name: newContact.name,
        cpf: newContact.cpf,
        phone: formatPhoneNumber(newContact.phone), // Formata o telefone
        cep: newContact.cep,
        address: newContact.address,
      };
  
      const response = await api.post('/contacts', payload, {
        headers: { Authorization: `Bearer ${token}` },
      });
  
      console.log('Contato cadastrado com sucesso!', response.data);
      fetchContacts(); // Recarrega os contatos
    } catch (error) {
      console.error('Erro ao adicionar contato:', error.response?.data || error.message);
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

  useEffect(() => {
    const token = localStorage.getItem('token');
    if (!token) {
      console.error('Token JWT ausente. Redirecionando para login...');
      window.location.href = '/login';
    } else {
      fetchContacts();
    }
  }, []);

  return (
    <div className="dashboard-container">
      <div className="row">
        <div className="col-md-4 contact-list">
          <h4>Contatos</h4>
          <input
            type="text"
            placeholder="Buscar contato"
            className="form-control mb-2"
            value={searchTerm}
            onChange={(e) => {
              setSearchTerm(e.target.value);
              fetchContacts(e.target.value);
            }}
          />

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

          {loading ? (
            <p>Carregando contatos...</p>
          ) : filteredContacts.length > 0 ? (
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
          ) : (
            <p>Nenhum contato encontrado.</p>
          )}
        </div>

        <div className="col-md-8">
          <LoadScript googleMapsApiKey="SEU TOKEN AQUI">
          <GoogleMap
            mapContainerStyle={{ height: '100vh', width: '100%' }}
            center={{ lat: -23.55052, lng: -46.633308 }} // Localização padrão do mapa
            zoom={6} // Zoom padrão
            >
            {markers.map((marker, index) => (
                <Marker
                key={index}
                position={{ lat: marker.lat, lng: marker.lng }}
                label={marker.name} // Nome do contato no marcador
                onClick={() => alert(`Contato: ${marker.name}\nEndereço: ${marker.address}`)}
                />
            ))}
            </GoogleMap>
          </LoadScript>
        </div>
      </div>
    </div>
  );
};

export default DashboardPage;
