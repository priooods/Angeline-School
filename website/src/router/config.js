import { createBrowserRouter } from "react-router-dom";
import App from "../App";
import IndexBeranda from "../view/beranda";
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
]);

export default router;
