import { Outlet } from "react-router-dom";
import NavigasiComponent from "./components/navigasi";
import icFb from "./assets/image/ic_facebook.png";
import icUd from "./assets/image/ic_udemy.png";
import icIg from "./assets/image/ic_instagram.png";
import { Tooltip } from "@primer/react";
function App() {
  return (
    <div className="App relative">
      <div className="fixed top-0 left-0 right-0 bg-white z-10">
        <NavigasiComponent />
      </div>
      <Outlet></Outlet>
      <div className="text-center w-full mb-8 mt-24">
        <div className="flex justify-center gap-x-3">
          <Tooltip aria-label="My Facebook">
            <img className="w-5 h-5 cursor-pointer" src={icFb} alt={icFb} />
          </Tooltip>
          <Tooltip aria-label="My Course">
            <img className="w-5 h-5 cursor-pointer" src={icUd} alt={icUd} />
          </Tooltip>
          <Tooltip aria-label="Find me">
            <img className="w-5 h-5 cursor-pointer" src={icIg} alt={icIg} />
          </Tooltip>
        </div>
        <p className="text-xs text-slate-400 mt-4">
          Powered By Angeline Universe
        </p>
      </div>
    </div>
  );
}

export default App;
