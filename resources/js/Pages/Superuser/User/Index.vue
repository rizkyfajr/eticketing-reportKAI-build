<script setup>
import { getCurrentInstance, nextTick, onMounted, onUnmounted, ref } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { useForm } from '@inertiajs/inertia-vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Card from '@/Components/Card.vue'
import Icon from '@/Components/Icon.vue'
import Builder from '@/Components/DataTable/Builder.vue'
import Th from '@/Components/DataTable/Th.vue'
import Swal from 'sweetalert2'
import Select from '@vueform/multiselect'
import Modal from '@/Components/Modal.vue'
import ButtonGreen from '@/Components/Button/Green.vue'
import ButtonBlue from '@/Components/Button/Blue.vue'
import ButtonRed from '@/Components/Button/Red.vue'
import Close from '@/Components/Button/Close.vue'
import Input from '@/Components/Input.vue'
import InputError from '@/Components/InputError.vue'
import Button from '@/Components/Button.vue'

const self = getCurrentInstance()
const render = ref(true)
const { permissions, roles, positions, divisions } = defineProps({
  positions: Array,
  divisions: Array,
  permissions: Array,
  roles: Array,
})

const form = useForm({
  id: null,
  name: '',
  username: '',
  position_id: '',
  division_id: '',
  positions: [],
  divisions: [],
  email: '',
  password: '',
  password_confirmation: '',
  permissions: [],
  roles: [],
})

const table = ref(null)
const open = ref(false)

const show = () => open.value = true

const close = () => {
  open.value = false
  form.reset()
  render.value = false
  nextTick(() => render.value = true)
}

const store = () => {
  return form.post(route('superuser.user.store'), {
    onSuccess: () => close(),
    onError: () => show(),
  })
}

const edit = user => {
  form.id = user.id
  form.name = user.name
  form.username = user.username
  form.position_id = user.position_id
  form.division_id = user.division_id
  form.email = user.email
  form.permissions = user.permissions.map(permission => permission.id)
  form.roles = user.roles.map(role => role.id)

  show()
}

const update = () => {
  return form.patch(route('superuser.user.update', form.id), {
    onSuccess: () => close(),
    onError: () => show(),
  })
}

const destroy = async user => {
  const response = await Swal.fire({
    title: __('are you sure') + '?',
    text: __('you can\'t restore it after deleted'),
    icon: 'question',
    showCancelButton: true,
    showCloseButton: true,
  })

  if (response.isConfirmed) {
    Inertia.on('finish', () => close())

    return Inertia.delete(route('superuser.user.destroy', user.id))
  }
}

const submit = () => form.id ? update() : store()

const detachPermission = async (user, permission) => {
  const response = await Swal.fire({
    title: __('are you sure') + '?',
    text: __('you can re adding it on edit page'),
    icon: 'question',
    showCancelButton: true,
    showCloseButton: true,
  })

  if (!response.isConfirmed)
    return

  Inertia.on('finish', () => close())
  Inertia.patch(route('superuser.user.permission.detach', { user: user.id, permission: permission.id }))
}

const detachRole = async (user, role) => {
  const response = await Swal.fire({
    title: __('are you sure') + '?',
    text: __('you can re adding it on edit page'),
    icon: 'question',
    showCancelButton: true,
    showCloseButton: true,
  })

  if (!response.isConfirmed)
    return

  Inertia.on('finish', () => close())
  Inertia.patch(route('superuser.user.role.detach', { user: user.id, role: role.id }))
}

const esc = e => e.key === 'Escape' && close()

onMounted(() => window.addEventListener('keydown', esc))
onUnmounted(() => window.removeEventListener('keydown', esc))
</script>

<style src="@vueform/multiselect/themes/default.css"></style>
<style src="@/multiselect.css"></style>

<template>
  <DashboardLayout
    :title="__('User')"
  >
    <!-- <main class="p-0 py-0 mb-[1.25rem] ml-[1.25rem] mt-[1.25rem]">
            <h2 class="font-bold text-2xl">Master Data User</h2>
           <a type="button" href="/" class="text-sm text-gray-500 font-semibold hover:text-sky-600 focus:text-sky-600">Home</a> 
           <span class="font-semibold text-sm pl-2 pr-2">-</span>
           <span class="text-sm text-gray-500 font-semibold hover:text-sky-600 focus:text-sky-700">Builtin</span> 
            <slot />
        </main> -->
    <Card class="bg-white pt-[1.100rem] pb-[2.5rem] shadow-lg border border-solid border-slate-200" style="border-radius: 0.625rem;">
      <template #header>
        <div class="flex items-center justify-end px-4 py-1 rounded space-x-2 p-2 pr-[1.688rem]">
          <Button
            v-if="can('create user')"
            @click.prevent="form.id = null; show()"
            class="grid md:grid-cols text-center items-center bg-green-600 hover:bg-green-800"
          >
            <!-- <Icon name="plus" /> -->
            <p class="font-bold text-xs">
              {{ __('Tambah') }}
            </p>
          </Button>
        </div>
      </template>

      <template #body>
        <div class="flex flex-col space-y-1">
          <Builder
            v-if="render"
            :url="route('superuser.user.paginate')"
            ref="table"
          >
            <template #thead="table">
              <tr class="bg-gray-100 border-b border-gray-300">
                <Th
                  :table="table"
                  :sort="false"
                  class="uppercase font-semibold border border-gray-300 px-3 py-1 text-left font-extrabold text-xs"
                >
                  {{ __('no').toUpperCase() }}
                </Th>

                <Th
                  :table="table"
                  :sort="false"
                  name="username"
                  class="uppercase font-semibold border border-gray-300 px-3 py-1 text-left font-extrabold text-xs"
                >
                  {{ __('nipp').toUpperCase() }}
                </Th>

                <Th
                  :table="table"
                  :sort="false"
                  name="name"
                  class="uppercase font-semibold border border-gray-300 px-3 py-1 text-left font-extrabold text-xs"
                >
                  {{ __('name').toUpperCase() }}
                </Th>

                <Th
                  :table="table"
                  :sort="false"
                  name="postion_id"
                  class="uppercase font-semibold border border-gray-300 px-3 py-1 text-left font-extrabold text-xs"
                >
                  {{ __('jabatan').toUpperCase() }}
                </Th>

                <Th
                  :table="table"
                  :sort="false"
                  name="division_id"
                  class="uppercase font-semibold border border-gray-300 px-3 py-1 text-left font-extrabold text-xs"
                >
                  {{ __('Posisi').toUpperCase() }}
                </Th>

                <!-- <Th
                  :table="table"
                  :sort="false"
                  name="email"
                  class="uppercase font-semibold border border-gray-300 px-3 py-1 text-left capitalize font-extrabold text-xs"
                >
                  {{ __('email').toUpperCase() }}
                </Th> -->

                <!-- <Th
                  :table="table"
                  :sort="false"
                  class="uppercase font-semibold border border-gray-300 px-3 py-1 text-left capitalize font-extrabold text-xs"
                >
                  {{ __('permissions') }}
                </Th> -->

                <Th
                  :table="table"
                  :sort="false"
                  class="uppercase font-semibold border border-gray-300 px-3 py-1 text-left font-extrabold text-xs"
                >
                  {{ __('roles').toUpperCase() }}
                </Th>

                <!-- <Th
                  :table="table"
                  :sort="false"
                  name="email_verified_at"
                  class="uppercase font-semibold border border-gray-300 px-3 py-1 text-left capitalize font-extrabold text-xs"
                >
                  {{ __('verified at') }}
                </Th>

                <Th
                  :table="table"
                  :sort="false"
                  name="created_at"
                  class="uppercase font-semibold border border-gray-300 px-3 py-1 text-left capitalize font-extrabold text-xs"
                >
                  {{ __('created at') }}
                </Th>

                <Th
                  :table="table"
                  :sort="false"
                  name="updated_at"
                  class="uppercase font-semibold border border-gray-300 px-3 py-1 text-left capitalize font-extrabold text-xs"
                >
                  {{ __('updated at') }}
                </Th> -->

                <Th
                  :table="table"
                  :sort="false"
                  class="uppercase font-semibold border border-gray-300 px-3 py-1 text-left font-extrabold text-xs"
                >
                  {{ __('Aksi').toUpperCase() }}
                </Th>
              </tr>
            </template>

            <template #tbody="{ data, processing, empty }">
              <TransitionGroup
                enterActiveClass="transition-all duration-200"
                leaveActiveClass="transition-all duration-200"
                enterFromClass="opacity-0 -scale-y-100"
                leaveToClass="opacity-0 -scale-y-100"
              >
                <template v-if="empty">
                  <tr>
                    <td class="text-5xl text-center p-4" colspan="1000">
                      <p class="lowercase first-letter:capitalize font-semibold text-xs">
                        {{ __('Tidak ada data untuk ditampilkan.') }}
                      </p>
                    </td>
                  </tr>
                </template>

                <template v-else>
                  <tr
                    v-for="(user, i) in data"
                    :key="i"
                    :class="processing && 'bg-gray-100'"
                    class="transition-all duration-300"
                  >
                    <td class="uppercase font-semibold border-b border-gray-300 px-4 py-3 text-left text-xs">
                      {{ i + 1 }}
                    </td>

                    <td class="uppercase font-semibold text-xs border-b border-gray-300 px-4 py-3 text-left text-xs">
                      {{ user.username }}
                    </td>

                    <td class="uppercase font-semibold border-b border-gray-300 px-4 py-3 text-left text-xs">
                      {{ user.name }}
                    </td>

                    <td class="uppercase font-semibold border-b border-gray-300 px-4 py-3 text-left text-xs">
                      {{ user.positions ? user.positions.position : '' }}
                      <!-- {{ user}} -->
                    </td>

                    <td class="uppercase font-semibold border-b border-gray-300 px-4 py-3 text-left text-xs">
                      {{ user.divisions ? user.divisions.division_name : '' }}
                    </td>

                    <!-- <td class="uppercase font-semibold border-b border-gray-300 px-4 py-3 text-left text-xs">
                      {{ user.email }}
                    </td> -->

                    <!-- <td class="uppercase font-semibold border-b border-gray-300 px-4 py-3 text-left text-xs">
                      <div class="flex-wrap">
                        <div
                          v-for="(permission, j) in user.permissions"
                          :key="j"
                          class="inline-block bg-gray-200 rounded-md px-3 py-1 m-[1px] text-sm"
                        >
                          <div class="flex items-center justify-between space-x-1">
                            <p class="uppercase font-semibold whitespace-nowrap text-xs">
                              {{ __(permission.name) }}
                            </p>

                            <Icon
                              v-if="can('update user')"
                              @click.prevent="detachPermission(user, permission)"
                              name="times"
                              class="px-2 py-1 rounded-md transition-all hover:bg-red-500 cursor-pointer"
                            />
                          </div>
                        </div>
                      </div>
                    </td> -->

                    <td class="uppercase font-semibold border-b border-gray-300 px-4 py-3 text-left text-xs">
                      <div class="flex-wrap">
                        <div
                          v-for="(role, j) in user.roles"
                          :key="j"
                          class="inline-block border rounded-md px-3 py-1 m-[1px] text-sm transition-all"
                        >
                          <div class="flex items-center justify-between space-x-2">
                            <p class="first-letter:capitalize font-bold text-xs">
                              {{ __(role.name) }}
                            </p>

                            <Icon
                              v-if="can('update user')"
                              @click.prevent="detachRole(user, role)"
                              name="times"
                              class="px-2 py-1 rounded-md transition-all hover:bg-red-500 cursor-pointer"
                            />
                          </div>
                        </div>
                      </div>
                    </td>

                    <!-- <td class="border border-gray-300 px-4 py-3 text-left text-xs">
                      {{ new Date(user.email_verified_at).toLocaleString('id') }}
                    </td>

                    <td class="border border-gray-300 px-4 py-3 text-left text-xs">
                      {{ new Date(user.created_at).toLocaleString('id') }}
                    </td>

                    <td class="border border-gray-300 px-4 py-3 text-left text-xs">
                      {{ new Date(user.updated_at).toLocaleString('id') }}
                    </td> -->

                    <td class="px-2 py-1 uppercase font-semibold border-b text-center">
                      <div class="flex justify-center gap-2">
                        <ButtonBlue
                          v-if="can('update user')"
                          @click.prevent="edit(user)"
                        >
                          <Icon name="edit" />
                          <p class="font-bold text-xs">
                            {{ __('Edit') }}
                          </p>
                        </ButtonBlue>

                        <ButtonRed
                          v-if="can('delete user')"
                          @click.prevent="destroy(user)"
                        >
                          <Icon name="trash" />
                          <p class="font-bold text-xs">
                            {{ __('Hapus') }}
                          </p>
                        </ButtonRed>
                      </div>
                    </td>
                  </tr>
                </template>
              </TransitionGroup>
            </template>
          </Builder>
        </div>
      </template>
    </Card>

    <Modal :show="open">
      <form
        @submit.prevent="submit"
        class="w-full max-w-xl sm:max-w-5xl h-fit shadow-xl"
      >
        <Card class="bg-gray-50">
          <template #header>
            <div class="flex items-center justify-end space-x-2 bg-gray-200 px-2 py-1 rounded-lg p-1 pb-[5px] pt-[5px]">
              <Close @click.prevent="close" />
            </div>
          </template>

          <template #body>
            <div class="flex flex-col space-y-3 p-3">
              <div class="flex flex-col space-y-2">
                <div class="flex items-center space-x-2">
                  <label for="name" class="w-1/3 lowercase first-letter:capitalize text-sm">
                    {{ __('name') }}
                  </label>

                  <Input
                    v-model="form.name"
                    :placeholder="__('name')"
                    type="text"
                    name="name"
                    required
                    autofocus
                    class="w-full text-sm"
                  />
                </div>

                <InputError :error="form.errors.name" />
              </div>

              <div class="flex flex-col space-y-2">
                <div class="flex items-center space-x-2">
                  <label for="username" class="w-1/3 lowercase first-letter:capitalize text-sm">
                    {{ __('nipp') }}
                  </label>
                  
                  <Input
                    v-model="form.username"
                    :placeholder="__('nipp')"
                    type="text"
                    name="username"
                    required
                    class="w-full text-sm"
                  />
                </div>

                <InputError :error="form.errors.username" />
              </div>

              <div class="flex flex-col space-y-2">
                <div class="flex items-center space-x-2">
                  <label for="position_id" class="w-1/3 lowercase first-letter:capitalize text-sm">
                    {{ __('jabatan') }}
                  </label>
                  
                  <Select
                    v-model="form.position_id"
                    :options="positions.map(position => ({
                    label: `${position.position}`,
                    value: position.id,
                    }))"
                    :searchable="true"
                    placeholder="Pilih jabatan"
                    class="w-full text-sm"
                  />
                </div>

                <InputError :error="form.errors.position_id" />
              </div>

              <div class="flex flex-col space-y-2">
                <div class="flex items-center space-x-2">
                  <label for="division_id" class="w-1/3 lowercase first-letter:capitalize text-sm">
                    {{ __('bagaian') }}
                  </label>
                  
                  <Select
                    v-model="form.division_id"
                    :options="divisions.map(division => ({
                      label: `${division.division_name}`,
                      value: division.id,
                    }))"
                    :searchable="true"
                    placeholder="Pilih bagian"
                    class="w-full text-sm"
                  />
                </div>

                <InputError :error="form.errors.division_id" />
              </div>

              <div class="flex flex-col space-y-2">
                <div class="flex items-center space-x-2">
                  <label for="email" class="w-1/3 lowercase first-letter:capitalize text-sm">
                    {{ __('email') }}
                  </label>

                  <Input
                    v-model="form.email"
                    :placeholder="__('email')"
                    type="email"
                    name="email"
                    class="w-full text-sm"
                  />
                </div>

                <InputError :error="form.errors.email" />
              </div>

              <div class="flex flex-col space-y-2">
                <div class="flex items-center space-x-2">
                  <label for="password" class="w-1/3 lowercase first-letter:capitalize text-sm">
                    {{ __('password') }}
                  </label>

                  <Input
                    v-model="form.password"
                    :placeholder="__('password')"
                    :required="form.id === null"
                    type="password"
                    name="password"
                    class="w-full text-sm"
                  />
                </div>

                <InputError :error="form.errors.password" />
              </div>

              <div class="flex flex-col space-y-2">
                <div class="flex items-center space-x-2">
                  <label for="password_confirmation" class="w-1/3 lowercase first-letter:capitalize text-sm">
                    {{ __('password confirmation') }}
                  </label>

                  <Input
                    v-model="form.password_confirmation"
                    :required="form.id === null"
                    :placeholder="__('password confirmation')"
                    type="password"
                    name="password_confirmation"
                    class="w-full text-sm"
                  />
                </div>

                <InputError :error="form.errors.password_confirmation" />
              </div>

              <div class="flex flex-col space-y-2">
                <div class="flex items-center space-x-2">
                  <label for="permissions" class="w-1/3 lowercase first-letter:capitalize text-sm">
                    {{ __('permissions') }}
                  </label>

                  <Select
                    v-model="form.permissions"
                    :options="permissions.map(p => ({
                      label: __(p.name),
                      value: p.id,
                    }))"
                    :clearOnSelect="false"
                    :closeOnSelect="false"
                    :searchable="true"
                    :placeholder="__('permissions')"
                    class="w-full text-sm"
                    mode="tags"
                  />
                </div>

                <InputError :error="form.errors.permissions" />
              </div>

              <div class="flex flex-col space-y-2">
                <div class="flex items-center space-x-2">
                  <label for="roles" class="w-1/3 lowercase first-letter:capitalize text-sm">
                    {{ __('roles') }}
                  </label>

                  <!-- <Select
                    v-model="form.roles"
                    :options="roles.map(r => ({
                      label: __(r.name),
                      value: r.id,
                    }))"
                    :searchable="true"
                    :clearOnSelect="false"
                    :closeOnSelect="false"
                    :placeholder="__('roles')"
                    class="uppercase"
                    mode="tags"
                  /> -->
                  <Select
                    v-model="form.roles"
                    :options="roles
                      .filter(r => r.id !== 1 && r.id !== 3)
                      .map(r => ({
                        label: __(r.name),
                        value: r.id,
                      }))"
                    :searchable="true"
                    :clearOnSelect="false"
                    :closeOnSelect="false"
                    :placeholder="__('roles')"
                    class="w-full text-sm"
                    mode="tags"
                  />
                </div>

                <InputError :error="form.errors.roles" />
              </div>
            </div>
          </template>

          <template #footer>
            <div class="flex items-center justify-end space-x-2 bg-gray-200 px-2 py-1 rounded-lg p-1 pb-[5px] pt-[5px]">
              <Button type="submit" class="grid md:grid-cols text-center items-center bg-green-600 hover:bg-green-800">
                <Icon name="check" />
                <p class="uppercase font-semibold text-sm">
                  {{ __(form.id ? 'update' : 'create') }}
                </p>
              </Button>
            </div>
          </template>
        </Card>
      </form>
    </Modal>
  </DashboardLayout>
</template>