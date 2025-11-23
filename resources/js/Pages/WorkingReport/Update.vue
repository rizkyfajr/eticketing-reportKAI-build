<script setup>
import { onMounted, onUnmounted } from 'vue'
import { useForm, Link, usePage } from '@inertiajs/inertia-vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Card from '@/Components/Card.vue'
import Swal from 'sweetalert2'
import Button from '@/Components/Button.vue'
import Select from '@vueform/multiselect'
import Input from '@/Components/Input.vue'

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
  cuaca: props.report?.cuaca || '',
  jenis_kpjr: props.report?.jenis_kpjr || '',
  nomor_mesin: props.report?.nomor_mesin || '',
  nomor_sarana: props.report?.nomor_sarana || '',
  waktu_start_engine: props.report?.waktu_start_engine || '',
  jam_traveling_awal: props.report?.jam_traveling_awal || '',
  jam_kerja_awal: props.report?.jam_kerja_awal || '',
  jam_mesin_awal: props.report?.jam_mesin_awal || '',
  jam_generator_awal: props.report?.jam_generator_awal || '',
  counter_tamping_awal: props.report?.counter_tamping_awal || '',
  oddometer_awal: props.report?.oddometer_awal || '',
  hsd_awal_kerja: props.report?.hsd_awal_kerja || '',
  operator_by1: props.report?.operator_by1 || '',
  operator_by2: props.report?.operator_by2 || '',
  operator_by3: props.report?.operator_by3 || '',
  approved_by: props.report?.approved_by || '',
  approved_by1: props.report?.approved_by1 || '',
  note: props.report?.note || '',
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
                
                <div class="grid grid-cols-1 gap-4 mb-4 md:grid-cols-2">
                  <div class="flex flex-col">
                    <label class="block text-xs font-semibold">Nama Mesin</label>
                    <!-- <select
                      v-model="form.machine_id"
                      class="w-full border rounded-md px-2 py-2 bg-white text-xs"
                    >
                      <option disabled value="">-- Pilih Mesin --</option>
                      <option v-for="machine in machines" :key="machine.id" :value="machine.id">
                          {{ `${machine.name} - ${machine.type} (${machine.region.name})` }}
                      </option>
                    </select> -->
                    <Select
                      v-model="form.machine_id"
                      class="w-full border rounded-md px-2 py-2 bg-white text-xs"
                      :options="machines.map(machine => ({
                        label: `${machine.name} - ${machine.type} - ${machine.nomor} - ${machine.no_sarana} (${machine.region.name})`,
                        value: machine.id,
                      }))"
                      :searchable="true"
                      placeholder="Pilih Mesin"
                      style="font-size: 0.7rem;"
                    />
                    <InputError :error="form.errors.machine_id" />
                  </div>

                  <div class="flex flex-col">
                    <label class="block text-xs font-semibold">Wilayah</label>      
                    <Select
                      v-model="form.region_id"
                      class="w-full border rounded-md px-2 py-2 bg-white text-xs"
                      :options="regions.map(region => ({
                        label: `${region.name}`,
                        value: region.id,
                      }))"
                      :searchable="true"
                      placeholder="Pilih Mesin"
                      style="font-size: 0.7rem;"
                    />
                    <InputError :error="form.errors.region_id" />
                  </div>

                  <div class="flex flex-col">
                    <label class="block text-xs font-semibold">Tanggal</label>
                    <Input
                      v-model="form.date"
                      type="datetime-local"
                      class="w-full border rounded-md px-2 py-2 text-xs"
                      placeholder="Isi tanggal"
                    />
                  </div>

                  <div class="flex flex-col">
                    <label class="block text-xs font-semibold">Cuaca</label>
                    <Input
                      v-model="form.cuaca"
                      type="text"
                      class="w-full border rounded-md px-2 py-2 text-xs"
                      placeholder="Isi Cuaca"
                    />
                  </div>

                  <div class="flex flex-col">
                    <label class="block text-xs font-semibold">Jenis KPJR</label>
                    <Input
                      v-model="form.jenis_kpjr"
                      type="text"
                      class="w-full border rounded-md px-2 py-2 text-xs"
                      placeholder="Isi Jenis KPJR"
                    />
                  </div>

                  <div class="flex flex-col">
                    <label class="block text-xs font-semibold">Nomor Mesin</label>
                    <Input
                      v-model="form.nomor_mesin"
                      type="text"
                      class="w-full border rounded-md px-2 py-2 text-xs"
                      placeholder="Isi Nomor Mesin"
                    />
                  </div>

                  <div class="flex flex-col">
                    <label class="block text-xs font-semibold">Nomor Sarana</label>
                    <Input
                      v-model="form.nomor_sarana"
                      type="text"
                      class="w-full border rounded-md px-2 py-2 text-xs"
                      placeholder="Isi Nomor Sarana"
                    />
                  </div>

                  <div class="flex flex-col">
                    <label class="block text-xs font-semibold">Waktu Start Engine</label>
                    <Input
                      v-model="form.waktu_start_engine"
                      type="time"
                      class="w-full border rounded-md px-2 py-2 text-xs"
                      placeholder="Isi Waktu Start Engine"
                    />
                  </div>

                  <div class="flex flex-col">
                    <label class="block text-xs font-semibold">Jam Traveling Awal</label>
                    <Input
                      v-model="form.jam_traveling_awal"
                      type="text"
                      class="w-full border rounded-md px-2 py-2 text-xs"
                      placeholder="Isi Jam Traveling Awal"
                    />
                  </div>

                  <div class="flex flex-col">
                    <label class="block text-xs font-semibold">Jam Kerja Awal</label>
                    <Input
                      v-model="form.jam_kerja_awal"
                      type="time"
                      class="w-full border rounded-md px-2 py-2 text-xs"
                      placeholder="Isi Jam Kerja Awal"
                    />
                  </div>

                  <div class="flex flex-col">
                    <label class="block text-xs font-semibold">Jam Mesin Awal</label>
                    <Input
                      v-model="form.jam_mesin_awal"
                      type="text"
                      class="w-full border rounded-md px-2 py-2 text-xs"
                      placeholder="Isi Jam Mesin Awal"
                    />
                  </div>

                  <div class="flex flex-col">
                    <label class="block text-xs font-semibold">Jam Generator Awal</label>
                    <Input
                      v-model="form.jam_generator_awal"
                      type="text"
                      class="w-full border rounded-md px-2 py-2 text-xs"
                      placeholder="Isi Jam Generator Awal"
                    />
                  </div>

                  <div class="flex flex-col">
                    <label class="block text-xs font-semibold">Counter Tamping Awal</label>
                    <Input
                      v-model="form.counter_tamping_awal"
                      type="number"
                      class="w-full border rounded-md px-2 py-2 text-xs"
                      placeholder="Isi Counter Tamping Awal"
                    />
                  </div>

                  <div class="flex flex-col">
                    <label class="block text-xs font-semibold">Oddometer Awal</label>
                    <Input
                      v-model="form.oddometer_awal"
                      type="number"
                      class="w-full border rounded-md px-2 py-2 text-xs"
                      placeholder="Isi Oddometer Awal"
                    />
                  </div>

                  <div class="flex flex-col">
                    <label class="block text-xs font-semibold">HSD Awal Kerja</label>
                    <Input
                      v-model="form.hsd_awal_kerja"
                      type="number"
                      class="w-full border rounded-md px-2 py-2 text-xs"
                      placeholder="Isi HSD Awal Kerja"
                    />
                  </div>

                  <div class="flex flex-col">
                    <label for="operator_by1" class="block text-xs font-semibold">
                      {{ __('Operator 1') }}
                    </label>
                    
                    <Select
                      v-model="form.operator_by1"
                      :options="users.filter(user => user.id !== 1).map(user => ({
                        label: `${user.name} - ${user.username}`,
                        value: user.id,
                      }))"
                      :searchable="true"
                      placeholder="Pilih Operator 1"
                      style="font-size: 0.7rem;"
                    />
                    <InputError :error="form.errors.operator_by1" />
                  </div>

                  <div class="flex flex-col">
                    <label for="operator_by2" class="block text-xs font-semibold">
                      {{ __('Operator 2') }}
                    </label>
                    
                    <Select
                      v-model="form.operator_by2"
                      :options="users.filter(user => user.id !== 1).map(user => ({
                        label: `${user.name} - ${user.username}`,
                        value: user.id,
                      }))"
                      :searchable="true"
                      placeholder="Pilih Operator 2"
                      style="font-size: 0.7rem;"
                    />
                    <InputError :error="form.errors.operator_by2" />
                  </div>

                  <div class="flex flex-col">
                    <label for="operator_by3" class="block text-xs font-semibold">
                      {{ __('Operator 3') }}
                    </label>
                    
                    <Select
                      v-model="form.operator_by3"
                      :options="users.filter(user => user.id !== 1).map(user => ({
                        label: `${user.name} - ${user.username}`,
                        value: user.id,
                      }))"
                      :searchable="true"
                      placeholder="Pilih Operator 3"
                      style="font-size: 0.7rem;"
                    />
                    <InputError :error="form.errors.operator_by3" />
                  </div>

                  <div class="flex flex-col">
                    <label for="approved_by" class="block text-xs font-semibold">
                      {{ __('Pengawal 1') }}
                    </label>
                    
                    <Select
                      v-model="form.approved_by"
                      :options="users.filter(user => user.id !== 1).map(user => ({
                        label: `${user.name} - ${user.username}`,
                        value: user.id,
                      }))"
                      :searchable="true"
                      placeholder="Pilih Pengawal 1"
                      style="font-size: 0.7rem;"
                    />
                    <InputError :error="form.errors.approved_by" />
                  </div>
                  

                  <div class="flex flex-col">
                    <label for="approved_by1" class="block text-xs font-semibold">
                      {{ __('Pengawal 2') }}
                    </label>
                    
                    <Select
                      v-model="form.approved_by1"
                      :options="users.filter(user => user.id !== 1).map(user => ({
                        label: `${user.name} - ${user.username}`,
                        value: user.id,
                      }))"
                      :searchable="true"
                      placeholder="Pilih Pengawal 2"
                      style="font-size: 0.7rem;"
                    />
                    <InputError :error="form.errors.approved_by1" />
                  </div>

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
