import React, { useState, useEffect, useContext } from 'react';
import { ArrowLeftCircle, Eye, EyeOff } from 'lucide-react';
import { useNavigate } from 'react-router-dom';
import AuthContext from '../contexts/context';

export default function RegisterPage() {
  const navigate = useNavigate();
  const { register, loading: authLoading, error: authError } = useContext(AuthContext);

  
  const [formData, setFormData] = useState({
    nom: '',
    prenom: '',
    description: '',
    email: '',
    password: '',
    password_confirmation: '',
    telephone: '',
    date_naissance: '',
    date_debut: '',
    date_fin: ''
  });

  const [files, setFiles] = useState({
    image: null,
    cv: null
  });

  const [previews, setPreviews] = useState({
    image: null
  });

  const [errors, setErrors] = useState({});
  const [showPassword, setShowPassword] = useState(false);
  const [showConfirmPassword, setShowConfirmPassword] = useState(false);
  const [generalError, setGeneralError] = useState('');

  
  const formatDate = (date) => {
    return date.toISOString().split('T')[0];
  };

  
  useEffect(() => {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('date_debut').min = today;
  }, []);

  // Gestion des changements de champs
  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({ ...prev, [name]: value }));
    
    // Clear error when typing
    if (errors[name]) {
      setErrors(prev => ({ ...prev, [name]: null }));
    }
  };

  // Gestion des fichiers
  const handleFileChange = (e) => {
    const { name, files: fileList } = e.target;
    if (!fileList[0]) return;

    const file = fileList[0];
    const newErrors = { ...errors };

    // Validation selon le type de fichier
    if (name === 'image') {
      if (file.size > 2 * 1024 * 1024) {
        newErrors.image = 'L\'image ne doit pas dépasser 2 Mo';
      } else if (!['image/jpeg', 'image/png', 'image/jpg', 'image/gif'].includes(file.type)) {
        newErrors.image = 'Format d\'image non valide';
      } else {
        newErrors.image = null;
        const reader = new FileReader();
        reader.onload = () => setPreviews(prev => ({ ...prev, image: reader.result }));
        reader.readAsDataURL(file);
      }
    } else if (name === 'cv') {
      if (file.size > 5 * 1024 * 1024) {
        newErrors.cv = 'Le CV ne doit pas dépasser 5 Mo';
      } else if (!['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'].includes(file.type)) {
        newErrors.cv = 'Format de CV non valide';
      } else {
        newErrors.cv = null;
      }
    }

    setErrors(newErrors);
    if (!newErrors[name]) {
      setFiles(prev => ({ ...prev, [name]: file }));
    }
  };

  // Handlers spécifiques pour les fichiers
  const handleImageChange = (e) => {
    handleFileChange(e);
  };

  const handleCvChange = (e) => {
    handleFileChange(e);
  };

  // Toggle password visibility
  const togglePasswordVisibility = () => {
    setShowPassword(!showPassword);
  };

  const toggleConfirmPasswordVisibility = () => {
    setShowConfirmPassword(!showConfirmPassword);
  };

  // Validation du formulaire
  const validateForm = () => {
    const newErrors = {};
    let isValid = true;

    // Validation des champs requis
    const requiredFields = ['nom', 'prenom', 'email', 'date_naissance', 'date_debut', 'date_fin', 'telephone'];
    requiredFields.forEach(field => {
      if (!formData[field]) {
        newErrors[field] = 'Ce champ est requis';
        isValid = false;
      }
    });

    // Validation email
    if (formData.email && !/\S+@\S+\.\S+/.test(formData.email)) {
      newErrors.email = 'Email non valide';
      isValid = false;
    }

    // Validation mot de passe
    if (formData.password.length < 8) {
      newErrors.password = '8 caractères minimum';
      isValid = false;
    }

    if (formData.password !== formData.password_confirmation) {
      newErrors.password_confirmation = 'Les mots de passe ne correspondent pas';
      isValid = false;
    }

    // Validation des dates
    if (formData.date_fin && formData.date_debut && formData.date_fin <= formData.date_debut) {
      newErrors.date_fin = 'Doit être après la date de début';
      isValid = false;
    }

    // Validation des fichiers
    if (!files.image) {
      newErrors.image = 'Image requise';
      isValid = false;
    }

    if (!files.cv) {
      newErrors.cv = 'CV requis';
      isValid = false;
    }

    setErrors(newErrors);
    return isValid;
  };

  // Soumission du formulaire
  const handleSubmit = async (e) => {
    e.preventDefault();
    setErrors({});
    setGeneralError('');

    if (!validateForm()) return;

    try {
      const formDataToSend = new FormData();
      
      // Ajout des champs texte
      Object.entries(formData).forEach(([key, value]) => {
        if (value) formDataToSend.append(key, value);
      });

      // Ajout des fichiers
      Object.entries(files).forEach(([key, file]) => {
        if (file) formDataToSend.append(key, file);
      });

      const response = await register(formDataToSend);
      
      if (response?.redirect_url) {
        window.location.href = response.redirect_url;
      } else {
        navigate('/');
      }
    } catch (err) {
      console.error("Registration error:", err);
      if (err.response?.data?.errors) {
        setErrors(err.response.data.errors);
      } else if (err.message) {
        setGeneralError(err.message);
      } else {
        setGeneralError('Une erreur est survenue lors de l\'inscription');
      }
    }
  };

  
  const {
    nom,
    prenom,
    description,
    email,
    password,
    password_confirmation,
    telephone,
    date_naissance,
    date_debut,
    date_fin
  } = formData;

  return (
    <div className="w-full max-w-2xl mx-auto my-16 px-4 p-6 md:p-8 shadow-sm relative">
      <button
        type="button"
        onClick={() => navigate('/')}
        className='absolute top-2 left-2 text-3xl p-3 text-blue-500'
      >
        <ArrowLeftCircle size={30} />
      </button>

      {generalError && (
        <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
          {generalError}
        </div>
      )}

      <h2 className="text-xl font-semibold text-center mb-6">Veuillez remplir le formulaire ci-dessous :</h2>
      <form 
        className="bg-white rounded-xl border border-gray-200 p-6 md:p-8 shadow-sm lg:p-10"
        onSubmit={handleSubmit}
        encType="multipart/form-data"
      >
        <div className="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
          {/* Nom */}
          <div className="mb-4">
            <label className="block text-gray-700 text-sm font-medium mb-2" htmlFor="nom">
              Nom *
            </label>
            <input
              type="text"
              id="nom"
              name="nom"
              className={`w-full px-3 py-2 border ${errors.nom ? 'border-red-500' : 'border-gray-300'} rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent`}
              value={nom}
              onChange={handleInputChange}
              required
            />
            {errors.nom && <p className="text-red-500 text-xs mt-1">{errors.nom}</p>}
          </div>

          {/* Prénom */}
          <div className="mb-4">
            <label className="block text-gray-700 text-sm font-medium mb-2" htmlFor="prenom">
              Prénom *
            </label>
            <input
              type="text"
              id="prenom"
              name="prenom"
              className={`w-full px-3 py-2 border ${errors.prenom ? 'border-red-500' : 'border-gray-300'} rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent`}
              value={prenom}
              onChange={handleInputChange}
              required
            />
            {errors.prenom && <p className="text-red-500 text-xs mt-1">{errors.prenom}</p>}
          </div>
        </div>

        {/* Date de naissance */}
        <div className="mb-4">
          <label className="block text-gray-700 text-sm font-medium mb-2" htmlFor="date_naissance">
            Date de Naissance *
          </label>
          <input
            type="date"
            id="date_naissance"
            name="date_naissance"
            className={`w-full px-3 py-2 border ${errors.date_naissance ? 'border-red-500' : 'border-gray-300'} rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent`}
            value={date_naissance}
            onChange={handleInputChange}
            required
          />
          {errors.date_naissance && <p className="text-red-500 text-xs mt-1">{errors.date_naissance}</p>}
        </div>

        {/* Email */}
        <div className="mb-4">
          <label className="block text-gray-700 text-sm font-medium mb-2" htmlFor="email">
            Email *
          </label>
          <input
            type="email"
            id="email"
            name="email"
            className={`w-full px-3 py-2 border ${errors.email ? 'border-red-500' : 'border-gray-300'} rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent`}
            value={email}
            onChange={handleInputChange}
            required
          />
          {errors.email && <p className="text-red-500 text-xs mt-1">{errors.email}</p>}
        </div>

        {/* Image avec aperçu */}
        <div className="mb-4">
          <label className="block text-gray-700 text-sm font-medium mb-2" htmlFor="image">
            Image * (JPEG, PNG, JPG, GIF, max 2Mo)
          </label>
          <input
            type="file"
            id="image"
            name="image"
            accept="image/jpeg,image/png,image/jpg,image/gif"
            className={`w-full px-3 py-2 border ${errors.image ? 'border-red-500' : 'border-gray-300'} rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100`}
            onChange={handleImageChange}
            required
          />
          {errors.image && <p className="text-red-500 text-xs mt-1">{errors.image}</p>}

          {/* Aperçu de l'image */}
          {previews.image && (
            <div className="mt-3 relative">
              <img
                src={previews.image}
                alt="Aperçu de l'image"
                className="w-40 h-40 object-cover rounded-md border border-gray-300"
              />
              <button
                type="button"
                onClick={() => {
                  setPreviews(prev => ({ ...prev, image: null }));
                  setFiles(prev => ({ ...prev, image: null }));
                }}
                className="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs"
                aria-label="Supprimer l'image"
              >
                ×
              </button>
            </div>
          )}
        </div>

        {/* Téléphone */}
        <div className="mb-4">
          <label className="block text-gray-700 text-sm font-medium mb-2" htmlFor="telephone">
            Téléphone *
          </label>
          <input
            type="tel"
            id="telephone"
            name="telephone"
            className={`w-full px-3 py-2 border ${errors.telephone ? 'border-red-500' : 'border-gray-300'} rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent`}
            value={telephone}
            onChange={handleInputChange}
            required
            placeholder="+212 0 00 00 00 00"
          />
          {errors.telephone && <p className="text-red-500 text-xs mt-1">{errors.telephone}</p>}
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
          {/* Date de Début */}
          <div className="mb-4">
            <label className="block text-gray-700 text-sm font-medium mb-2" htmlFor="date_debut">
              Date de Début * (À partir d'aujourd'hui)
            </label>
            <input
              type="date"
              id="date_debut"
              name="date_debut"
              className={`w-full px-3 py-2 border ${errors.date_debut ? 'border-red-500' : 'border-gray-300'} rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent`}
              value={date_debut}
              onChange={handleInputChange}
              min={formatDate(new Date())}
              required
            />
            {errors.date_debut && <p className="text-red-500 text-xs mt-1">{errors.date_debut}</p>}
          </div>

          {/* Date de Fin */}
          <div className="mb-4">
            <label className="block text-gray-700 text-sm font-medium mb-2" htmlFor="date_fin">
              Date de Fin *
            </label>
            <input
              type="date"
              id="date_fin"
              name="date_fin"
              className={`w-full px-3 py-2 border ${errors.date_fin ? 'border-red-500' : 'border-gray-300'} rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent`}
              value={date_fin}
              onChange={handleInputChange}
              min={date_debut || formatDate(new Date())}
              required
            />
            {errors.date_fin && <p className="text-red-500 text-xs mt-1">{errors.date_fin}</p>}
          </div>
        </div>

        {/* CV */}
        <div className="mb-4">
          <label className="block text-gray-700 text-sm font-medium mb-2" htmlFor="cv">
            CV * (PDF, DOC, DOCX, max 5Mo)
          </label>
          <input
            type="file"
            id="cv"
            name="cv"
            accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
            className={`w-full px-3 py-2 border ${errors.cv ? 'border-red-500' : 'border-gray-300'} rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100`}
            onChange={handleCvChange}
            required
          />
          {errors.cv && <p className="text-red-500 text-xs mt-1">{errors.cv}</p>}
          {files.cv && <p className="text-xs mt-1 text-gray-500">Fichier sélectionné: {files.cv.name}</p>}
        </div>

        {/* Description */}
        <div className="mb-4">
          <label className="block text-gray-700 text-sm font-medium mb-2" htmlFor="description">
            Description
          </label>
          <textarea
            id="description"
            name="description"
            className={`w-full px-3 py-2 border ${errors.description ? 'border-red-500' : 'border-gray-300'} rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent h-32`}
            value={description}
            onChange={handleInputChange}
            maxLength={1000}
          />
          {errors.description && <p className="text-red-500 text-xs mt-1">{errors.description}</p>}
          <p className="text-xs text-gray-500 mt-1">{description.length}/1000 caractères</p>
        </div>

        {/* Créer mot de passe */}
        <div className="mb-4">
          <label className="block text-gray-700 text-sm font-medium mb-2" htmlFor="password">
            Créer un mot de passe * (8 caractères minimum)
          </label>
          <div className="relative">
            <input
              type={showPassword ? "text" : "password"}
              id="password"
              name="password"
              className={`w-full px-3 py-2 border ${errors.password ? 'border-red-500' : 'border-gray-300'} rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent`}
              value={password}
              onChange={handleInputChange}
              required
              minLength={8}
            />
            <button
              type="button"
              className="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none"
              onClick={togglePasswordVisibility}
              aria-label={showPassword ? "Masquer le mot de passe" : "Afficher le mot de passe"}
            >
              {showPassword ? <EyeOff size={20} /> : <Eye size={20} />}
            </button>
          </div>
          {errors.password && <p className="text-red-500 text-xs mt-1">{errors.password}</p>}
        </div>

        {/* Valider mot de passe */}
        <div className="mb-6">
          <label className="block text-gray-700 text-sm font-medium mb-2" htmlFor="password_confirmation">
            Valider le mot de passe *
          </label>
          <div className="relative">
            <input
              type={showConfirmPassword ? "text" : "password"}
              id="password_confirmation"
              name="password_confirmation"
              className={`w-full px-3 py-2 border ${errors.password_confirmation ? 'border-red-500' : 'border-gray-300'} rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent`}
              value={password_confirmation}
              onChange={handleInputChange}
              required
            />
            <button
              type="button"
              className="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none"
              onClick={toggleConfirmPasswordVisibility}
              aria-label={showConfirmPassword ? "Masquer le mot de passe" : "Afficher le mot de passe"}
            >
              {showConfirmPassword ? <EyeOff size={20} /> : <Eye size={20} />}
            </button>
          </div>
          {errors.password_confirmation && <p className="text-red-500 text-xs mt-1">{errors.password_confirmation}</p>}
        </div>

        {/* Bouton de soumission */}
        <div className="flex items-center justify-center">
          <button
            type="submit"
            disabled={authLoading}
            className={`px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors duration-200 ${
              authLoading ? 'opacity-75 cursor-not-allowed' : ''
            }`}
          >
            {authLoading ? 'Envoi en cours...' : 'Envoyer ma demande'}
          </button>
        </div>
      </form>
    </div>
  );
}