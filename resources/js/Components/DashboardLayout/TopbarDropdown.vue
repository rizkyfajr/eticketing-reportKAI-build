<script setup>
import { getCurrentInstance, ref } from 'vue'
import { usePage, Link } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'
import Icon from '../Icon.vue'
import Swal from 'sweetalert2'

const open = ref(false)
const { user } = usePage().props.value

const logout = async () => {
  const response = await Swal.fire({
    title: 'Are you sure?',
    icon: 'question',
    showCloseButton: true,
    showCancelButton: true,
  })

  response.isConfirmed && Inertia.post(route('logout'))
}
</script>

<style scoped>
.slide-enter-active,
.slide-leave-active {
  transition: all 500ms ease-in-out;
  opacity: 1;
}

.slide-enter-from,
.slide-leave-to {
  right: -100vw;
  opacity: 0;
}

@media (min-width: 669px) {

  .slide-enter-from,
  .slide-leave-to {
    right: -12rem;
    opacity: 0;
  }
}
</style>


<template>
<!-- Profile -->
  <div ref="container" class="flex-none flex items-center justify-center mr-5">
    <!-- Name User -->
    <!-- <p class="hidden md:inline font-semibold lowercase first-letter:capitalize truncate w-full">{{ user.name }}</p> -->
    <button @click.prevent="open = ! open" class="rounded-md w-full h-full text-gray-700 transition-all ease-in-out duration-150 hover:border-gray-700 hover:text-gray-900">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" data-slot="icon" class="w-[35px;] ">
   <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
    </svg> <!-- <Icon name="caret-left" class="transition-all ease-linear duration-500" :class="open && '-rotate-90'" /> -->
      </button>
   </div>
<!-- Card Profile Open -->
  <transition name="slide">
    <div v-if="open" 
      class="fixed right-0 md:right-4 top-12 w-full md:max-w-xl md:w-48 bg-white rounded-md shadow-xl">
      <!-- Name User -->
      <div class="flex items-center justify-center font-semibold mb-3 pb-3 w-full border-b-[1px] ">
        <p class="font-semibold flex items-center justify-center capitalize w-full mt-3 text-black">{{ user.name }}</p>
      </div>
      <Link :href="route('profile.show')" as="button"
        class="w-full border-l-8 border-transparent px-4 py-2 rounded-t-md transition-all ease-linear duration-150 hover:bg-gray-200">
      <!-- Profile -->
      <div class="flex items-center space-x-2 font-semibold ">
        <Icon name="user" />
        <p class="capitalize text-black ">profile</p>
      </div>
      </Link>

      <button @click.prevent="logout" class="w-full border-l-8 border-transparent px-4 py-2 rounded-b-md transition-all ease-linear duration-150 hover:bg-gray-200">
        <div class="flex items-center space-x-2 font-semibold">
          <Icon name="door-open" />

          <p class="capitalize text-black ">logout</p>
        </div>
      </button>
    </div>
  </transition>
</template>