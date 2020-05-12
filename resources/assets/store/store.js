import 'es6-promise/auto'
import Vue from 'vue'
import Vuex from 'vuex'
import mutations from './mutations'

Vue.use(Vuex)

var getUrl = window.location; 
var baseUrl = getUrl.protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
var prefix = getUrl.pathname.split('/')[2];

// if (prefix == '') {
//     var baseUrl = baseUrl;
// } else {
//     var baseUrl = baseUrl + "/" + prefix;
// }

//=======vuex store start===========
const store = new Vuex.Store({
    state: {
        baseUrl:baseUrl, 
       // baseUrl: prefixcomplete,
        prefix : prefix,
        left_open: true,
        preloader: true,
        site_name: "Lumberjaxe",
        page_title: null,
        language:{},
        user: {
            name: "Howdy",
            picture: require("img/authors/lumberjaxe-avatar.png"),
            job: "Project Manager"
        },
        google_analytics_key: null,
        bookingTool : {},
        settings : {
            setting_primary_color: '#24272A',
            setting_secondary_color:'#F5333F',
            setting_text_color: '#3D5500',
            setting_text_bg_color: '#FFFFFF',
            setting_background: '#f2f2f2',
            setting_language: 'en',
            image_url:require("img/lumberjaxe-logo.png")
        }
    },
    mutations
})
//=======vuex store end===========
export default store