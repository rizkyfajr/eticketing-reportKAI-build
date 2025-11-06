<script setup>
import { getCurrentInstance, onMounted, onUpdated, ref } from 'vue'
import Icon from '@/Components/Icon.vue'

const { menu, childs, open } = defineProps({
  menu: Object,
  open: Boolean,
  childs: Array,
  padding: Number,
})

const counter = ref(0)
const element = ref(null)
const trace = menu => {
  if (menu.childs?.length) {
    for (const child of menu.childs) {
      if (trace(child)) {
        return true
      }
    }
  }

  return route().current(menu.route_or_url) || menu.actives.filter(active => route().current(active)).length > 0
}

const active = childs.find(trace)
const self = getCurrentInstance()
const show = ref(open ? (active ? true : false) : false)
const fetch = async () => {
  if (!menu.counter_handler) {
    return
  }
  try {
    const { data } = await axios.get(route(`superuser.menu.counter`, menu.id))
    counter.value = data.count
  } catch (e) {
    element.value.innerHTML = `<i class="fa fa-times"></i>`
  }
}

const resize = () => {
  if (!element.value) {
    return
  }

  const width = element.value.clientWidth
  const height = element.value.clientHeight

  if (width != height) {
    const max = Math.max(width, height)
    element.value.style.width = `${max}px`
    element.value.style.height = `${max}px`
  }
}

onMounted(resize)
onMounted(fetch)
onUpdated(resize)
onUpdated(fetch)
</script>

<style scoped>
  .slide-fade-enter-active {
    transition: all 0.3s ease-out;
  }

  .slide-fade-leave-active {
    transition: all 0.3s cubic-bezier(1, 0.5, 0.8, 1);
  }

  .slide-fade-enter-from,
  .slide-fade-leave-to {
    transform: translateX(-20px);
    opacity: 0;
  }
</style>

<template>
  <div class="w-full flex flex-col">
    <button
      @click.prevent="show = ! show"
      :title="menu.name"
      :class="[
        'w-full px-4 py-3', // Ukuran padding
        'text-gray-600', // 1. Warna teks default
        'hover:bg-gray-100 hover:text-gray-900', // 2. Style hover (background + teks gelap)
        active ? 'bg-blue-50 text-blue-600' : '', // 3. Style aktif (background + teks gelap)
        `pl-${padding !== 0 && padding}` // 4. Padding dinamis Anda (tetap berfungsi)
      ]"
    >
      <div class="flex items-center justify-center space-x-2 ">
        <Icon :name="menu.icon" />

        <template v-if="open">
          <p class="capitalize font-semibold w-full text-left ">{{ __(menu.name) }}</p>

          <div class="flex items-center space-x-2 ">
            <div v-if="counter > 0" ref="element" class="flex items-center justify-center bg-red-500 text-white text-center rounded-full p-1 ">
              <p class="text-xs">
                {{ counter }}
              </p>
            </div>
            <svg :class="show && '-rotate-180'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" data-slot="icon" class="w-5 h-5 transition-all ease-in-out duration-150 ">
            <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
              </svg>
            </div>
        </template>
      </div>
    </button>

    <Transition name="slide-fade" mode="in-out">
      <div v-if="show && open" class="flex flex-col" ref="container">
        <slot />
      </div>
    </Transition>
  </div>
</template>
