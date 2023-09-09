<script setup lang="ts">
defineProps<{
  showError: boolean
  error: string
  id: string
}>()
</script>

<template>
  <div :id="id" class="mt-5px mb-5px w-full" :class="{ 'display-none': !showError }">
    <p class="m-0 color-#e74c3c text-12px">{{ error }}</p>
  </div>
</template>