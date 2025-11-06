<script setup>
import { getCurrentInstance, nextTick, onMounted, onUnmounted, ref } from 'vue'
import { useForm } from '@inertiajs/inertia-vue3'
import axios from 'axios'
import Swal from 'sweetalert2'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Card from '@/Components/Card.vue'
import Icon from '@/Components/Icon.vue'
import { Inertia } from '@inertiajs/inertia'
import Modal from '@/Components/Modal.vue'
import Close from '@/Components/Button/Close.vue'
import ButtonGreen from '@/Components/Button/Green.vue'
import ButtonBlue from '@/Components/Button/Blue.vue'
import ButtonRed from '@/Components/Button/Red.vue'
import Input from '@/Components/Input.vue'
import InputError from '@/Components/InputError.vue'

const self = getCurrentInstance()
const permissions = ref([])
const search = ref('')
const open = ref(false)
const form = useForm({
  id: null,
  name: '',
})

const fetch = async () => {
  try {
    const response = await axios.get(route('superuser.permission'))
    permissions.value = response.data
  } catch (e) {
    const response = await Swal.fire({
      title: __('do you want to try again') + '?',
      text: __(`${e}`),
      icon: 'error',
      showCancelButton: true,
      showCloseButton: true,
    })
    
    response.isConfirmed && fetch()
  }
}

fetch()

const show = () => {
  open.value = true

  nextTick(() => self.refs.name?.focus())
}

const close = () => {
  open.value = false
  form.reset()
  form.clearErrors()
}

const esc = e => {
  if (e.key === 'Escape') close()
}

const store = () => form.post(route('superuser.permission.store'), {
  onSuccess: () => close() || fetch(),
  onError: show,
})

const edit = permission => {
  form.id = permission.id
  form.name = permission.name
  show()
}

const update = () => form.patch(route('superuser.permission.update', form.id), {
  onSuccess: () => close() || fetch(),
  onError: show,
})

const destroy = async permission => {
  const response = await Swal.fire({
    title: __('are you sure') + '?',
    text: __('you can\'t restore it after deleted'),
    icon: 'warning',
    showCancelButton: true,
  })

  if (response.isConfirmed) {
    return Inertia.delete(route('superuser.permission.destroy', permission.id), {
      onSuccess: fetch,
    })
  }
}

const submit = () => form.id ? update() : store()

onMounted(() => {
  window.addEventListener('keydown', esc)

  document.querySelector('[type=search]')?.focus()
})
onUnmounted(() => window.removeEventListener('keydown', esc))
</script>

<template>
  <DashboardLayout
    :title="__('permission')"
  >
  <main class="p-0 py-0 mb-[1.25rem] ml-[1.25rem] mt-[1.25rem]">
            <h2 class="font-bold text-2xl">Permission</h2>
           <a type="button" href="/" class="text-sm text-gray-500 font-semibold hover:text-sky-600 focus:text-sky-600">Home</a> 
           <span class="font-semibold text-sm pl-2 pr-2">-</span>
           <span class="text-sm text-gray-500 font-semibold hover:text-sky-600 focus:text-sky-700">Builtin</span> 
            <slot />
        </main>
    <Card class="bg-white pt-[1.875rem] pb-[2.5rem] shadow-lg border border-solid border-slate-200" style="border-radius: 0.625rem;">
      <template #header>
        <div class="flex items-center justify-end space-x-2 p-2 pr-[1.688rem]">
          <ButtonGreen
            v-if="can('create permission')"
            @click.prevent="form.id = null; show()"
            class="flex items-center justify-center grid gap-1 w-auto h-11 mr-[1.313rem] rounded-md text-center bg-green-600 hover:bg-green-700 active:bg-green-800 h-[35px] text-center pr-[30px] pl-[30px] w-full flex items-center justify-center space-x-2 ml-[20px] mr-[20px]"
          >
            <!-- <Icon name="plus" /> -->
            <p class="font-semibold capitalize">{{ __('create') }}</p>
          </ButtonGreen>
        </div>
      </template>

      <template #body>
        <div class="flex flex-col space-y-2  p-4 h-screen max-h-96 overflow-auto">
          <div class="flex items-center justify-end space-x-2 text-sm px-4">
            <input
              v-model="search"
              :placeholder="__('search')"
              type="search"
              class="bg-transparent w-full max-w-sm rounded-md placeholder:capitalize py-1"
            >
          </div>

          <div class="flex-wrap px-4 pb-2 rounded-b-md">
            <TransitionGroup
              enterActiveClass="transition-all duration-300"
              leaveActiveClass="transition-all duration-300"
              enterFromClass="opacity-0 -translate-y-100"
              leaveToClass="opacity-0 -translate-y-100"
            >
              <div
                v-for="(permission, i) in permissions.filter(p => p.name?.toLowerCase().includes(search?.trim().toLowerCase()))"
                :key="i"
                class="inline-block bg-gray-200 hover:bg-gray-100 transition-all duration-300 border rounded-md m-[2px] px-3 py-1"
              >
                <div class="flex items-center space-x-2 text-sm">
                  <p class="uppercase">
                    {{ __(permission.name) }}
                  </p>

                  <div class="flex items-center space-x-1">
                    <Icon
                      v-if="can('update permission')"
                      @click.prevent="edit(permission)"
                      name="pen"
                      class="px-2 py-1 rounded cursor-pointer text-white bg-blue-600 hover:bg-blue-700 transition-all"
                    />

                    <Icon
                      v-if="can('delete permission')"
                      @click.prevent="destroy(permission)"
                      name="trash"
                      class="px-2 py-1 rounded cursor-pointer text-white bg-red-600 hover:bg-red-700 transition-all"
                    />
                  </div>
                </div>
              </div>
            </TransitionGroup>
          </div>
        </div>
      </template>
    </Card>

    <Modal :show="open">
      <form
        @submit.prevent="submit"
        class="w-full max-w-xl h-fit shadow-xl"
      >
        <Card class="bg-gray-50 border">
          <template #header>
            <div class="flex items-center space-x-2 justify-end bg-gray-200 p-2">
              <Close
                @click.prevent="close" 
              />
            </div>
          </template>

          <template #body>
            <div class="flex flex-col space-y-4 p-4">
              <div class="flex flex-col space-y-2">
                <div class="flex items-center space-x-2">
                  <label for="name" class="lowercase first-letter:capitalize flex-none w-1/4">
                    {{ __('name') }}
                  </label>
                  
                  <Input
                    v-model="form.name"
                    :placeholder="__('name')"
                    type="text"
                    required
                    autofocus
                  />
                </div>
                
                <InputError
                  :error="form.errors.name"
                />
              </div>
            </div>
          </template>

          <template #footer>
            <div class="flex items-center space-x-2 justify-end bg-gray-200 text-white px-2 py-1">
              <ButtonGreen type="submit">
                <Icon name="check" />
                <p class="uppercase font-semibold">
                  {{ __(form.id ? 'update' : 'create') }}
                </p>
              </ButtonGreen>
            </div>
          </template>
        </Card>
      </form>
    </Modal>
  </DashboardLayout>
</template>