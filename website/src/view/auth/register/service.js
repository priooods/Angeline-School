import configuration from "../../../services/configuration";

export const getFormRegister = (callback) => {
  configuration
    .get("/formRegister")
    .then((res) => callback(res))
    .catch((err) => {});
};

export const callRegister = async (data, callback) => {
  await configuration
    .post("/register", data)
    .then((res) => callback(res.data))
    .catch((err) => callback(err.response.data));
};
