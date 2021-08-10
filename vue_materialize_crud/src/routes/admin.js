import DashboardLayout from "@/pages/Layout/DashboardLayout.vue";
import AuthorList from '../views/cadastros/author/List.vue';
import BookList from '../views/cadastros/book/List.vue';
import CategoryList from '../views/cadastros/category/List.vue';
import Auth from './auth';
import Technical_information from '../views/cadastros/technical_information/List.vue';

let adminRoutes = {
    path: "/admin",
    component: DashboardLayout,
    name: "Admin",
    icon: "settings",
    beforeEnter: Auth,
    redirect: "/admin/book",
    children: [
        {
            path: "book",
            name: "Book",
            icon: "book",
            components: { default: BookList }
        },
        {
            path: "author",
            name: "Author",
            icon: "person_pin",
            components: { default: AuthorList }
        },
        {
            path: "category",
            name: "Category",
            icon: "category",
            components: { default: CategoryList }
        },
        {
            path: "tech_info",
            name: "Technical information",
            icon: "category",
            components: { default: Technical_information }
        },
     

    ]
};

export default adminRoutes;