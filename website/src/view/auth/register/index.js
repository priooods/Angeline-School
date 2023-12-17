import { useEffect, useState } from "react";
import logo from "../../../assets/image/logo.svg";
import { callRegister, getFormRegister } from "./service";
import { Button, FormControl, TextInput, Spinner, Flash } from "@primer/react";
function IndexRegister() {
  const [form, setForm] = useState(null);
  const [register, setRegister] = useState(null);
  const [loading, setLoading] = useState(false);
  const [input, setInput] = useState(null);

  useEffect(() => {
    getFormRegister((res) => {
      setForm(res.data?.response_data);
      res.data?.response_data.forEach((item) => {
        const items = Object.keys(item);
        items.forEach(function (key) {
          if (key === item["name"]) {
            setInput((prev) => ({ ...prev, [key]: item[key] }));
          }
        });
      });
    });
  }, []);

  const onChange = (event) => {
    setInput({ ...input, [event?.target.name]: event?.target.value });
  };

  function createAccount() {
    setLoading(true);
    setRegister(null);
    callRegister(input, (res) => {
      setRegister(res);
      setLoading(false);
    });
  }

  return (
    <div>
      <img
        src={logo}
        alt={logo}
        className=" h-12 mx-auto text-center mt-2 mb-14 cursor-pointer"
      />
      <div className="md:w-4/12 mx-auto">
        <p className="font-semibold text-2xl font-rubiksemibold">
          Buat Akun Baru
        </p>
        <p className="text-sm font-rubikregular">
          Pastikan kamu memasukan informasi yang benar
        </p>
        <div className="md:mt-14 font-rubikregular text-xs">
          {form?.map((item) => (
            <div key={item.name} className="mt-4">
              <p className="mb-1">
                {item.label}{" "}
                <span className="text-red-500">{item.required ? "*" : ""}</span>
              </p>
              <FormControl>
                <FormControl.Label visuallyHidden></FormControl.Label>
                <TextInput
                  {...item}
                  block
                  contrast
                  value={input[item.name]}
                  aria-label={item.label}
                  onChange={onChange}
                />
                {item.name === "password" && input[item.name].length > 7 && (
                  <FormControl.Validation variant="success">
                    Password valid
                  </FormControl.Validation>
                )}
                {item.name === "repassword" &&
                  input[item.name] !== "" &&
                  input[item.name] !== input.password && (
                    <FormControl.Validation variant="error">
                      {item.name} not matching with password
                    </FormControl.Validation>
                  )}
                {item.name === "repassword" &&
                  input[item.name] !== "" &&
                  input[item.name] === input.password && (
                    <FormControl.Validation variant="success">
                      {item.name} matching with password
                    </FormControl.Validation>
                  )}
                <FormControl.Caption>{item.description}</FormControl.Caption>
              </FormControl>
            </div>
          ))}
          {register && (
            <Flash
              variant={register.response_notification.type}
              sx={{
                color: register.response_notification.color,
                marginTop: "20px",
              }}
            >
              <div>
                <p className=" font-rubiksemibold">
                  {register?.response_notification?.title}
                </p>
                <span>{register?.response_notification?.description}</span>
              </div>
            </Flash>
          )}
          <Button
            variant="primary"
            className="ml-auto mt-5"
            aria-disabled="true"
            onClick={createAccount}
          >
            {loading && (
              <div className="flex">
                <div className="my-auto mr-2">
                  <Spinner size="small" />
                </div>
                <span className="my-auto">Mengirim permintaan ...</span>
              </div>
            )}
            {!loading && "Buat akun baru"}
          </Button>
        </div>
      </div>
    </div>
  );
}

export default IndexRegister;
