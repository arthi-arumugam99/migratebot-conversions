// MIGRATED: removed @nuxt/types Plugin import — no longer needed in Nuxt 3

type Truncater = (text: string) => string

// MIGRATED: removed Vue 2 module augmentation (vue/types/vue) — use Nuxt 3 plugin provide typing instead
declare module '#app' {
  interface NuxtApp {
    $truncate: Truncater
  }
}

declare module 'vue' {
  interface ComponentCustomProperties {
    $truncate: Truncater
  }
}

const truncater: Truncater = (text: string) => text.length > 15 ? text.substring(0, 15) : text

// MIGRATED: Plugin format updated to defineNuxtPlugin(); inject() replaced with nuxtApp.provide()
export default defineNuxtPlugin((nuxtApp) => {
  nuxtApp.provide('truncate', truncater)
})