import { createApp } from 'vue' // MIGRATED: Vue 3 — replaced `import Vue from 'vue'` with named import
import App from '@/App.vue'
import router from '@/router'

import '@/assets/css/main.css'

// MIGRATED: Vue 3 — removed `Vue.config.productionTip = false` (not supported in Vue 3)
// MIGRATED: Vue 3 — removed `Vue.config.devtools = true` (use browser devtools extension config instead)
// TODO: Manual review — Vue 3 devtools configuration is handled via app.config.performance or the devtools extension itself

const app = createApp(App) // MIGRATED: Vue 3 — replaced `new Vue({ render: h => h(App) })` with `createApp(App)`

app.use(router) // MIGRATED: Vue 3 — router registered via app.use() instead of passing to new Vue()

app.mount('#app') // MIGRATED: Vue 3 — replaced `el: '#app'` option with explicit `app.mount('#app')` call