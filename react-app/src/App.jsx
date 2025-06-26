import Header from "./components/header";
import HomePage from "./components/home";
import { BrowserRouter,Route, Routes } from "react-router-dom";
import RegisterPage from "./components/register";
import { AuthProvider } from "./contexts/context";


function App() {

  return (
   
    <div className="relative">
       <AuthProvider>
      <Header />
      <BrowserRouter>
        <Routes>
          <Route path="/login" element={<HomePage />} />
           <Route path="/" element={<HomePage/>} />
          <Route path="/Register" element={<RegisterPage />} />
        </Routes>
      </BrowserRouter>

      </AuthProvider>
    </div>
  );
}


export default App;
