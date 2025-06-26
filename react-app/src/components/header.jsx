import logo from './logo.png';

export default function Header() {
  return (
    <header className="mb-20 mx-3 p-4 lg:p-5 bg-slate-500 text-gray-900 rounded-lg shadow-md  transition-shadow duration-300">
      <div className="flex lg:pl-20 pl-8 lg:justify-start space-x-7">
        <img 
          src={logo} 
          alt="Logo de l'entreprise"
          className="h-20 w-28  drop-shadow-md  transition-all duration-300 lg:h-24 lg:w-32 " 
        />
        <div className=" lg:block border-l-2 border-slate-500 pl-4 pt-3">
          <h1 className="text-3xl font-bold  text-white">Soft Seven Art</h1>
          <p className="text-base italic">Art in Every Pixel</p>
        </div>
      </div>
    </header>
  );
}