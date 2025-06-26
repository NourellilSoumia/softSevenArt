import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { useNavigate } from 'react-router-dom';

function Profile() {
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);
const navigate = useNavigate()

  useEffect(() => {
    const token = localStorage.getItem('token');
    
    if (!token) {
        navigate('/login');
      return;
    }

    const fetchProfile = async () => {
      try {
        const response = await axios.get('http://localhost:8000/api/user', {
          headers: {
            'Authorization': `Bearer ${token}`
          }
        });
        
        setUser(response.data);
        setLoading(false);
      } catch (err) {
        console.error("Erreur lors de la récupération du profil:", err);
        localStorage.removeItem('token');
        navigate('/login');
      }
    };

    fetchProfile();
  }, [navigate]);

  const handleLogout = () => {
    localStorage.removeItem('token');
    navigate('/');
  };

  if (loading) {
    return <div className="text-center mt-10">Chargement...</div>;
  }

  return (
    <div className="max-w-md mx-auto mt-10 p-6 bg-white rounded-lg shadow-md">
      <h2 className="text-2xl font-bold mb-6 text-center">Profil Utilisateur</h2>
      
      <div className="mb-6">
        <p className="text-gray-700"><strong>Nom:</strong> {user.nom}</p>
        <p className="text-gray-700"><strong>Prenom:</strong> {user.prenom}</p>
        <p className="text-gray-700"><strong>Email:</strong> {user.email}</p>
      </div>
      
      <button
        onClick={handleLogout}
        className="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full"
      >
        Se déconnecter
      </button>
    </div>
  );
}

export default Profile;