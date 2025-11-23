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

const { report, machine, region, users, hasPendingKUPT, hasCreatedToday} = defineProps({
    machine: Array,
    region: Array,
    users: Array,
    hasPendingKUPT: Boolean,
    hasCreatedToday: Boolean
})

const { user } = usePage().props.value

const form = useForm({
    id: null,
    machine_id: '',
    region_id: '',
    date: '',
    has_trouble: '',
    status: '',
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
    return form.post(route('working-reports.store'), {
        onSuccess: () => close(),
        onError: () => show(),
    })
}

const update = () => {
    return form.patch(route('working-reports.update', form.id), {
        onSuccess: () => close(),
        onError: () => show(),
    })
}

const destroy = async report => {
    const response = await Swal.fire({
        title: __('Apakah Anda Yakin') + '?',
        text: __('Anda tidak dapat mengembalikannya setelah dihapus'),
        icon: 'question',
        showCancelButton: true,
        showCloseButton: true,
    })

    if (response.isConfirmed) {
        return form.delete(route('working-reports.destroy', report.id), {
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
    <DashboardLayout :title="__('Working Order')">
        <Card class="bg-white pt-[1.100rem] pb-[2.5rem] shadow-lg border border-solid border-slate-200" style="border-radius: 0.625rem;">
            <template #header>
                <!-- <h1 class="w-full flex justify-center items-center h-[80px] text-2xl font-bold">Data <span class="ml-2 mr-2"
                        style="font-family: taviraj;"></span>Divison</h1> -->
                <div class="flex items-center justify-end px-4 py-1 rounded space-x-2 p-2 pr-[1.688rem]">
                    <Link :href="route('working-reports.create')">
                      <!-- <Button
                          v-if="can('create working report') && !hasCreatedToday && !hasPendingKUPT"
                          class="grid md:grid-cols text-center items-center bg-green-600 hover:bg-green-800"
                      >
                        <p class="font-bold text-xs">
                          {{ __('Tambah') }}
                        </p>
                      </Button> -->
                      <Button
                          v-if="can('create working report')"
                          class="grid md:grid-cols text-center items-center bg-green-600 hover:bg-green-800"
                      >
                        <p class="font-bold text-xs">
                          {{ __('Tambah') }}
                        </p>
                      </Button>
                    </Link>
                </div>
            </template>

            <template #body>
                <div class="flex flex-col space-y-1">
                    <Builder v-if="render" :url="route('working-reports.paginate')" ref="table">
                        <template #thead="table">
                            <tr class="bg-gray-100 border-b border-gray-300">
                                <Th :table="table" :sort="false" name="id"
                                    class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                    {{ __('no').toUpperCase() }}
                                </Th>
                                
                                <Th :table="table" :sort="false" name="machine_id"
                                    class="border border-gray-300 px-3 py-1 text-left capitalize font-extrabold text-xs ">
                                    {{ __('mesin').toUpperCase() }}
                                </Th>
                                
                                <Th :table="table" :sort="false" name="region_id"
                                    class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                    {{ __('wilayah').toUpperCase() }}
                                </Th>
                                
                                <Th :table="table" :sort="false" name="date"
                                    class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                    {{ __('tanggal').toUpperCase() }}
                                </Th>
                                
                                <Th :table="table" :sort="false" name="status"
                                    class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                    {{ __('mode').toUpperCase() }}
                                </Th>
                                
                                <Th :table="table" :sort="false" name="status"
                                    class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                    {{ __('status').toUpperCase() }}
                                </Th>

                                <Th :table="table" :sort="false"
                                    class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                    {{ __('Action').toUpperCase() }}
                                </Th>
                            </tr>
                        </template>

                        <template #tbody="{ data, processing, empty }">
                            <TransitionGroup enterActiveClass="transition-all duration-200"
                                leaveActiveClass="transition-all duration-200" enterFromClass="opacity-0 -scale-y-100"
                                leaveToClass="opacity-0 -scale-y-100">
                                
                                <template v-if="empty">
                                    <tr>
                                        <td class="text-base text-center p-8 border" colspan="1000">
                                            <p class="lowercase first-letter:capitalize font-semibold text-gray-500">
                                                {{ __('Tidak ada data untuk ditampilkan.') }}
                                            </p>
                                        </td>
                                    </tr>
                                </template>

                                <template v-else>
                                    <tr v-for="(report, i) in data" :key="report.id" :class="processing ? 'bg-gray-50' : 'hover:bg-gray-50'"
                                        class="transition-all duration-300">
                                        
                                        <td class="border-b border-gray-300 px-4 py-3 text-center text-xs">
                                            {{ i + 1 }}
                                        </td>
                                        
                                        <td class="border-b border-gray-300 px-4 py-3 text-center text-xs font-medium whitespace-nowrap">
                                            {{ 
                                                report.machine 
                                                ? `${report.machine.name}${report.machine.type ? ' - ' + report.machine.type : ''}${report.machine.region && report.machine.region.name ? ' (' + report.machine.region.name + ')' : ''}` 
                                                : '-' 
                                            }}
                                        </td>
                                        
                                        <td class="border-b border-gray-300 px-4 py-3 text-center text-xs whitespace-nowrap">
                                            {{ report.region?.name ?? '-' }}
                                        </td>
                                        
                                        <td class="border-b border-gray-300 px-4 py-3 text-center text-xs whitespace-nowrap">
                                            {{ new Date(report?.date).toOnlyIndonesianDate() }} - {{ new Date(report?.date).toLocaleTimeString('id-ID', {
                                                hour: '2-digit',
                                                minute: '2-digit'
                                            }) }}
                                        </td>      
                                        
                                        <td class="border-b border-gray-300 px-4 py-3 text-center text-xs capitalize">
                                            {{ report.mode ?? '-' }}
                                        </td>
                                        
                                        <td class="border-b border-gray-300 px-4 py-3 text-center text-xs whitespace-nowrap">
                                            <span
                                                :class="[
                                                'px-2 py-1 rounded-full text-xs font-semibold',
                                                {
                                                    'bg-gray-100 text-gray-800': report.status === 'draft',
                                                    'bg-yellow-100 text-yellow-800': report.status === 'checksheet_done',
                                                    'bg-orange-100 text-orange-800': report.status === 'warming_up_done',
                                                    'bg-blue-100 text-blue-800': report.status === 'photo_uploaded',
                                                    'bg-blue-100 text-blue-800': report.status === 'work_done',
                                                    'bg-green-500 text-white': report.status === 'finished',
                                                    'bg-green-100 text-green-800': report.status === 'selesai',
                                                },
                                                ]"
                                            >
                                                {{
                                                report.status === 'draft'
                                                    ? 'Process Working Order'
                                                    : report.status === 'checksheet_done'
                                                    ? 'Process Checksheet'
                                                    : report.status === 'warming_up_done'
                                                    ? 'Process Warming Up'
                                                    : report.status === 'photo_uploaded'
                                                    ? 'Process Upload'
                                                    : report.status === 'work_done'
                                                    ? 'Process Work Result'
                                                    : report.status === 'finished'
                                                    ? 'Approve KUPT'
                                                    : report.status === 'selesai'
                                                    ? 'Selesai'
                                                    : report.status
                                                }}
                                            </span>
                                        </td>

                                        <td class="border-b border-gray-300 px-1 py-1 text-center">
                                            <div class="flex justify-center gap-4">
                                                
                                                <Button class="bg-gray-600 text-white text-sm px-2 py-0 rounded-md hover:bg-gray-700">
                                                    <Link :href="route('working-reports.detail', report.id)" class="bg-gray-600 text-white px-2 py-0.5 rounded hover:bg-gray-700"> 
                                                        <!-- <Icon name="eye" class="w-4 h-4"/>  -->
                                                        <p class="text-xs font-bold">Detail</p>
                                                    </Link>
                                                </Button>

                                                <!-- <Button class="bg-blue-600 text-white text-sm px-2 py-0 rounded-md hover:bg-blue-700">
                                                    <Link v-if="can('update working report')" :href="route('working-reports.edit', report.id)" class="bg-blue-600 text-white px-2 py-0.5 rounded hover:bg-blue-700 transition duration-150"> 
                                                        <Icon name="pen" class="w-4 h-4"/> 
                                                        <p class="text-xs font-bold">Edit</p>
                                                    </Link>
                                                </Button> -->

                                                <Button v-if="can('delete working report') && !report?.kupt_by1" @click.prevent="destroy(report)" class="bg-red-600 text-white px-2 py-0 rounded hover:bg-red-700 transition duration-150">
                                                    <div class="bg-red-600 text-white px-2 py-0.5 rounded-md hover:bg-red-700">  
                                                        <!-- <Icon name="trash" class="w-4 h-4"/>  -->
                                                        <p class="text-xs font-bold">Hapus</p>
                                                    </div>
                                                </Button> 
                                                
                                                <Button v-if="report?.kupt_at1" class="bg-blue-600 text-white text-sm px-2 py-0 rounded-md hover:bg-blue-700">
                                                    <Link :href="route('working-reports.download', report.id)" class="bg-blue-600 text-white px-2 py-0.5 rounded hover:bg-blue-700"> 
                                                        <!-- <Icon name="eye" class="w-4 h-4"/>  -->
                                                        <p class="text-xs font-bold">Download</p>
                                                    </Link>
                                                </Button>                    
                                                
                                                <!-- <Button v-if="report?.kupt_by1" class="bg-blue-600 text-white text-sm px-2 py-0 rounded-md hover:bg-blue-700">
                                                    <Link :href="route('working-reports.detail', report.id)" class="bg-blue-600 text-white px-2 py-0.5 rounded-md hover:bg-blue-700"> 
                                                        <p class="text-xs font-bold">Print</p>
                                                    </Link>
                                                </Button> -->
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
