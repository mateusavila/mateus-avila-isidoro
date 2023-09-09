<script lang="ts" setup>
defineProps<{
  wrapperClass?: string
  id: string
  value: any
  modelValue: any
  labelClass?: string
  type: 'checkbox' | 'radio'
  theme?: 'white' | 'black' | ''
}>()
const model = defineModel<string | boolean>()
</script>

<template>
  <div
    :class="[wrapperClass, theme]"
    class="relative center-flex w-full flex-wrap justify-between gap-15px">
    <input 
      class="input-toggle invisible opacity-0 absolute left-0 top-0"
      type="checkbox"
      :name="id"
      :id="id"
      v-model="model"
      :value="value">
    <label
      :for="id"
      class="block-toggle relative block w-50px h-18px rd-24px"
      :class="theme">
      <span 
        :class="theme"
        class="ball absolute bg-ffffff top-1px w-16px h-16px rd-16px">
      </span>
    </label>
    <label
      class="wc-100-80"
      :for="id"
      :class="[labelClass, theme, 'empty-box']">
      <slot />
    </label>
  </div>
</template>
<style lang="sass" scoped>
  .empty-box:empty
    display: none
  .ball
    transition: all .2s cubic-bezier(0.18, 0.89, 0.35, 1.15)
  .input-toggle + .block-toggle
    background: #dedede
    border: 1px solid darken(#dedede, 10%)
    .ball
      left: 1px
  .input-toggle:checked + .block-toggle
    background: #00b894
    border: 1px solid darken(#00b894, 10%)
  .input-toggle:active + .block-toggle .ball
    width: 24px
  .input-toggle:checked + .block-toggle .ball
    left: calc(100% - 17px)
  .input-toggle:checked:active + .block-toggle .ball
    margin-left: -8px
</style>