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

    <Card class="bg-white pt-[1.875rem] pb-[2.5rem] shadow-lg border border-solid border-slate-200" style="border-radius: 0.625rem;">
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
          <!-- Ganti url paginate sesuai backend-mu -->
          <Builder v-if="render" :url="route('maintenance-orders.paginate')" ref="table">
            <template #thead="table">
              <tr class="bg-gray-200 border-gray-300">
                <Th :table="table" :sort="false" class="border px-3 py-2 text-center font-bold">#</Th>
                <Th :table="table" :sort="true"  name="title" class="border px-3 py-2 text-center font-bold">{{ __('judul') }}</Th>
                <Th :table="table" :sort="true"  name="master_machine_id" class="border px-3 py-2 text-center whitespace-nowrap font-bold">{{ __('mesin') }}</Th>
                <Th :table="table" :sort="true"  name="working_report_id" class="border px-3 py-2 text-center whitespace-nowrap font-bold">{{ __('working report') }}</Th>
                <Th :table="table" :sort="true"  name="category" class="border px-3 py-2 text-center font-bold">{{ __('kategori') }}</Th>
                <Th :table="table" :sort="true"  name="trouble_at" class="border px-3 py-2 text-center whitespace-nowrap font-bold">{{ __('waktu gangguan') }}</Th>
                <Th :table="table" :sort="false" class="border px-3 py-2 text-center font-bold">{{ __('Action') }}</Th>
              </tr>
            </template>

            <template #tfoot="table">
              <tr class="bg-gray-200 border-gray-300">
                <Th :table="table" :sort="false" class="border px-3 py-2 text-center font-bold">#</Th>
                <Th :table="table" :sort="false" class="border px-3 py-2 text-center font-bold">{{ __('judul') }}</Th>
                <Th :table="table" :sort="false" class="border px-3 py-2 text-center font-bold">{{ __('mesin') }}</Th>
                <Th :table="table" :sort="false" class="border px-3 py-2 text-center font-bold">{{ __('working report') }}</Th>
                <Th :table="table" :sort="false" class="border px-3 py-2 text-center font-bold">{{ __('kategori') }}</Th>
                <Th :table="table" :sort="false" class="border px-3 py-2 text-center font-bold">{{ __('waktu gangguan') }}</Th>
                <Th :table="table" :sort="false" class="border px-3 py-2 text-center font-bold">{{ __('Action') }}</Th>
              </tr>
            </template>

            <template #tbody="{ data, processing, empty }">
              <TransitionGroup enterActiveClass="transition-all duration-200"
                               leaveActiveClass="transition-all duration-200"
                               enterFromClass="opacity-0 -scale-y-100"
                               leaveToClass="opacity-0 -scale-y-100">
                <template v-if="empty">
                  <tr>
                    <td class="text-5xl text-center p-4" colspan="1000">
                      <p class="lowercase first-letter:capitalize font-semibold">
                        {{ __('Tidak ada data untuk ditampilkan.') }}
                      </p>
                    </td>
                  </tr>
                </template>

                <template v-else>
                  <tr v-for="(order, i) in data" :key="order.id" :class="processing && 'bg-gray-100'"
                      class="transition-all duration-300 h-[4.375rem]">
                    <td class="px-2 py-1 border-b text-center">{{ i + 1 }}</td>

                    <td class="font-semibold text-center border-b">
                      {{ order.title ?? '-' }}
                    </td>

                    <td class="capitalize font-semibold text-center border-b">
                      {{ order.machine ? `${order.machine.name}${order.machine.type ? ' - ' + order.machine.type : ''}` : '-' }}
                    </td>

                    <td class="font-semibold text-center border-b">
                      {{ order.working_report_id ? ('WR #' + order.working_report_id) : '-' }}
                    </td>

                    <td class="capitalize font-semibold text-center border-b">
                      {{ __(order.category ?? '-') }}
                    </td>

                    <td class="capitalize font-semibold text-center border-b">
                      {{ new Date(order.trouble_at).toLocaleString('id-ID') }}
                    </td>

                    <td class="px-2 py-2 border-b text-center">
                      <div class="flex justify-center gap-2">
                        <Button class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                          <Link :href="route('maintenance-orders.show', order.id)">
                            <Icon name="eye" />
                          </Link>
                        </Button>

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
                </template>
              </TransitionGroup>
            </template>
          </Builder>
        </div>
      </template>
    </Card>
  </DashboardLayout>
</template>
