import './bootstrap';
import { createApp } from 'vue';

import { createRouter, createWebHistory } from 'vue-router'
import App from './App.vue';

const routes = [
    {name: 'home', path: '/', component: () => import('./views/Home.vue')},
];

const pages = Object.entries(import.meta.globEager(`./views/dashboard/**/**/**/*.vue`)); /** */

// console.log(pages);
pages.forEach(([path, moduleDefinition]) => {

     let route = {
      component: moduleDefinition.default || moduleDefinition,
      name: path.replace('./views/dashboard/', '')
                .replace(/\.vue$/, '')
                .replace(/\/(.*?)/g, '-') //changes / to -
                .replace(/{([\w-]+)}/g, '-$1-')
                .replace(/{\[([\w-]+)]\}/g, "-$1-")
                .replace(/\-{2,}/g, '-')
                .replace(/\-$/, '')
                .toLowerCase(),

      path: path.replace('./views/dashboard', '')
            .replace(/\.vue$/, '')
            .replace(/{([\w-]+)}/g, '/:$1/')
            .replace(/{\[([\w-]+)]\}/g, "/:$1?/")
            .replace(/\/{2,}/g, "/")
            .replace(/\/$/, '')
            .toLowerCase(),
     }


     routes.push(route)
})
console.log(import.meta.env.BASE_URL);
const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
})

const app = createApp(App);

import ExampleComponent from './components/ExampleComponent.vue';
app.component('example-component', ExampleComponent);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// Object.entries(import.meta.glob('./**/*.vue', { eager: true })).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });

/**
 * Finally, we will attach the application instance to a HTML element with
 * an "id" attribute of "app". This element is included with the "auth"
 * scaffolding. Otherwise, you will need to add an element yourself.
 */

app.use(router).mount('#app');
