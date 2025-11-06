<script setup>
import { onMounted, onUnmounted, ref, watch, computed } from 'vue'
import { Head, usePage } from '@inertiajs/inertia-vue3'
import Toggler from '@/Components/DashboardLayout/SidebarToggler.vue'
import TopbarDropdown from '@/Components/DashboardLayout/TopbarDropdown.vue'
import Sidebar from '@/Components/DashboardLayout/Sidebar.vue';

defineProps({
  title: String,
})

const path = computed(() => window.location.pathname)
const displayTitle = computed(() => {
  const segments = path.value.split('/').filter(Boolean)
  if (segments.length === 0) return 'Home'
  const last = segments[segments.length - 1]
  return 'Home - ' + last.replace(/[-_]/g, ' ').replace(/\b\w/g, l => l.toUpperCase())
})

const { $config } = usePage().props.value
const open = ref(Boolean(
  Number(localStorage.getItem('sidebar_open'))
))

// Menjaga state sidebar di localStorage
watch(open, () => {
  localStorage.setItem('sidebar_open', Number(open.value))
})

// Shortcut keyboard (Q) untuk toggle
const q = e => {
  if (e.key === 'q' && !(e.target instanceof HTMLInputElement)) {
    open.value = !open.value
  }
}

onMounted(() => document.addEventListener('keyup', q))
onUnmounted(() => document.removeEventListener('keyup', q))
</script>

<style>
/* Transisi ini bisa digunakan untuk konten di dalam <slot> */
.fade-enter-active,
.fade-leave-active {
  transition: all 300ms ease-in-out;
  opacity: 1;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
<template>
  <div class="font-sans w-full h-full min-h-screen bg-slate-50">
    <Head :title="title" />
    <div class="sticky top-0 z-30 flex h-11 w-full items-center justify-between border-b border-gray-200 bg-blue-5/10 bg-gradient-to-r from-blue-600 to-blue-400 px-4 text-white shadow">
      <button
        @click="open = !open"
        class="md:hidden p-2 rounded-md hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-indigo-700"
      >
        <svg v-if="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
        <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
      </button>

      <div class="flex-1 text-center md:text-left text-lg font-semibold">
        </div>

      <div>
        <TopbarDropdown />
      </div>
    </div>

    <div class="sticky top-0 z-30 w-full border-b border-gray-200 bg-gradient-to-r from-white to-gray-50 shadow-sm md:pl-16 lg:pl-64">
      <div class="flex items-center justify-between px-4 py-3">
        <div class="flex items-center space-x-2 text-gray-700 text-sm font-semibold tracking-wide">
          <i class="text-gray-400 text-xs"></i>
          <span>{{ displayTitle }}</span>
        </div>
      </div>
    </div>

    <div
      class="sidebar fixed top-0 left-0 z-40 flex h-full flex-col bg-white border-r border-gray-200 transition-all duration-300"
      :class="{
        'md:w-64': open,
        'md:w-14': !open,
        'w-64': open,
        'translate-x-0': open,
        'w-64 -translate-x-full md:translate-x-0': !open
      }"
    >
      <div class="hidden h-14 md:flex items-center justify-between px-3 border-b border-gray-200">
        <div
          :class="{ 'flex-1 mr-2': open, 'w-full flex justify-center': !open }"
          class="flex items-center overflow-hidden"
        >
          <img class="w-[30px] h-[30px] flex-shrink-0" src="/assets/TJFE (2).png" alt="Logo">
          <span v-if="open" class="text-lg font-bold text-gray-700 ml-2 whitespace-nowrap">RAMCES</span>
        </div>

        <button
          @click="open = !open"
          class="p-2 rounded-full text-gray-500 hover:bg-gray-100 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-colors"
          :class="{ 'rotate-180': !open }"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </button>
      </div>

      <div class="flex h-14 items-center justify-between px-4 md:hidden border-b border-gray-200">
        <span class="font-bold text-lg text-gray-700">RAMCES</span>
        <button
          @click="open = !open"
          class="p-2 rounded-full text-gray-500 hover:bg-gray-100 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-colors"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
      </div>

      <div class="flex-1 overflow-y-auto pt-2">
        <Sidebar :open="open" />
      </div>
    </div>

    <div
      v-if="open"
      @click="open = false"
      class="fixed inset-0 z-30 bg-black/30 backdrop-blur-sm md:hidden"
    ></div>

    <div
      class="transition-all duration-300"
      :class="{
        'md:pl-64': open,
        'md:pl-14': !open
      }"
    >
      <main class="p-4 md:p-6">
        <slot />
      </main>
    </div>

  </div>
</template>
