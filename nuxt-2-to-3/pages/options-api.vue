<template>
  <main>
    <h1>With Options API</h1>
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
    <ul>
      <!-- TODO: Manual review needed — Vuex store (descriptionOnStore, isDarkMode, toggleDarkMode) must be migrated to Pinia or useState() -->
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
    <p>{{ $truncate(userAgent) }}</p>
  </main>
</template>

<script setup lang="ts">
// MIGRATED: Options API with asyncData/fetch → Composition API with useAsyncData/useFetch

definePageMeta({
  // MIGRATED: middleware array from Options API → definePageMeta
  middleware: ['user-agent']
})

useHead(() => ({
  // MIGRATED: head() option → useHead() composable
  title: 'Options API Demo',
  meta: [{
    name: 'message',
    content: computedMessage.value
  }]
}))

interface ToDo {
  userId: number
  id: number
  title: string
  completed: boolean
}

// MIGRATED: data() → ref()
const message = ref<string>("I'm defined on data()")

// MIGRATED: computed() → computed()
const computedMessage = computed<string>(() => message.value.replace('data()', 'computed()'))

// MIGRATED: asyncData() → useAsyncData(); userAgent previously came from context.userAgent (set by user-agent middleware)
// TODO: Manual review needed — context.userAgent was injected by Nuxt 2 middleware; verify the user-agent middleware sets this value via useState or another mechanism in Nuxt 3
const { data: asyncData } = await useAsyncData('options-api-async', () => Promise.resolve({
  asyncMessage: "I'm defined on asyncData()",
  userAgent: import.meta.server ? useRequestHeaders(['user-agent'])['user-agent'] ?? '' : navigator.userAgent
}))

const asyncMessage = computed(() => asyncData.value?.asyncMessage ?? '')
const userAgent = computed(() => asyncData.value?.userAgent ?? '')

// MIGRATED: fetch() hook with fetchOnServer: false → useFetch() with server: false
const { data: fetchedTodosData, pending: fetchPending } = await useFetch<ToDo[]>(
  'https://jsonplaceholder.typicode.com/todos',
  {
    // MIGRATED: fetchOnServer: false → server: false
    server: false
  }
)
const fetchedTodos = computed<ToDo[]>(() => fetchedTodosData.value ?? [])

// TODO: Manual review needed — Vuex mapState/mapActions for descriptionOnStore, isDarkMode, toggleDarkMode must be migrated to Pinia or useState()
const descriptionOnStore = ref('')
const isDarkMode = ref(false)
const toggleDarkMode = () => {
  // TODO: Manual review needed — replace with Pinia store action for toggleDarkMode
}
</script>