import './bootstrap';
import { createApp } from 'vue';
import { createPinia } from 'pinia'
import { createRouter, createWebHistory } from 'vue-router'
import App from './App.vue';
import { User } from './stores/user';


const pinia = createPinia();

const routes = [
    {name: 'index', path: '/', component: () => import('./views/Home.vue')},
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
      meta: moduleDefinition.default?.meta
     }

     routes.push(route)
})

// console.log(routes)
const router = createRouter({
  history: createWebHistory(),
  routes
})


router.beforeEach(to => {
    const user = User();
    const isLoggedIn = user.isLogged;


if (user.canNavigate(to)) {
    if (to.meta.redirectIfLoggedIn && isLoggedIn)
      return '/'
  }
  else {
    if (isLoggedIn)
      return { name: 'not-authorized' }
    else
      return { name: 'login', query: { to: to.name !== 'index' ? to.fullPath : undefined } }
  }
})

const app = createApp(App);


app.use(pinia).use(router).mount('#app');
