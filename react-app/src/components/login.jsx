import React, { useContext, useState } from "react";
import { Eye, EyeOff } from "lucide-react";
import AuthContext from "../contexts/context";


export default function LoginPage() {
  const [showPassword, setShowPassword] = useState(false);
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState("");

  const { login, error: authError } = useContext(AuthContext);

  const handlePassword = () => {
    setShowPassword(!showPassword);
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError("");

    try {
      const result = await login(email, password);
      if (result?.redirect_url) {
        window.location.href = `http://localhost:8000${result.redirect_url}`;
      } else {
        window.location.href = "http://localhost:3000";
      }
    }
    catch (err) {
      console.error("Login error:", err);
      setError(authError || "Une erreur s'est produite lors de la connexion");
    }
  };

  return (
    <div className="w-full my-14 px-2">
      <form
        className="relative bg-white rounded-xl border border-gray-200 p-4 md:p-8 shadow-sm max-w-md mx-auto"
        onSubmit={handleSubmit}
        method="post"
      >
        <h2 className="absolute -top-3 px-2 left-1/2 transform -translate-x-1/2 bg-white text-lg font-bold whitespace-nowrap">
          Connexion
        </h2>

        {(error || authError) && (
          <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {error || authError}
          </div>
        )}

        <div className="mb-4">
          <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="email">
            Email
          </label>
          <input
            type="email"
            id="email"
            className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            required
            autoComplete="email"
          />
        </div>

        <div className="mb-6">
          <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="password">
            Mot de passe
          </label>
          <div className="relative">
            <input
              type={showPassword ? "text" : "password"}
              id="password"
              className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              required
              autoComplete="current-password"
            />
            <button
              type="button"
              className="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none"
              onClick={handlePassword}
              aria-label={showPassword ? "Masquer le mot de passe" : "Afficher le mot de passe"}
            >
              {showPassword ? <EyeOff size={20} /> : <Eye size={20} />}
            </button>
          </div>
        </div>

        <div className="flex items-center justify-center pb-6">
          <button
            type="submit"
            className="w-full md:w-auto px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-md transition-colors duration-200"
          >
            Se connecter
          </button>
        </div>
      </form>
    </div>
  );
}