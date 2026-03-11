// MIGRATED: Vuex store → Pinia store (defineStore)
// MIGRATED: removed explicit imports for vuex types — no longer needed
import { defineStore } from 'pinia'

// MIGRATED: Vuex → Pinia store
// TODO: Manual review needed — nuxtServerInit is a Vuex-specific hook with no direct Pinia equivalent.
// You must replicate its logic in a Nuxt plugin (e.g., plugins/init.server.ts) using:
//   const settingStore = useSettingStore(); settingStore.setDarkMode(true)

export const useSettingStore = defineStore('setting', {
  // MIGRATED: state() function preserved as-is
  state: (): SettingState => ({
    darkMode: false
  }),

  // MIGRATED: Vuex getters → Pinia getters (empty, preserved as-is)
  getters: {},

  actions: {
    // MIGRATED: mutation CHANGE_DARK_MODE → direct state mutation in action setDarkMode
    setDarkMode (newMode: boolean) {
      this.darkMode = newMode
    },

    // MIGRATED: action TOGGLE_DARK_MODE → Pinia action (direct state mutation, no commit needed)
    toggleDarkMode () {
      this.darkMode = !this.darkMode
    }
  }
})

export interface SettingState {
  darkMode: boolean
}

// MIGRATED: MutationType/actionType constants kept for reference during migration, but no longer needed with Pinia
// TODO: Manual review needed — remove MutationType and actionType once all callers are migrated to use store actions directly
export const MutationType = {
  CHANGE_DARK_MODE: 'changeDarkMode'
}

export const actionType = {
  TOGGLE_DARK_MODE: 'toggleDarkMode'
}