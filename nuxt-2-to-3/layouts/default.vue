<template>
  <div>
    <slot /> <!-- MIGRATED: <nuxt /> → <slot /> for Nuxt 3 layout compatibility -->
  </div>
</template>

<script lang="ts">
export default {
  name: 'LayoutDefault'
}
</script>