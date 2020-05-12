
import layout_routes_admin from './layout_admin';
import layout_routes_site from './layout_site';


var getUrl = window.location;
// var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
var prefix = getUrl.pathname.split('/')[2];

const routes = [{
    path: '/admin/dashboard',
    component: resolve => require(['src/layout'], resolve),
    children: layout_routes_admin
    }, {
    path: '/dashboard',
    component: resolve => require(['site/layout'], resolve),
    children: layout_routes_site
    }, {
        path: '/admin',
        component: resolve => require(['pages/login'], resolve),
        meta: {
            title: "Login",
            isNotLoggedIn: true,
        }
    }, 
    // {
    //     path: '/',
    //     component: resolve => require(['site/blank'], resolve),
    //     meta: {
    //         title: "Site",
    //         forSite: true,
    //     }
    // }, 
    {
        path: '/register',
        component: resolve => require(['pages/register'], resolve),
        meta: {
            title: "register",
            isNotLoggedIn: true,
        }
    }, {
        path: '/forgotpassword',
        component: resolve => require(['pages/forgotpassword'], resolve),
        meta: {
            title: "Forgot Password",
            isNotLoggedIn: true,
        }
    }, {
        path: '/reset_password',
        component: resolve => require(['pages/reset_password'], resolve),
        meta: {
            title: "Reset Password",
            isNotLoggedIn: true,
        }
    }, {
        path: '/500',
        component: resolve => require(['pages/500'], resolve),
        meta: {
            title: "500",
            isNotLoggedIn: true,
        }
    },
    {
        path: '*',
        component: resolve => require(['pages/404'], resolve),
        meta: {
            title: "404",
            isNotLoggedIn: true,
        }
    }]


    if (prefix == '') {
        var loginpath = {
        path: '/admin',
        component: resolve => require(['pages/login'], resolve),
        meta: {
            title: "Login",
            isNotLoggedIn: true,
        }
    }
    routes.push(loginpath);
    } else if (prefix != '') {
        var loginpath = {
            path: '/locations',
            component: resolve => require(['site/locations'], resolve),
            meta: {
                title: "Locations",
                forSite: true,
            }
        }
        routes.push(loginpath);
    }
export default routes