<script setup lang="ts">
import Navigation from './components/Navigation.vue';
import Header from './components/Header.vue'

</script>

<template>
  <div class="w-full">
    <Header />
    <Navigation />
    <RouterView />
  </div>
</template>

<style scoped lang="sass">
html, body
  margin: 0
  padding: 0
*, *:before, *:after
  box-sizing: border-box
</style>
