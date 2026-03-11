import type { NuxtConfig } from '@nuxt/types'

const config: NuxtConfig = {
  build: {},
  modules: [
    '@nuxtjs/composition-api/module',
    '@nuxt/typescript-build'
  ],
  css: [],
  env: {},
  // MIGRATED: head → app.head
  // TODO: Manual review needed — wrap in app: { head: { ... } }
  head: {
    title: 'nuxt-community/typescript-template',
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: 'A boilerplate to start a Nuxt+TS project quickly' }
    ],
    link: []
  },
  loading: { color: '#0c64c1' },
  modules: [],
  plugins: [
    '~/plugins/truncate'
  ]
}

export default config
