import { Button } from "@primer/react";
import logo from "../assets/image/logo.svg";
function NavigasiComponent() {
  return (
    <div>
      <div className="md:flex md:px-6 md:py-1 bg-white items-center justify-start w-full">
        <img src={logo} alt={logo} className=" h-14" />
        <div className="grid md:grid-cols-2 mx-auto gap-3 text-xs">
          <span className=" cursor-pointer font-semibold hover:text-color-primary">
            Makanan
          </span>
          <span className=" cursor-pointer font-semibold hover:text-color-primary">
            Tempat Hiburan
          </span>
        </div>
        <div className="grid grid-cols-2 gap-2">
          <Button variant="invisible" size="small">
            Masuk
          </Button>
          <Button variant="primary" size="small">
            Daftar
          </Button>
        </div>
      </div>
    </div>
  );
}

export default NavigasiComponent;
