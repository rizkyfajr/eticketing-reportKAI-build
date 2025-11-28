<script setup>
import { onMounted, onUnmounted, ref, watch } from 'vue'
import { useForm, usePage, Link } from '@inertiajs/inertia-vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Card from '@/Components/Card.vue'
import Swal from 'sweetalert2'
import Select from '@vueform/multiselect'
import Input from '@/Components/Input.vue'
import AttachmentInline from '@/Components/Button/AttachmentInline.vue'
import axios from 'axios'

const props = defineProps({
  report: Object,
  mglurusanawal: Object,
  mglengkunganawal: Object,
  mgweselawal: Object,
  pemeriksaansilangkpjr: Object,
  pemeriksaansilanglahan: Object,
  perekamanawal: Object,
  mglurusanawal_attachments: Array,
  mglengkunganawal_attachments: Array,
  mgweselawal_attachments: Array,
  pemeriksaansilangkpjr_attachments: Array,
  pemeriksaansilanglahan_attachments: Array,
  perekamanawal_attachments: Array,
  machines: Array,
  regions: Array,
  users: Array,
});

const { user } = usePage().props.value

const form = useForm({
  machine_id: props.report?.machine_id || '',
  region_id: props.report?.region_id || '',
  date: props.report?.date || '',
  has_trouble: props.report?.has_trouble || '',
  status: props.report?.status || '',
  cuaca: props.report?.cuaca || '',
  jenis_kpjr: props.report?.jenis_kpjr || '',
  nomor_mesin: props.report?.nomor_mesin || '',
  nomor_sarana: props.report?.nomor_sarana || '',
  waktu_start_engine: props.report?.waktu_start_engine || '',
  jam_traveling_awal: props.report?.jam_traveling_awal || '',
  jam_kerja_awal: props.report?.jam_kerja_awal || '',
  jam_mesin_awal: props.report?.jam_mesin_awal || '',
  jam_generator_awal: props.report?.jam_generator_awal || '',
  counter_tamping_awal: props.report?.counter_tamping_awal || '',
  oddometer_awal: props.report?.oddometer_awal || '',
  hsd_awal_kerja: props.report?.hsd_awal_kerja || '',
  operator_by1: props.report?.operator_by1 || '',
  operator_by2: props.report?.operator_by2 || '',
  operator_by3: props.report?.operator_by3 || '',
  approved_by: props.report?.approved_by || '',
  approved_by1: props.report?.approved_by1 || '',
  note: props.report?.note || '',
  mode: props.report?.mode || '',
  nama_pengawal: props.report?.nama_pengawal || '',
  nipp: props.report?.nipp || '',
  nama_pengawal1: props.report?.nama_pengawal1 || '',
  nipp1: props.report?.nipp1 || '',
})

const form1 = useForm({
  id: props.mglurusanawal?.id || null,
  working_report_id: props.report?.id || null,
  ada: props.mglurusanawal?.ada || '0', 
  tidak: props.mglurusanawal?.tidak || '0',
})

const form2 = useForm({
  id: props.mglengkunganawal?.id || null,
  working_report_id: props.report?.id || null,
  ada: props.mglengkunganawal?.ada || '0', 
  tidak: props.mglengkunganawal?.tidak || '0',
})

const form3 = useForm({
  id: props.mgweselawal?.id || null,
  working_report_id: props.report?.id || null,
  ada: props.mgweselawal?.ada || '0', 
  tidak: props.mgweselawal?.tidak || '0',
})

const form4 = useForm({
  id: props.pemeriksaansilangkpjr?.id || null,
  working_report_id: props.report?.id || null,
  ada: props.pemeriksaansilangkpjr?.ada || '0', 
  tidak: props.pemeriksaansilangkpjr?.tidak || '0',
})

const form5 = useForm({
  id: props.pemeriksaansilanglahan?.id || null,
  working_report_id: props.report?.id || null,
  ada: props.pemeriksaansilanglahan?.ada || '0', 
  tidak: props.pemeriksaansilanglahan?.tidak || '0',
})

const form6 = useForm({
  id: props.perekamanawal?.id || null,
  working_report_id: props.report?.id || null,
  ada: props.perekamanawal?.ada || '0', 
  tidak: props.perekamanawal?.tidak || '0',
})

watch(
  () => form.machine_id,
  (newVal) => {
    if (!newVal) {
      form.jenis_kpjr = ''
      form.nomor_mesin = ''
      form.nomor_sarana = ''
      form.region_id = ''
      return
    }

    const selected = props.machines.find(m => m.id === newVal)

    if (selected) {
      form.jenis_kpjr = `${selected.name} ${selected.type}`
      form.nomor_mesin = selected.nomor || ''
      form.nomor_sarana = selected.no_sarana || ''
      form.region_id = selected.region_id || ''
    }
  }
)

watch(() => props.mglurusanawal_attachments, (val) => {
    if (val?.length > 0) {
        form1.ada = "1";
        form1.tidak = "0";
    }
}, { immediate: true });

watch(() => props.mglengkunganawal_attachments, (val) => {
    if (val?.length > 0) {
        form2.ada = "1";
        form2.tidak = "0";
    }
}, { immediate: true });

watch(() => props.mgweselawal_attachments, (val) => {
    if (val?.length > 0) {
        form3.ada = "1";
        form3.tidak = "0";
    }
}, { immediate: true });

watch(() => props.pemeriksaansilangkpjr_attachments, (val) => {
    if (val?.length > 0) {
        form4.ada = "1";
        form4.tidak = "0";
    }
}, { immediate: true });

watch(() => props.pemeriksaansilanglahan_attachments, (val) => {
    if (val?.length > 0) {
        form5.ada = "1";
        form5.tidak = "0";
    }
}, { immediate: true });

watch(() => props.perekamanawal_attachments, (val) => {
    if (val?.length > 0) {
        form6.ada = "1";
        form6.tidak = "0";
    }
}, { immediate: true });

function oppositeWatcher(form) {
    watch(() => form.ada, (v) => { if (v === '1') form.tidak = '0' })
    watch(() => form.tidak, (v) => { if (v === '1') form.ada = '0' })
}

oppositeWatcher(form1)
oppositeWatcher(form2)
oppositeWatcher(form3)
oppositeWatcher(form4)
oppositeWatcher(form5)
oppositeWatcher(form6)

const showForm1 = ref(false)

onMounted(() => {
  const report = props.report

  if (
    report?.mglurusanawal ||
    report?.mglengkunganawal ||
    report?.mgweselawal ||
    report?.pemeriksaansilangkpjr ||
    report?.pemeriksaansilanglahan ||
    report?.perekamanawal
  ) {
    showForm1.value = true
  }
});

const validateForms = () => {
    const failedForms = [];
    const validationMap = [
        { form: form1, name: 'MG 1 (Lurusan)' },
        { form: form2, name: 'MG 2 (Lengkungan)' },
        { form: form3, name: 'MG 3 (Wesel)' },
        { form: form4, name: 'Pemeriksaan Silang KPJR' },
        { form: form5, name: 'Pemeriksaan Silang Lahan' },
        { form: form6, name: 'Perekaman Awal' },
    ];

    validationMap.forEach(item => {
        if (item.form.ada === '0' && item.form.tidak === '0') {
            failedForms.push(item.name);
        }
    });

    return failedForms;
};

const submit = () => {
  Swal.fire({
    title: 'Menyimpan data...',
    didOpen: () => Swal.showLoading(),
    allowOutsideClick: false,
  })

  form.post(route('working-reports.store'), {
    onSuccess: () => {
      Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: 'Working Report berhasil disimpan.',
        timer: 1200,
        showConfirmButton: false,
      });      
      setTimeout(() => window.location.reload(), 1000);
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

const submitForms = () => {
  const failedValidations = validateForms();

  if (failedValidations.length > 0) {
      let errorMessage = 'Wajib upload pada tahap berikut: <br><br>';
      errorMessage += '<ul>';
      failedValidations.forEach(name => {
          errorMessage += `<li class="text-red-500 text-sm">- ${name}</li>`;
      });
      errorMessage += '</ul>';

      Swal.fire({
          icon: 'warning',
          title: 'Mohon Lengkapi Data!',
          html: errorMessage,
          confirmButtonText: 'OK',
      });
      return;
  }
  Swal.fire({
      title: 'Menyimpan data...',
      didOpen: () => Swal.showLoading(),
      allowOutsideClick: false,
  });

  const payload = {
    working_report_id: props.report.id,

    mg1: form1.data(),
    mg2: form2.data(),
    mg3: form3.data(),
    silang_kpjr: form4.data(),
    silang_lahan: form5.data(),
    perekaman: form6.data(),
  };

  axios.post(route('working-reports.submit-form'), payload)
  .then((res) => {
      Swal.fire({
          icon: 'success',
          title: 'Berhasil!',
          text: 'Data berhasil disimpan.',
          timer: 1000,
          showConfirmButton: false,
      });

      setTimeout(() => {
          window.location.href = res.data.redirect;
      }, 1000);
  });

};

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
              <div v-if="!showForm1" class="space-y-4">
                
                <div class="grid grid-cols-1 gap-4 mb-4 md:grid-cols-2">                  

                  <div class="flex flex-col">
                    <label for="approved_by1" class="block text-xs font-semibold">
                      {{ __('Mode') }}
                    </label>
                    
                    <select 
                      v-model="form.mode"
                      class="border border-gray-300 rounded-md px-2 py-1 h-9 text-xs bg-white focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                    >
                      <option value="" disabled>Pilih</option>
                      <option value="warmingup">Warming Up</option>
                      <option value="working">Working</option>
                    </select>
                    <InputError :error="form.errors.approved_by1" />
                  </div>

                  <div class="flex flex-col">
                    <label class="block text-xs font-semibold">Nama Mesin</label>
                    <!-- <select
                      v-model="form.machine_id"
                      class="w-full border rounded-md px-2 py-2 bg-white text-xs"
                    >
                      <option disabled value="">-- Pilih Mesin --</option>
                      <option v-for="machine in machines" :key="machine.id" :value="machine.id">
                          {{ `${machine.name} - ${machine.type} (${machine.region.name})` }}
                      </option>
                    </select> -->
                    <Select
                      v-model="form.machine_id"
                      class="w-full border rounded-md px-2 py-2 bg-white text-xs"
                      :options="machines.map(machine => ({
                        label: `[${machine.nomor}] ${machine.name} - ${machine.type} - ${machine.no_sarana} (${machine.region.name})`,
                        value: machine.id,
                      }))"
                      :searchable="true"
                      placeholder="Pilih Mesin"
                      style="font-size: 0.7rem;"
                    >
                      <template #option="{ option }">
                        <span class="text-xs antialiased">
                            {{ option.label }}
                        </span>
                      </template>
                    </Select>
                    <InputError :error="form.errors.machine_id" />
                  </div>

                  <div class="flex flex-col">
                    <label class="block text-xs font-semibold">Wilayah</label>      
                    <Select
                      v-model="form.region_id"
                      class="w-full border rounded-md px-2 py-2 bg-white text-xs"
                      :options="regions.map(region => ({
                        label: `${region.name}`,
                        value: region.id,
                      }))"
                      :searchable="true"
                      placeholder="Pilih Mesin"
                      style="font-size: 0.7rem;"
                      disabled
                    >
                      <template #option="{ option }">
                        <span class="text-xs antialiased">
                            {{ option.label }}
                        </span>
                      </template>
                    </Select>
                    <InputError :error="form.errors.region_id" />
                  </div>

                  <div class="flex flex-col">
                    <label class="block text-xs font-semibold">Tanggal</label>
                    <Input
                      v-model="form.date"
                      type="datetime-local"
                      class="w-full border rounded-md px-2 py-2 text-xs"
                      placeholder="Isi tanggal"
                    />
                  </div>

                  <div class="flex flex-col">
                    <label class="block text-xs font-semibold">Cuaca</label>
                    <Input
                      v-model="form.cuaca"
                      type="text"
                      class="w-full border rounded-md px-2 py-2 text-xs"
                      placeholder="Isi Cuaca"
                    />
                  </div>

                  <div class="flex flex-col">
                    <label class="block text-xs font-semibold">Jenis KPJR</label>
                    <Input
                      v-model="form.jenis_kpjr"
                      type="text"
                      class="w-full border rounded-md px-2 py-2 text-xs"
                      placeholder="Isi Jenis KPJR"
                    />
                  </div>

                  <div class="flex flex-col">
                    <label class="block text-xs font-semibold">Nomor Mesin</label>
                    <Input
                      v-model="form.nomor_mesin"
                      type="text"
                      class="w-full border rounded-md px-2 py-2 text-xs"
                      placeholder="Isi Nomor Mesin"
                    />
                  </div>

                  <div class="flex flex-col">
                    <label class="block text-xs font-semibold">Nomor Sarana</label>
                    <Input
                      v-model="form.nomor_sarana"
                      type="text"
                      class="w-full border rounded-md px-2 py-2 text-xs"
                      placeholder="Isi Nomor Sarana"
                    />
                  </div>

                  <div class="flex flex-col">
                    <label class="block text-xs font-semibold">Waktu Start Engine</label>
                    <Input
                      v-model="form.waktu_start_engine"
                      type="time"
                      class="w-full border rounded-md px-2 py-2 text-xs"
                      placeholder="Isi Waktu Start Engine"
                    />
                  </div>

                  <div class="flex flex-col">
                    <label class="block text-xs font-semibold">Jam Traveling Awal</label>
                    <Input
                      v-model="form.jam_traveling_awal"
                      type="text"
                      class="w-full border rounded-md px-2 py-2 text-xs"
                      placeholder="Isi Jam Traveling Awal"
                    />
                  </div>

                  <div class="flex flex-col">
                    <label class="block text-xs font-semibold">Jam Kerja Awal</label>
                    <Input
                      v-model="form.jam_kerja_awal"
                      type="time"
                      class="w-full border rounded-md px-2 py-2 text-xs"
                      placeholder="Isi Jam Kerja Awal"
                    />
                  </div>

                  <div class="flex flex-col">
                    <label class="block text-xs font-semibold">Jam Mesin Awal</label>
                    <Input
                      v-model="form.jam_mesin_awal"
                      type="text"
                      class="w-full border rounded-md px-2 py-2 text-xs"
                      placeholder="Isi Jam Mesin Awal"
                    />
                  </div>

                  <div class="flex flex-col">
                    <label class="block text-xs font-semibold">Jam Generator Awal</label>
                    <Input
                      v-model="form.jam_generator_awal"
                      type="text"
                      class="w-full border rounded-md px-2 py-2 text-xs"
                      placeholder="Isi Jam Generator Awal"
                    />
                  </div>

                  <div class="flex flex-col">
                    <label class="block text-xs font-semibold">Counter Tamping Awal</label>
                    <Input
                      v-model="form.counter_tamping_awal"
                      type="number"
                      class="w-full border rounded-md px-2 py-2 text-xs"
                      placeholder="Isi Counter Tamping Awal"
                    />
                  </div>

                  <div class="flex flex-col">
                    <label class="block text-xs font-semibold">Oddometer Awal</label>
                    <Input
                      v-model="form.oddometer_awal"
                      type="number"
                      class="w-full border rounded-md px-2 py-2 text-xs"
                      placeholder="Isi Oddometer Awal"
                    />
                  </div>

                  <div class="flex flex-col">
                    <label class="block text-xs font-semibold">HSD Awal Kerja</label>
                    <Input
                      v-model="form.hsd_awal_kerja"
                      type="number"
                      class="w-full border rounded-md px-2 py-2 text-xs"
                      placeholder="Isi HSD Awal Kerja"
                    />
                  </div>

                  <div class="flex flex-col">
                    <label for="operator_by1" class="block text-xs font-semibold">
                      {{ __('Operator 1') }}
                    </label>
                    
                    <Select
                      v-model="form.operator_by1"
                      :options="users.filter(user => user.id !== 1 && user.id !== 3).map(user => ({
                        label: `[${user.username}] ${user.name.toUpperCase()}`,
                        value: user.id,
                      }))"
                      :searchable="true"
                      placeholder="Pilih Operator 1"
                      style="font-size: 0.7rem;"
                    >
                      <template #option="{ option }">
                        <span class="text-xs antialiased">
                            {{ option.label }}
                        </span>
                      </template>
                    </Select>
                    <InputError :error="form.errors.operator_by1" />
                  </div>

                  <div class="flex flex-col">
                    <label for="operator_by2" class="block text-xs font-semibold">
                      {{ __('Operator 2') }}
                    </label>
                    
                    <Select
                      v-model="form.operator_by2"
                      :options="users.filter(user => user.id !== 1 && user.id !== 3).map(user => ({
                        label: `[${user.username}] ${user.name.toUpperCase()}`,
                        value: user.id,
                      }))"
                      :searchable="true"
                      placeholder="Pilih Operator 2"
                      style="font-size: 0.7rem;"
                    >
                      <template #option="{ option }">
                        <span class="text-xs antialiased">
                            {{ option.label }}
                        </span>
                      </template>
                    </Select>
                    <InputError :error="form.errors.operator_by2" />
                  </div>

                  <div class="flex flex-col">
                    <label for="operator_by3" class="block text-xs font-semibold">
                      {{ __('Operator 3') }}
                    </label>
                    
                    <Select
                      v-model="form.operator_by3"
                      :options="users.filter(user => user.id !== 1 && user.id !== 3).map(user => ({
                        label: `[${user.username}] ${user.name.toUpperCase()}`,
                        value: user.id,
                      }))"
                      :searchable="true"
                      placeholder="Pilih Operator 3"
                      style="font-size: 0.7rem;"
                    >
                      <template #option="{ option }">
                        <span class="text-xs antialiased">
                            {{ option.label }}
                        </span>
                      </template>
                    </Select>
                    <InputError :error="form.errors.operator_by3" />
                  </div>

                  <!-- <div class="flex flex-col">
                    <label for="approved_by" class="block text-xs font-semibold">
                      {{ __('Pengawal 1') }}
                    </label>
                    
                    <Select
                      v-model="form.approved_by"
                      :options="users.filter(user => user.id !== 1 && user.id !== 3).map(user => ({
                        label: `[${user.username}] ${user.name.toUpperCase()}`,
                        value: user.id,
                      }))"
                      :searchable="true"
                      placeholder="Pilih Pengawal 1"
                      style="font-size: 0.7rem;"
                    >
                      <template #option="{ option }">
                        <span class="text-xs antialiased">
                            {{ option.label }}
                        </span>
                      </template>
                    </Select>
                    <InputError :error="form.errors.approved_by" />
                  </div>

                  <div class="flex flex-col">
                    <label for="approved_by1" class="block text-xs font-semibold">
                      {{ __('Pengawal 2') }}
                    </label>
                    
                    <Select
                      v-model="form.approved_by1"
                      :options="users.filter(user => user.id !== 1 && user.id !== 3).map(user => ({
                        label: `[${user.username}] ${user.name.toUpperCase()}`,
                        value: user.id,
                      }))"
                      :searchable="true"
                      placeholder="Pilih Pengawal 2"
                      style="font-size: 0.7rem;"
                    >
                      <template #option="{ option }">
                        <span class="text-xs antialiased">
                            {{ option.label }}
                        </span>
                      </template>
                    </Select>
                    <InputError :error="form.errors.approved_by1" />
                  </div>                  -->                  

                  <div class="flex flex-col">
                    <label class="block text-xs font-semibold">NIPP Pengawal 1</label>
                    <Input
                      v-model="form.nipp"
                      type="number"
                      class="w-full border rounded-md px-2 py-2 text-xs"
                      placeholder="Isi NIPP Pengawal 1"
                    />
                  </div>
                  
                  <div class="flex flex-col">
                    <label class="block text-xs font-semibold">Nama Pengawal 1</label>
                    <Input
                      v-model="form.nama_pengawal"
                      type="text"
                      class="w-full border rounded-md px-2 py-2 text-xs"
                      placeholder="Isi Nama Pengawal 1"
                    />
                  </div>       

                  <div class="flex flex-col">
                    <label class="block text-xs font-semibold">NIPP Pengawal 2</label>
                    <Input
                      v-model="form.nipp1"
                      type="number"
                      class="w-full border rounded-md px-2 py-2 text-xs"
                      placeholder="Isi NIPP Pengawal 2"
                    />
                  </div>
                  
                  <div class="flex flex-col">
                    <label class="block text-xs font-semibold">Nama Pengawal 2</label>
                    <Input
                      v-model="form.nama_pengawal1"
                      type="text"
                      class="w-full border rounded-md px-2 py-2 text-xs"
                      placeholder="Isi Nama Pengawal 2"
                    />
                  </div>

                </div>

                <div class="flex items-center justify-end px-4 py-1 rounded space-x-2 p-2 pr-[1.100rem]">
                  <Link
                    :href="route('working-reports.index')"
                    class="bg-gray-600 text-white text-xs px-3 py-1 rounded-md hover:bg-gray-700 mt-10 flex items-center space-x-1"
                  >
                    <Icon name="edit" />
                    <p class="font-bold text-xs">Kembali</p>
                  </Link>

                  <Button
                    type="submit"
                    class="bg-green-600 text-white text-xs px-3 py-1 rounded-md hover:bg-green-700 mt-10"
                  >
                  <p class="font-bold text-xs">Simpan</p> 
                  </Button>
                </div>
              </div>

              <div v-if="showForm1 && form.mode === 'working'" class="space-y-1 p-1 rounded-lg ">
    
                  <p class="font-bold text-sm text-gray-800">
                      1. Data Opname Resor Jalan Rel (Awal)
                  </p>

                  <div class="space-y-1">
                      
                      <div class="p-2 border border-gray-200 rounded-lg shadow-sm hover:border-sky-500 transition duration-150">
                          <div class="flex flex-row items-start justify-between text-sm"> 
                              
                              <label for="mg1_awal" class="flex-1 text-xs text-black font-semi-bold pr-2">
                                  a. MG 1 (Lurusan)
                              </label>

                              <div class="flex space-x-4 flex-shrink-0 text-xs">
                                  
                                  <label 
                                      class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                      :class="{'text-sky-600 font-semibold': form1.ada === '1', 'text-gray-500': form1.ada !== '1'}"
                                  >
                                      <input 
                                          type="checkbox" 
                                          v-model="form1.ada" 
                                          true-value="1" 
                                          false-value="0"
                                          class="rounded text-sky-600 focus:ring-sky-500 w-3 h-3"
                                      >
                                      <span>Ada</span>
                                  </label>
                                  
                                  <label 
                                      class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                      :class="{'text-red-600 font-semibold': form1.tidak === '1', 'text-gray-500': form1.tidak !== '1'}"
                                  >
                                      <input 
                                          type="checkbox" 
                                          v-model="form1.tidak" 
                                          true-value="1" 
                                          false-value="0"
                                          class="rounded text-red-600 focus:ring-red-500 w-3 h-3"
                                      >
                                      <span>Tidak ada</span>
                                  </label>
                              </div>
                          </div>

                          <div class="mt-2 px-3">
                            <AttachmentInline 
                              :model="mglurusanawal ?? {}" 
                              type="MgLurusanAwal" 
                              :redaction="`Lampiran (MG 1 Lurusan)`"
                              :attachments="mglurusanawal_attachments" 
                            />
                          </div>
                      </div>

                      <div class="p-2 border border-gray-200 rounded-lg shadow-sm hover:border-sky-500 transition duration-150">
                          <div class="flex flex-row items-start justify-between text-sm">
                              
                              <label for="mg2_awal" class="flex-1 text-xs text-black font-semi-bold pr-2">
                                  b. MG 2 (Lengkungan)
                              </label>

                              <div class="flex space-x-4 flex-shrink-0 text-xs">
                                  
                                  <label 
                                      class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                      :class="{'text-sky-600 font-semibold': form2.ada === '1', 'text-gray-500': form2.ada !== '1'}"
                                  >
                                      <input 
                                          type="checkbox" 
                                          v-model="form2.ada" 
                                          true-value="1" 
                                          false-value="0"
                                          class="rounded text-sky-600 focus:ring-sky-500 w-3 h-3"
                                      >
                                      <span>Ada</span>
                                  </label>
                                  
                                  <label 
                                      class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                      :class="{'text-red-600 font-semibold': form2.tidak === '1', 'text-gray-500': form2.tidak !== '1'}"
                                  >
                                      <input 
                                          type="checkbox" 
                                          v-model="form2.tidak" 
                                          true-value="1" 
                                          false-value="0"
                                          class="rounded text-red-600 focus:ring-red-500 w-3 h-3"
                                      >
                                      <span>Tidak ada</span>
                                  </label>
                              </div>
                          </div>

                          <div class="mt-2 px-3">
                            <AttachmentInline 
                              :model="mglengkunganawal ?? {}" 
                              type="MgLengkunganAwal" 
                              :redaction="`Lampiran (MG 2 Lengkungan)`"
                              :attachments="mglengkunganawal_attachments" 
                            />
                          </div>
                      </div>

                      <div class="p-2 border border-gray-200 rounded-lg shadow-sm hover:border-sky-500 transition duration-150">
                          <div class="flex flex-row items-start justify-between text-sm">
                              
                              <label for="mg3_awal" class="flex-1 text-xs text-black font-semi-bold pr-2">
                                  c. MG 3 (Wesel)
                              </label>

                              <div class="flex space-x-4 flex-shrink-0 text-xs">
                                  
                                  <label 
                                      class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                      :class="{'text-sky-600 font-semibold': form3.ada === '1', 'text-gray-500': form3.ada !== '1'}"
                                  >
                                      <input 
                                          type="checkbox" 
                                          v-model="form3.ada" 
                                          true-value="1" 
                                          false-value="0"
                                          class="rounded text-sky-600 focus:ring-sky-500 w-3 h-3"
                                      >
                                      <span>Ada</span>
                                  </label>
                                  
                                  <label 
                                      class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                      :class="{'text-red-600 font-semibold': form3.tidak === '1', 'text-gray-500': form3.tidak !== '1'}"
                                  >
                                      <input 
                                          type="checkbox" 
                                          v-model="form3.tidak" 
                                          true-value="1" 
                                          false-value="0"
                                          class="rounded text-red-600 focus:ring-red-500 w-3 h-3"
                                      >
                                      <span>Tidak ada</span>
                                  </label>
                              </div>
                          </div>

                          <div class="mt-2 px-3">
                            <AttachmentInline 
                              :model="mgweselawal ?? {}" 
                              type="MgWeselAwal" 
                              :redaction="`Lampiran (MG 3 Wesel)`"
                              :attachments="mgweselawal_attachments" 
                            />
                          </div>
                      </div>

                  </div>

                  <p class="font-bold text-sm text-gray-800">
                      2. Data Pemeriksaan Silang
                  </p>

                  <div class="space-y-1">
                      
                      <div class="p-2 border border-gray-200 rounded-lg shadow-sm hover:border-sky-500 transition duration-150">
                          <div class="flex flex-row items-start justify-between text-sm"> 
                              
                              <label for="mg1_awal" class="flex-1 text-xs text-black font-semi-bold pr-2">
                                  a. KPJR
                              </label>

                              <div class="flex space-x-4 flex-shrink-0 text-xs">
                                  
                                  <label 
                                      class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                      :class="{'text-sky-600 font-semibold': form4.ada === '1', 'text-gray-500': form4.ada !== '1'}"
                                  >
                                      <input 
                                          type="checkbox" 
                                          v-model="form4.ada" 
                                          true-value="1" 
                                          false-value="0"
                                          class="rounded text-sky-600 focus:ring-sky-500 w-3 h-3"
                                      >
                                      <span>Ada</span>
                                  </label>
                                  
                                  <label 
                                      class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                      :class="{'text-red-600 font-semibold': form4.tidak === '1', 'text-gray-500': form4.tidak !== '1'}"
                                  >
                                      <input 
                                          type="checkbox" 
                                          v-model="form4.tidak" 
                                          true-value="1" 
                                          false-value="0"
                                          class="rounded text-red-600 focus:ring-red-500 w-3 h-3"
                                      >
                                      <span>Tidak ada</span>
                                  </label>
                              </div>
                          </div>

                          <div class="mt-2 px-3">
                            <AttachmentInline 
                              :model="pemeriksaansilangkpjr ?? {}" 
                              type="PemeriksaanSilangKpjr" 
                              :redaction="`Lampiran (Pemeriksaan Silang KPJR)`"
                              :attachments="pemeriksaansilangkpjr_attachments" 
                            />
                          </div>
                      </div>

                      <div class="p-2 border border-gray-200 rounded-lg shadow-sm hover:border-sky-500 transition duration-150">
                          <div class="flex flex-row items-start justify-between text-sm">
                              
                              <label for="mg2_awal" class="flex-1 text-xs text-black font-semi-bold pr-2">
                                  b. Lahan
                              </label>

                              <div class="flex space-x-4 flex-shrink-0 text-xs">
                                  
                                  <label 
                                      class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                      :class="{'text-sky-600 font-semibold': form5.ada === '1', 'text-gray-500': form5.ada !== '1'}"
                                  >
                                      <input 
                                          type="checkbox" 
                                          v-model="form5.ada" 
                                          true-value="1" 
                                          false-value="0"
                                          class="rounded text-sky-600 focus:ring-sky-500 w-3 h-3"
                                      >
                                      <span>Ada</span>
                                  </label>
                                  
                                  <label 
                                      class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                      :class="{'text-red-600 font-semibold': form5.tidak === '1', 'text-gray-500': form5.tidak !== '1'}"
                                  >
                                      <input 
                                          type="checkbox" 
                                          v-model="form5.tidak" 
                                          true-value="1" 
                                          false-value="0"
                                          class="rounded text-red-600 focus:ring-red-500 w-3 h-3"
                                      >
                                      <span>Tidak ada</span>
                                  </label>
                              </div>
                          </div>

                          <div class="mt-2 px-3">
                            <AttachmentInline 
                              :model="pemeriksaansilanglahan ?? {}" 
                              type="PemeriksaanSilangLahan" 
                              :redaction="`Lampiran (Pemeriksaan Silang Lahan)`"
                              :attachments="pemeriksaansilanglahan_attachments" 
                            />
                          </div>
                      </div>

                  </div>

                  <p class="font-bold text-sm text-gray-800">
                      3. Data Perekaman Awal
                  </p>

                  <div class="space-y-1">
                      
                      <div class="p-2 border border-gray-200 rounded-lg shadow-sm hover:border-sky-500 transition duration-150">
                          <div class="flex flex-row items-start justify-between text-sm"> 
                              
                              <label for="mg1_awal" class="flex-1 text-xs text-black font-semi-bold pr-2">
                                  a. Data Perekaman Awal
                              </label>

                              <div class="flex space-x-4 flex-shrink-0 text-xs">
                                  
                                  <label 
                                      class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                      :class="{'text-sky-600 font-semibold': form6.ada === '1', 'text-gray-500': form6.ada !== '1'}"
                                  >
                                      <input 
                                          type="checkbox" 
                                          v-model="form6.ada" 
                                          true-value="1" 
                                          false-value="0"
                                          class="rounded text-sky-600 focus:ring-sky-500 w-3 h-3"
                                      >
                                      <span>Ada</span>
                                  </label>
                                  
                                  <label 
                                      class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                      :class="{'text-red-600 font-semibold': form6.tidak === '1', 'text-gray-500': form6.tidak !== '1'}"
                                  >
                                      <input 
                                          type="checkbox" 
                                          v-model="form6.tidak" 
                                          true-value="1" 
                                          false-value="0"
                                          class="rounded text-red-600 focus:ring-red-500 w-3 h-3"
                                      >
                                      <span>Tidak ada</span>
                                  </label>
                              </div>
                          </div>

                          <div class="mt-2 px-3">
                            <AttachmentInline 
                              :model="perekamanawal ?? {}" 
                              type="PerekamanAwal" 
                              :redaction="`Lampiran (Perekaman Awal)`"
                              :attachments="perekamanawal_attachments" 
                            />
                          </div>
                      </div>
                  </div>
                  
                  <div class="flex items-center justify-end px-4 py-1 rounded space-x-2 p-2 pr-[1.100rem]">
                    <Button 
                      type="button"
                      @click="submitForms" 
                      class="bg-green-600 text-white text-xs px-3 py-1 rounded-md hover:bg-green-700 mt-10"
                    >
                        Simpan
                    </Button>
                  </div>
              </div>
              
            </form>
          </template>
        </Card>
    </div>
  </DashboardLayout>
</template>
