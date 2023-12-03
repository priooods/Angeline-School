import { createBrowserRouter } from "react-router-dom";
import App from "../App";
import IndexBeranda from "../view/beranda";
import IndexRegister from "../view/auth/register";
const router = createBrowserRouter([
  {
    path: "/",
    element: <App />,
    children: [
      {
        path: "/",
        element: <IndexBeranda />,
      },
    ],
  },
  {
    path: "/register",
    element: <IndexRegister />,
  },
]);

export default router;
