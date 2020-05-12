window.axios = require('axios')
//import axios from 'axios';
import Vue from 'vue'
import App from './App'
import router from './router'
import store from './store/store.js'
import VueAnalytics from 'vue-analytics'
import Auth from './Auth.js'
import Global from './global.js';
Vue.use(Auth);
Vue.use(Global);

const EventBus = new Vue();
window.EventBus = EventBus;

var getUrl = window.location; 
var baseUrl = getUrl.protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
var prefix = getUrl.pathname.split('/')[2];
// alert(baseUrl);
var instance = window.axios.create({
  baseURL: baseUrl+'/api/'
});
// Alter defaults after instance has been created
instance.defaults.headers.common['Authorization'] = 'Bearer '+Vue.auth.getCookie("token");
instance.defaults.headers.common['Accept'] = 'application/json';
instance.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
instance.defaults.headers.common['Access-Control-Allow-Origin'] = true;

axios.defaults.headers.common['Authorization'] = 'Bearer '+Vue.auth.getCookie("token");
axios.defaults.headers.common['Accept'] = 'application/json';
// console.log(instance.defaults.headers.common['Authorization']);
// Google Analytics
const google_analytics_key = store.state.google_analytics_key

if (google_analytics_key && google_analytics_key.length) {

    Vue.use(VueAnalytics, {
        id:google_analytics_key,
        router,
        checkDuplicatedScript: true,
        autoTracking: {
            pageviewTemplate(route) {
                return {
                    page: 'default/' + route.path
                }
            }
        }
    })
}


function checkbeforeMoveToRoute(from,to,next){
    console.log(from.path);
    if (from.path == '/') {
       axios.get(store.state.baseUrl + "/api/lang/")
            .then(response => {
                if (response.data.error == false) {
                    store.state.language = response.data.language;
                    axios.get(store.state.baseUrl + '/api/isloggedin')
                    .then(response =>  {
                        console.log("isloggedin checking");
                        if (response.status == 200 && response.data.error == false && Vue.auth.getCookie("token")) {
                            next(); 
                        }else{
                            next({ path: '/admin' });
                        }
                    })
                    .catch(error => {
                        //redirect to login on 401 unauthorized access
                        if (error.response && error.response.status==401) {
                            //redirect to login
                            next({ path: '/admin' });
                        }else{
                            //redirect user to something went wrong page and generate some exception 
                            next({ path: '/admin' });
                        }  
                    })
                }
            })
        
    }else if (to.path != from.path) {
        if (Vue.auth.getCookie("token") ) {
            console.log("Token Existed");
            next();
        }
    }
}

router.beforeEach((to, from, next) => {
    // if (from.path == '/') {
        // Vue.global.setToken();
    // }
    // 
    if(to.path == '/' && from.path == '/' && prefix === ''){
        next( {path:'/admin'}); 
    }
    //If a user is logged in, They should not be able to view pages like login, registration, forget pass
    if (to.matched.some(record => record.meta.isNotLoggedIn)) {
        axios.get(store.state.baseUrl + '/api/isloggedin')
        .then(response =>  {
            if (response.status == 200 && response.data.error == false && Vue.auth.getCookie("token")) {
                if(prefix != '' && to.path != '/admin'){
                    next( {path:'/locations'});
                }else{
                    next( {path:'/admin/dashboard'});
                }
                store.commit("routeChange", "end"); 
            }else{
                //redirect to login
                next();
                store.commit("routeChange", "end");
            }
        })
        .catch(error => {
            //redirect to login on 401 unauthorized access
            if (error.response && error.response.status==401) {
                if(prefix != '' && to.path != '/admin'){
                    next( {path:'/locations'});
                }else if(prefix != ''  && to.path == '/admin'){
                    next();
                }else{
                    next();
                }
                store.commit("routeChange", "end");
            }else{
                //redirect user to something went wrong page and generate some exception 
                next();
            }
        })
    } else if (to.matched.some(record => record.meta.isLoggedIn)) {
        if (Vue.auth.getCookie("token") ) {
            checkbeforeMoveToRoute(from,to,next)
        }else{
            next({path: '/admin'});
            store.commit("routeChange", "end");
        }
    }
    if(prefix != ''){
        if (to.matched.some(record => record.meta.forSite)) {
            next();
        }
    }
    else{
        next();
    }
});

// Remove the productionTip in dev tool console
Vue.config.productionTip = false

new Vue({
    el: '#app',
    router,
    store,
    render: h => h(App),
})