<script setup>
import { getCurrentInstance, nextTick, onMounted, onUnmounted, ref , computed } from 'vue'
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

const { users, report, feedback, systemSections, divisionSections, reportuser } = defineProps({
  users: Array,
  reportuser: Array,
  report: Object,
  feedback: Object,
  systemSections:Array,
  divisionSections:Array,
})

const { user } = usePage().props.value

const form = useForm({
  id: null,
  kode: null,
  bagian_sistem: '',
  // bagian_sistem1: '',
  bagian_sistem2: '',
  bagian_hardware: '',
  bagian_hardware1: '',
  bagian_id: '',
  tanggal: '',
  bagian_pelapor: '',
  kategori: '',
  kendala: '',
  dampak: '',
  kontak: '',
  url: '',
  status: '',
  catatan: '',
  catatan1: '',
  jenis: '',
  users:[],
  systemSections:[],
});

const form1 = useForm({
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

const form2 = useForm({
  report_id: report.id,
  id: null,
  catatan: '',
});

const render = ref(true)
const self = getCurrentInstance()
const regis = ref(false)
const table = ref(null)
const open = ref(false)

const show = () => open.value = true
const button_show = ref(false);
const balas = ref(false)
const open2 = ref(false)
const show1 = () => balas.value = true
const show3 = () => open2.value = true

const close = () => {
  form.reset()
  render.value = false
  nextTick(() => {
    render.value = true
    nextTick(() => open.value = false)
    nextTick(() => regis.value = false)

    nextTick(() => balas.value = false)

    nextTick(() => open2.value = false)
  })
}

const store = () => {
  form.bagian_sistem = form.kategori === 'Jaringan' ? 'Jaringan' : form.bagian_sistem;
  return form.post(route('report.store'), {
    onSuccess: () => close(),
    onError: () => show(),
  })
}

const edit = (report)=> {
  form.id = report.id
  form.kode = report.kode
  form.kategori = ! [
                    'Software',
                    'Hardware',
                    'Jaringan',
                ].includes(report.kategori) ? 'Lainnya' : report.kategori
                // ].includes(report.kategori) ? 'Lainnya' : report.kategori
  // form.bagian_hardware = ! ['Troubleshoot Laptop','Instalasi Sistem Operasi',].includes(report.bagian_hardware) ? 'Lainnya' : report.bagian_hardware
  form.bagian_hardware = report.bagian_hardware
  // form.bagian_hardware = ! ['Troubleshoot Laptop','Troubleshoot Komputer','Instalasi Sistem Operasi'].includes(report.bagian_hardware) ? 'Lainnya' : report.bagian_hardware
  // form.bagian_hardware1 = report.bagian_hardware === 'Lainnya' ? report.bagian_hardware : ''
  form.bagian_sistem = report.bagian_sistem
  // form.bagian_sistem = report.kategori === 'Jaringan' ? 'Jaringan' : form.bagian_sistem;
  // form.bagian_sistem = report.bagian_sistem
  // form.bagian_sistem2 = report.bagian_sistem2
  form.tanggal = report.tanggal
  form.bagian_id = report.bagian_id
  form.bagian_pelapor = report.bagian_pelapor
  form.kendala = report.kendala
  form.dampak = report.dampak
  form.kontak = report.kontak
  form.url = report.url
  form.catatan = report.catatan
  form.catatan1 = report.catatan1
  form.jenis = report.jenis
  show();
}

const repair = async (report) => {
  const { value, isConfirmed } = await Swal.fire({
    title: __('Apakah yakin akan diperbaiki') + '?',
    text: __('Keterangan'),
    icon: 'question',
    input: 'text',
    showCancelButton: true,
    showCloseButton: true,
  });

  if (isConfirmed) {
    form.catatan = value;

    const response = await form.post(route('report.repair', { report: report.id }), {
      catatan: value,
      onFinish: close,
    });

    return response;
  }
}

const reject = async report => {
  const { isConfirmed, value } = await Swal.fire({
    title: `<span>${__('Yakin akan tunda pekerjaan ini?')}?</span>`,
    text: __('Beri Keterangan'),
    icon: 'question',
    input: 'text',
    inputValidator: value => value ? undefined : __('you must describe why you reject this approval'),
    showCloseButton: true,
    showCancelButton: true,
  })

  if (isConfirmed) {
    form.catatan1 = value;

    const response = await form.post(route('report.reject', { report: report.id }), {
      catatan1: value,
      onFinish: close,
    });

    return response;
  }

}

const done = async (report) => {
  const { isConfirmed, value } = await Swal.fire({
    title: `<span>${__('Anda yakin masalah ini sudah diperbaiki')}?</span>`,
    icon: 'question',
    text: __('Tindakan ini tidak dapat dibatalkan'),
    html: `
      <div style="display: flex; flex-direction: column;">
        <input id="swal-input-tanggal" class="swal2-input" type="date" required>
        <input id="swal-input-catatan" class="swal2-input" placeholder="Isi Link" required></input>
      </div>
    `,
    focusConfirm: false,
    preConfirm: () => {
      const tanggal = document.getElementById('swal-input-tanggal').value;
      const catatan = document.getElementById('swal-input-catatan').value;
      if (!tanggal) {
        Swal.showValidationMessage('Anda harus memilih tanggal');
      }
      return { tanggal, catatan };
    },
    inputValidator: (value) => {
      if (!value.tanggal) {
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

  const { tanggal, catatan } = value;

  const response = await useForm({ tanggal, catatan }).post(route('report.done', { report: report.id }), {
    onSuccess: () => close(),
  });

  return response;
};

const edit1 = (report) => {
    form1.id = report.id
    form1.report_id = report.id;
    form1.balasan = report.balasan
    form1.balasan1 = report.balasan1

    show1();
}

const update = () => {
  return form.patch(route('report.update', form.id), {
    onSuccess: () => close(),
    onError: () => show(),
  })
}

const update1 = () => {
    return form1.post(route('report.stored', form1.id), {
        onSuccess: () => close(),
        onError: () => show(),
    })
}

const update2 = () => {
    return form2.post(route('joblist.store', form2.id), {
        onSuccess: () => close(),
        onError: () => show(),
    })
}

const sendData = async report => {
  const response = await Swal.fire({
    title: __('Apakah kamu yakin mengirim data ini') + '?',
    icon: 'question',
    showCancelButton: true,
    showCloseButton: true,
  })
  console.log(report)
  if (response.isConfirmed) {
    report.status = 1;

    return form.patch(route('report.sync', { report : report.id }), {
      onFinish: close,
    })
  }
}

const sync = () => {
  form.patch(route('report.sync1', form.report.id), {
    users: form.users,
    onSuccess: () => form.report = null,
    onFinish: () => {
      render.value = false;
      nextTick(() => render.value = true);
      console.log(form.errors);
    },
  });
};

const jaringan = (report)=> {
  
  show3();
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
    return form.delete(route('report.destroy', report.id), {
      onFinish: close,
    })
  }
}

const submit = () => form.id ? update() : store()
const submit1 = () => form1.id ? update1() : store()
const submit2 = () => form2.id ? update2() : store()

const report_data = ref(null);

const showButtonAction = (report) => {
    report_data.value = report;
    button_show.value = !button_show.value;

    console.log(button_show);
}

const esc = e => e.key === 'Escape' && close()
onMounted(() => window.addEventListener('keydown', esc))
onUnmounted(() => window.removeEventListener('keydown', esc))

const checkboxes = [
'1. Pastikan tablet (device), laptop atau PC tersambung kepada wifi yang memiliki jaringan lokal',
'2. Pastikan tersambung dengan wifi yang mempunyai sinyal bagus',
'3. Matikan wifi, kemudian nyalakan mode pesawat lalu matikan kembali mode pesawat dan nyalakan kembali wifi',
'4.  Gunakan website lain atau browser lain untuk mengakses Kembali',
'5. Jika masih dalam keadaan error maka dokumentasikan error yang diakses berupa screenshot, foto, video serta keterangan tambahan aktifitas yang dilakukan sebelum terjadi error'];
const checked = ref({});
checkboxes.forEach(checkbox => {
  checked.value[checkbox] = false;
});

const allChecked = computed(() => {
  return Object.values(checked.value).every(item => item);
});

const checkAllChecked = () => {
  if (allChecked.value) {
    console.log('Semua checkbox tercentang!');
  }
};
</script>

<style src="@vueform/multiselect/themes/default.css"></style>
<style src="@/multiselect.css"></style>

<template>
  <DashboardLayout :title="__('Laporin')">
      <div
        class="transition-all duration-300"
        :class="{
            'pl-1 md:pl-64': open,
        }"
      > 
      
        <main class="p-0 py-0 mb-[1.25rem] ml-[1.25rem] mt-[1.25rem]">
            <h2 class="font-bold text-2xl">Data Laporin</h2>
            <a type="button" href="/" class="text-sm text-gray-500 font-semibold hover:text-sky-600 focus:text-sky-600">Home</a> 
            <span class="font-semibold text-sm pl-2 pr-2">-</span>
            <span class="text-sm text-gray-500 font-semibold hover:text-sky-600 focus:text-sky-700">Laporin</span> 
            <slot />
        </main>
      </div>
      <Card class="bg-white pt-[1.875rem] pb-[2.5rem] shadow-lg border border-solid border-slate-200" style="border-radius: 0.625rem;">
        <template #header>
          <div class="flex items-center justify-end space-x-2 p-2 pr-[1.688rem]">            
            <Button
              v-if="can('create report')"
              @click.prevent="form.id = null; show()"
              class="flex items-center justify-center grid gap-1 w-auto h-11 mr-[1.313rem] rounded-md text-center bg-sky-500 hover:bg-sky-600" 
            >
              <div class="flex items-center">
                <p class="capitalize font-semibold text-[0.938rem]">
                    {{ __('Laporin') }}
                </p>
              </div>
            </Button>  

            <!-- <Button
              v-if="can('create report')"
              @click.prevent="jaringan(report)" 
              class="flex items-center justify-center grid gap-1 w-auto h-11 mr-[1.313rem] rounded-md text-center bg-stone-400 hover:bg-stone-500" 
            >
              <div class="flex items-center">
                <p class="capitalize font-semibold text-[0.938rem]">
                    {{ __('Kendala Jaringan') }}
                </p>
              </div>
            </Button> -->
          </div>
        </template>

        <template #body>
          <div class="flex flex-col space-y-2">
            <Builder
              v-if="render"
              :url="route('report.paginate')"
              ref="table"
            >
              <template #thead="table">
                <tr class="bg-gray-200 border-gray-300">
                  <Th
                    :table="table"
                    :sort="false"
                    name="id"
                    class="border px-3 py-2 text-center capitalize font-bold"
                  >
                    {{ __('no') }}
                  </Th>

                  <Th
                    :table="table"
                    :sort="true"
                    name="kode"
                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                  >
                    {{ __('kode') }}
                  </Th>

                  <Th
                    :table="table"
                    :sort="true"
                    name="bagian_sistem"
                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                  >
                    {{ __('bagian bermasalah') }}
                  </Th>

                  <Th
                    :table="table"
                    :sort="true"
                    name="tanggal"
                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                  >
                    {{ __('tanggal') }}
                  </Th>

                  <Th
                    :table="table"
                    :sort="true"
                    name="bagian_pelapor"
                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                  >
                    {{ __('bagian pelapor') }}
                  </Th>

                  <Th
                    :table="table"
                    :sort="true"
                    name="kategori"
                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                  >
                    {{ __('kategori') }}
                  </Th>

                  <Th
                    :table="table"
                    :sort="true"
                    name="kontak"
                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                  >
                    {{ __('kontak') }}
                  </Th>
                  
                  <Th
                    :table="table"
                    :sort="true"
                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold "
                  >
                    {{ __('status') }}
                  </Th>

                  <Th
                    v-if="hasRole('it' && 'admin')"
                    :table="table"
                    :sort="true"
                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                  >
                    {{ __('dibuat oleh') }}
                  </Th>

                  <Th
                    :table="table"
                    :sort="true"
                    class="border  px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                  >
                    {{ __('Action') }}
                  </Th>
                </tr>
              </template>

              <template #tfoot="table">
                <tr class="bg-gray-200 border-gray-300">
                  <Th
                    :table="table"
                    :sort="false"
                    class="border px-3 py-2 text-center capitalize font-bold pl-[2.75rem] pb-[1.5rem]"
                  >
                    {{ __('no') }}
                  </Th>

                  <Th
                    :table="table"
                    :sort="true" 
                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold" 
                  >
                    {{ __('kode') }}
                  </Th>

                    <Th
                    :table="table"
                    :sort="true"
                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                  >
                    {{ __('bagian bermasalah') }}
                  </Th>

                  <Th
                    :table="table"
                    :sort="true"
                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                  >
                    {{ __('tanggal') }}
                  </Th>

                  <Th
                    :table="table"
                    :sort="true"
                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                  >
                    {{ __('bagian pelapor') }}
                  </Th>

                  <Th
                    :table="table"
                    :sort="true"
                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                  >
                    {{ __('kategori') }}
                  </Th>

                  <Th
                    :table="table"
                    :sort="true"
                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                  >
                    {{ __('kontak') }}
                  </Th>
                  
                  <Th
                    :table="table"
                    :sort="true"
                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                  >
                    {{ __('status') }}
                  </Th>

                  <Th
                    v-if="hasRole('it' && 'admin')"
                    :table="table"
                    :sort="true"
                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                  >
                    {{ __('dibuat oleh') }}
                  </Th>
                  
                  <Th
                    :table="table"
                    :sort="true"
                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                  >
                    {{ __('Action') }}
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
                      <td class="text-5xl text-center p-4 " colspan="1000">
                      <p class="lowercase first-letter:capitalize font-semibold">
                          {{ __('Tidak ada data untuk ditampilkan.') }}
                      </p>
                      </td>
                    </tr>
                  </template>

                  <template v-else>
                    <tr
                      v-for="(report, i) in data"
                      :key="report.id"
                      :class="processing && 'bg-gray-100'"
                      class="h-[4.375rem] transition-all duration-300"
                    >
                      <td class="px-2 py-1 text-center border-b">
                        {{ i + 1 }}
                      </td>

                      <td class="capitalize font-semibold text-center border-b">
                        {{ __(report.kode) }}
                      </td>

                      <td class="capitalize font-semibold text-center border-b">
                        {{ (report && report.bagian_hardware) ? __(report.bagian_hardware) : (report.bagian ? __(report.bagian.nama) : '') }} 
                        {{ (report && report.bagian_sistem) ? __(report.bagian_sistem) : '' }} 
                        {{ (report && report.bagian) ? __(report.bagian.singkatan) : '' }}
                      </td>

                      <td class="capitalize font-semibold text-center border-b"> 
                        {{ new Date(report.tanggal).toLocaleString('id-ID', { dateStyle: 'medium'}) }}
                      </td>

                      <td class="capitalize font-semibold text-center border-b">
                        {{ __(report.bagian_pelapor) }}
                      </td>

                      <!-- Awalnya -->
                      <!-- <td class="capitalize font-semibold text-center border-b ">
                      {{ __(report.kategori) }}
                      </td> -->
                      <!-- Update  -->
                      <td class="capitalize font-semibold text-center border-b"
                        :class="{
                            'text-cyan-600': report.kategori === 'Minor',
                            'text-rose-600': report.kategori === 'Mayor',
                            'text-orange-400': report.kategori === 'Critical',
                        }"
                      >
                        {{ __(report.kategori) }}
                      </td>

                      <td class="capitalize font-semibold text-center border-b">
                        {{ __(report.kontak) }}
                      </td>

                      <td class="border-b pl-[5.25rem]">
                        <div v-if="report.status === 1" class="bg-sky-100 text-blue-600 text-center capitalize rounded-md font-semibold text-sm p-1 w-[5rem]">
                          Terkirim
                        </div>
                        <div v-else-if="report.status === 2" class="bg-orange-200 text-center text-orange-700 capitalize rounded-md font-semibold text-sm p-1 w-[5rem]">
                          Perbaikan
                        </div>
                        <div v-else-if="report.status === 3" class="bg-green-100 text-center text-emerald-600 capitalize rounded-md font-semibold text-sm p-1 w-[5rem]">
                          Selesai
                        </div>
                        <div v-else-if="report.status === 4" class="bg-red-200 text-center text-rose-700 capitalize rounded-md  font-semibold text-sm p-1 w-[5rem]">
                          Pending
                        </div>
                        <div v-else-if="report.status === 5" class="bg-stone-100 text-center text-black capitalize font-semibold p-1 rounded-md text-sm w-[5rem]">
                          Diterima
                        </div>
                        <div v-else-if="report.status === 0" class="bg-slate-200 text-slate-500 capitalize flex justify-center text-sm text-center rounded-md font-semibold p-1 w-[5rem]">
                          Drafting
                        </div>
                        <div v-else class="bg-gray-600 text-white capitalize text-center rounded-lg font-semibold p-1 w-[120px]">
                          {{ __(report.status) }}
                        </div>
                      </td>

                      <td 
                        v-if="hasRole('it' && 'admin')"
                        class="font-semibold text-center capitalize border-b text-center "
                      >
                        {{ (report.created_by.name) }} -
                        {{ new Date(report.created_at).toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' }) }}
                      </td>

                      <td class="px-2 py-2 text-center pl-[20px] pr-[20px] border-b">
                        <div class="grid w-full pt-[5px]">
                          <button @click="showButtonAction(report)" class="inline-flex justify-center w-full px-4 py-2 text-sm text-black font-medium text-gray-700 bg-slate-100 rounded-md shadow-sm hover:bg-blue-100 hover:text-blue-700 hover:border-blue-100  focus:bg-blue-200 focus:text-blue-800 ">
                            Select Action
                            <svg  xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-2 -mr-1" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M6.293 9.293a1 1 0 011.414 0L10 11.586l2.293-2.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                          </button>

                          <!-- <ButtonGroup> -->
                          <!-- List Button -->
                          <div  v-if="button_show && report_data" class="fixed top-0 left-0 w-full h-full flex items-end justify-end " style="z-index: 20;" @click.self="button_show = false" >
                            <div  class=" flex flex-col p-6 justify-start space-y-4 mr-[130px] mb-[100px] pb-[32px] fixed top-30 pt-[30px] pb-[40px] shadow-lg shadow-[#EAEAEA] bg-white " style="border-radius: 5px; " @click.self="button_show = false">   
                              <div class="border-b pb-3 font-bold">Action List</div>  
                              <Button class="text-slate-700 text-sm font-semibold hover:bg-blue-100 hover:text-blue-700 hover:border-blue-100 focus:bg-blue-200 focus:text-blue-800 h-8">
                                <a :href="route('report.detail', report_data.id)" target="_blank" class="flex items-center">
                                  <!-- <Icon name="circle-info" class="mr-2" /> -->
                                  <p class="capitalize">
                                    {{ __('detail') }}
                                  </p>
                                </a>
                              </Button>

                              <BtnAttachment
                                :model="report_data"
                                type="Report"
                                :redaction="`Report Bug Sistem`"
                                :closed="false"
                              />

                              <Button
                                v-if="report_data.status === 0 && hasRole(['user']) && !report_data?.reportuser?.some(user => [1].includes(user.user_id))"
                                @click.prevent="sendData(report_data)" 
                                class="text-slate-700 text-sm font-semibold hover:bg-blue-100 hover:text-blue-700 hover:border-blue-100 focus:bg-blue-200 focus:text-blue-800 h-8"
                              >
                                <!-- <Icon name="paper-plane" /> -->
                                <p class="capitalize">
                                  {{ __('Kirim') }}
                                </p>
                              </Button>

                              <Button
                                v-if="report_data.status === 1 && hasRole(['superuser', 'admin']) && report_data?.reportuser?.some(user => [3].includes(user.user_id))"
                                @click.prevent="form.report = report_data; form.users = report_data.users.map(u => u.id)" 
                                class="text-slate-700 text-sm font-semibold hover:bg-blue-100 hover:text-blue-700 hover:border-blue-100 focus:bg-blue-200 focus:text-blue-800 h-8"
                              >
                                <!-- <Icon name="paper-plane" /> -->
                                <p class="capitalize">
                                  {{ __('Assign') }}
                                </p>
                              </Button>
                                
                              <Button
                                v-if="can('update1 report') && report_data.status !==3 && (report_data.feedbacks.length === 0 || !report_data.feedbacks[0].balasan) && hasRole(['it'])"
                                @click.prevent="edit1(report_data)"
                                class="text-slate-700 text-sm font-semibold hover:bg-blue-100 hover:text-blue-700 hover:border-blue-100 focus:bg-blue-200 focus:text-blue-800 h-8"
                              >
                                <!-- <Icon name="edit" /> -->
                                <p class="capitalize">
                                  {{ __('Balasan') }}
                                </p>
                              </Button>

                              <Button
                                v-if="can('update report') && report_data.created_by_id === user.id && report_data.status ==0 && !report_data?.reportuser?.some(user => [1].includes(user.user_id))"
                                @click.prevent="edit(report_data)"
                                class="text-slate-700 text-sm font-semibold hover:bg-blue-100 hover:text-blue-700 hover:border-blue-100 focus:bg-blue-200 focus:text-blue-800 h-8"
                              >
                                <!-- <Icon name="edit" /> -->
                                <p class="capitalize">
                                  {{ __('ubah') }}
                                </p>
                              </Button>
                              
                              <!-- <Button
                                v-if="can('delete report') && !report_data.code && report_data.status !==3 && report_data.created_by_id === user.id"
                                @click.prevent="destroy(report_data)"
                                class="text-slate-700 text-sm font-semibold hover:bg-blue-100 hover:text-blue-700 hover:border-blue-100 focus:bg-blue-200 focus:text-blue-800 h-8"
                              >
                                <p class="capitalize">
                                  {{ __('hapus') }}
                                </p>
                              </Button> -->

                              <!-- <Button
                                v-if="hasRole(['superuser', 'it']) && report_data.status !==2 && report_data.status !==3 && report_data.status !==4 && report_data.status !==5"
                                @click.prevent="listjob(report_data)"
                                class="text-slate-700 text-sm font-semibold hover:bg-blue-100 hover:text-blue-700 hover:border-blue-100 focus:bg-blue-200 focus:text-blue-800 h-8"
                              >
                                <p class="capitalize">
                                  {{ __('add list') }}
                                </p>
                              </Button> -->
                          
                              <Button 
                                v-if="hasRole(['superuser', 'it']) && report_data.status !== 2 && report_data.status !==3"
                                @click.prevent="repair(report_data)" 
                                class="text-slate-700 text-sm font-semibold hover:bg-blue-100 hover:text-blue-700 hover:border-blue-100 focus:bg-blue-200 focus:text-blue-800 h-8"
                              >
                                <!-- <Icon name="wrench" /> -->
                                <p class="capitalize">
                                  {{ __('perbaiki') }}
                                </p>
                              </Button>

                              <Button 
                                v-if="hasRole(['superuser', 'it']) && report_data.status !==4 && report_data.status !==3"
                                @click.prevent="reject(report_data)"
                                class="text-slate-700 text-sm font-semibold hover:bg-blue-100 hover:text-blue-700 hover:border-blue-100 focus:bg-blue-200 focus:text-blue-800 h-8"
                              >
                                <!-- <Icon name="circle-xmark" /> -->
                                <p class="capitalize">
                                  {{ __('pending') }}
                                </p>
                              </Button>

                              <Button 
                                v-if="hasRole(['superuser', 'it']) && report_data.status == 2 "
                                @click.prevent="done(report_data)" 
                                class="text-slate-700 text-sm font-semibold hover:bg-blue-100 hover:text-blue-700 hover:border-blue-100 focus:bg-blue-200 focus:text-blue-800 h-8"
                              >
                                <!-- <Icon name="circle-check" /> -->
                                <p class="capitalize">
                                  {{ __('Selesai') }}
                                </p>
                              </Button>
                            </div>
                          </div>  
                          <!-- </ButtonGroup> -->
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
        <form
          @submit.prevent="submit"
          class="w-10/12 max-w-7xl shadow-xl" 
        >
          <Card class="bg-white">
            <!-- Close icon -->
            <template #header>
              <div class="flex items-center border-b h-[70px] mb-[30px] bg-white justify-end rounded-t-lg p-2 pb-[0.938rem] pt-[0.938rem]">
                <!-- <Close @click.prevent="close" /> -->   <h2 class="font-bold text-xl w-full ml-10">Data Laporin</h2>
                <button class="pr-5">
                  <svg @click.prevent="close" class="w-6 h-6 text-gray-700 hover:text-cyan-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>
                  </svg>
                </button>
              </div>
            </template>

            <template #body>
              <div class="flex flex-col space-y-4 p-4">
                <template v-if="hasRole (['superuser', 'it']) && regis">
                  <div class="flex flex-col space-y-2">
                    <div class="flex items-center space-x-2">
                      <label for="kode" class="w-1/3 capitalize">
                        {{ __('kode') }}
                      </label>

                      <Input
                        v-model="form.kode"
                        :placeholder="__('kode')"
                        type="text"
                      />
                    </div>

                    <InputError
                      :error="form.errors.kode"
                    />
                  </div>
                </template>
    
                <template v-if="hasRole (['superuser', 'user', 'it', 'admin']) && ! regis">
                  <div class="flex flex-col space-y-2" >
                    <div class="flex items-center space-x-2">
                      <label for="kategori" class="w-1/3 capitalize">
                        {{ __('kategori kendala yang ditemukan') }}
                      </label>

                      <Select
                        v-model="form.kategori"
                        :placeholder="__('Pilih Kategori')"
                        :options="[
                          'Software',
                          'Hardware',
                          'Jaringan',
                        ]"
                        type="text"
                        :searchable="true"
                        requiered
                      />
                    </div>

                    <InputError
                      :error="form.errors.kategori"
                    />
                  </div>           

                  <div v-if="form.kategori == 'Jaringan'" class="flex flex-col space-y-2">
                    <div v-for="(checkbox, index) in checkboxes" :key="index">
                      <input type="checkbox" :id="'checkbox' + index" v-model="checked[checkbox]" @change="checkAllChecked">
                      <label :for="'checkbox' + index">{{ checkbox }}</label>
                    </div>
                    <p class="font-semibold text-red-600">
                      * {{ __('Jika pada semua langkah diatas sudah dilakukan dan tidak ada perubahan, mohon isi data laporin terkait jaringan') }}
                    </p>
                    <div v-if="allChecked"><div>
                    <br>
                    <div class="flex items-center space-x-2">
                      <label for="tanggal" class="w-1/3 capitalize">
                        {{ __('Tanggal ditemukan kendala') }}
                      </label>

                      <Input
                        v-model="form.tanggal"
                        type="date"
                        required
                      />
                    </div>

                    <InputError
                      :error="form.errors.tanggal"
                    />
                  </div>

                  <br>
                  <div>
                    <div class="flex items-center space-x-2">
                      <label for="bagian_pelapor" class="w-1/3 capitalize">
                        {{ __('Bagian Pelapor') }}
                      </label>

                      <Select
                        v-model="form.bagian_pelapor"
                        :options="divisionSections.map(division => ({
                          label: `${division.division_name}`,
                          value: division.division_name,
                        }))"
                        :searchable="true"
                        placeholder="Pilih Bagian"
                      />
                    </div>

                    <InputError :error="form.errors.bagian_pelapor" />
                  </div>

                  <br>
                  <div>
                    <div class="flex items-center space-x-2">
                      <label for="kendala" class="w-1/3 capitalize">
                        {{ __('Uraian kendala yang ditemukan') }}
                      </label>

                      <TextArea
                        v-model="form.kendala"
                        :placeholder="__('Isi uraian kendala yang ditemukan')"
                        required
                      />
                    </div>

                    <InputError
                      :error="form.errors.kendala"
                    />
                  </div>

                  <br>
                  <div>
                    <div class="flex items-center space-x-2">
                      <label for="dampak" class="w-1/3 capitalize">
                        {{ __('Dampak dari kendala') }}
                      </label>

                      <TextArea
                        v-model="form.dampak"
                        :placeholder="__('Isi dampak dari kendala')"
                        required
                      />
                    </div>

                    <InputError
                      :error="form.errors.dampak"
                    />
                  </div>

                  <br>
                  <div>
                    <div class="flex items-center space-x-2">
                      <label for="url" class="w-1/3 capitalize">
                        {{ __('Link URL') }}
                      </label>

                      <Input
                        v-model="form.url"
                        :placeholder="__('Salin link/url yang mengalami bug/error')"
                        type="text"
                        required
                      />
                    </div>

                    <InputError
                      :error="form.errors.url"
                    />
                  </div>

                  <br>
                  <div>
                    <div class="flex items-center space-x-2">
                      <label for="kontak" class="w-1/3 capitalize">
                        {{ __('Kontak yang dapat dihubungi') }}
                      </label>

                      <Input
                        v-model="form.kontak"
                        :placeholder="__('Kontak yang dapat dihubungi')"
                        type="text"
                        required
                      />
                    </div>

                    <InputError
                      :error="form.errors.kontak"
                    />
                  </div>
                </div>
              </div>
                
              <div v-if="form.kategori == 'Hardware'" class="flex flex-col space-y-2">
                <div class="flex items-center space-x-2">
                  <label for="bagian_hardware" class="w-1/3 capitalize">
                    {{ __('bagian hardware bermasalah') }}
                  </label>

                  <Select
                    v-model="form.bagian_hardware"
                    :placeholder="__('pilih bagian sistem yang bermasalah')"
                    :options="[
                        'Troubleshoot Laptop',
                        'Troubleshoot Komputer',
                        'Instalasi Sistem Operasi',
                    ]"
                    type="text"
                    :searchable="true"
                    requiered
                  />
                </div>

                <InputError
                  :error="form.errors.bagian_hardware"
                />
              </div>
                
              <!-- <div v-if="form.bagian_sistem == 'Lainnya'" class="flex flex-col space-y-2"> -->
              <!-- <div v-if="form.kategori != 'Jaringan' && form.kategori != 'Software' && !['Troubleshoot Laptop','Instalasi Sistem Operasi'].includes(form.bagian_sistem)" class=" flex flex-col space-y-1"> -->
              <!-- <div v-if="form.kategori === 'Hardware' && form.bagian_hardware === 'Lainnya'" class=" flex flex-col space-y-1"> -->
              <!-- <div v-if="! ['Troubleshoot Laptop','Troubleshoot Komputer','Instalasi Sistem Operasi'].includes(form.bagian_hardware) && form.kategori === 'Hardware'" class=" flex flex-col space-y-1">
                <div class="flex items-center space-x-2">
                  <label for="bagian_hardware1" class="w-1/3 capitalize">
                    {{ __('Bagian yang Bermasalah') }}
                  </label>

                  <Input
                    v-model="form.bagian_hardware1"
                    placeholder="Masukan detail masalah"
                    type="text"
                    required
                  />
                </div>

                <InputError
                  :error="form.errors.bagian_hardware1"
                />
              </div> -->

              <div v-if="form.kategori == 'Software'" class="flex flex-col space-y-2">
                <div class="flex items-center space-x-2">
                  <label for="bagian_id" class="w-1/3 capitalize">
                    {{ __('bagian sistem yang bermasalah') }}
                  </label>
                  <Select
                    v-model="form.bagian_id"
                    :options="systemSections.map(section => ({
                    label: `${section.nama} (${section.singkatan})`,
                    value: section.id,
                    }))"
                    :searchable="true"
                    placeholder="Pilih Sistem yang bermasalah"
                  />
                </div>

                <InputError :error="form.errors.bagian_id" />
              </div>
              
              <div v-if="form.kategori == 'Software'" class="flex flex-col space-y-2">
                <div class="flex items-center space-x-2">
                  <label for="jenis" class="w-1/3 capitalize">
                    {{ __('Jenis') }}
                  </label>

                  <Select
                    v-model="form.jenis"
                    :placeholder="__('Pilih Jenis Kendala (bug/error)/Permintaan Penambahan Fitur dll pada Sistem')"
                    :options="[
                        'Kendala Sistem',
                        'Penambahan Fitur Sistem',
                    ]"
                    type="text"
                    :searchable="true"
                    requiered
                  />
                </div>

                <InputError
                  :error="form.errors.jenis"
                />
              </div>                            

              <div class="flex flex-col space-y-2">
                <div v-if="form.kategori == 'Hardware'">
                  <div class="flex items-center space-x-2">
                    <label for="tanggal" class="w-1/3 capitalize">
                      {{ __('Tanggal ditemukan kendala') }}
                    </label>

                    <Input
                      v-model="form.tanggal"
                      type="date"
                      required
                    />
                  </div>

                  <InputError
                    :error="form.errors.tanggal"
                  />
                </div>

                <div v-if="form.kategori == 'Software'">
                  <div class="flex items-center space-x-2">
                    <label for="tanggal" class="w-1/3 capitalize">
                      {{ __('Tanggal ditemukan kendala') }}
                    </label>

                    <Input
                      v-model="form.tanggal"
                      type="date"
                      required
                    />
                  </div>

                  <InputError
                    :error="form.errors.tanggal"
                  />
                </div>
              </div>          

              <div class="flex flex-col space-y-2">
                <div v-if="form.kategori == 'Hardware'">
                  <div class="flex items-center space-x-2">
                    <label for="bagian_pelapor" class="w-1/3 capitalize">
                      {{ __('Bagian Pelapor') }}
                    </label>

                    <Select
                      v-model="form.bagian_pelapor"
                      :options="divisionSections.map(division => ({
                        label: `${division.division_name}`,
                        value: division.division_name,
                      }))"
                      :searchable="true"
                      placeholder="Pilih Bagian"
                    />
                  </div>

                  <InputError :error="form.errors.bagian_pelapor" />
                </div>

                <div v-if="form.kategori == 'Software'">
                  <div class="flex items-center space-x-2">
                    <label for="bagian_pelapor" class="w-1/3 capitalize">
                      {{ __('Bagian Pelapor') }}
                    </label>

                    <Select
                      v-model="form.bagian_pelapor"
                      :options="divisionSections.map(division => ({
                        label: `${division.division_name}`,
                        value: division.division_name,
                      }))"
                      :searchable="true"
                      placeholder="Pilih Bagian"
                    />
                  </div>

                  <InputError :error="form.errors.bagian_pelapor" />
                </div>
              </div>

              <div class="flex flex-col space-y-2">
                <div v-if="form.kategori == 'Hardware'">
                  <div class="flex items-center space-x-2">
                    <label for="kendala" class="w-1/3 capitalize">
                      {{ __('Uraian kendala yang ditemukan') }}
                    </label>

                    <TextArea
                      v-model="form.kendala"
                      :placeholder="__('Isi uraian kendala yang ditemukan')"
                      required
                    />
                  </div>

                  <InputError
                    :error="form.errors.kendala"
                  />
                </div>

                <div v-if="form.kategori == 'Software'">
                  <div class="flex items-center space-x-2">
                    <label for="kendala" class="w-1/3 capitalize">
                      {{ __('Uraian kendala yang ditemukan') }}
                    </label>

                    <TextArea
                      v-model="form.kendala"
                      :placeholder="__('Isi uraian kendala yang ditemukan')"
                      required
                    />
                  </div>

                  <InputError
                    :error="form.errors.kendala"
                  />
                </div>
              </div>

              <div class="flex flex-col space-y-2">
                <div v-if="form.kategori == 'Hardware'">
                  <div class="flex items-center space-x-2">
                    <label for="dampak" class="w-1/3 capitalize">
                      {{ __('Dampak dari kendala') }}
                    </label>

                    <TextArea
                      v-model="form.dampak"
                      :placeholder="__('Isi dampak dari kendala')"
                      required
                    />
                  </div>

                  <InputError
                    :error="form.errors.dampak"
                  />
                </div>

                <div v-if="form.kategori == 'Software'">
                  <div class="flex items-center space-x-2">
                    <label for="dampak" class="w-1/3 capitalize">
                      {{ __('Dampak dari kendala') }}
                    </label>

                    <TextArea
                      v-model="form.dampak"
                      :placeholder="__('Isi dampak dari kendala')"
                      required
                    />
                  </div>

                  <InputError
                    :error="form.errors.dampak"
                  />
                </div>
              </div>
              
              <div class="flex flex-col space-y-2">
                <div v-if="form.kategori == 'Hardware'">
                  <div class="flex items-center space-x-2">
                    <label for="url" class="w-1/3 capitalize">
                      {{ __('Link URL') }}
                    </label>

                    <Input
                      v-model="form.url"
                      :placeholder="__('Salin link/url yang mengalami bug/error')"
                      type="text"
                      required
                    />
                  </div>

                  <InputError
                    :error="form.errors.url"
                  />
                </div>

                <div v-if="form.kategori == 'Software'">
                  <div class="flex items-center space-x-2">
                    <label for="url" class="w-1/3 capitalize">
                      {{ __('Link URL') }}
                    </label>

                    <Input
                      v-model="form.url"
                      :placeholder="__('Salin link/url yang mengalami bug/error')"
                      type="text"
                      required
                    />
                  </div>

                  <InputError
                    :error="form.errors.url"
                  />
                </div>
              </div>                  

              <div class="flex flex-col space-y-2">
                <div v-if="form.kategori == 'Hardware'">
                  <div class="flex items-center space-x-2">
                    <label for="kontak" class="w-1/3 capitalize">
                      {{ __('Kontak yang dapat dihubungi') }}
                    </label>

                    <Input
                      v-model="form.kontak"
                      :placeholder="__('Kontak yang dapat dihubungi')"
                      type="text"
                      required
                    />
                  </div>

                  <InputError
                    :error="form.errors.kontak"
                  />
                </div>

                <div v-if="form.kategori == 'Software'">
                  <div class="flex items-center space-x-2">
                    <label for="kontak" class="w-1/3 capitalize">
                      {{ __('Kontak yang dapat dihubungi') }}
                    </label>

                    <Input
                      v-model="form.kontak"
                      :placeholder="__('Kontak yang dapat dihubungi')"
                      type="text"
                      required
                    />
                  </div>

                  <InputError
                    :error="form.errors.kontak"
                  />
                </div>
              </div>    
            </template>
            </div>
          </template>

          <template #footer>
            <div class="flex items-center justify-end space-x-2 bg-white px-8 py-1 h-[100px] mr-[10px]">
            <Button 
              class="rounded-md text-center bg-sky-500 hover:bg-sky-600 flex items-center justify-center w-[130px] h-[40px]"
              type="submit" v-if="! regis"
            >
              <p class="capitalize font-semibold">
                {{ __(form.id ? 'simpan' : 'simpan') }}
              </p>
              <svg class="w-4 h-4 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"/>
              </svg>
            </Button>

            <ButtonGreen type="submit" v-if="regis" @click.prevent="update">
              <Icon name="check" />
              <p class="uppercase font-semibold">
                {{ __('regis') }}
              </p>
            </ButtonGreen>
            </div>
          </template>
        </Card>
        </form>
      </Modal>

      <Modal :show="balas">
        <form
          @submit.prevent="submit1"
          class="w-full max-w-3xl h-fit shadow-xl" 
        >
          <Card class="bg-white">
            <template #header>
              <div class="flex items-center border-b h-[70px] mb-[30px] bg-white justify-end rounded-t-lg p-2 pb-[0.938rem] pt-[0.938rem]">
                <!-- <Close @click.prevent="close" /> -->
                <button class="pr-5">
                  <svg @click.prevent="close" class="w-6 h-6 text-gray-700 hover:text-cyan-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>
                  </svg>
                </button>
              </div>
            </template>

            <template #body>
              <div class="flex flex-col space-y-4 p-4">
                <template v-if="hasRole(['superuser', 'admin', 'it']) && !regis">  
                  <div class="flex flex-col space-y-2">
                    <div class="flex items-center space-x-2">
                      <label for="balasan" class="w-1/3 capitalize">
                        {{ __('kirim balasan anda') }}
                      </label>
                      <TextArea
                        v-model="form1.balasan"
                        :placeholder="__('kirim balasan anda')"
                        type="text"
                        required
                      />
                    </div>

                        
                    <InputError
                    :error="form.errors.balasan"
                    />
                  </div>    
                </template>
              </div>
            </template>

            <template #footer>
              <div class="flex items-center border-b h-[70px] mb-[30px] bg-white justify-end rounded-t-lg p-2 pb-[0.938rem] pt-[0.938rem]">
                <Button 
                  class="rounded-md text-center bg-sky-500 hover:bg-sky-600 flex items-center justify-center w-[130px] h-[40px]"
                  type="submit" v-if="! regis"
                >
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

      <Modal :show="open2">
        <form
          @submit.prevent="submit3"
          class="w-10/12 max-w-7xl shadow-xl" 
        >
          <Card class="bg-white">
            <template #header>
              <div class="flex items-center border-b h-[70px] mb-[30px] bg-white justify-end rounded-t-lg p-2 pb-[0.938rem] pt-[0.938rem]">
                <!-- <Close @click.prevent="close" /> -->
                <button class="pr-5">
                  <svg @click.prevent="close" class="w-6 h-6 text-gray-700 hover:text-cyan-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>
                  </svg>
                </button>
              </div>
            </template>

            <template #body>
              <div class="flex flex-col space-y-4 p-4 ml-6 mr-6">  
                <div class="flex flex-col space-y-2">
                  <div>
                    <p class="uppercase font-bold text-center">
                      {{ __('Langkah pertama yang harus dilakukan jika ada kendala pada jaringan') }}
                    </p><br>
                    <p class="font-semibold">
                      {{ __('1. Pastikan tablet (device), laptop atau PC tersambung kepada wifi yang memiliki jaringan lokal') }}
                    </p>
                    <p class="font-semibold">
                      {{ __('2. Pastikan tersambung dengan wifi yang mempunyai sinyal bagus') }}
                    </p>
                    <p class="font-semibold">
                      {{ __('3. Matikan wifi, kemudian nyalakan mode pesawat lalu matikan kembali mode pesawat dan nyalakan kembali wifi') }}
                    </p>
                    <p class="font-semibold">
                      {{ __('4. Gunakan website lain atau browser lain untuk mengakses Kembali') }}
                    </p>
                    <p class="font-semibold">
                      {{ __('5. Jika masih dalam keadaan error maka dokumentasikan error yang diakses berupa screenshot, foto, video serta keterangan tambahan aktifitas yang dilakukan sebelum terjadi error') }}
                    </p><br>pjop
                    <p class="font-semibold text-red-600">
                      {{ __(' * Jika pada semua langkah diatas sudah dilakukan dan tidak ada perubahan, mohon isi data laporin terkait jaringan') }}
                    </p>
                  </div>
                </div>    
              </div>
            </template>
            <template #footer>
              <div class="flex items-center justify-end space-x-2 bg-gray-200 px-2 py-1">
                <!-- <ButtonGreen type="submit" v-if="!regis">
                    <p class="uppercase font-semibold">
                    {{ __(form.id ? 'simpan' : 'simpan') }}
                    </p>
                </ButtonGreen> -->
              </div>
            </template>
          </Card>
        </form>
      </Modal>

      <Modal :show="form.report != null">
        <form @submit.prevent="sync" class="w-10/12 max-w-2xl shadow-xl">
          <Card class="bg-white">
            <template #header>
              <div class="flex items-center border-b h-[70px] mb-[30px] bg-white justify-end rounded-t-lg p-2 pb-[0.938rem] pt-[0.938rem]">
                <!-- <Close @click.prevent="form.report = null" /> -->
                <button class="pr-5">
                  <svg @click.prevent="form.report = null" class="w-6 h-6 text-gray-700 hover:text-cyan-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>
                  </svg>
                </button>
              </div>
            </template>
            
            <template #body>
              <div class="p-4">
                <Select
                  v-model="form.users"
                  :options="users.filter(user => user.id !== 3 && user.id !== 1).map(user => ({
                  label: user.name,
                  value: user.id,
                  }))"
                  :searchable="true"
                  mode="tags"
                  class="uppercase"
                  placeholder="receiver"
                />
              </div>
            </template>

            <template #footer>
              <div class="flex items-center justify-end space-x-2 bg-white px-8 py-1 h-[100px] mr-[10px]">
                <ButtonGreen
                  :disabled="form.processing"
                >
                  <Icon name="check" />
                  <p class="uppercase font-semibold">
                    {{ __('submit') }}
                  </p>
                </ButtonGreen>
              </div>
            </template>
          </Card>
        </form>
      </Modal>
  </DashboardLayout>
</template>
