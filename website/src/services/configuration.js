import axios from "axios";
const configuration = axios.create({
  baseURL: "http://localhost:8000/api",
  timeout: 6000,
});

configuration.interceptors.response.use(
  function (response) {
    return response;
  },
  function (error) {
    return Promise.reject(error);
  }
);

export default configuration;
