import Vue from 'vue';
import Router from 'vue-router';
import store from './store.js';

import Login from './components/pages/auth/Login.vue';
import Register from './components/pages/auth/Register.vue';
import Dashboard from './components/pages/Dashboard.vue';
import NotFound from './components/pages/NotFound.vue';

Vue.use(Router);

const router = new Router({
    mode: 'history',
    routes: [
    {
        path: '/',
        name: 'home',
        redirect: '/login'
    },
    {
        path: '/login',
        component: Login,
        name: 'login'
    },
    {
        path: '/register',
        component: Register,
        name: 'register'
    },
    {
        path: '/dashboard',
        component: Dashboard,
        name: 'dashboard'
    },
    {
        path: '/:notFound(.*)',
        component: NotFound
    },
    ] 
});

router.beforeEach((to, from, next) => {
    if (store.getters.auth.loggedIn === null) {
        axios.get('api/authenticated').then(() => {
            if (to.name === 'login' || to.name === 'register') {
                store.getters.auth.loggedIn = true;
                return next({ name: 'dashboard' })
            } else {
                store.getters.auth.loggedIn = false;
                next();
            }
        }).catch(() => {
            if (to.name === 'dashboard') {
                store.getters.auth.loggedIn = false;
                return next({ name: 'login' })
            } else {
                store.getters.auth.loggedIn = true;
                next();
            }
        });
    } else {
        next();
    }
});

export default router;

// export default {
//     mode: 'history',
//     routes: [
//     {
//         path: '/',
//         name: 'home',
//         redirect: '/login'
//     },
//     {
//         path: '/login',
//         component: Login,
//         name: 'login'
//     },
//     {
//         path: '/register',
//         component: Register,
//         name: 'register'
//     },
//     {
//         path: '/dashboard',
//         component: Dashboard,
//         name: 'dashboard'
//     },
//     {
//         path: '/:notFound(.*)',
//         component: NotFound
//     },
//     ]
// };