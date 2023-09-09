<script lang="ts" setup>
import Loading from './Loading.vue'
import RoundedButton from './RoundedButton.vue';
import { TranslationWP } from '../types'

// @ts-ignore
const t: TranslationWP = languages[0]

const { id } = defineProps<{
  id: string
  loading: boolean
}>()

const emit = defineEmits<{
  close: []
}>()

const close = () => emit('close')
</script>

<template>
  <dialog 
    class="dialog w-full p-0 bg-#ffffff max-w-500px border-none rd-6px transition-.2 box-border" 
    :id="id">
    <div class="w-full relative z-10 p-20px box-border">
      <Loading v-if="loading" />
      <RoundedButton
        :label="t.modal_accessibility"
        wrapper-class="absolute top-10px right-10px z-100"
        icon="dashicons-no-alt" 
        @action="close" />
      <h2 class="box-border m-0-0-10 flex items-center gap-6px"><span class="dashicons dashicons-star-filled"></span> {{ t.modal_title }}</h2>
      <p class="box-border">{{ t.modal_paragraph }}</p>
      <button type="button" class="button button-primary" @click="close" :aria-label="t.modal_accessibility">{{ t.modal_submit }}</button>
    </div>
    <slot />
  </dialog>
</template>

<style lang="sass">
@keyframes showDialog
  from
    opacity: 0
    transform: translateX(100%)
  to
    opacity: 1
    transform: translateX(0%)
@keyframes hideDialog
  to
    opacity: 0
    transform: translateX(100%)
@keyframes showBackdrop
  from
    opacity: 0
  to
    opacity: 1
@keyframes hideBackdrop
  to
    opacity: 0
.dialog
  border-radius: 6px
  &[open]
    border: 1px solid rgba(0, 0, 0, 0.3)
    box-shadow: 0 3px 7px rgba(0, 0, 0, 0.3)
    &::backdrop
      animation: showBackdrop .5s ease-in-out normal
  &::backdrop
    position: fixed
    top: 0
    left: 0
    right: 0
    bottom: 0
    background-color: rgba(0, 0, 0, .5)
    animation: none
</style>