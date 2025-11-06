<script setup>
import { getCurrentInstance, nextTick, onMounted, onUnmounted, ref } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { useForm, Link, usePage } from '@inertiajs/inertia-vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Card from '@/Components/Card.vue'
import Icon from '@/Components/Icon.vue'
import Builder from '@/Components/DataTable/Builder.vue'
import Th from '@/Components/DataTable/Th.vue'
import Swal from 'sweetalert2'
import Select from '@vueform/multiselect'
import Modal from '@/Components/Modal.vue'
import Close from '@/Components/Button/Close.vue'
import Button from '@/Components/Button.vue'
import ButtonBlue from '@/Components/Button/Blue.vue'
import ButtonGreen from '@/Components/Button/Green.vue'
import ButtonRed from '@/Components/Button/Red.vue'
import Input from '@/Components/Input.vue'
import InputError from '@/Components/InputError.vue'

const { result, machine, region, users, reportuser } = defineProps({
    machine: Array,
    region: Array,
    users: Array,
    reportuser: Array,
})

const { user } = usePage().props.value

const form = useForm({
    id: null,
    machine_id: '',
    region_id: '',
    antara: '',
    km_hm: '',
    jumlah_msp: '',
    waktu_start_engine: '',
    jam_luncuran: '',
    jam_kerja: '',
    jam_mesin: '',
    jam_genset: '',
    counter_pecok: '',
    oddometer: '',
    penggunaan_hsd: '',
    hsd_tersedia: '',
    pengawal_id: '',
    note: '',
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
    return form.post(route('work-results.store'), {
        onSuccess: () => close(),
        onError: () => show(),
    })
}

const edit = (result) => {
    form.id = result.id
    form.machine_id = result.machine_id
    form.region_id = result.region_id
    form.antara = result.antara
    form.km_hm = result.km_hm
    form.jumlah_msp = result.jumlah_msp
    form.waktu_start_engine = result.waktu_start_engine
    form.jam_luncuran = result.jam_luncuran
    form.jam_kerja = result.jam_kerja
    form.jam_mesin = result.jam_mesin
    form.jam_genset = result.jam_genset
    form.counter_pecok = result.counter_pecok
    form.oddometer = result.oddometer
    form.penggunaan_hsd = result.penggunaan_hsd
    form.hsd_tersedia = result.hsd_tersedia
    form.pengawal_id = result.pengawal_id
    form.note = result.note
    show();
}

const update = () => {
    return form.patch(route('work-results.update', form.id), {
        onSuccess: () => close(),
        onError: () => show(),
    })
}

const destroy = async result => {
    const response = await Swal.fire({
        title: __('Apakah Anda Yakin') + '?',
        text: __('Anda tidak dapat mengembalikannya setelah dihapus'),
        icon: 'question',
        showCancelButton: true,
        showCloseButton: true,
    })

    if (response.isConfirmed) {
        return form.delete(route('work-results.destroy', result.id), {
            onFinish: close,
        })
    }
}

const esc = e => e.key === 'Escape' && close()
onMounted(() => window.addEventListener('keydown', esc))
onUnmounted(() => window.removeEventListener('keydown', esc))

</script>

<style src="@vueform/multiselect/themes/default.css"></style>
<style src="@/multiselect.css"></style>

<template>
    <DashboardLayout :title="__('Laporin')">
        <div class="transition-all duration-300" :class="{
            'pl-1 md:pl-64': open,
        }">
        <main class="p-0 py-0 mb-[1.25rem] ml-[1.25rem] mt-[1.25rem]">
            <h2 class="font-bold text-2xl">Laporan Pekerjaan</h2>
           <a type="button" href="/" class="text-sm text-gray-500 font-semibold hover:text-sky-600 focus:text-sky-600">Home</a> 
           <span class="font-semibold text-sm pl-2 pr-2">-</span>
           <span class="text-sm text-gray-500 font-semibold hover:text-sky-600 focus:text-sky-700">Laporan Pekerjaan</span> 
            <slot />
        </main>
        </div>
        <Card
            class="bg-white pt-[1.875rem] pb-[2.5rem] shadow-lg border border-solid border-slate-200" style="border-radius: 0.625rem;"
          >
            <template #header>
                <!-- <h1 class="w-full flex justify-center items-center h-[80px] text-2xl font-bold">Data <span class="ml-2 mr-2"
                        style="font-family: taviraj;"></span>Divison</h1> -->
                <div class="flex items-center justify-end space-x-2 p-2 pr-[1.688rem]">
                    <Link :href="route('work-results.create')">
                      <Button
                          v-if="can('create work result')"
                          class="grid md:grid-cols text-center items-center bg-green-600 hover:bg-green-800"
                      >
                        <p class="uppercase font-bold">
                          {{ __('Lapor') }}
                        </p>
                      </Button>
                    </Link>
                </div>
            </template>

            <template #body>
                <div class="flex flex-col space-y-2">
                    <Builder v-if="render" :url="route('work-results.paginate')" ref="table">
                        <template #thead="table">
                            <tr class="bg-gray-200 border-gray-300">
                                <Th :table="table" :sort="false" name="id"
                                    class="border px-3 py-2 text-center capitalize font-bold">
                                    {{ __('no') }}
                                </Th>
                                
                                <Th :table="table" :sort="true" name="machine_id"
                                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('mesin') }}
                                </Th>
                                
                                <Th :table="table" :sort="true" name="region_id"
                                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('wilayah') }}
                                </Th>
                                
                                <Th :table="table" :sort="true" name="antara"
                                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('antara') }}
                                </Th>
                                
                                <Th :table="table" :sort="true" name="km_hm"
                                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('km hm') }}
                                </Th>
                                
                                <Th :table="table" :sort="true" name="jumlah_msp"
                                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('jumlah msp') }}
                                </Th>
                                
                                <Th :table="table" :sort="true" name="waktu_start_engine"
                                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('waktu start engine') }}
                                </Th>
                                
                                <Th :table="table" :sort="true" name="jam_luncuran"
                                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('jam luncuran') }}
                                </Th>
                                
                                <Th :table="table" :sort="true" name="jam_kerja"
                                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('jam kerja') }}
                                </Th>
                                
                                <Th :table="table" :sort="true" name="jam_mesin"
                                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('jam mesin') }}
                                </Th>
                                
                                <Th :table="table" :sort="true" name="jam_genset"
                                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('jam genset') }}
                                </Th>
                                
                                <Th :table="table" :sort="true" name="counter_pecok"
                                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('counter pecok') }}
                                </Th>
                                
                                <Th :table="table" :sort="true" name="oddometer"
                                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('oddometer') }}
                                </Th>
                                
                                <Th :table="table" :sort="true" name="penggunaan_hsd"
                                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('penggunaan hsd') }}
                                </Th>
                                
                                <Th :table="table" :sort="true" name="hsd_tersedia"
                                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('hsd tersedia') }}
                                </Th>
                                
                                <Th :table="table" :sort="true" name="pengawal_id"
                                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('pengawal') }}
                                </Th>
                                
                                <Th :table="table" :sort="true" name="note"
                                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('note') }}
                                </Th>

                                <Th :table="table" :sort="true"
                                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('Action') }}
                                </Th>
                            </tr>
                        </template>

                        <template #tfoot="table">
                            <tr class="bg-gray-200 border-gray-300 ">
                                <Th :table="table" :sort="false"
                                    class="border px-3 py-2 text-center capitalize font-bold">
                                    {{ __('no') }}
                                </Th>
                                
                                <Th :table="table" :sort="false"
                                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('mesin') }}
                                </Th>
                                
                                <Th :table="table" :sort="false"
                                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('wilayah') }}
                                </Th>
                                
                                <Th :table="table" :sort="false"
                                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('antara') }}
                                </Th>
                                
                                <Th :table="table" :sort="false"
                                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('km hm') }}
                                </Th>
                                
                                <Th :table="table" :sort="false"
                                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('jumlah msp') }}
                                </Th>
                                
                                <Th :table="table" :sort="false"
                                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('waktu start engine') }}
                                </Th>
                                
                                <Th :table="table" :sort="false"
                                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('jam luncuran') }}
                                </Th>
                                
                                <Th :table="table" :sort="false"
                                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('jam kerja') }}
                                </Th>
                                
                                <Th :table="table" :sort="false"
                                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('jam mesin') }}
                                </Th>
                                
                                <Th :table="table" :sort="false"
                                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('jam genset') }}
                                </Th>
                                
                                <Th :table="table" :sort="false"
                                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('counter pecok') }}
                                </Th>
                                
                                <Th :table="table" :sort="false"
                                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('oddometer') }}
                                </Th>
                                
                                <Th :table="table" :sort="false"
                                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('penggunaan hsd') }}
                                </Th>
                                
                                <Th :table="table" :sort="false"
                                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('hsd tersedia') }}
                                </Th>
                                
                                <Th :table="table" :sort="false"
                                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('pengawal') }}
                                </Th>
                                
                                <Th :table="table" :sort="false"
                                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('note') }}
                                </Th>

                                <Th :table="table" :sort="false"
                                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('Action') }}
                                </Th>
                            </tr>
                        </template>

                        <template #tbody="{ data, processing, empty }">
                            <TransitionGroup enterActiveClass="transition-all duration-200"
                                leaveActiveClass="transition-all duration-200" enterFromClass="opacity-0 -scale-y-100"
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
                                    <tr v-for="(result, i) in data" :key="result.id" :class="processing && 'bg-gray-100'"
                                        class="transition-all duration-300 h-[4.375rem]">
                                        <td class="px-2 py-1 border-b text-center">
                                            {{ i + 1 }}
                                        </td>
                                        
                                        <td class="capitalize font-semibold text-center border-b">
                                            {{ __(result.machine?.name ?? '-') }}
                                        </td>
                                        
                                        <td class="capitalize font-semibold text-center border-b">
                                            {{ __(result.region?.name ?? '-') }}
                                        </td>
                                        
                                        <td class="capitalize font-semibold text-center border-b">
                                            {{ __(result.antara) }}
                                        </td>
                                        
                                        <td class="capitalize font-semibold text-center border-b">
                                            {{ __(result.km_hm) }}
                                        </td>
                                        
                                        <td class="capitalize font-semibold text-center border-b">
                                            {{ __(result.jumlah_msp) }}
                                        </td>
                                        
                                        <td class="capitalize font-semibold text-center border-b">
                                            {{ __(result.waktu_start_engine) }}
                                        </td>
                                        
                                        <td class="capitalize font-semibold text-center border-b">
                                            {{ __(result.jam_luncuran) }}
                                        </td>
                                        
                                        <td class="capitalize font-semibold text-center border-b">
                                            {{ __(result.jam_kerja) }}
                                        </td>
                                        
                                        <td class="capitalize font-semibold text-center border-b">
                                            {{ __(result.jam_mesin) }}
                                        </td>
                                        
                                        <td class="capitalize font-semibold text-center border-b">
                                            {{ __(result.jam_genset) }}
                                        </td>
                                        
                                        <td class="capitalize font-semibold text-center border-b">
                                            {{ __(result.counter_pecok) }}
                                        </td>
                                        
                                        <td class="capitalize font-semibold text-center border-b">
                                            {{ __(result.oddometer) }}
                                        </td>
                                        
                                        <td class="capitalize font-semibold text-center border-b">
                                            {{ __(result.penggunaan_hsd) }}
                                        </td>
                                        
                                        <td class="capitalize font-semibold text-center border-b">
                                            {{ __(result.hsd_tersedia) }}
                                        </td>
                                        
                                        <td class="capitalize font-semibold text-center border-b">
                                            {{ __(result.pengawal?.name ?? '-') }}
                                        </td>
                                        
                                        <td class="capitalize font-semibold text-center border-b">
                                            {{ __(result.note) }}
                                        </td>

                                        <td class="px-2 py-2 border-b text-center">
                                            <div class="flex justify-center gap-2">

                                                <ButtonBlue
                                                    v-if="can('update work result')"
                                                >
                                                    <Link :href="route('work-results.edit', result.id)">
                                                        <Icon name="edit" />
                                                        <p class="uppercase">{{ __('ubah') }}</p>
                                                    </Link>
                                                </ButtonBlue>

                                                <ButtonRed
                                                    v-if="can('delete work result')"
                                                    @click.prevent="destroy(result)">
                                                    <Icon name="trash" />
                                                    <p class="uppercase">{{ __('hapus') }}</p>
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
