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
import ButtonGreen from '@/Components/Button/Green.vue'
import Button from '@/Components/Button.vue'
import Input from '@/Components/Input.vue'
import InputError from '@/Components/InputError.vue'

const { report, joblist, users, usered } = defineProps({
  report: Object,
  joblist: Object,
  users: Array,
  usered: Array,
})

const { user } = usePage().props.value

const form = useForm({
  id: null,
  report_id: report.id,
  created_by_id: '',
  job_id: '',
  catatan: '',
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
  return form.post(route('joblist.store'), {
    onSuccess: () => close(),
    onError: () => show(),
  })
}

const edit = (joblist)=> {
  form.id = joblist.id
  form.bagian_sistem = joblist.report.bagian_sistem
  form.kendala = joblist.report.kendala
  form.dampak = joblist.report.dampak
  form.tanggal = joblist.report.tanggal
  form.catatan = joblist.catatan
  form.status = joblist.report.status
  form.bagian_pelapor = joblist.report.bagian_pelapor
  show();
}

const update = () => {
  return form.patch(route('joblist.update', form.id), {
    onSuccess: () => close(),
    onError: () => show(),
  })
}

const destroy = async joblist => {
  const response = await Swal.fire({
    title: __('Apakah Anda Yakin') + '?',
    text: __('Anda tidak dapat mengembalikannya setelah dihapus'),
    icon: 'question',
    showCancelButton: true,
    showCloseButton: true,
  })

  if (response.isConfirmed) {
    return form.delete(route('joblist.destroy', joblist.id), {
      onFinish: close,
    })
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
        <input id="swal-input-catatan" class="swal2-input" placeholder="Isi Link"></input>
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

  useForm({ tanggal, catatan }).post(route('report.done', { report: report.id }));
};

const submit = () => form.id ? update() : store()

const esc = e => e.key === 'Escape' && close()
onMounted(() => window.addEventListener('keydown', esc))
onUnmounted(() => window.removeEventListener('keydown', esc))

</script>

<style src="@vueform/multiselect/themes/default.css"></style>
<style src="@/multiselect.css"></style>

<template>
    <DashboardLayout :title="__('List Kerjaan')">
        <div
        class="transition-all duration-300"
        :class="{
            'pl-1 md:pl-64': open,
        }"
        >
        <main class="p-0 py-0 mb-[1.25rem] ml-[1.25rem] mt-[1.25rem]">
            <h2 class="font-bold text-2xl">List Kerjaan</h2>
            <a type="button" href="/" class="text-sm text-gray-500 font-semibold hover:text-sky-600 focus:text-sky-600">Home</a>  
            <slot />
        </main>
        </div>
        <Card class="bg-white pt-[1.875rem] pb-[2.5rem] shadow-lg border border-solid border-slate-200" style="border-radius: 0.625rem;">
        <template #header>
            
        </template>

        <template #body>
            <div class="flex flex-col space-y-2">
            <Builder
                v-if="render"
                :url="route('joblist.paginate')"
                ref="table"
            >
                <template #thead="table">
                <tr class="bg-gray-200 border-gray-300">
                    <Th
                    :table="table"
                    :sort="false"
                    name="id"
                    class="border px-3 py-2 text-center capitalize font-bold "
                    >
                    {{ __('no') }}
                    </Th>

                    <Th :table="table" :sort="true" class="border px-3 py-2 text-center whitespace-nowrap">
                       {{ __('kode') }}
                    </Th>

                    <Th
                    :table="table"
                    :sort="true"
                    name="report_id"
                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold "
                    >
                    {{ __('bagian sistem') }}
                    </Th>

                    <Th
                    :table="table"
                    :sort="true"
                    name="catatan"
                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                    >
                    {{ __('kendala') }}
                    </Th>

                    <Th
                    :table="table"
                    :sort="true"
                    name="catatan"
                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                    >
                    {{ __('dampak') }}
                    </Th>

                    <Th
                    :table="table"
                    :sort="true"
                    name="tanggal"
                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold "
                    >
                    {{ __('tanggal laporan') }}
                    </Th>

                    <Th
                    :table="table"
                    :sort="true"
                    name="catatan"
                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                    >
                    {{ __('Keterangan') }}
                    </Th>

                    <Th
                    v-if="hasRole('it')"
                    :table="table"
                    :sort="true"
                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold "
                    >
                    {{ __('pelapor') }}
                    </Th>
                    
                    <Th
                    :table="table"
                    :sort="true"
                    name="status"
                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                    >
                    {{ __('status') }}
                    </Th>

                    <Th
                    :table="table"
                    :sort="true"
                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold "
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
                    
                    <Th :table="table" :sort="true" class="border px-3 py-2 text-center whitespace-nowrap">
                       {{ __('kode') }}
                    </Th>

                    <Th
                    :table="table"
                    :sort="true"
                    name="report_id"
                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold "
                    >
                    {{ __('bagian sistem') }}
                    </Th>

                    <Th
                    :table="table"
                    :sort="true"
                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                    >
                    {{ __('kendala') }}
                    </Th>

                    <Th
                    :table="table"
                    :sort="true"
                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                    >
                    {{ __('dampak') }}
                    </Th>

                    <Th
                    :table="table"
                    :sort="true"
                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                    >
                    {{ __('tanggal laporan') }}
                    </Th>

                    <Th
                    :table="table"
                    :sort="true"
                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                    >
                    {{ __('keterangan') }}
                    </Th>

                    <Th
                    v-if="hasRole('it')"
                    :table="table"
                    :sort="true"
                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                    >
                    {{ __('pelapor') }}
                    </Th>
                     <Th
                    :table="table"
                    :sort="true"
                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                    >
                    {{ __('status') }}
                    </Th>

                    <Th
                    :table="table"
                    :sort="true"
                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold "
                    >
                    {{ __('action') }}
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
                        <p class="lowercase first-letter:capitalize font-semibold">
                            {{ __('Tidak ada data untuk ditampilkan.') }}
                        </p>
                        </td>
                    </tr>
                    </template>

                    <template v-else>
                    <tr
                        v-for="(joblist, i) in data"
                        :key="joblist.id"
                        :class="processing && 'bg-gray-100'"
                        class="h-[4.375rem] transition-all duration-300"
                    >
                        <td class="px-2 py-1 border-b text-center">
                        {{ i + 1 }}
                        </td>

                        <td class="capitalize font-semibold text-center border-b">
                        {{ __(joblist.report.kode) }}
                        </td>

                        <td class="capitalize font-semibold text-center border-b">
                          
                        {{ (joblist.report && joblist.report.bagian_hardware) ? __(joblist.report.bagian_hardware) : (joblist.report.bagian ? __(joblist.report.bagian.nama) : '') }} 
                        {{ (joblist.report && joblist.report.bagian_sistem) ? __(joblist.report.bagian_sistem) : '' }} 
                        {{ (joblist.report && joblist.report.bagian) ? __(joblist.report.bagian.singkatan) : '' }}
                        <!-- {{ __(joblist.report.bagian_sistem) }} -->
                        </td>

                        <td class="capitalize font-semibold text-center border-b">
                        {{ __(joblist.report.kendala) }}
                        </td>

                        <td class="capitalize font-semibold text-center border-b">
                        {{ __(joblist.report.dampak) }}
                        </td>

                        <td class="capitalize font-semibold text-center border-b"> 
                        {{ new Date(joblist.report.tanggal).toLocaleString('id-ID', { dateStyle: 'medium'}) }}
                        </td>

                        <td class="capitalize font-semibold text-center border-b">
                        {{ __(joblist.catatan) }}
                        </td>

                        <!-- <td :class="{
                            'text-green-600': joblist.report.status === 1,
                            'text-orange-600': joblist.report.status === 2,
                            'text-blue-600': joblist.report.status === 3,
                            'text-red-600': joblist.report.status === 4,
                            'text-yellow-600': joblist.report.status === 5,
                            'text-gray-600': joblist.report.status === 0
                            }" class="capitalize font-semibold text-center border">
                            {{ 
                                joblist.report.status === 1 ? 'Terkirim' : 
                                joblist.report.status === 2 ? 'Perbaikan' : 
                                joblist.report.status === 3 ? 'Selesai' : 
                                joblist.report.status === 4 ? 'Pending' : 
                                joblist.report.status === 5 ? 'Diterima' : 
                                joblist.report.status === 0 ? 'Drafting' : 
                                __(joblist.report.status) 
                            }}
                        </td> -->
                        
                        <td 
                            v-if="hasRole('it')"
                            class="font-semibold text-center capitalize border-b">
                            {{ (joblist.report.created_by.name)}} <br>
                            {{ (joblist.bagian_pelapor) }}
                        </td>

                        <td class="border-b pl-[5.25rem]">
                          <div v-if="joblist.report.status === 1" class="bg-sky-100 text-blue-600 text-center capitalize rounded-md font-semibold text-sm p-1 w-[5rem]">
                            Terkirim
                          </div>
                          <div v-else-if="joblist.report.status === 2" class="bg-orange-200 text-center text-orange-700 capitalize rounded-md font-semibold text-sm p-1 w-[5rem]">
                            Perbaikan
                          </div>
                          <div v-else-if="joblist.report.status === 3" class="bg-green-100 text-center text-emerald-600 capitalize rounded-md font-semibold text-sm p-1 w-[5rem]">
                            Selesai
                          </div>
                          <div v-else-if="joblist.report.status === 4" class="bg-red-200 text-center text-rose-700 capitalize rounded-md  font-semibold text-sm p-1 w-[5rem]">
                            Pending
                          </div>
                          <div v-else-if="joblist.report.status === 5" class="bg-stone-100 text-center text-black capitalize font-semibold p-1 rounded-md text-sm w-[5rem]">
                            Diterima
                          </div>
                          <div v-else class="bg-slate-200 text-slate-500 ml-[1.25rem] capitalize flex justify-center text-center rounded-md font-semibold p-1 w-[1.875rem]">
                            {{ __(joblist.report.status) }}
                          </div>
                        </td>

                        <td class="px-2 py-5 text-center pl-[1.25rem] pr-[1.25rem] border-b flex items-center justify-center">
                        
                          <div class="pt-[0.313rem] flex-row space-y-4">
                            <Button 
                                v-if="hasRole(['superuser', 'it']) && joblist.report.status === 5"
                                @click.prevent="done(joblist.report)" 
                                class="bg-green-500 hover:bg-green-800 text-white w-24 flex justify-center items-center text-center rounded-md">
                                <!-- <Icon name="circle-check" /> -->
                                <p class="capitalize">
                                    {{ __('Selesai') }}
                                </p>
                            </Button>

                            <Button class="bg-sky-500 hover:bg-sky-700 w-24 flex justify-center items-center text-center rounded-md">
                            <a :href="route('joblist.detail', joblist.id)" target="_blank" class="flex items-center">
                                <!-- <Icon name="circle-info" class="mr-2" /> -->
                                <p class="capitalize">
                                {{ __('detail') }}
                                </p>
                            </a>
                            </Button>

                        <Button class="bg-green-700 w-24 hover:bg-green-600  flex justify-center items-center text-center rounded-md"	
                       v-if="can('update joblist') && joblist.created_by_id === user.id && joblist.status !== 3"
                       @click.prevent="edit(joblist)">
                       <!-- <Icon name="edit" /> -->
                      <p class="capitalize">
                        {{ __('Assign') }}
                             </p>
                          </Button>
                            
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
        <form @submit.prevent="submit" class="w-10/12 max-w-7xl shadow-xl" >
            <Card class="bg-white">
            <template #header>
                <div class="flex items-center border-b h-[70px] mb-[30px] bg-white justify-end rounded-t-lg p-2 pb-[0.938rem] pt-[0.938rem]">
                <!-- <Close @click.prevent="close" /> --><h2 class="font-bold text-xl w-full ml-10">Assign</h2>
                <button class="pr-5">
                <svg @click.prevent="close" class="w-6 h-6 text-gray-700 hover:text-cyan-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>
                </svg>
                </button>
                </div>
            </template>

            <template #body>
                <div class="flex flex-col space-y-4 p-4 ml-6 mr-6">
                <template v-if="hasRole (['superuser', 'it']) && !regis">                   
             <div class="flex flex-col space-y-2">
                                      <div class="flex items-center space-x-2">
                                          <label for="bagian_sistem" class="w-1/3 capitalize">
                                              {{ __('bagian_sistem') }}
                                          </label>

                                          <Input v-model="form.bagian_sistem"
                                              :placeholder="__('bagian_sistem')" type="text"
                                              required readonly/>
                                      </div>

                                      <InputError :error="form.errors.bagian_sistem" />
                                  </div>


  <div class="flex flex-col space-y-2">
                                        <div class="flex items-center space-x-2">
                                            <label for="kendala" class="w-1/3 capitalize">
                                                {{ __('kendala') }}
                                            </label>

                                            <Input v-model="form.kendala"
                                                :placeholder="__('kendala')" type="text"
                                                required readonly/>
                                        </div>

                                        <InputError :error="form.errors.kendala" />
                                    </div>




                                <div class="flex flex-col space-y-2">
                                      <div class="flex items-center space-x-2">
                                          <label for="dampak" class="w-1/3 capitalize">
                                              {{ __('dampak') }}
                                          </label>

                                          <Input v-model="form.dampak"
                                              :placeholder="__('dampak')" type="text"
                                              required readonly/>
                                      </div>

                                      <InputError :error="form.errors.dampak" />
                                  </div>
                                  <div class="flex flex-col space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <label for="tanggal" class="w-1/3 capitalize">
                                            {{ __('tanggal') }}
                                        </label>

                                        <Input v-model="form.tanggal"
                                            :placeholder="__('tanggal')" type="text"
                                            required readonly/>
                                    </div>

                                    <InputError :error="form.errors.tanggal" />
                                </div>



                                <div class="flex flex-col space-y-2">
                                      <div class="flex items-center space-x-2">
                                          <label for="catatan" class="w-1/3 capitalize">
                                              {{ __('catatan') }}
                                          </label>

                                          <Input v-model="form.catatan"
                                              :placeholder="__('catatan')" type="text"
                                              required readonly/>
                                      </div>

                                      <InputError :error="form.errors.catatan" />
                                  </div>
                                <div class="flex flex-col space-y-2">
                                      <div class="flex items-center space-x-2">
                                          <label for="status" class="w-1/3 capitalize">
                                              {{ __('status') }}
                                          </label>

                                          <Input v-model="form.status"
                                              :placeholder="__('status')" type="text"
                                              required readonly/>
                                      </div>

                                      <InputError :error="form.errors.status" />
                                  </div>
                               
                                <div class="flex flex-col space-y-2">
                                      <div class="flex items-center space-x-2">
                                          <label for="bagian_pelapor" class="w-1/3 capitalize">
                                              {{ __('bagian_pelapor') }}
                                          </label>

                                          <Input v-model="form.bagian_pelapor"
                                              :placeholder="__('bagian_pelapor')" type="text"
                                              required readonly/>
                                      </div>

                                      <InputError :error="form.errors.bagian_pelapor" />
                                  </div>
                  
                   
   

  <div class="flex flex-col space-y-2">
          <div class="flex items-center space-x-2">
              <label for="job_id" class="w-1/3 capitalize">
                  {{ __('Assign Kepada') }}
              </label>
              <select v-model="form.job_id"  type="text"
                              id="job_id" class="w-full rounded-md" required>
                  <option value="" disabled>Assign Kepada</option>
              
                  <option v-for="us in usered" :key="us.id" :value="us.name">{{ us.name }}</option>
              </select>
          </div>
          <InputError :error="form.errors.job_id" />
      </div>



                </template>
                </div>
            </template>

            <template #footer>
                <div class="flex items-center justify-end space-x-2 bg-white px-8 py-1 h-[100px] mr-[10px]">
                <Button type="submit" v-if="! regis" 
                     class="rounded-md text-center bg-sky-500 hover:bg-sky-600 flex items-center justify-center w-[130px] h-[40px]">
                    <p class="uppercase font-semibold">
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
    </DashboardLayout>
</template>