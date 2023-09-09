<script setup lang="ts">
defineProps<{
  icon: string
  wrapperClass?: string
  disabled?: boolean
  label: string
}>()

const emit = defineEmits<{
  action: []
}>()

const action = () => emit('action')
</script>

<template>
  <button
    :aria-label="label"
    :disabled="disabled" 
    type="button" 
    class="flex items-center gap-5px cursor-pointer bg-#fafafa hover:bg-#eeeeee transition-.2 border-none w-32px h-32px rd-32px disabled-opacity-20" 
    :class="wrapperClass" 
    @click="action">
    <span class="dashicons text-20px color-#333333" :class="icon"></span>
  </button>
</template>