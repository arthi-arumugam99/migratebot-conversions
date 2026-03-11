// MIGRATED: Vuex store → Pinia store with defineStore()
// TODO: Manual review needed — nuxtServerInit is a Vuex-specific hook with no direct Pinia equivalent.
//       In Nuxt 3, server-side initialization should be handled via a server plugin or
//       a Nuxt plugin with `import.meta.server` guard. See comments below.

import { defineStore } from 'pinia'

// MIGRATED: RootState interface kept for type reference, used as Pinia state shape
export interface RootState {
  description: string
}

// MIGRATED: Vuex module (state + getters + mutations + actions) → Pinia store
export const useRootStore = defineStore('root', {
  // MIGRATED: state() function kept as-is, same pattern in Pinia
  state: (): RootState => ({
    description: "I'm defined as an initial state"
  }),

  // MIGRATED: Vuex getters → Pinia getters
  getters: {
    reversedName: (state): string => state.description.split('').reverse().join('')
  },

  // MIGRATED: Vuex mutations removed — direct state mutation inside Pinia actions
  // MIGRATED: MutationType.CHANGE_DESCRIPTION mutation → changeDescription action
  actions: {
    changeDescription(newDescription: string) {
      // MIGRATED: commit(MutationType.CHANGE_DESCRIPTION, newDescription) → direct state assignment
      this.description = newDescription
    },

    // TODO: Manual review needed — nuxtServerInit has no Pinia/Nuxt 3 equivalent.
    //       Replace this with a Nuxt plugin in plugins/init.server.ts:
    //
    //       import { defineNuxtPlugin } from '#app'
    //       import { useRootStore } from '~/stores/useRootStore'
    //
    //       export default defineNuxtPlugin(() => {
    //         const store = useRootStore()
    //         store.changeDescription("I'm defined by server side")
    //       })
    //
    //       The plugin filename suffix `.server.ts` ensures it only runs server-side.
    nuxtServerInit() {
      this.changeDescription("I'm defined by server side")
    }
  }
})

// MIGRATED: MutationType exported for any consumers that referenced it — can be removed
//           once all call sites are updated to use store.changeDescription() directly.
// TODO: Manual review needed — remove MutationType once all consumers are migrated.
export const MutationType = {
  CHANGE_DESCRIPTION: 'changeDescription'
}