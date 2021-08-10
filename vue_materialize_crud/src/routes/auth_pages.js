import DashboardLayout from "@/pages/Layout/DashboardLayout.vue";
import AuthLayout from "@/pages/Layout/AuthLayout.vue";


import Login from "@/views/auth/Login.vue";
//import Login from "@/pages/Login.vue";
//import Register from "@/pages/Dashboard/Pages/Register.vue";
//import Lock from "@/pages/Dashboard/Pages/Lock.vue";
//import Pricing from "@/pages/Dashboard/Pages/Pricing.vue";

let authPages = {
    path: "/",
    component: AuthLayout,
    name: "Authentication",
    children: [
      {
        path: "/login",
        name: "Login",
        component: Login
      },
      /* {
        path: "/register",
        name: "Register",
        component: Register
      },
      {
        path: "/pricing",
        name: "Pricing",
        component: Pricing
      },
      {
        path: "/lock",
        name: "Lock",
        component: Lock
      }
      */
    ]
  };

  export default authPages;