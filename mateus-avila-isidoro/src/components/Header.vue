<script setup lang="ts">
import { TranslationWP } from '../types'

// @ts-ignore
const t: TranslationWP = languages[0]
</script>

<template>
  <div class="w-full flex p-0-20 m-20-0 gap-5px justify-between flex-wrap items-center h-60px">
    <span class="dashicons dashicons-awards text-60px w-60px h-60px"></span>
    <div class="wc-100-65">
      <h1 class="m-0 important-p-0-0-5">Mateus Ávila Isidoro</h1>
      <p class="m-0">{{ t.header_component_paragraph }}</p>
    </div>
  </div>
</template>