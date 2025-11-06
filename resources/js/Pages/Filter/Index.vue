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
import BtnAttachment from '@/Components/Button/Attachment.vue'
import TextArea from '@/Components/TextArea.vue'

const { users, report, feedback,system, job } = defineProps({
    users: Array,
    report: Object,
    feedback: Object,
    system: Object,
    job: Object,
})

const { user } = usePage().props.value

const form = useForm({
    id: null,
report_id: '',
    user_id: '',
  
   
    users: [],
});


const render = ref(true)
const self = getCurrentInstance()
const regis = ref(false)
const table = ref(null)
const open = ref(false)
// const balas = ref(false)
const show = () => open.value = true

const balas = ref(false)
const list = ref(false)



const close = () => {
    form.reset()
    render.value = false
    nextTick(() => {
        render.value = true
        nextTick(() => open.value = false)
        nextTick(() => regis.value = false)
        // nextTick(() => balas.value = false)
        nextTick(() => balas.value = false)
        nextTick(() => list.value = false)
    })
}





const esc = e => e.key === 'Escape' && close()
onMounted(() => window.addEventListener('keydown', esc))
onUnmounted(() => window.removeEventListener('keydown', esc))

</script>

<style src="@vueform/multiselect/themes/default.css"></style>
<style src="@/multiselect.css"></style>

<template>
    <DashboardLayout :title="__('Data Bagian')">
        <div class="transition-all duration-300" :class="{
            'pl-1 md:pl-64': open,
        }">
           <main class="p-0 py-0 mb-[1.25rem] ml-[1.25rem] mt-[1.25rem]">
            <h2 class="font-bold text-2xl">Assign</h2>
            <a type="button" href="/" class="text-sm text-gray-500 font-semibold hover:text-sky-600 focus:text-sky-600">Home</a>  
            <slot />
        </main>
        </div>

     
        <Card class="bg-white pt-[1.875rem] pb-[2.5rem] shadow-lg border border-solid border-slate-200" style="border-radius: 0.625rem;">

            <template #body>
                <div class="flex flex-col space-y-2">
                    <Builder v-if="render" :url="route('job.paginate')" ref="table">
                        <template #thead="table">
                            <tr class="bg-gray-200 border-gray-300">
                                <Th :table="table" 
                                    :sort="false" name="id"
                                    class="border px-3 py-2 text-center capitalize font-bold ">
                                    {{ __('no') }}
                                </Th>

                                 <Th :table="table" 
                                    :sort="true" name="user_id"
                                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('Developer') }} <!-- Mengubah label menjadi 'user name' -->
                                    </Th>
                                <Th :table="table" 
                                    :sort="true"
                                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('Sistem') }}
                                </Th>
                                <Th :table="table" 
                                    :sort="true"
                                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('Status') }}
                                </Th>
                            </tr>
                        </template>

                        <template #tfoot="table">
                            <tr class="bg-gray-200 border-gray-300">
                                <Th :table="table"
                                    :sort="false"
                                    class="border px-3 py-2 text-center capitalize font-bold pl-[2.75rem] pb-[1.5rem]">
                                    {{ __('no') }}
                                </Th>

                                <Th :table="table"
                                    :sort="true" name="user_id"
                                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('Developer') }} <!-- Mengubah label menjadi 'user name' -->
                                    </Th>
                                <Th :table="table" 
                                    :sort="true"
                                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('Sistem') }}
                                    </Th>
                                 <Th :table="table"
                                     :sort="true"
                                     class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                     {{ __('Status') }}
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
                                    <tr v-for="(job, i) in data" :key="job.id" :class="processing && 'bg-gray-100'"
                                        class="transition-all  h-[4.375rem] duration-300">
                                        <td class="px-2 py-1 border-b text-center">
                                            {{ i + 1 }}
                                        </td>

                                        <td class="capitalize font-semibold text-center border-b">
                                            {{ job.user.name }} <!-- Menampilkan nama pengguna -->
                                         </td>
                                            <td class="capitalize font-semibold text-center border-b">
                                             {{ job.report.kode }}
                                        </td>
                                    <td class="border-b pl-[8.25rem]">                       
                                        <div v-if="job.report.status === 0" class="bg-slate-200 text-slate-500 capitalize flex  justify-center text-sm text-center rounded-md font-semibold p-1 w-[5rem]">
                                                Drafting
                                        </div>
                                        
                                        <div v-else-if="job.report.status === 1" class="bg-sky-100 text-blue-600 text-center capitalize rounded-md font-semibold text-sm p-1 w-[5rem]">
                                                Terkirim
                                        </div>
                                        <div v-else-if="job.report.status === 2" class="bg-orange-200 text-center text-orange-700 capitalize rounded-md font-semibold text-sm p-1 w-[5rem]">
                                                Perbaiki
                                        </div>
                                        <div v-else-if="job.report.status === 3" class="bg-green-100 text-center text-emerald-600 capitalize rounded-md font-semibold text-sm p-1 w-[5rem]">
                                                Selesai
                                        </div>
                                        <div v-else-if="job.report.status === 4" class="bg-stone-100 text-center text-black capitalize font-semibold p-1 rounded-md text-sm w-[5rem]">
                                            Diterima
                                        </div>
                                        <div v-else-if="job.report.status === 5" class="bg-red-200 text-center text-rose-700 capitalize rounded-md  font-semibold text-sm p-1 w-[5rem]">
                                            Pending   
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