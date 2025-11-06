<script setup>
import { getCurrentInstance, nextTick, onMounted, onUnmounted, ref } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { useForm, Link, usePage } from '@inertiajs/inertia-vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Card from '@/Components/Card.vue'
import Icon from '@/Components/Icon.vue'
import Button from '@/Components/Button.vue'
import BtnAttachment from '@/Components/Button/Attachment.vue'

const { users, report } = defineProps({
  users: Array,
  report: Array,
})

const { user } = usePage().props.value

const form = useForm({
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
  catatan: '',
  users:[],
});

const reg = (report) => {
  edit(report)
}

const render = ref(true)
const table = ref(null)
const open = ref(false)
const show = () => open.value = true

const edit = (report)=> {
  form.id = report.id
  form.kode = report.kode
  form.bagian_sistem = ! [
                    'E-KFPB',
                    'E-SDM KFPB',
                    'E-Dokumen KFPB',
                    'E-Logbook', 
                    'Electronic Batch Record (EBR)',
                    'Electronic Quality Management System (EQMS)', 
                    'Learning Management System (LMS)', 
                ].includes(report.bagian_sistem) ? 'Lainnya' : report.bagian_sistem
  form.tanggal = report.tanggal
  form.bagian_pelapor = report.bagian_pelapor
  form.kategori = ! [
                    'Minor',
                    'Mayor',
                    'Critical',
                ].includes(report.kategori) ? 'Lainnya' : report.kategori
  form.kendala = report.kendala
  form.dampak = report.dampak
  form.kontak = report.kontak
  form.url = report.url
  form.catatan = report.catatan

  show();
  perbaiki(report);
  batal(report);
}

const repair = async report => {
  const response = await Swal.fire({
    title: __('Apakah yakin akan diperbaiki') + '?',
    icon: 'question',
    showCancelButton: true,
    showCloseButton: true,
  })
  if (response.isConfirmed) {
    return form.post(route('report.repair', { report : report.id }), {
      onFinish: close,
    })
  }
}

const reject = async report => {
  const { isConfirmed, value } = await Swal.fire({
    title: `<span>${__('Anda yakin tidak akan memperbaiki masalah ini')}?</span>`,
    text: __('Tindakan ini tidak dapat dibatalkan'),
    icon: 'question',
    input: 'textarea',
    inputValidator: value => value ? undefined : __('you must describe why you reject this approval'),
    showCloseButton: true,
    showCancelButton: true,
  })

  if (!isConfirmed) {
    return
  }

  useForm({ catatan: value }).post(route('report.reject', { report : report.id}))
}

const done = async (report) => {
  const { isConfirmed, value } = await Swal.fire({
    title: `<span>${__('Anda yakin masalah ini sudah diperbaiki')}?</span>`,
    icon: 'question',
    text: __('Tindakan ini tidak dapat dibatalkan'),
    html: `
      <span>Tanggal<input id="swal-input" class="swal2-input" type="date" required></span>
    `,
    focusConfirm: false,
    preConfirm: () => {
      const tanggal = document.getElementById('swal-input').value;
      if (!tanggal) {
        Swal.showValidationMessage('Anda harus memilih tanggal');
      }
      return tanggal;
    },
    inputValidator: (value) => {
      if (!value) {
        return __('Anda harus memilih tanggal');
      }
      return undefined;
    },
    showCloseButton: true,
    showCancelButton: true,
  });

  if (!isConfirmed) {
    return;
  }

  const tanggal = value;

  useForm({ tanggal }).post(route('report.done', { report: report.id }));
};

const close = () => {
  form.reset()
  render.value = false
  nextTick(() => {
    render.value = true
    nextTick(() => open.value = false)
  })
}

const store = () => {
  return form.post(route('report.store'), {
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
    <DashboardLayout :title="__('detail report')">
        <div
        class="transition-all duration-300"
        :class="{
            'pl-1 md:pl-64': open,
        }"
        >
        <main class="p-0 py-0 mb-[1.25rem] ml-[1.25rem] mt-[1.25rem]">
          <h2 class="font-bold text-2xl">Detail Data Laporin</h2>
           <a type="button" href="/" class="text-sm text-gray-500 font-semibold hover:text-sky-600 focus:text-sky-600">Home</a>  
           <span class="font-semibold text-sm pl-2 pr-2">-</span>
           <span class="text-sm text-gray-500 font-semibold hover:text-sky-600 focus:text-sky-700">Laporin</span> 
           <span class="font-semibold text-sm pl-2 pr-2">-</span>
           <a type="button" href="/report" class="text-sm text-gray-500 font-semibold hover:text-sky-600 focus:text-sky-600">Data Laporin</a> 
            <slot />
        </main>
        </div>
        <Card class="flex flex-col rounded-lg bg-white shadow-lg border border-solid border-slate-200 pb-[40px] pt-[30px]" style="border-radius: 0.625rem;">
        <template #header>
            <div class="flex items-center justify-center space-x-2 p-2 ml-[1.563rem] mr-[1.563rem] h-[6.875rem] " style="border-bottom: 1px solid #929292;">   
              <div class="w-3/4 flex items-center justify-end text-xl font-bold">Detail Laporin {{ report.bagian_sistem }} </div> 
              <div class="flex items-center justify-end w-2/4 pr-[1.25rem]">
              <Link :href="route('report.index', report.id)" target="_blank" class="">
                    <Button
                    class="bg-gray-900 hover:bg-black rounded-[0.625rem] float-left pl-[1.25rem] pr-[1.875rem] pb-[0.625rem] pt-[0.625rem]"
                    > 
                    <!-- <Icon name="caret-left" /> -->
                    <img src="../../../../bootstrap/arrow.png" class="w-3" alt="">
                    <p class="capitalize pl-[0.813rem]">
                    {{ __('back') }}
                    </p>
                    </Button>
                </Link> 
              </div>
            </div>
        </template>

        <template #body>
            <div class="flex flex-col space-y-2 pt-5"><br>
                <div class="flex items-center">
                    <div class="w-80 pr-2 ml-6">Kode</div>
                    <div class="pr-2">:</div>
                    <div>{{ report.kode }}</div>
                </div>

                <div class="flex items-center">
                    <div class="w-80 pr-2 ml-6">Kategori Kendala yang ditemukan</div>
                    <div class="pr-2">:</div>
                    <div>{{ report.kategori }}</div>
                </div>

                <div class="flex items-center">
                    <div class="w-80 pr-2 ml-6">Bagian yang Bermasalah</div>
                    <div class="pr-2">:</div>
                    <div>{{ (report && report.bagian_hardware) ? __(report.bagian_hardware) : (report.bagian ? __(report.bagian.nama) : '') }} </div>
                    <div>{{ report.bagian_sistem ? __(report.bagian_sistem) : '' }}</div>
                    <div>{{ report.bagian ? (__(report.bagian.nama) + ' (' + __(report.bagian.singkatan) + ')') : '' }}</div>
                </div>

                <div v-if="report.jenis" class="flex items-center">
                    <div class="w-80 pr-2 ml-6">Jenis</div>
                    <div class="pr-2">:</div>
                    <div>{{ report.jenis }}</div>
                </div>

                <div class="flex items-center">
                    <div class="w-80 pr-2 ml-6">Tanggal ditemukan Kendala</div>
                    <div class="pr-2">:</div>
                    <div>{{ new Date(report.tanggal).toLocaleString('id-ID', { dateStyle: 'medium'}) }}</div>
                </div>

                <div class="flex items-center">
                    <div class="w-80 pr-2 ml-6">Bagian Pembuat Laporan</div>
                    <div class="pr-2">:</div>
                    <div>{{ report.bagian_pelapor }}</div>
                </div>

                <div class="flex items-center">
                    <div class="w-80 pr-2 ml-6">Uraian Kendala yang ditemukan</div>
                    <div class="pr-2">:</div>
                    <div class="overflow-hidden">
                        <div class="h-auto max-h-32">{{ report.kendala }}</div>
                    </div>

                </div>

                <div class="flex items-center">
                    <div class="w-80 pr-2 ml-6">Dampak dari Kendala</div>
                    <div class="pr-2">:</div>
                    <div class="overflow-hidden">
                        <div class="h-auto max-h-32">{{ report.dampak }}</div>
                    </div>
                </div>

                <div class="flex items-center">
                    <div class="w-80 pr-2 ml-6">Link url</div>
                    <div class="pr-2">:</div>
                    <div>{{ report.url }}</div>
                </div>

                <div class="flex items-center">
                    <div class="w-80 pr-2 ml-6">Kontak yang dapat dihubungi</div>
                    <div class="pr-2">:</div>
                    <div>{{ report.kontak }}</div>
                </div>

                <div class="flex items-center">
                    <div class="w-80 pr-2 ml-6">Status</div>
                    <div class="pr-2">:</div>
                    
                    <div :class="{
                        'bg-slate-200 text-slate-500 capitalize flex justify-center text-sm text-center rounded-md font-semibold p-1 w-[5rem]': report.status === 0, 
                        'bg-sky-100 text-blue-600 text-center capitalize rounded-md font-semibold text-sm p-1 w-[5rem]': report.status === 1, 
                        'bg-orange-200 text-center text-orange-700 capitalize rounded-md font-semibold text-sm p-1 w-[5rem]': report.status === 2, 
                        'bg-green-100 text-center text-emerald-600 capitalize rounded-md font-semibold text-sm p-1 w-[5rem]': report.status === 3, 
                        'bg-red-200 text-center text-rose-700 capitalize rounded-md  font-semibold text-sm p-1 w-[5rem]': report.status === 4,
                        'bg-stone-100 text-center text-black capitalize font-semibold p-1 rounded-md text-sm w-[5rem]': report.status === 5
                        }">
                        {{ 
                        report.status === 0 ? 'Drafting' : 
                        report.status === 1 ? 'Terkirim' :
                        report.status === 2 ? 'Perbaikan' : 
                        report.status === 3 ? 'Selesai' : 
                        report.status === 4 ? 'Pending' : 
                        report.status === 5 ? 'Diterima' : 
                        __(report.status) }}
                    </div>
                  </div>

                <div v-if="(report.status == 2 && report.catatan) || (report.status == 4 && report.catatan1)" class="flex items-center">
                    <div class="w-80 pr-2 ml-6" :class="{'text-orange-600': report.status == 2, 'text-red-600': report.status == 4, 'text-blue-600': report.status != 2 && report.status != 4}">Catatan</div>
                    <div class="pr-2" :class="{'text-orange-600': report.status == 2, 'text-red-600': report.status == 4, 'text-blue-600': report.status != 2 && report.status != 4}">:</div>
                    <div v-if="report.status == 2 && report.catatan" :class="{'text-orange-600': report.status == 2, 'text-red-600': report.status == 4, 'text-blue-600': report.status != 2 && report.status != 4}">{{ report.catatan }}</div>
                    <div v-else-if="report.status == 4 && report.catatan1" :class="{'text-orange-600': report.status == 2, 'text-red-600': report.status == 4, 'text-blue-600': report.status != 2 && report.status != 4}">{{ report.catatan1 }}</div>
                </div>

                <div class="flex items-center">
                    <div class="w-80 pr-2 ml-6">Pembuat Laporan</div>
                    <div class="pr-2">:</div>
                    <div>{{ report.created_by.name }}</div>
                </div>

                <div class="flex items-center">
                    <div class="w-80 pr-2 ml-6"> File</div>
                    <div class="pr-2">:</div>
                    <!-- button 1 -->
                    <Button
                      class="text-black text-sm font-semibold h-auto bg-blue-100 hover:text-blue-700 hover:border-blue-100 focus:bg-blue-200 ">
                      <Icon name="file-circle-plus"/>
                        <BtnAttachment
                        :model="report"
                        type="Report"
                        :redaction="`Report Bug Sistem`"
                        :closed="false" /> 
                    </Button>
                    
                </div><br><br>
                
                <!-- <div class="flex justify-center">                    
                    <div class="grid md:grid-cols-2 gap-1 w-64">
                    <Button 
                        v-if="hasRole(['superuser', 'it']) && report.status !== 2 && report.status !==4 && report.status !==3"
                        @click.prevent="repair(report)" 
                        class="bg-green-700 hover:bg-green-600 rounded-md">
                        <Icon name="wrench" />
                        <p class="uppercase">
                            {{ __('perbaiki') }}
                        </p>
                    </Button>

                    <Button 
                        v-if="hasRole(['superuser', 'it']) && report.status !== 2 && report.status !==4 && report.status !==3"
                        @click.prevent="reject(report)" 
                        class="bg-orange-700 hover:bg-orange-600 rounded-md">
                        <Icon name="circle-xmark" />
                        <p class="uppercase">
                            {{ __('pending') }}
                        </p>
                    </Button>

                    <Button 
                        v-if="hasRole(['superuser', 'it']) && report.status == 2 "
                        @click.prevent="done(report)" 
                        class="bg-blue-700 hover:bg-blue-600 rounded-md">
                        <Icon name="circle-check" />
                        <p class="uppercase">
                            {{ __('Selesai') }}
                        </p>
                    </Button>
                    </div>
                </div><br><br><br><br><br><br><br><br> -->
            </div>
        </template>
        </Card>
    </DashboardLayout>
</template>