<script setup>
import { getCurrentInstance, nextTick, onMounted, onUnmounted, ref } from 'vue'
import { useForm, Link, usePage } from '@inertiajs/inertia-vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Card from '@/Components/Card.vue'
import Swal from 'sweetalert2'
import Select from '@vueform/multiselect'
import Button from '@/Components/Button.vue'
import Input from '@/Components/Input.vue'
import InputError from '@/Components/InputError.vue'
import TextArea from '@/Components/TextArea.vue'

const props = defineProps({
    warmingup: Object,
    warmingup_user: Array,
    regions: Array,
    users: Array,
    machines: Array,
})

const form = useForm({
    id: props.warmingup?.id || null,
    machine_id: props.warmingup?.machine_id || null,
    waktu_start_engine: props.warmingup?.waktu_start_engine || null,
    jam_kerja: props.warmingup?.jam_kerja || null,
    jam_mesin: props.warmingup?.jam_mesin || null,
    jam_genset: props.warmingup?.jam_genset || null,
    counter_pecok: props.warmingup?.counter_pecok || null,
    oddometer: props.warmingup?.oddometer || null,
    waktu_stop_engine: props.warmingup?.waktu_stop_engine || null,
    penggunaan_hsd: props.warmingup?.penggunaan_hsd || null,
    hsd_tersedia: props.warmingup?.hsd_tersedia || null,
    note: props.warmingup?.note || null,
    user_id: props.warmingup?.warmingup_user.map(warmingup_user => warmingup_user.user_id) || [],
});

const render = ref(true)
const self = getCurrentInstance()
const table = ref(null)
const open = ref(false)
const show = () => open.value = true

const close = () => {
    form.reset()
    render.value = false
    nextTick(() => {
        render.value = true
        nextTick(() => open.value = false)
    })
}

const store = () => {
  Swal.fire({
    title: 'Menyimpan data...',
    didOpen: () => Swal.showLoading(),
    allowOutsideClick: false,
  })

  form.post(route('warming-up.update', props.id), {
    onSuccess: () => {
      Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: 'Data berhasil disimpan.',
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

const update = () => {
    return form.patch(route('master-regions.update', form.id), {
        onSuccess: () => close(),
        onError: () => show(),
    })
}

const submit = () => form.id ? update() : store()

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
            <div class="p-4">
            <h1 class="text-center text-sm font-bold py-2">Detail Warming Up</h1><hr class="py-1">
              <div class="grid grid-cols-1 md:grid-cols-1 gap-x-6 gap-y-3 text-xs">
                
                <div class="flex py-1">
                  <span class="font-bold w-32">Nama Mesin</span>
                  <span>: {{ warmingup.machine ? `${warmingup.machine.name} - ${warmingup.machine.type} - ${warmingup.machine.nomor} - ${warmingup.machine.no_sarana} (${warmingup.machine.region?.name})` : '-' }}</span>
                </div>

                <div class="flex py-1">
                  <span class="font-bold w-32">Waktu Start Engine</span>
                  <span>: {{ warmingup.waktu_start_engine }}</span>
                </div>

                <div class="flex py-1">
                  <span class="font-bold w-32">Jam Kerja</span>
                  <span>: {{ warmingup.jam_kerja ? warmingup.jam_kerja.slice(0,5) : '-' }}</span>
                </div>

                <div class="flex py-1">
                  <span class="font-bold w-32">Jam Mesin</span>
                  <span>: {{ warmingup.jam_mesin ? warmingup.jam_mesin.slice(0,5) : '-' }}</span>
                </div>

                <div class="flex py-1">
                  <span class="font-bold w-32">Jam Genset</span>
                  <span>: {{ warmingup.jam_genset ? warmingup.jam_genset.slice(0,5) : '-' }}</span>
                </div>

                <div class="flex py-1">
                  <span class="font-bold w-32">Counter Pecok</span>
                  <span>: {{ warmingup.counter_pecok }}</span>
                </div>

                <div class="flex py-1">
                  <span class="font-bold w-32">Oddo Meter</span>
                  <span>: {{ warmingup.oddometer }}</span>
                </div>

                <div class="flex py-1">
                  <span class="font-bold w-32">Waktu Stop Engine</span>
                  <span>: {{ warmingup.waktu_stop_engine }}</span>
                </div>

                <div class="flex py-1">
                  <span class="font-bold w-32">Penggunaan HSD</span>
                  <span>: {{ warmingup.penggunaan_hsd }}</span>
                </div>

                <div class="flex py-1">
                  <span class="font-bold w-32">HSD Tersedia</span>
                  <span>: {{ warmingup.hsd_tersedia }}</span>
                </div>

                <div class="flex py-1">
                  <span class="font-bold w-32">Crew</span>
                   <span v-if="warmingup.warmingup_user?.length">
                    : {{ warmingup.warmingup_user.map(u => u.user?.name).join(', ') }}
                  </span>
                  <span v-else>-</span>
                </div>

                <div class="flex py-1">
                  <span class="font-bold w-32">Keterangan</span>
                  <span>: {{ warmingup.note }}</span>
                </div>
              </div>

              <div class="flex justify-end mt-6">
                <Link
                  :href="route('warming-up.index')"
                  class="bg-gray-600 text-white text-xs px-3 py-1 rounded-md hover:bg-gray-700 flex items-center space-x-1"
                >
                  <Icon name="arrow-left" />
                  <p class="font-bold text-xs">Kembali</p>
                </Link>
              </div>
            </div>
          </template>

        </Card>
    </div>
  </DashboardLayout>
</template>
