<script setup>
import { ref } from 'vue'
import Icon from '@/Components/Icon.vue'

const { table, sort, name } = defineProps({
  table: Object,
  sort: Boolean,
  name: String,
  class: String,
})

const asc = ref(table.config.order.dir === 'asc')
const desc = ref(table.config.order.dir === 'desc')

const click = () => {
  table.config.order.key = name
  
  if (table.config.order.dir === 'asc') {
    table.config.order.dir = 'desc'
    asc.value = false
    desc.value = true
  } else {
    table.config.order.dir = 'asc'
    asc.value = true
    desc.value = false
  }

  table.refresh()
}
</script>

<template>
  <th ref="th" class="relative capitalize font-semibold bg-white text-inherit border-0" style="z-index: 10; " :class="`${table?.config?.sticky ? 'sticky top-0 left-0 ' : ''} ${sort && 'cursor-pointer'} ${$props.class}`" @click.prevent="sort && table && click()">
    <div class="flex items-center justify-center space-x-44 space-y-2.5">
      <div>
        <slot />
      </div>

      <template v-if="table && sort">
        <div class="flex flex-col items-center justify-center text-xs ">
          <Icon name="caret-up" class="transition-all duration-300" :class="table.config.order.key === name && table.config.order.dir === 'asc' && asc ? 'text-black' : ' text-gray-400'" />
          <Icon name="caret-down" class="transition-all duration-300" :class="table.config.order.key === name && table.config.order.dir === 'desc' && desc ? 'text-black' : 'text-gray-400'" />
        </div>
      </template>
    </div>
<!-- Thead border -->
    <div class="absolute top-0 left-0 w-full h-full border-b border-t border-gray-200 bg-[#FAFAFA] text-black flex items-center justify-center font-bold">
      <div class="flex items-center justify-center space-x-2  ">
        <div>
          <slot />
        </div>

        <template v-if="table && sort">
          <div class="flex l items-center justify-center text-xs">
            <img src="../../../../public/up-arrow.png" class="w-2.5 transition-all duration-300 " :class="table.config.order.key === name && table.config.order.dir === 'asc' && asc ? 'text-black' : 'text-gray-400'"  alt="">
            <img src="../../../../public/assets/down-arrow.png" class="w-2.5 transition-all duration-300" :class="table.config.order.key === name && table.config.order.dir === 'desc' && desc ? 'text-black' : 'text-gray-400'" alt="">
          </div>
        </template>
      </div>
    </div>
  </th>
</template>