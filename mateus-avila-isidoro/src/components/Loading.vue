<template>
  <div class="w-full flex h-full items-center justify-center absolute top-0 left-0 bg-#ffffff z-1000" role="alert" aria-label="loading">
    <span class="dashicons dashicons-update animation-loading w-60px h-60px text-60px"></span>
  </div>
</template>

<style lang="sass">
  @keyframes rotate
    from
      transform: rotate(0deg)
    to 
      transform: rotate(359deg)

  .animation-loading
    animation: rotation 2s infinite linear
</style>