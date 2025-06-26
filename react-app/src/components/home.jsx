import { Link } from "react-router-dom";
import LoginPage from "./login";

export default function HomePage() {
    return (
        <div className="sm:flex sm:flex-col items-center justify-center min-h-screen absolute -top-5 w-full m-4 ">
  <main className="w-full max-w-lg p-6 bg-white rounded-lg shadow-lg">
    <LoginPage />
    <Link to='/Register'>
      <span className="block w-full py-3 mb-6 text-center text-white bg-slate-400 rounded-lg hover:bg-blue-600  font-bold ">
        Demande de stage
      </span>
    </Link>
  </main>
</div>
    );
}

