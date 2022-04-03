import Dashboard from './pages/dashboard';
import Products from './pages/products';
import Observe from './pages/observe';
import Users from './pages/users';
import Roles from './pages/roles';
import AssignRole from './pages/assignRole';
import Ad from './pages/ad';
import Slider from './pages/slider';
import Information from './pages/information';
import Login from './pages/login';
import forgetPassword from './pages/forgetPassword';
import resetPassword from './pages/resetPassword';
import createProduct from './pages/createProduct';
import editProduct from './pages/editProduct';
import productDetails from './pages/productDetails';
import notFound from './components/notFound';

export const routes =
[
    {
        path: '/',
        component: Information,
        name:'اطلاعات کاربری'
    },
    {
        path: '/dashboard',
        component: Dashboard,
        name:'داشبورد'
    },
    {
        path: '/products',
        component: Products,
        name:'محصولات'
    },
    {
        path: '/panelusers',
        component: Users,
        name:'کاربران پنل'
    },
    {
        path: '/roles',
        component: Roles,
        name:'نفش ها'
    },
    {
        path: '/ads',
        component: Ad,
        name:'تبلیغات'
    },
    {
        path: '/slider',
        component: Slider,
        name:'اسلایدر'
    },
    {
        path: '*',
        component: notFound,
        name: 'notfound'

    },
    {
        path: '/login',
        component: Login,
        name:'login'
    },
    {
        path: '/forget-password',
        component: forgetPassword,
        name:'forgetpassword'
    },
    {
        path: '/reset-password',
        component: resetPassword,
        name:'resetpassword'
    },
    {
        path: '/products/create',
        component: createProduct,
        name:'create-product'
    },
    {
        path: '/products/edit:id',
        component: editProduct,
        name:'edit-product'
    },
    {
        path: '/products/details:id',
        component: productDetails,
        name:'product-details'
    },
    // unsubmitted pages
    {
        path: '/observeproduct',
        component: Observe,
        name:'observeproduct'
    },
    {
        path: '/assignrole/:id',
        component: AssignRole,
        name:'تعیین دسترسی'
    },
];
