<script setup>
import { getCurrentInstance, nextTick, onMounted, onUnmounted, ref } from 'vue'
import { Link, usePage } from '@inertiajs/inertia-vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Card from '@/Components/Card.vue'
import Icon from '@/Components/Icon.vue'
import Builder from '@/Components/DataTable/Builder.vue'
import Th from '@/Components/DataTable/Th.vue'
import Swal from 'sweetalert2'
import Button from '@/Components/Button.vue'
import ButtonBlue from '@/Components/Button/Blue.vue'
import ButtonRed from '@/Components/Button/Red.vue'

const self = getCurrentInstance()
const render = ref(true)
const table = ref(null)
const open = ref(false)
const show = () => open.value = true
const close = () => {
  render.value = false
  nextTick(() => {
    render.value = true
    nextTick(() => open.value = false)
  })
}

const destroy = async (order) => {
  const response = await Swal.fire({
    title: __('Apakah Anda Yakin') + '?',
    text: __('Anda tidak dapat mengembalikannya setelah dihapus'),
    icon: 'question',
    showCancelButton: true,
    showCloseButton: true,
  })

  if (response.isConfirmed) {
    self.appContext.config.globalProperties.$inertia.delete(
      route('maintenance-orders.destroy', order.id),
      { onFinish: close }
    )
  }
}

const esc = e => e.key === 'Escape' && close()
onMounted(() => window.addEventListener('keydown', esc))
onUnmounted(() => window.removeEventListener('keydown', esc))
</script>

<template>
  <DashboardLayout :title="__('Maintenance Order')">
    <div class="transition-all duration-300" :class="{ 'pl-1 md:pl-64': open }">
      <main class="p-0 py-0 mb-[1.25rem] ml-[1.25rem] mt-[1.25rem]">
        <h2 class="font-bold text-2xl">Maintenance Order</h2>
        <a type="button" href="/" class="text-sm text-gray-500 font-semibold hover:text-sky-600 focus:text-sky-600">Home</a>
        <span class="font-semibold text-sm pl-2 pr-2">-</span>
        <span class="text-sm text-gray-500 font-semibold hover:text-sky-700">Maintenance Order</span>
        <slot />
      </main>
    </div>

    <Card class="bg-white pt-[1.875rem] pb-[2.5rem] px-6 md:px-8 shadow-lg border border-solid border-slate-200" style="border-radius: 0.625rem;">
      <template #header>
        <div class="flex items-center justify-end space-x-2 p-2 pr-[1.688rem]">
          <Link :href="route('maintenance-orders.create')">
            <Button
              v-if="can('create maintenance order')"
              class="grid md:grid-cols text-center items-center bg-green-600 hover:bg-green-800"
            >
              <p class="uppercase font-bold">{{ __('Tambah') }}</p>
            </Button>
          </Link>
        </div>
      </template>

      <template #body>
        <div class="flex flex-col space-y-2">
          <Builder v-if="render" :url="route('maintenance-orders.paginate')" ref="table">

            <!-- Desktop Table View (hidden on mobile) -->
            <template #thead="table">
              <tr class="bg-gray-200 border-gray-300 hidden md:table-row">
                <Th :table="table" :sort="false" class="border px-3 py-2 text-center font-bold">#</Th>
                <Th :table="table" :sort="true"  name="status" class="border px-3 py-2 text-center font-bold">{{ __('Status') }}</Th>
                <Th :table="table" :sort="true"  name="title" class="border px-3 py-2 text-center font-bold">{{ __('Judul') }}</Th>
                <Th :table="table" :sort="true"  name="master_machine_id" class="border px-3 py-2 text-center whitespace-nowrap font-bold">{{ __('Mesin') }}</Th>
                <Th :table="table" :sort="true"  name="category" class="border px-3 py-2 text-center font-bold">{{ __('Kategori') }}</Th>
                <Th :table="table" :sort="true"  name="plan_start_at" class="border px-3 py-2 text-center whitespace-nowrap font-bold">{{ __('Rencana Mulai') }}</Th>
                <Th :table="table" :sort="true"  name="trouble_at" class="border px-3 py-2 text-center whitespace-nowrap font-bold">{{ __('Waktu Gangguan') }}</Th>
                <Th :table="table" :sort="false" class="border px-3 py-2 text-center font-bold">{{ __('Aksi') }}</Th>
              </tr>
            </template>

            <template #tfoot="table">
              <tr class="bg-gray-200 border-gray-300 hidden md:table-row">
                <Th :table="table" :sort="false" class="border px-3 py-2 text-center font-bold">#</Th>
                <Th :table="table" :sort="false" class="border px-3 py-2 text-center font-bold">{{ __('Status') }}</Th>
                <Th :table="table" :sort="false" class="border px-3 py-2 text-center font-bold">{{ __('Judul') }}</Th>
                <Th :table="table" :sort="false" class="border px-3 py-2 text-center font-bold">{{ __('Mesin') }}</Th>
                <Th :table="table" :sort="false" class="border px-3 py-2 text-center font-bold">{{ __('Kategori') }}</Th>
                <Th :table="table" :sort="false" class="border px-3 py-2 text-center font-bold">{{ __('Rencana Mulai') }}</Th>
                <Th :table="table" :sort="false" class="border px-3 py-2 text-center font-bold">{{ __('Waktu Gangguan') }}</Th>
                <Th :table="table" :sort="false" class="border px-3 py-2 text-center font-bold">{{ __('Aksi') }}</Th>
              </tr>
            </template>

            <template #tbody="{ data, processing, empty }">
              <TransitionGroup enterActiveClass="transition-all duration-200"
                               leaveActiveClass="transition-all duration-200"
                               enterFromClass="opacity-0 -scale-y-100"
                               leaveToClass="opacity-0 -scale-y-100">
                <template v-if="empty">
                  </template>

                <template v-else>
                  <!-- Desktop Row (Table) -->
                  <tr v-for="(order, i) in data" :key="order.id" :class="processing && 'bg-gray-100'"
                      class="transition-all duration-300 h-[4.375rem] hidden md:table-row">

                    <td class="px-2 py-1 border-b text-center">{{ i + 1 }}</td>

                    <td class="capitalize font-semibold text-center border-b">
                      <span
                        class="font-bold px-3 py-1 rounded-full text-xs text-white"
                        :class="{
                            'bg-blue-500': order.status === 'BARU',
                            'bg-cyan-500': order.status === 'DIPROSES',
                            'bg-yellow-500': order.status === 'DIKERJAKAN',
                            'bg-green-600': order.status === 'SELESAI',
                        }"
                      >
                        {{ order.status }}
                      </span>
                    </td>

                    <td class="font-semibold text-center border-b">
                      {{ order.title ?? '-' }}
                    </td>

                    <td class="capitalize font-semibold text-center border-b">
                      {{ order.machine ? `${order.machine.name}${order.machine.type ? ' - ' + order.machine.type : ''}` : '-' }}
                    </td>

                    <td class="capitalize font-semibold text-center border-b">
                      <span
                        class="font-semibold px-2 py-1 rounded-full text-xs"
                        :class="{
                          'bg-blue-100 text-blue-800': order.category === 'planned',
                          'bg-red-100 text-red-800': order.category === 'unplanned',
                        }"
                      >
                        {{ __(order.category ?? '-') }}
                      </span>
                    </td>

                    <td class="capitalize font-semibold text-center border-b text-sm">
                      <span v-if="order.category === 'planned'">
                        {{ new Date(order.plan_start_at).toLocaleString('id-ID') }}
                      </span>
                      <span v-else class="text-gray-400">-</span>
                    </td>

                    <td class="capitalize font-semibold text-center border-b text-sm">
                      <span v-if="order.category === 'unplanned'" class="text-red-600">
                        {{ new Date(order.trouble_at).toLocaleString('id-ID') }}
                      </span>
                      <span v-else class="text-gray-400">-</span>
                    </td>

                    <td class="px-2 py-2 border-b text-center">
                      <div class="flex justify-center gap-2 flex-wrap">
                        <!-- Tombol Lanjutkan berdasarkan Status -->
                        <Button v-if="order.status === 'BARU'" class="bg-cyan-600 text-white px-3 py-2 rounded-md hover:bg-cyan-700 whitespace-nowrap">
                          <Link :href="route('maintenance-orders.show', order.id)" class="flex items-center gap-1">
                            <Icon name="arrow-right" />
                            <span class="text-xs font-semibold">Follow Up</span>
                          </Link>
                        </Button>
                        <Button v-else-if="order.status === 'DIPROSES'" class="bg-yellow-600 text-white px-3 py-2 rounded-md hover:bg-yellow-700 whitespace-nowrap">
                          <Link :href="route('maintenance-orders.show', order.id)" class="flex items-center gap-1">
                            <Icon name="wrench" />
                            <span class="text-xs font-semibold">Mulai Kerja</span>
                          </Link>
                        </Button>
                        <Button v-else-if="order.status === 'DIKERJAKAN'" class="bg-green-600 text-white px-3 py-2 rounded-md hover:bg-green-700 whitespace-nowrap">
                          <Link :href="route('maintenance-orders.show', order.id)" class="flex items-center gap-1">
                            <Icon name="check-circle" />
                            <span class="text-xs font-semibold">Selesaikan</span>
                          </Link>
                        </Button>

                        <!-- Tombol Detail selalu ada -->
                        <Button class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                          <Link :href="route('maintenance-orders.show', order.id)">
                            <Icon name="eye" />
                          </Link>
                        </Button>

                        <!-- Tombol Edit & Hapus -->
                        <ButtonBlue v-if="can('update maintenance order')">
                          <Link :href="route('maintenance-orders.edit', order.id)">
                            <Icon name="pen" />
                          </Link>
                        </ButtonBlue>
                        <ButtonRed v-if="can('delete maintenance order')" @click.prevent="destroy(order)">
                          <Icon name="trash" />
                        </ButtonRed>
                      </div>
                    </td>
                  </tr>

                  <!-- Mobile Card View -->
                  <tr v-for="(order, i) in data" :key="`mobile-${order.id}`" class="block md:hidden border-b">
                    <td colspan="8" class="p-0">
                      <div class="bg-white border rounded-lg shadow-sm mb-3 overflow-hidden">
                        <!-- Header Card dengan Status dan Tombol Detail -->
                        <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-4 py-3 flex justify-between items-center border-b">
                          <div class="flex items-center gap-2">
                            <span class="text-gray-600 font-bold text-sm">#{{ i + 1 }}</span>
                            <span
                              class="font-bold px-3 py-1 rounded-full text-xs text-white"
                              :class="{
                                'bg-blue-500': order.status === 'BARU',
                                'bg-cyan-500': order.status === 'DIPROSES',
                                'bg-yellow-500': order.status === 'DIKERJAKAN',
                                'bg-green-600': order.status === 'SELESAI',
                              }"
                            >
                              {{ order.status }}
                            </span>
                          </div>
                          <Link :href="route('maintenance-orders.show', order.id)">
                            <Button class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 shadow-md">
                              <div class="flex items-center gap-1">
                                <Icon name="eye" class="w-4 h-4" />
                                <span class="text-sm font-semibold">Detail</span>
                              </div>
                            </Button>
                          </Link>
                        </div>

                        <!-- Body Card -->
                        <div class="p-4 space-y-3">
                          <!-- Judul -->
                          <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Judul</p>
                            <p class="font-bold text-gray-800">{{ order.title ?? '-' }}</p>
                          </div>

                          <!-- Mesin -->
                          <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Mesin</p>
                            <p class="font-semibold text-gray-700">
                              {{ order.machine ? `${order.machine.name}${order.machine.type ? ' - ' + order.machine.type : ''}` : '-' }}
                            </p>
                          </div>

                          <!-- Kategori dan Waktu -->
                          <div class="grid grid-cols-2 gap-3">
                            <div>
                              <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Kategori</p>
                              <span
                                class="inline-block font-semibold px-2 py-1 rounded-full text-xs"
                                :class="{
                                  'bg-blue-100 text-blue-800': order.category === 'planned',
                                  'bg-red-100 text-red-800': order.category === 'unplanned',
                                }"
                              >
                                {{ __(order.category ?? '-') }}
                              </span>
                            </div>
                            <div v-if="order.category === 'planned'">
                              <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Rencana Mulai</p>
                              <p class="text-xs font-semibold text-gray-700">
                                {{ new Date(order.plan_start_at).toLocaleString('id-ID') }}
                              </p>
                            </div>
                            <div v-else>
                              <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Waktu Gangguan</p>
                              <p class="text-xs font-semibold text-red-600">
                                {{ new Date(order.trouble_at).toLocaleString('id-ID') }}
                              </p>
                            </div>
                          </div>

                          <!-- Tombol Aksi -->
                          <div class="space-y-2 pt-2 border-t">
                            <!-- Tombol Lanjutkan berdasarkan Status -->
                            <Button v-if="order.status === 'BARU'" class="w-full bg-cyan-600 text-white py-3 rounded-lg hover:bg-cyan-700 shadow-md">
                              <Link :href="route('maintenance-orders.show', order.id)" class="flex items-center justify-center gap-2">
                                <Icon name="arrow-right" class="w-5 h-5" />
                                <span class="font-semibold">Lanjutkan ke Follow Up</span>
                              </Link>
                            </Button>
                            <Button v-else-if="order.status === 'DIPROSES'" class="w-full bg-yellow-600 text-white py-3 rounded-lg hover:bg-yellow-700 shadow-md">
                              <Link :href="route('maintenance-orders.show', order.id)" class="flex items-center justify-center gap-2">
                                <Icon name="wrench" class="w-5 h-5" />
                                <span class="font-semibold">Mulai Pekerjaan</span>
                              </Link>
                            </Button>
                            <Button v-else-if="order.status === 'DIKERJAKAN'" class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 shadow-md">
                              <Link :href="route('maintenance-orders.show', order.id)" class="flex items-center justify-center gap-2">
                                <Icon name="check-circle" class="w-5 h-5" />
                                <span class="font-semibold">Selesaikan Pekerjaan</span>
                              </Link>
                            </Button>

                            <!-- Tombol Edit & Hapus -->
                            <div class="flex gap-2">
                              <ButtonBlue v-if="can('update maintenance order')" class="flex-1">
                                <Link :href="route('maintenance-orders.edit', order.id)" class="flex items-center justify-center gap-1">
                                  <Icon name="pen" class="w-4 h-4" />
                                  <span class="text-sm">Edit</span>
                                </Link>
                              </ButtonBlue>
                              <ButtonRed v-if="can('delete maintenance order')" @click.prevent="destroy(order)" class="flex-1">
                                <div class="flex items-center justify-center gap-1">
                                  <Icon name="trash" class="w-4 h-4" />
                                  <span class="text-sm">Hapus</span>
                                </div>
                              </ButtonRed>
                            </div>
                          </div>
                        </div>
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
  </DashboardLayout>
</template>
