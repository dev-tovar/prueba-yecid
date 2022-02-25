/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import '@mdi/font/css/materialdesignicons.css' // Ensure you are using css-loader

import Vue from 'vue'
import Vuetify from 'vuetify'
import VueRouter from 'vue-router'
import 'vuetify/dist/vuetify.min.css'
import 'animate.css';

window.Vue = require('vue');

Vue.use(Vuetify)
Vue.use(VueRouter)

Vue.component('home-component', require('./components/HomeComponent.vue').default);
Vue.component('autores-component', require('./components/AutoresComponent.vue').default);
Vue.component('editoriales-component', require('./components/EditorialesComponent.vue').default);
Vue.component('curriculum-component', require('./components/CurriculumComponent.vue').default);

let home_component = {
    template: `<home-component></home-component>`
}
let autores_component = {
    template: `<autores-component></autores-component>`
}
let editoriales_component = {
    template: `<editoriales-component></editoriales-component>`
}
let curriculum_component = {
    template: `<curriculum-component></curriculum-component>`
}

const router = new VueRouter({
    routes: [
        {
            path: '/admin-libros',
            name: 'home_component',
            component: home_component
        },
        {
            path: '/',
            name: 'autores_component',
            component: autores_component
        },
        {
            path: '/admin-editoriales',
            name: 'editoriales_component',
            component: editoriales_component
        },
        {
            path: '/curriculum_yecid_tovar',
            name: 'curriculum',
            component: curriculum_component
        },
    ],
    mode: 'history'
})


const app = new Vue({
    router,
    vuetify: new Vuetify({
        iconfont: 'mdi',
        theme: {
            themes: {
                light: {
                    primary: '#1976D2',
                    secondary: '#424242',
                    accent: '#82B1FF',
                    error: '#FF5252',
                    info: '#2196F3',
                    success: '#4CAF50',
                    warning: '#FFC107'
                }
            }
        },
    }),
    el: '#app',
    vuetify: new Vuetify(),
    data: () => ({
        drawer: true,
        itemsBar: [
     
            {
                icon: "mdi-feather",
                text: "Gestión de autores",
                ruta: "/",
            },
            {
                icon: "mdi-book-open",
                text: "Gestión de editoriales",
                ruta: "/admin-editoriales",
            },
            {
                icon: "mdi-book-open-page-variant",
                text: "Gestión de obras literarias",
                ruta: "/admin-libros",
            },
            /* color: '#c12120' */
        ],
    }),
    beforeMount() {
        document.getElementById("loadOverlay").style.display = "none";
    },
    methods: {
        clickToggleDrawer() {
            this.drawer = !this.drawer
            
        },
    }

});