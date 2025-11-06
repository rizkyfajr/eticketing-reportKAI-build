<script setup>
import { getCurrentInstance, nextTick, onMounted, onUnmounted, ref } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { useForm, Link, usePage } from '@inertiajs/inertia-vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Card from '@/Components/Card.vue'
import Icon from '@/Components/Icon.vue'
import Button from '@/Components/Button.vue'
import BtnAttachment from '@/Components/Button/Attachment.vue'

const { users, joblist, report } = defineProps({
  users: Array,
  joblist: Object,
  report: Object,
})

const { user } = usePage().props.value

const form = useForm({
  id: null,
  report_id: null,
  catatan: '',
});

const open = ref(false)

const esc = e => e.key === 'Escape' && close()
onMounted(() => window.addEventListener('keydown', esc))
onUnmounted(() => window.removeEventListener('keydown', esc))

</script>

<style src="@vueform/multiselect/themes/default.css"></style>
<style src="@/multiselect.css"></style>

<template>
    <DashboardLayout :title="__('Detail Pekerjaan')">
        <div
        class="transition-all duration-300"
        :class="{
            'pl-1 md:pl-64': open,
        }"
        >
        <main class="p-0 py-0 mb-[1.25rem] ml-[1.25rem] mt-[1.25rem]">
          <h2 class="font-bold text-2xl">Detail List Kerjaan</h2>
          <a type="button" href="/" class="text-sm text-gray-500 font-semibold hover:text-sky-600 focus:text-sky-600">Home</a> 
           <span class="font-semibold text-sm pl-2 pr-2">-</span>
           <a type="button" href="/joblist" class="text-sm text-gray-500 font-semibold hover:text-sky-600 focus:text-sky-600">List Kerjaan</a>  
            <slot />
        </main>
        </div>
        <Card class="flex flex-col rounded-lg bg-white shadow-lg border border-solid border-slate-200 pb-[40px] pt-[30px]" style="border-radius: 0.625rem;">
        <template #header>
            <div class="flex items-center justify-center space-x-2 p-2 ml-[1.563rem] mr-[1.563rem] h-[6.875rem] " style="border-bottom: 1px solid #929292;">   
                <div class="w-3/4 flex items-center justify-end text-xl font-bold">Detail List Pekerjaan {{ joblist.report.bagian_sistem }} </div>
                    <div class="flex items-center justify-end w-2/4 pr-[1.25rem]">
                <Link :href="route('joblist.index', joblist.id)" target="_blank" class="">
                    <Button
                    class="bg-gray-900 hover:bg-black rounded-[0.625rem] float-left pl-[1.25rem] pr-[1.875rem] pb-[0.625rem] pt-[0.625rem]"
                    > 
                    <img src="../../../../bootstrap/arrow.png" class="w-3" alt="">
                    <!-- <Icon name="caret-left" /> -->
                    <p class="capitalize pl-[0.813rem]">
                    {{ __('back') }}
                    </p>
                    </Button>
                </Link> 
                </div> 
            </div>
        </template>

        <template #body>
            <div class="flex flex-col space-y-2"><br>
                <!-- <div class="flex justify-center items-center">
                    <div class="font-bold pr-2 uppercase">Detail List Pekerjaan</div>
                    <div class="font-bold">{{ joblist.report.bagian_sistem }}</div>
                </div><br> -->

                <div class="flex items-center">
                    <div class="w-80 pr-2 ml-6">Kode</div>
                    <div class="pr-2">:</div>
                    <div>{{ joblist.report.kode }}</div>
                </div>

                <div class="flex items-center">
                    <div class="w-80 pr-2 ml-6">Bagian yang Bermasalah</div>
                    <div class="pr-2">:</div>
                    <div>{{ joblist.report.bagian_sistem }}</div>
                    <div>{{ joblist.report.bagian.nama }} ({{ joblist.report.bagian.singkatan }})</div>
                </div>

                <div class="flex items-center">
                    <div class="w-80 pr-2 ml-6">Tanggal ditemukan Kendala</div>
                    <div class="pr-2">:</div>
                    <div>{{ new Date(joblist.report.tanggal).toLocaleString('id-ID', { dateStyle: 'medium'}) }}</div>
                </div>

                <div class="flex items-center">
                    <div class="w-80 pr-2 ml-6">Bagian Pembuat Laporan</div>
                    <div class="pr-2">:</div>
                    <div>{{ joblist.report.bagian_pelapor }}</div>
                </div>

                <div class="flex items-center">
                    <div class="w-80 pr-2 ml-6">Kategori Kendala yang ditemukan</div>
                    <div class="pr-2">:</div>
                    <div>{{ joblist.report.kategori }}</div>
                </div>

                <div class="flex items-center">
                    <div class="w-80 pr-2 ml-6">Uraian Kendala yang ditemukan</div>
                    <div class="pr-2">:</div>
                    <div class="overflow-hidden">
                        <div class="h-auto max-h-32">{{ joblist.report.kendala }}</div>
                    </div>
                </div>

                <div class="flex items-center">
                    <div class="w-80 pr-2 ml-6">Dampak dari Kendala</div>
                    <div class="pr-2">:</div>
                    <div class="overflow-hidden">
                        <div class="h-auto max-h-32">{{ joblist.report.dampak }}</div>
                    </div>
                </div>

                <div class="flex items-center">
                    <div class="w-80 pr-2 ml-6">Link url</div>
                    <div class="pr-2">:</div>
                    <div>{{ joblist.report.url }}</div>
                </div>

                <div class="flex items-center">
                    <div class="w-80 pr-2 ml-6">Kontak yang dapat dihubungi</div>
                    <div class="pr-2">:</div>
                    <div>{{ joblist.report.kontak }}</div>
                </div>

                <div class="flex items-center">
                    <div class="w-80 pr-2 ml-6">Status</div>
                    <div class="pr-2">:</div>
                    <div :class="{
                        'bg-sky-100 text-blue-600 text-center capitalize rounded-md font-semibold text-sm p-1 w-[5rem]': joblist.report.status === 1, 
                        'bg-orange-200 text-center text-orange-700 capitalize rounded-md font-semibold text-sm p-1 w-[5rem]': joblist.report.status === 2, 
                        'bg-green-100 text-center text-emerald-600 capitalize rounded-md font-semibold text-sm p-1 w-[5rem]': joblist.report.status === 3, 
                        'bg-red-200 text-center text-rose-700 capitalize rounded-md  font-semibold text-sm p-1 w-[5rem': joblist.report.status === 4,
                        'bg-stone-100 text-center text-black capitalize font-semibold p-1 rounded-md text-sm w-[5rem]': joblist.report.status === 5
                        }">
                        {{ 
                        joblist.report.status === 1 ? 'Terkirim' : 
                        joblist.report.status === 2 ? 'Perbaikan' : 
                        joblist.report.status === 3 ? 'Selesai' : 
                        joblist.report.status === 4 ? 'Pending' : 
                        joblist.report.status === 5 ? 'Diterima' : 
                        __(joblist.report.status) }}
                    </div>
                </div>

                <div v-if="report.catatan && report.status != 3" class="flex items-center">
                    <div class="w-80 pr-2 ml-6" :class="{'text-orange-600': report.status == 2, 'text-red-600': report.status == 4, 'text-blue-600': report.status != 2 && report.status != 4}">Catatan</div>
                    <div class="pr-2" :class="{'text-orange-600': report.status == 2, 'text-red-600': report.status == 4, 'text-blue-600': report.status != 2 && report.status != 4}">:</div>
                    <div v-if="report.status == 2" :class="{'text-orange-600': report.status == 2, 'text-red-600': report.status == 4, 'text-blue-600': report.status != 2 && report.status != 4}">{{ report.catatan }}</div>
                    <div v-if="report.status == 4" :class="{'text-orange-600': report.status == 2, 'text-red-600': report.status == 4, 'text-blue-600': report.status != 2 && report.status != 4}">{{ report.catatan1 }}</div>
                </div>

                <div class="flex items-center">
                    <div class="w-80 pr-2 ml-6">Pembuat Laporan</div>
                    <div class="pr-2">:</div>
                    <div>{{ joblist.report.created_by.name }}</div>
                </div>

                <div class="flex items-center">
                    <div class="w-80 pr-2 ml-6">File</div>
                    <div class="pr-2">:</div>
                    <Button
                      class="text-black text-sm font-semibold h-auto bg-blue-100 hover:text-blue-700 hover:border-blue-100 focus:bg-blue-200 ">
                      <Icon name="file-circle-plus"/>
                    <BtnAttachment
                        :model="joblist.report"
                        type="Report"
                        :redaction="`Report Bug Sistem`"
                        :closed="false"
                    /></Button>
                </div><br><br><br><br>
                <hr><br><br><br>
                                
                <div class="flex items-center">
                    <div class="w-80 pr-2 ml-6">Keterangan </div>
                    <div class="pr-2">:</div>
                    <div>{{ joblist.catatan }}</div>
                </div><br><br><br><br>
            </div>
        </template>
        </Card>
    </DashboardLayout>
</template>