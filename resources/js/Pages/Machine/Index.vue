<script setup>
import { getCurrentInstance, nextTick, onMounted, onUnmounted, ref, watch } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { useForm, Link, usePage } from '@inertiajs/inertia-vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Card from '@/Components/Card.vue'
import Icon from '@/Components/Icon.vue'
import Builder from '@/Components/DataTable/Builder.vue'
import Th from '@/Components/DataTable/Th.vue'
import Swal from 'sweetalert2'
import Modal from '@/Components/Modal.vue'
import Close from '@/Components/Button/Close.vue'
import Button from '@/Components/Button.vue'
import ButtonBlue from '@/Components/Button/Blue.vue'
import ButtonGreen from '@/Components/Button/Green.vue'
import ButtonRed from '@/Components/Button/Red.vue'
import Input from '@/Components/Input.vue'
import InputError from '@/Components/InputError.vue'
import Select from '@vueform/multiselect'

const { machine } = defineProps({
    machine: Array,
    regions: Array
})

const form = useForm({
  region_id: '',
  name: '',
  type: '',
//   code: '',
  nomor: '',
  tahun_md: '',
  umur: '',
  no_sarana: '',
  keterangan: '',
})

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

watch(() => form.tahun_md, (value) => {
    if (!value) {
        form.umur = '';
        return;
    }

    const currentYear = new Date().getFullYear();
    form.umur = currentYear - parseInt(value);
});

const store = () => {
    return form.post(route('master-machines.store'), {
        onSuccess: () => close(),
        onError: () => show(),
    })
}

const edit = (machine) => {
    form.id = machine.id
    form.region_id = machine.region_id
    form.name = machine.name
    form.type = machine.type
    // form.code = machine.code
    form.nomor = machine.nomor
    form.tahun_md = machine.tahun_md
    form.umur = machine.umur
    form.no_sarana = machine.no_sarana
    form.keterangan = machine.keterangan
    show()
}

const update = () => {
    return form.patch(route('master-machines.update', form.id), {
        onSuccess: () => close(),
        onError: () => show(),
    })
}

const destroy = async machien => {
    const response = await Swal.fire({
        title: __('Apakah Anda Yakin') + '?',
        text: __('Anda tidak dapat mengembalikannya setelah dihapus'),
        icon: 'question',
        showCancelButton: true,
        showCloseButton: true,
    })

    if (response.isConfirmed) {
        return form.delete(route('master-machines.destroy', machien.id), {
            onFinish: close,
        })
    }
}

const submit = () => form.id ? update() : store()

const esc = e => e.key === 'Escape' && close()
onMounted(() => window.addEventListener('keydown', esc))
onUnmounted(() => window.removeEventListener('keydown', esc))

</script>

<style src="@vueform/multiselect/themes/default.css"></style>
<style src="@/multiselect.css"></style>

<template>
    <DashboardLayout :title="__('Mesin')">
        <!-- <div class="transition-all duration-300" :class="{
            'pl-1 md:pl-64': open,
        }">
        <main class="p-0 py-0 mb-[1.25rem] ml-[1.25rem] mt-[1.25rem]">
            <h2 class="font-bold text-2xl">Master Data Mesin</h2>
           <a type="button" href="/" class="text-sm text-gray-500 font-semibold hover:text-sky-600 focus:text-sky-600">Home</a> 
           <span class="font-semibold text-sm pl-2 pr-2">-</span>
           <span class="text-sm text-gray-500 font-semibold hover:text-sky-600 focus:text-sky-700">Master</span> 
            <slot />
        </main>
        </div> -->     
        <Card class="bg-white pt-[1.100rem] pb-[2.5rem] shadow-lg border border-solid border-slate-200" style="border-radius: 0.625rem;">
            <template #header>
                <!-- <h1 class="w-full flex justify-center items-center h-[80px] text-2xl font-bold">Data <span class="ml-2 mr-2"
                        style="font-family: taviraj;"></span>Divison</h1> -->
                <div class="flex items-center justify-end px-4 py-1 rounded space-x-2 p-2 pr-[1.688rem]">
                    <Button v-if="can('create machine')" @click.prevent="form.id = null; show()"
                        class="grid md:grid-cols text-center items-center bg-green-600 hover:bg-green-800"
                       >
                        <!-- <Icon name="plus" class="w-full" /> -->
                        <p class="font-bold text-xs">
                            {{ __('Tambah') }}
                        </p>
                    </Button>
                </div>
            </template>

            <template #body>
                <div class="flex flex-col space-y-2">
                    <Builder v-if="render" :url="route('master-machines.paginate')" ref="table">
                        <template #thead="table">
                            <tr class="bg-gray-100 border-b border-gray-300">
                                <Th :table="table" :sort="false" name="id"
                                    class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                    {{ __('no').toUpperCase() }}
                                </Th>

                                <Th :table="table" :sort="false" name="region_id"
                                    class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                    {{ __('Daop').toUpperCase() }}
                                </Th>   

                                <Th :table="table" :sort="false" name="name"
                                    class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                    {{ __('Jenis').toUpperCase() }}
                                </Th>    

                                <Th :table="table" :sort="false" name="type"
                                    class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                    {{ __('type').toUpperCase() }}
                                </Th>  

                                <Th :table="table" :sort="false" name="nomor"
                                    class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                    {{ __('nomor').toUpperCase() }}
                                </Th>  

                                <Th :table="table" :sort="false" name="tahun_md"
                                    class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                    {{ __('tahun mulai dinas').toUpperCase() }}
                                </Th>     

                                <Th :table="table" :sort="false" name="umur"
                                    class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                    {{ __('umur').toUpperCase() }}
                                </Th>     

                                <Th :table="table" :sort="false" name="nomor_sarana"
                                    class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                    {{ __('nomor sarana').toUpperCase() }}
                                </Th>      

                                <Th :table="table" :sort="false" name="keterangan"
                                    class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                    {{ __('keterangan').toUpperCase() }}
                                </Th>                         

                                <Th :table="table" :sort="false"
                                    class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                    {{ __('Action').toUpperCase() }}
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
                                v-for="(machine, i) in data"
                                :key="machine.id"
                                :class="processing && 'bg-gray-100'"
                                class="transition-all duration-300"
                              >
                                <td class="border-b uppercase border-gray-300 px-4 py-1 text-center text-xs">
                                  {{ i + 1 }}
                                </td>

                                <td class="border-b uppercase border-gray-300 px-4 py-1 text-center text-xs">
                                  {{ machine.region?.name }}
                                </td>

                                <td class="border-b uppercase border-gray-300 px-4 py-1 text-center text-xs">
                                  {{ machine.name }}
                                </td>

                                <td class="border-b uppercase border-gray-300 px-4 py-1 text-center text-xs">
                                  {{ machine.type || '-' }}
                                </td>

                                <td class="border-b uppercase border-gray-300 px-4 py-1 text-center text-xs">
                                  {{ machine.nomor || '-' }}
                                </td>

                                <td class="border-b uppercase border-gray-300 px-4 py-1 text-center text-xs">
                                  {{ machine.tahun_md || '-' }}
                                </td>

                                <td class="border-b uppercase border-gray-300 px-4 py-1 text-center text-xs">
                                  {{ machine.umur || '-' }}
                                </td>

                                <td class="border-b uppercase border-gray-300 px-4 py-1 text-center text-xs">
                                  {{ machine.no_sarana || '-' }}
                                </td>

                                <td class="border-b uppercase border-gray-300 px-4 py-1 text-center text-xs">
                                  {{ machine.keterangan || '-' }}
                                </td>

                                <td class="ppx-2 py-1 border-b text-center">
                                  <div class="flex justify-center gap-2">
                                    <ButtonBlue
                                      v-if="can('update machine')"
                                      @click.prevent="edit(machine)"
                                    >
                                      <Icon name="edit" />
                                      <p class="font-bold text-xs">{{ __('Ubah') }}</p>
                                    </ButtonBlue>

                                    <ButtonRed
                                      v-if="can('delete machine')"
                                      @click.prevent="destroy(machine)"
                                    >
                                      <Icon name="trash" />
                                      <p class="font-bold text-xs">{{ __('Hapus') }}</p>
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
            <form @submit.prevent="submit" class="w-full max-w-4xl h-fit shadow-xl">
                <Card class="bg-gray-50">
                    <!-- Close icon -->
                    <template #header>
                        <div
                            class="flex items-center justify-end bg-gray-200 rounded-lg p-2 pb-[5px] pt-[5px]">
                            <Close @click.prevent="close" />
                        </div>
                    </template>

                    <template #body>
                        <div class="flex flex-col space-y-4 p-4">
                            <template v-if="hasRole(['superuser', 'it', 'admin']) && !regis">
                                <div class="flex flex-col space-y-2">
                                  <div class="flex items-center space-x-2">
                                      <label for="region_id" class="w-1/3 capitalize text-sm">
                                          {{ __('Daop') }}
                                      </label>

                                      <Select
                                            v-model="form.region_id"
                                            :options="regions.map(region => ({
                                            label: region.name,
                                            value: region.id,
                                            }))"
                                            :searchable="true"
                                            placeholder="Pilih Daop"
                                            required
                                            style="font-size: 0.9rem;"
                                        />
                                  </div>

                                  <InputError :error="form.errors.region_id" />
                                </div>

                                <div class="flex flex-col space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <label for="name" class="w-1/3 capitalize text-sm">
                                            {{ __('name') }}
                                        </label>

                                        <Input v-model="form.name"
                                            :placeholder="__('name')" 
                                            type="text"
                                            required 
                                            class="text-sm"
                                        />
                                    </div>

                                    <InputError :error="form.errors.name" />
                                </div>

                                <div class="flex flex-col space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <label for="type" class="w-1/3 capitalize text-sm">
                                            {{ __('type') }}
                                        </label>

                                        <Input v-model="form.type"
                                            :placeholder="__('type')" 
                                            type="text"
                                            required
                                            class="text-sm"
                                        />
                                    </div>

                                    <InputError :error="form.errors.type" />
                                </div>

                                <div class="flex flex-col space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <label for="nomor" class="w-1/3 capitalize text-sm">
                                            {{ __('nomor') }}
                                        </label>

                                        <Input v-model="form.nomor"
                                            :placeholder="__('nomor')" 
                                            type="text"
                                            required 
                                            class="text-sm"
                                        />
                                    </div>

                                    <InputError :error="form.errors.nomor" />
                                </div>

                                <div class="flex flex-col space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <label for="tahun_md" class="w-1/3 capitalize text-sm">
                                            {{ __('tahun mulai dinas') }}
                                        </label>

                                        <Input v-model="form.tahun_md"
                                            :placeholder="__('tahun mulai dinas')" 
                                            type="number"
                                            required 
                                            class="text-sm"
                                        />
                                    </div>

                                    <InputError :error="form.errors.tahun_md" />
                                </div>

                                <div class="flex flex-col space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <label for="umur" class="w-1/3 capitalize text-sm">
                                            {{ __('umur') }}
                                        </label>

                                        <Input v-model="form.umur"
                                            :placeholder="__('umur')" 
                                            type="number"
                                            required 
                                            class="text-sm"
                                        />
                                    </div>

                                    <InputError :error="form.errors.umur" />
                                </div>

                                <div class="flex flex-col space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <label for="no_sarana" class="w-1/3 capitalize text-sm">
                                            {{ __('Nomor Sarana') }}
                                        </label>

                                        <Input v-model="form.no_sarana"
                                            :placeholder="__('Nomor Sarana')" 
                                            type="text"
                                            required 
                                            class="text-sm"
                                        />
                                    </div>

                                    <InputError :error="form.errors.no_sarana" />
                                </div>

                                <div class="flex flex-col space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <label for="keterangan" class="w-1/3 capitalize text-sm">
                                            {{ __('keterangan') }}
                                        </label>

                                        <Input v-model="form.keterangan"
                                            :placeholder="__('keterangan')" 
                                            type="text"
                                            class="text-sm"
                                        />
                                    </div>

                                    <InputError :error="form.errors.keterangan" />
                                </div>
                                
                            </template>
                        </div>
                    </template>

                    <template #footer>
                        <div
                            class="flex items-center justify-end space-x-2 bg-gray-200 rounded-lg px-2 py-1 pb-[5px] pt-[5px]">
                            <Button type="submit" v-if="!regis" class="grid md:grid-cols text-center items-center bg-green-600 hover:bg-green-800">
                                <p class="capitalize font-semibold">
                                    {{ __(form.id ? 'simpan' : 'simpan') }}
                                </p>
                            </Button>

                            <Button type="submit" v-if="regis" @click.prevent="update" class="grid md:grid-cols text-center items-center bg-green-600 hover:bg-green-800">
                                <Icon name="check" />
                                <p class="uppercase font-semibold">
                                    {{ __('regis') }}
                                </p>
                            </Button>
                        </div>
                    </template>
                </Card>
            </form>
        </Modal>

    </DashboardLayout>
</template>
