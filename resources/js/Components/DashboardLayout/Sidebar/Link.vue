<script setup>
import { getCurrentInstance, nextTick, onMounted, onUpdated, ref } from 'vue'
import { Link } from '@inertiajs/inertia-vue3'
import Icon from '@/Components/Icon.vue'
import axios from 'axios'

const { menu } = defineProps({
  menu: Object,
  open: Boolean,
  padding: Number,
})

const element = ref(null)

const counter = ref(null)
const active = route().current(menu.route_or_url) || menu.actives.filter(active => route().current(active)).length > 0
const link = route().has(menu.route_or_url) ? route(menu.route_or_url) : menu.route_or_url
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

onMounted(resize)
onUpdated(resize)
onMounted(fetch)
onUpdated(fetch)
</script>

<template>
  <Link
    :href="link"
    :title="menu.name"
    :class="[
      'w-full px-4 py-3', // Ukuran padding
      'text-gray-600', // 1. Warna teks default
      'hover:bg-gray-100 hover:text-gray-900', // 2. Style hover (background + teks gelap)
      active ? 'bg-blue-50 text-blue-600' : '', // 3. Style aktif (background + teks gelap)
      `pl-${padding !== 0 && padding}` // 4. Padding dinamis Anda (tetap berfungsi)
    ]"
  >
    <div class="flex items-center" :class="{ 'justify-between': open, 'justify-center': !open }">
      <div class="flex items-center space-x-2 ">
        <Icon :name="menu.icon" />
        <p v-if="open" class="capitalize font-semibold">{{ __(menu.name) }}</p>
      </div>

      <div v-if="open && menu.counter_handler && counter !== null" ref="element" class="flex-none flex items-center justify-center min-w-[1.5rem] min-h-[1.5rem] rounded-full bg-red-500 text-white text-xs p-1 transition-all">
        <p>{{ counter }}</p>
      </div>
    </div>
  </Link>
</template>
