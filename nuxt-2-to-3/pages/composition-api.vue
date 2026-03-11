<template>
  <main>
    <h1>With Composition API</h1>
    <h2>data ()</h2>
    <p>
      {{ message }}
    </p>
    <h2>computed ()</h2>
    <p>
      {{ computedMessage }}
    </p>
    <h2>asyncData ()</h2>
    <p>
      {{ asyncMessage }}
    </p>
    <h2>fetch ()</h2>
    <p v-if="fetchPending">
      Fetching from frontend...
    </p>
    <ul v-else>
      <li
        v-for="todo in fetchedTodos.slice(0, 5)"
        :key="todo.id"
      >
        {{ todo.completed ? '&#9989;' : '&#11036;' }} {{ todo.id }}: {{ todo.title }}
      </li>
    </ul>
    <h2>Vuex Store</h2>
    <!-- TODO: Manual review needed — Vuex store (descriptionOnStore, isDarkMode, toggleDarkMode) must be migrated to Pinia or useState() -->
    <ul>
      <li>On root store: {{ descriptionOnStore }}</li>
      <li>On setting store (namespaced): dark mode is <strong>{{ isDarkMode ? 'enabled' : 'disabled' }}</strong></li>
      <a
        href
        @click.prevent="toggleDarkMode"
      >
        Toggle dark mode
      </a>
    </ul>
    <h2>Nuxt Middleware</h2>
    <p>{{ userAgent }}</p>
    <h2>Nuxt Plugin</h2>
    <p>{{ $truncate(userAgent || '') }}</p>
  </main>
</template>

<script lang="ts" setup>
// MIGRATED: removed explicit import — auto-imported by Nuxt 3
// MIGRATED: removed @nuxtjs/composition-api imports, replaced with Nuxt 3 composables

import { useNuxtApp } from '#app'

interface ToDo {
  userId: number
  id: number
  title: string
  completed: boolean
}

// MIGRATED: definePageMeta replaces middleware option and head
definePageMeta({
  middleware: 'user-agent',
  // MIGRATED: fetchOnServer: false → server: false in useFetch options
})

useHead(() => ({
  title: 'Composition API Demo',
  meta: [{
    name: 'message',
    content: computedMessage.value
  }]
}))

const message = ref("I'm defined on data()")
const fetchedTodos = ref<ToDo[]>([])

const computedMessage = computed(() => message.value.replace('data()', 'computed()'))

// MIGRATED: useAsync(() => ...) → useAsyncData() composable
const { data: asyncMessage } = await useAsyncData('async-message', () => Promise.resolve("I'm defined on asyncData()"))

// MIGRATED: useAsync(() => useContext().userAgent) → useRequestHeaders / useNuxtApp
const nuxtApp = useNuxtApp()
const { data: userAgent } = await useAsyncData('user-agent', () => Promise.resolve(nuxtApp.ssrContext?.event?.node?.req?.headers?.['user-agent'] ?? ''))

// MIGRATED: useFetch hook → useFetch composable, fetchOnServer: false → server: false
const { pending: fetchPending } = await useFetch<ToDo[]>(
  'https://jsonplaceholder.typicode.com/todos',
  {
    server: false, // MIGRATED: fetchOnServer: false
    onResponse({ response }) {
      fetchedTodos.value = response._data
    }
  }
)

// TODO: Manual review needed — Vuex store migration required. Replace with Pinia store or useState().
// The following are placeholder stubs. Migrate store/index.ts and store/setting.ts to Pinia stores.
const descriptionOnStore = ref('')
const isDarkMode = ref(false)
const toggleDarkMode = (): void => {
  // TODO: Manual review needed — dispatch(`setting/TOGGLE_DARK_MODE`) must be replaced with Pinia store action
}
</script>