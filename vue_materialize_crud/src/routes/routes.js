import DashboardLayout from "@/pages/Layout/DashboardLayout.vue";

import Dashboard from "@/pages/Dashboard.vue";
import UserProfile from "@/pages/UserProfile.vue";
import TableList from "@/pages/TableList.vue";
import Typography from "@/pages/Typography.vue";
import Icons from "@/pages/Icons.vue";
import Maps from "@/pages/Maps.vue";
import Notifications from "@/pages/Notifications.vue";
import UpgradeToPRO from "@/pages/UpgradeToPRO.vue";
import AuthorList from "@/views/cadastros/author/List.vue";
import authPages from './auth_pages';
import adminRoutes from './admin';
import Auth from './auth';

const routes = [
  {
    path: "/",
    component: DashboardLayout,
    redirect: "/admin/book",
    name: "Home",
  },
  authPages,
  adminRoutes,
  {
          path: "/dashboard",
          component: DashboardLayout,
          beforeEnter: Auth,
          children: [
            {
              path: "",
              name: "Dashboard",
              component: Dashboard
            },
            {
              path: "user",
              name: "User Profile",
              component: UserProfile
            },
            {
              path: "table",
              name: "Table List",
              component: TableList
            },
            {
              path: "typography",
              name: "Typography",
              component: Typography
            },
            {
              path: "icons",
              name: "Icons",
              component: Icons
            },
            {
              path: "maps",
              name: "Maps",
              meta: {
                hideFooter: true
              },
              component: Maps
            },
            {
              path: "notifications",
              name: "Notifications",
              component: Notifications
            },
            {
              path: "upgrade",
              name: "Upgrade to PRO",
              component: UpgradeToPRO
            },
            {
              path: "author",
              name: "Cadastro de author",
              component: AuthorList
            }
          ]
  },

   
];

export default routes;
