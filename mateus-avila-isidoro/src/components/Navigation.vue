<script setup lang="ts">
import { TranslationWP } from '../types'
import { useCapitalize } from '../composables/useCapitalize'

const { toCapitalize } = useCapitalize()

// @ts-ignore
const t: TranslationWP = languages[0]
</script>

<template>
  <nav
    role="navigation"
    aria-label="Main"
    class="w-full flex gap-25px items-center justify-start bg-#fff h-58px pl-20px">
    <routerLink class="h-58px hover:bdb-2-#e27730 no-underline bdb-2-#ffffff color-#666666 transition-.2 flex items-center" to="/">{{ toCapitalize(t.home) }}</routerLink>
    <routerLink class="h-58px hover:bdb-2-#e27730 no-underline bdb-2-#ffffff color-#666666 transition-.2 flex items-center" to="/graphic">{{ toCapitalize(t.graphic) }}</routerLink>
    <routerLink class="h-58px hover:bdb-2-#e27730 no-underline bdb-2-#ffffff color-#666666 transition-.2 flex items-center" to="/settings">{{ toCapitalize(t.settings) }}</routerLink>
  </nav>
</template>

<style lang="sass">
.router-link-exact-active
  border-bottom: 2px solid #e27730
</style>