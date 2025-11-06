<script setup>
import Button from '../Button.vue'
</script>

<template>
  <Button class="bg-green-600 hover:bg-green-700 active:bg-green-800 h-[35px] text-center pr-[30px] pl-[30px]">
    <slot />
  </Button>
</template>