import { createApp } from 'vue'
import { createPinia } from 'pinia'
import './style.css'
import 'virtual:uno.css'
import App from './App.vue'
import Home from './pages/Home.vue'
import Graph from './pages/Graph.vue'
import Form from './pages/Form.vue';

import { createRouter, createWebHashHistory } from 'vue-router'

const router = createRouter({
  history: createWebHashHistory(),
  routes: [
    { path: '/', component: Home },
    { path: '/graphic', component: Graph },
    { path: '/settings', component: Form },
  ],
})

const pinia = createPinia()
const app = createApp(App)
app.use(router)

app.use(pinia)
app.mount('#app')
