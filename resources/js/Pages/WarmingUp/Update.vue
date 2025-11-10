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
    jam_kerja: props.warmingup?.jam_kerja?.slice(0, 5) || null,
    jam_mesin: props.warmingup?.jam_mesin?.slice(0, 5) || null,
    jam_genset: props.warmingup?.jam_genset?.slice(0, 5) || null,
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
    return form.patch(route('warming-up.update', form.id), {
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
            <form @submit.prevent="submit" class="gap-6 p-4">
              <div class="space-y-4">
                
                <div class="grid grid-cols-1 gap-4 mb-4 md:grid-cols-2">
                  <div class="flex flex-col items-start space-y-1">
                    <label for="machine_id"  class="font-bold text-xs">
                      {{ __('Nama Mesin') }}
                    </label>
                    
                    <Select
                      v-model="form.machine_id"
                      :options="machines.map(machine => ({
                        label: `${machine.name} - ${machine.type} - ${machine.nomor} - ${machine.no_sarana} (${machine.region.name})`,
                        value: machine.id,
                      }))"
                      :searchable="true"
                      placeholder="Pilih Mesin"
                      style="font-size: 0.7rem;"
                      required
                    />

                    <InputError :error="form.errors.machine_id"/>
                  </div> 

                  <div class="flex flex-col items-start space-y-1">
                    <label for="waktu_start_engine" class="font-bold text-xs">
                      {{ __('Waktu Start Engine') }}
                    </label>
                    
                    <Input
                      v-model="form.waktu_start_engine"
                      :placeholder="__('Waktu Start Engine')"
                      type="datetime-local"
                      class="text-xs"
                      required
                    />

                    <InputError :error="form.errors.waktu_start_engine"/>
                  </div>

                  <div class="flex flex-col items-start space-y-1">
                    <label for="jam_kerja" class="font-bold text-xs">
                      {{ __('Jam Kerja') }}
                    </label>
                    
                    <Input
                      v-model="form.jam_kerja"
                      :placeholder="__('Jam Kerja')"
                      type="time"
                      class="text-xs"
                      required
                    />     

                    <InputError :error="form.errors.jam_kerja"/>
                  </div>
                
                  <div class="flex flex-col items-start space-y-1">
                    <label for="jam_mesin" class="font-bold text-xs">
                      {{ __('Jam Mesin') }}
                    </label>
                    
                    <Input
                      v-model="form.jam_mesin"
                      :placeholder="__('Jam Mesin')"
                      type="time"
                      class="text-xs"
                      required
                      step="60"  
                    />

                    <InputError :error="form.errors.jam_mesin"/>
                  </div>

                  <div class="flex flex-col items-start space-y-1">
                    <label for="jam_genset" class="font-bold text-xs">
                      {{ __('Jam Genset') }}
                    </label>
                    
                    <Input
                      v-model="form.jam_genset"
                      :placeholder="__('Jam Genset')"
                      type="time"
                      class="text-xs"
                      required
                    />

                    <InputError :error="form.errors.jam_genset"/>
                  </div>

                  <div class="flex flex-col items-start space-y-1">
                    <label for="counter_pecok" class="font-bold text-xs">
                      {{ __('Counter Pecok') }}
                    </label>

                    <Input
                      v-model="form.counter_pecok"
                      :placeholder="__('Counter Pecok')"
                      type="number"
                      class="text-xs"
                      required
                    />

                    <InputError :error="form.errors.counter_pecok"/>
                  </div>

                  <div class="flex flex-col items-start space-y-1">
                    <label for="oddometer" class="font-bold text-xs">
                      {{ __('Oddo Meter') }}
                    </label>
                    
                    <Input
                      v-model="form.oddometer"
                      :placeholder="__('Oddo Meter')"
                      type="number"
                      step="any"
                      class="text-xs"
                      required
                    />

                    <InputError :error="form.errors.oddometer"/>
                  </div>

                  <div class="flex flex-col items-start space-y-1">
                    <label for="waktu_stop_engine" class="font-bold text-xs">
                      {{ __('Waktu Stop Engine') }}
                    </label>
                    
                    <Input
                      v-model="form.waktu_stop_engine"
                      :placeholder="__('Waktu Stop Engine')"
                      type="datetime-local"
                      class="text-xs"
                      required
                    />
                      
                    <InputError :error="form.errors.waktu_stop_engine"/>
                  </div>

                  <div class="flex flex-col items-start space-y-1">
                    <label for="penggunaan_hsd" class="font-bold text-xs">
                      {{ __('Penggunaan HSD') }}
                    </label>
                    
                    <div class="flex items-center w-full border border-gray-300 rounded-md px-2 py-1 focus-within:ring-1 focus-within:ring-blue-400">
                      <Input
                        v-model="form.penggunaan_hsd"
                        :placeholder="__('Penggunaan HSD')"
                        type="number"
                        class="text-xs border-none focus:ring-0 w-full"
                        required
                      />
                      <span class="ml-1 text-gray-600 text-xs font-semibold">Liter</span>
                    </div>
                          
                    <InputError :error="form.errors.penggunaan_hsd"/>
                  </div>

                  <div class="flex flex-col items-start space-y-1">
                      <label for="hsd_tersedia" class="font-bold text-xs">
                        {{ __('HSD Tersedia') }}
                      </label>

                      <div class="flex items-center w-full border border-gray-300 rounded-md px-2 py-1 focus-within:ring-1 focus-within:ring-blue-400">
                        <Input
                          v-model="form.hsd_tersedia"
                          :placeholder="__('HSD Tersedia')"
                          type="number"
                          class="text-xs border-none focus:ring-0 w-full"
                          required
                        />
                        <span class="ml-1 text-gray-600 text-xs font-semibold">%</span>
                      </div>

                      <InputError :error="form.errors.hsd_tersedia" />
                    </div>
                  
                    <div class="flex flex-col items-start space-y-1">
                      <label for="user_id" class="font-bold text-xs">
                        {{ __('Crew') }}
                      </label>
                      
                      <Select
                        v-model="form.user_id"
                        :options="users.filter(user => user.id !== 1).map(user => ({
                          label: user.name,
                          value: user.id,
                        }))"
                        :searchable="true"
                        mode="tags"
                        placeholder="Pilih Crew"
                        required
                        style="font-size: 0.5rem;"
                      />

                      <InputError :error="form.errors.user_id"/>
                    </div> 

                    <div class="flex flex-col items-start space-y-1">
                      <label for="note" class="font-bold text-xs">
                        {{ __('Keterangan') }}
                      </label>
                      
                      <TextArea
                        v-model="form.note"
                        :placeholder="__('Keterangan')"
                        type="text"
                        class="text-xs"
                      />
                      <InputError :error="form.errors.note"/>
                    </div>
                  </div>
                  
                  <div class="flex items-center justify-end px-4 py-1 rounded space-x-2 p-2 pr-[1.100rem]">
                    <Link
                      :href="route('warming-up.index')"
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
