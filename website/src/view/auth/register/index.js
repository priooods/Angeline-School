import logo from "../../../assets/image/logo.svg";
import { TextInput } from "@primer/react";
function IndexRegister() {
  return (
    <div>
      <img
        src={logo}
        alt={logo}
        className=" h-12 mx-auto text-center mt-2 mb-14 cursor-pointer"
      />
      <div className="md:w-2/6 mx-auto">
        <p className="font-semibold text-2xl font-rubiksemibold">
          Buat Akun Baru
        </p>
        <p className="text-sm font-rubikregular">
          Pastikan kamu memasukan informasi yang benar
        </p>
        <div className="md:mt-16 font-rubikregular text-xs">
          <TextInput block placeholder="Masukan email" />
        </div>
      </div>
    </div>
  );
}

export default IndexRegister;
