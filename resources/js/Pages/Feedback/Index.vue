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
import Modal from '@/Components/Modal.vue'
import Close from '@/Components/Button/Close.vue'
import Button from '@/Components/Button.vue'
import ButtonGreen from '@/Components/Button/Green.vue'
import Input from '@/Components/Input.vue'
import InputError from '@/Components/InputError.vue'
import TextArea from '@/Components/TextArea.vue'

const { users, report } = defineProps({
    users: Array,
    report: Object,
})

const { user } = usePage().props.value

const form = useForm({
  report_id: report.id,
  id: null,
  kode: null,
  bagian_sistem: '',
  tanggal: '',
  bagian_pelapor: '',
  kategori: '',
  kendala: '',
  dampak: '',
  kontak: '',
  url: '',
  status: '',
  balasan: '',
  balasan1: '',
  catatan: '',
  users:[],
});

const render = ref(true)
const self = getCurrentInstance()
const regis = ref(false)
const table = ref(null)
const open = ref(false)
const show = () => open.value = true

const close = () => {
    form.reset()
    render.value = false
    nextTick(() => {
        render.value = true
        nextTick(() => open.value = false)
        nextTick(() => regis.value = false)
    })
}

const store = () => {
    return form.post(route('feedback.store'), {
        onSuccess: () => close(),
        onError: () => show(),
    })
}

const edit = (feedback) => {
    form.id = feedback.id
    form.kode = feedback.kode
    form.bagian_sistem = ![
        'E-KFPB',
        'E-SDM KFPB',
        'E-Dokumen KFPB',
        'E-Logbook',
        'Electronic Batch Record (EBR)',
        'Electronic Quality Management System (EQMS)',
        'Learning Management System (LMS)',
    ].includes(feedback.bagian_sistem) ? 'Lainnya' : feedback.bagian_sistem
    form.tanggal = feedback.tanggal
    form.bagian_pelapor = feedback.bagian_pelapor
    form.kategori = ![
        'Minor',
        'Mayor',
        'Critical',
    ].includes(feedback.kategori) ? 'Lainnya' : feedback.kategori
    form.kendala = feedback.kendala
    form.dampak = feedback.dampak
    form.kontak = feedback.kontak
    form.url = feedback.url
    form.catatan = feedback.catatan

    show();
}

const update = () => {
    return form.patch(route('feedback.update', form.id), {
        onSuccess: () => close(),
        onError: () => show(),
    })
}

const destroy = async feedback => {
    const response = await Swal.fire({
        title: __('Apakah Anda Yakin') + '?',
        text: __('Anda tidak dapat mengembalikannya setelah dihapus'),
        icon: 'question',
        showCancelButton: true,
        showCloseButton: true,
    })

    if (response.isConfirmed) {
        return form.delete(route('feedback.destroy', feedback.id), {
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
    <DashboardLayout :title="__('Feedback')">
        <div class="transition-all duration-300" :class="{
            'pl-1 md:pl-64': open,
        }">
        <main class="p-0 py-0 mb-[1.25rem] ml-[1.25rem] mt-[1.25rem]">
            <h2 class="font-bold text-2xl">feedback</h2>
            <a type="button" href="/" class="text-sm text-gray-500 font-semibold hover:text-sky-600 focus:text-sky-600">Home</a>  
            <slot />
        </main>
        </div>
        <Card class="bg-white pt-[1.875rem] pb-[2.5rem] shadow-lg border border-solid border-slate-200" style="border-radius: 0.625rem;">
            <template #header>
              
            </template>

            <template #body>
                <div class="flex flex-col space-y-2">
                    <Builder v-if="render" :url="route('feedback.paginate')" ref="table">
                        <template #thead="table">
                            <tr class="bg-gray-200 border-gray-300">
                                <Th :table="table" :sort="false" class="border px-3 py-2 text-center capitalize font-bold pl-[2.75rem] pb-[1.5rem]">
                                    {{ __('no') }}
                                </Th>

                                <Th :table="table" :sort="true" class="border px-3 py-2 text-center whitespace-nowrap">
                                    {{ __('kode') }}
                                </Th>

                                <Th :table="table" :sort="true" class="border px-3 py-2 text-center whitespace-nowrap">
                                    {{ __('bagian bermasalah') }}
                                </Th>

                                <Th :table="table" :sort="true"
                                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('catatan dari') }}
                                </Th>

                                <Th :table="table" :sort="true" class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold ">
                                    {{ __('pesan it') }}
                                </Th>

                                <Th :table="table" :sort="true" class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('pesan user') }}
                                </Th>

                                <Th :table="table" :sort="true" class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('status') }}
                                </Th>

                                <Th :table="table" :sort="true" class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('Action') }}
                                </Th>
                                
                                <!-- <Th v-if="table && table.feedback && table.feedback.status !== 3" :table="table" :sort="false" class="border px-3 py-2 text-center whitespace-nowrap">
                                    {{ __('#') }}
                                </Th> -->

                            </tr>
                        </template>

                        <template #tfoot="table">
                            <tr class="bg-gray-200 border-gray-300">
                                <Th :table="table" :sort="false" class="border px-3 py-2 text-center capitalize font-bold pl-[2.75rem] pb-[1.5rem]">
                                    {{ __('no') }}
                                </Th>

                                <Th :table="table" :sort="true" class="border px-3 py-2 text-center whitespace-nowrap">
                                    {{ __('kode') }}
                                </Th>

                                <Th :table="table" :sort="true" class="border px-3 py-2 text-center whitespace-nowrap">
                                    {{ __('bagian bermasalah') }}
                                </Th>

                                <Th :table="table" :sort="true"
                                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('catatan dari') }}
                                </Th>

                                <Th :table="table" :sort="true" class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold ">
                                    {{ __('pesan it') }}
                                </Th>

                                <Th :table="table" :sort="true" class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('pesan user') }}
                                </Th>

                                <Th :table="table" :sort="true" class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('status') }}
                                </Th>

                                <Th :table="table" :sort="true" class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('Action') }}
                                </Th>
                                
                                <!-- <Th v-if="table && table.feedback && table.feedback.status !== 3" :table="table" :sort="false" class="border px-3 py-2 text-center whitespace-nowrap">
                                    {{ __('#') }}
                                </Th> -->

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
                                   <tr
                            v-for="(feedback, i) in data"
                            :key="feedback.id"
                            :class="processing && 'bg-gray-100'"
                            class="h-[4.375rem] transition-all duration-300"
                        >
                                        <td class="px-2 py-1 text-center border-b">
                                            {{ i + 1 }}
                                        </td>

                                        <td class="capitalize font-semibold text-center border-b">
                                        {{ __(feedback.report.kode) }}
                                        </td>

                                        <td class="capitalize font-semibold text-center border-b">
                                        {{ (feedback.report && feedback.report.bagian_sistem) ? __(feedback.report.bagian_sistem) : (feedback.report.bagian ? __(feedback.report.bagian.nama) : '') }} 
                                        {{ (feedback.report && feedback.report.bagian) ? __(feedback.report.bagian.singkatan) : '' }}
                                        </td>

                                        <td class="capitalize font-semibold text-center border-b">
                                          {{ __(feedback.created_by.name) }}
                                        </td>

                                        <td class="capitalize font-semibold text-center border-b">
                                        {{ __(feedback.balasan) }}
                                        </td>

                                        <td class="capitalize font-semibold text-center border-b">
                                        {{ __(feedback.balasan1) }}
                                        </td>

                                        <td class="border-b pl-[5.25rem]">
                                            <div v-if="feedback.report.status === 1" class="bg-sky-100 text-blue-600 text-center capitalize rounded-md font-semibold text-sm p-1 w-[5rem]">
                                                Terkirim
                                            </div>
                                            <div v-else-if="feedback.report.status === 2" class="bg-orange-200 text-center text-orange-700 capitalize rounded-md font-semibold text-sm p-1 w-[5rem]">
                                                Perbaikan
                                            </div>
                                            <div v-else-if="feedback.report.status === 3" class="bg-green-100 text-center text-emerald-600 capitalize rounded-md font-semibold text-sm p-1 w-[5rem]">
                                                Selesai
                                            </div>
                                            <div v-else-if="feedback.report.status === 4" class="bg-red-200 text-center text-rose-700 capitalize rounded-md  font-semibold text-sm p-1 w-[5rem]">
                                                Pending
                                            </div>
                                            <div v-else-if="feedback.report.status === 5" class="bg-stone-100 text-center text-black capitalize font-semibold p-1 rounded-md text-sm w-[5rem]">
                                                Diterima
                                            </div>
                                            <div v-else class="bg-slate-200 text-slate-500 capitalize flex justify-center text-sm text-center rounded-md font-semibold p-1 w-[5rem]">
                                                {{ __(feedback.report.status) }}
                                            </div>
                                        </td>
                                        
                                        <!-- <td v-if="feedback.balasan1 !== null" class="capitalize font-semibold text-center border">
                                            {{ __(feedback.balasan1) }}
                                        </td> -->

                                        <td v-if="feedback.report.status !== 3" class="border-b pl-[5.25rem]">
                                         
                                            
                                            <!-- <Button class="bg-sky-500 hover:bg-sky-700 w-24 flex justify-center items-center text-center rounded-md">
                                                <a :href="route('feedback.detail', feedback.id)" target="_blank" class="flex items-center">
                                                    <Icon name="circle-info" class="mr-2" />
                                                    <p class="capitalize">
                                                    {{ __('detail') }}
                                                    </p>
                                                </a>
                                            </Button> -->
                                            
                                            <Button
                                                v-if="hasRole('user') && feedback.report.status !== 3 && !feedback.balasan1"
                                                @click.prevent="edit(feedback)"
                                                class="bg-green-500 w-[6.25rem] hover:bg-green-700 flex items-center justify-center rounded-md">
                                                <!-- <Icon name="edit" /> -->
                                                <p class="capitalize">
                                                    {{ __('Balas') }}
                                                </p>
                                            </Button>
                                        
                                       
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
                class="w-full max-w-3xl h-fit shadow-xl" 
            >
                <Card class="bg-white">
                <template #header>
                    <div class="flex items-center border-b h-[70px] mb-[30px] bg-white justify-end rounded-t-lg p-2 pb-[0.938rem] pt-[0.938rem]">
                    <!-- <Close @click.prevent="close" /> --> <h2 class="font-bold text-xl w-full ml-10">Balasan</h2>
                    <button class="pr-5">
                    <svg @click.prevent="close" class="w-6 h-6 text-gray-700 hover:text-cyan-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>
                    </svg>
                </button>
                    </div>
                </template>

                <template #body>
                    <div class="flex flex-col space-y-4 p-4 ml-6 mr-6">
                    <template v-if="hasRole(['superuser', 'user', 'asman', 'spv', 'ampr', 'mpm', 'it']) && !regis">    
                    
                     <div class="flex flex-col space-y-2">
                        <div class="flex items-center space-x-2">
                            <label for="balasan" class="w-1/3 capitalize">
                                {{ __('kirim balasan anda') }}
                            </label>
                            <TextArea
                                v-model="form.balasan1"
                                :placeholder="__('kirim balasan anda')"
                                type="text"
                                required
                            />
                            </div>

                            
                            <InputError
                            :error="form.errors.balasan1"
                            />
                        </div>    
                    </template>
                    </div>
                </template>

                <template #footer>
                    <div class="flex items-center justify-end space-x-2 bg-white px-8 py-1 h-[100px] mr-[10px]">
                        <Button 
                        class="rounded-md text-center bg-sky-500 hover:bg-sky-600 flex items-center justify-center w-[130px] h-[40px]"
                        type="submit" v-if="! regis">
                        <p class="capitalize font-semibold">
                        {{ __(form.id ? 'simpan' : 'simpan') }}
                        </p>
                        <svg class="w-4 h-4 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"/>
                        </svg>
                        </Button>
                    </div>
                </template>
                </Card>
            </form>
        </Modal>
    </DashboardLayout>
</template>