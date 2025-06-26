import { createContext, useContext, useState, useMemo } from 'react';
import axios from 'axios';

const AuthContext = createContext();

export const useAuth = () => useContext(AuthContext);

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null);
  const [token, setToken] = useState(localStorage.getItem('token') || null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  
  const configureAxios = (token) => {
    if (token) {
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
      axios.defaults.withCredentials = true;
    } else {
      delete axios.defaults.headers.common['Authorization'];
    }
  };

  configureAxios(token);

  const login = async (email, password) => {
    setLoading(true);
    setError(null);
   
    try {
    
      await axios.get('http://localhost:8000/sanctum/csrf-cookie', {
        withCredentials: true
      });

      const response = await axios.post(
        'http://localhost:8000/api/login',
        { email, password },
        {
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          },
          withCredentials: true
        }
      );

     
      localStorage.setItem('token', response.data.token);
      setToken(response.data.token);
      setUser(response.data.user);
      configureAxios(response.data.token);

      return response.data;
    } catch (err) {
      setError(err.response?.data?.message || 'Email ou mot de passe incorrect');
      if (err.response?.data?.errors) {
        return { errors: err.response.data.errors };
      }
      throw err;
    } finally {
      setLoading(false);
    }
  };

 
  const register = async (formData) => {
    setLoading(true);
    setError(null);
    console.log(formData)
    try {
    
      await axios.get('http://localhost:8000/sanctum/csrf-cookie', {
        withCredentials: true
      });

      const response = await axios.post(
        'http://localhost:8000/api/register',
        formData,
        {
          headers: {
            'Content-Type': 'multipart/form-data'
          },
          withCredentials: true
        }
      );

    
      localStorage.setItem('token', response.data.token);
      setToken(response.data.token);
      setUser(response.data.user);
      configureAxios(response.data.token);

      return response.data;
    } catch (err) {
      setError(err.response?.data?.message || 'Erreur lors de l\'inscription');
      if (err.response?.data?.errors) {
        return { errors: err.response.data.errors };
      }
      throw err;
    } finally {
      setLoading(false);
    }
  };

  
  // const logout = async () => {
  //   try {
  //     await axios.post('http://localhost:8000/api/logout', {}, {
  //       headers: {
  //         Authorization: `Bearer ${token}`
  //       },
  //       withCredentials: true
  //     });
  //   } catch (err) {
  //     console.error('Logout error:', err);
  //   } finally {
  //     // Clear storage and state
  //     localStorage.removeItem('token');
  //     setUser(null);
  //     setToken(null);
  //     delete axios.defaults.headers.common['Authorization'];
  //   }
  // };

  // Memoize the context value to prevent unnecessary re-renders
  const value = useMemo(() => ({
    user,
    token,
    loading,
    error,
    login,
    register,
    // logout,
    // isAuthenticated: !!user,
    setUser,
    setToken
  }), [user, token, loading, error]);

  return (
    <AuthContext.Provider value={value}>
      {children}
    </AuthContext.Provider>
  );
};

export default AuthContext  ;