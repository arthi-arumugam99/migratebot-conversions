<template>
  <main>
    <h1>With Class API</h1>
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

<script lang="ts">
// MIGRATED: removed nuxt-property-decorator imports — migrated to Composition API
// MIGRATED: removed vue-meta import — Nuxt 3 uses useHead()
// TODO: Manual review needed — Vuex store (RootState, settingStoreNamespace, SettingState, actionType) must be migrated to Pinia or useState()
import { useSettingStore } from '~/stores/setting' // TODO: Manual review needed — create this Pinia store from ~/store/setting

interface ToDo {
  userId: number
  id: number
  title: string
  completed: boolean
}

export default defineComponent({
  setup () {
    // MIGRATED: definePageMeta used for middleware and layout
    definePageMeta({
      middleware: 'user-agent'
    })

    // TODO: Manual review needed — Vuex @State('description') descriptionOnStore migrated to Pinia; verify store shape
    // TODO: Manual review needed — Vuex @SettingStore.Action and @SettingStore.State migrated to Pinia; verify store shape
    const settingStore = useSettingStore()
    const descriptionOnStore = computed(() => settingStore.description)
    const isDarkMode = computed(() => settingStore.darkMode)
    const toggleDarkMode = () => settingStore.toggleDarkMode()

    // MIGRATED: data() properties → refs
    const message = ref("I'm defined on data()")
    const fetchedTodos = ref<ToDo[]>([])

    // MIGRATED: computed() → computed()
    const computedMessage = computed(() => message.value.replace('data()', 'computed()'))

    // MIGRATED: asyncData() → useAsyncData()
    const { data: asyncData } = useAsyncData('class-api-async', () => {
      const nuxtApp = useNuxtApp()
      return Promise.resolve({
        asyncMessage: "I'm defined on asyncData()",
        // TODO: Manual review needed — context.userAgent in asyncData was set by user-agent middleware; read from request headers or middleware context
        userAgent: import.meta.server ? useRequestHeaders(['user-agent'])['user-agent'] ?? '' : navigator.userAgent
      })
    })

    const asyncMessage = computed(() => asyncData.value?.asyncMessage ?? 'I will be overwritten by asyncData')
    const userAgent = computed(() => asyncData.value?.userAgent ?? 'I will be overwritten by asyncData')

    // MIGRATED: fetch() hook with fetchOnServer: false → useLazyAsyncData() (client-only)
    const { pending: fetchPending } = useLazyAsyncData('class-api-todos', async () => {
      const data = await $fetch<ToDo[]>('https://jsonplaceholder.typicode.com/todos')
      fetchedTodos.value = data
      return data
    }, { server: false }) // MIGRATED: fetchOnServer: false → server: false

    // MIGRATED: head() → useHead()
    useHead(computed(() => ({
      title: 'Class API Demo',
      meta: [{
        name: 'message',
        content: computedMessage.value
      }]
    })))

    return {
      message,
      computedMessage,
      asyncMessage,
      userAgent,
      fetchedTodos,
      fetchPending,
      descriptionOnStore,
      isDarkMode,
      toggleDarkMode
    }
  }
})
</script>