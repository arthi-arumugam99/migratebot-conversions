import type { RouteRecordRaw } from 'vue-router' // MIGRATED: Vue 3 — RouteConfig → RouteRecordRaw
import { createRouter, createWebHistory } from 'vue-router' // MIGRATED: Vue 3 — createRouter/createWebHistory replace new VueRouter
import Home from '@/views/Home.vue'
import NotFound from '@/views/NotFound.vue'

// MIGRATED: Vue 3 — removed Vue.use(VueRouter); plugin registration moves to main entry point via app.use(router)

export const routes: RouteRecordRaw[] = [ // MIGRATED: Vue 3 — RouteConfig → RouteRecordRaw
  {
    path: '/',
    name: 'Home',
    // NOTE: you can also apply meta information
    // meta: {authRequired: false }
    component: Home,
    // NOTE: you can also lazy-load the component
    // component: () => import("@/views/About.vue")
  },
  {
    path: '/:path(.*)',
    name: 'NotFound',
    component: NotFound,
  },
]

const router = createRouter({ // MIGRATED: Vue 3 — new VueRouter() → createRouter()
  history: createWebHistory('/'), // MIGRATED: Vue 3 — mode: 'history' + base → history: createWebHistory(base)
  routes,
})

export default router