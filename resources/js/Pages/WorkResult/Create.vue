<script setup>
import { onMounted, onUnmounted } from 'vue'
import { useForm, usePage } from '@inertiajs/inertia-vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Card from '@/Components/Card.vue'
import Swal from 'sweetalert2'

const props = defineProps({
  result: Object,
  machines: Array,
  regions: Array,
  users: Array,
  reportuser: Array,
})

const { user } = usePage().props.value

const form = useForm({
  machine_id: props.result?.machine_id || '',
  region_id: props.result?.region_id || '',
  antara: props.result?.antara || '',
  km_hm: props.result?.km_hm || '',
  jumlah_msp: props.result?.jumlah_msp || '',
  waktu_start_engine: props.result?.waktu_start_engine || '',
  jam_luncuran: props.result?.jam_luncuran || '',
  jam_kerja: props.result?.jam_kerja || '',
  jam_mesin: props.result?.jam_mesin || '',
  jam_genset: props.result?.jam_genset || '',
  counter_pecok: props.result?.counter_pecok || '',
  oddometer: props.result?.oddometer || '',
  penggunaan_hsd: props.result?.penggunaan_hsd || '',
  hsd_tersedia: props.result?.hsd_tersedia || '',
  pengawal_id: props.result?.pengawal_id || '',
  note: props.result?.note || '',
})

const submit = () => {
  Swal.fire({
    title: 'Menyimpan data...',
    didOpen: () => Swal.showLoading(),
    allowOutsideClick: false,
  })

  form.post(route('work-results.store'), {
    onSuccess: () => {
      Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: 'Laporan kerja berhasil disimpan.',
        timer: 1500,
        showConfirmButton: false,
      })
    },
    onError: () => {
      Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: 'Terjadi kesalahan saat menyimpan data.',
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
  <DashboardLayout :title="__('Laporan Pekerjaan')">
    <div class="transition-all duration-300">
      <main class="p-4">
        <h2 class="font-bold text-lg mb-1">Laporan Pekerjaan</h2>
        <p class="text-sm text-gray-500 mb-4">Halaman Tambah Data Laporan Pekerjaan</p>
        <Card class="bg-white dark:bg-gray-700 dark:text-gray-100 shadow-md">

          <template #header>
             <div class="flex justify-center items-center p-2 bg-gray-200 dark:bg-gray-800">
              <p class="font-semibold text-center">Form Input Laporan Pekerjaan</p>
            </div>
          </template>

          <template #body>
            <form @submit.prevent="submit" class="grid grid-cols-2 gap-6 p-4">

              <div>
                <label class="block text-sm font-semibold mb-1">Nama Mesin</label>
                <select
                  v-model="form.machine_id"
                  class="w-full border rounded-md px-3 py-2 bg-white"
                >
                  <option disabled value="">-- Pilih Mesin --</option>
                  <option v-for="machine in machines" :key="machine.id" :value="machine.id">
                    {{ machine.name }}
                  </option>
                </select>
                <div v-if="form.errors.machine_id" class="text-red-500 text-sm">
                  {{ form.errors.machine_id }}
                </div>
              </div>

              <div>
                <label class="block text-sm font-semibold mb-1">Wilayah</label>
                <select
                  v-model="form.region_id"
                  class="w-full border rounded-md px-3 py-2 bg-white"
                >
                  <option disabled value="">-- Pilih Wilayah --</option>
                  <option v-for="region in regions" :key="region.id" :value="region.id">
                    {{ region.name }}
                  </option>
                </select>
                <div v-if="form.errors.region_id" class="text-red-500 text-sm">
                  {{ form.errors.region_id }}
                </div>
              </div>

              <div>
                <label class="block text-sm font-semibold mb-1">Antara</label>
                <input
                  v-model="form.antara"
                  type="text"
                  class="w-full border rounded-md px-3 py-2"
                  placeholder="Masukkan antara"
                />
              </div>

              <div>
                <label class="block text-sm font-semibold mb-1">Km/Hm</label>
                <input
                  v-model="form.km_hm"
                  type="number"
                  class="w-full border rounded-md px-3 py-2"
                  placeholder="Masukkan Km/Hm"
                />
              </div>

              <div>
                <label class="block text-sm font-semibold mb-1">Jumlah MSP</label>
                <input
                  v-model="form.jumlah_msp"
                  type="number"
                  class="w-full border rounded-md px-3 py-2"
                  placeholder="Masukkan jumlah MSP"
                />
              </div>

              <div>
                <label class="block text-sm font-semibold mb-1">Waktu Start Engine</label>
                <input
                  v-model="form.waktu_start_engine"
                  type="datetime-local"
                  class="w-full border rounded-md px-3 py-2"
                />
              </div>

              <div>
                <label class="block text-sm font-semibold mb-1">Jam Luncuran</label>
                <input
                  v-model="form.jam_luncuran"
                  type="time"
                  class="w-full border rounded-md px-3 py-2"
                />
              </div>

              <div>
                <label class="block text-sm font-semibold mb-1">Jam Kerja</label>
                <input
                  v-model="form.jam_kerja"
                  type="time"
                  class="w-full border rounded-md px-3 py-2"
                />
              </div>

              <div>
                <label class="block text-sm font-semibold mb-1">Jam Mesin</label>
                <input
                  v-model="form.jam_mesin"
                  type="time"
                  class="w-full border rounded-md px-3 py-2"
                />
              </div>

              <div>
                <label class="block text-sm font-semibold mb-1">Jam Genset</label>
                <input
                  v-model="form.jam_genset"
                  type="time"
                  class="w-full border rounded-md px-3 py-2"
                />
              </div>

              <div>
                <label class="block text-sm font-semibold mb-1">Counter Pecok</label>
                <input
                  v-model="form.counter_pecok"
                  type="number"
                  class="w-full border rounded-md px-3 py-2"
                />
              </div>

              <div>
                <label class="block text-sm font-semibold mb-1">Oddometer</label>
                <input
                  v-model="form.oddometer"
                  type="number"
                  class="w-full border rounded-md px-3 py-2"
                />
              </div>

              <div>
                <label class="block text-sm font-semibold mb-1">Penggunaan HSD</label>
                <input
                  v-model="form.penggunaan_hsd"
                  type="number"
                  step="0.01"
                  class="w-full border rounded-md px-3 py-2"
                />
              </div>

              <div>
                <label class="block text-sm font-semibold mb-1">HSD Tersedia</label>
                <input
                  v-model="form.hsd_tersedia"
                  type="number"
                  step="0.01"
                  class="w-full border rounded-md px-3 py-2"
                />
              </div>

              <!-- <div>
                <label class="block text-sm font-semibold mb-1">Crew</label>
                <select
                  v-model="form.users"
                  class="w-full border rounded-md px-3 py-2 bg-white"
                >
                  <option value="">Pilih Crew</option>
                  <option
                    :options="users.map(user => ({
                    label: user.name,
                    value: user.id,
                    }))"
                    :searchable="true"
                    mode="tags"
                    class="uppercase"
                    placeholder="receiver"
                  >
                    {{ user.name }}
                  </option>
                </select>
              </div> -->

              <div>
                <label class="block text-sm font-semibold mb-1">Pengawal</label>
                <select
                  v-model="form.pengawal_id"
                  class="w-full border rounded-md px-3 py-2 bg-white"
                >
                  <option value="">Pilih Pengawal</option>
                  <option
                    v-for="user in props.users"
                    :key="user.id"
                    :value="user.id"
                  >
                    {{ user.name }}
                  </option>
                </select>

                <div v-if="form.errors.pengawal_id" class="text-red-500 text-sm">
                  {{ form.errors.pengawal_id }}
                </div>
              </div>

              <div class="col-span-2">
                <label class="block text-sm font-semibold mb-1">Keterangan</label>
                <textarea
                  v-model="form.note"
                  class="w-full border rounded-md px-3 py-2"
                  rows="3"
                  placeholder="Tambahkan keterangan tambahan..."
                ></textarea>
              </div>

              <div class="col-span-2 flex justify-end mt-4">
                <button
                  type="submit"
                  class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700"
                >
                  Simpan
                </button>
              </div>
            </form>
          </template>
        </Card>
      </main>
    </div>
  </DashboardLayout>
</template>
