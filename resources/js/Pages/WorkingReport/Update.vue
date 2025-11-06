<script setup>
import { onMounted, onUnmounted } from 'vue'
import { useForm, Link, usePage } from '@inertiajs/inertia-vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Card from '@/Components/Card.vue'
import Swal from 'sweetalert2'
import Button from '@/Components/Button.vue'

const props = defineProps({
  report: Object,
  machines: Array,
  regions: Array,
  users: Array,
  reportuser: Array,
})

const { user } = usePage().props.value

const form = useForm({
  machine_id: props.report?.machine_id || '',
  region_id: props.report?.region_id || '',
  date: props.report?.date || '',
  status: props.report?.status || '',
})

const submit = () => {
  Swal.fire({
    title: 'Menyimpan data...',
    didOpen: () => Swal.showLoading(),
    allowOutsideClick: false,
  })

  form.patch(route('working-reports.update', props.report.id), {
    onSuccess: () => {
      Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: 'Working Report berhasil diubah.',
        timer: 1500,
        showConfirmButton: false,
      })
    },
    onError: () => {
      Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: 'Terjadi kesalahan saat ubah data.',
      })
    },
  })
}

const esc = e => e.key === 'Escape' && close()
onMounted(() => window.addEventListener('keydown', esc))
onUnmounted(() => window.removeEventListener('keydown', esc))
</script>

<style src="@vueform/multiselect/themes/default.css"></style>
<style src="@/multiselect.css"></style>

<template>
  <DashboardLayout :title="__('Working Order')">
    <div class="transition-all duration-300">
        <Card class="bg-white dark:bg-gray-700 dark:text-gray-100 shadow-md">
          <template #body>
            <form @submit.prevent="submit" class="gap-6 p-4">
              <div class="space-y-4">
                
                <div class="flex flex-col">
                  <label class="block text-xs font-semibold mb-1 text-xs">Nama Mesin</label>
                  <select
                    v-model="form.machine_id"
                    class="w-full border rounded-md px-3 py-2 bg-white text-xs"
                  >
                    <option disabled value="">-- Pilih Mesin --</option>
                    <option v-for="machine in machines" :key="machine.id" :value="machine.id">
                        {{ `${machine.name} - ${machine.type} (${machine.region.name})` }}
                    </option>
                  </select>
                  <div v-if="form.errors.machine_id" class="text-red-500 text-xs">
                    {{ form.errors.machine_id }}
                  </div>
                </div>

                <div>
                  <label class="block text-xs font-semibold mb-1 text-xs">Wilayah</label>
                  <select
                    v-model="form.region_id"
                    class="w-full border rounded-md px-3 py-2 bg-white text-xs"
                  >
                    <option disabled value="">-- Pilih Wilayah --</option>
                    <option v-for="region in regions" :key="region.id" :value="region.id">
                      {{ region.name }}
                    </option>
                  </select>
                  <div v-if="form.errors.region_id" class="text-red-500 text-xs">
                    {{ form.errors.region_id }}
                  </div>
                </div>

                <div>
                  <label class="block text-xs font-semibold mb-1 text-xs">Tanggal</label>
                  <input
                    v-model="form.date"
                    type="datetime-local"
                    class="w-full border rounded-md px-3 py-2 text-xs"
                  />
                </div>

                <div class="flex items-center justify-end px-4 py-1 rounded space-x-2 p-2 pr-[1.100rem]">

                  <Link
                    :href="route('working-reports.index')"
                    class="bg-gray-600 text-white text-xs px-3 py-1 rounded-md hover:bg-gray-700 mt-10 flex items-center space-x-1"
                  >
                    <Icon name="edit" />
                    <p class="font-bold text-xs">Kembali</p>
                  </Link>

                  <Button
                    type="submit"
                    class="bg-green-600 text-white text-xs px-3 py-1 rounded-md hover:bg-green-700 mt-10"
                  >
                    <p class="font-bold text-xs">Ubah</p>
                  </Button>

                </div>
              </div>
            </form>
          </template>
        </Card>
    </div>
  </DashboardLayout>
</template>
