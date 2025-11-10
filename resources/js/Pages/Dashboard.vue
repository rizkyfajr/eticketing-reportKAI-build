<script setup>
import { ref,nextTick } from 'vue'
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import Welcome from '@/Jetstream/Welcome.vue';
import { usePage } from '@inertiajs/inertia-vue3';
import Modal from '@/Components/Modal.vue'
import Close from '@/Components/Button/Close.vue'
import Card from '@/Components/Card.vue'
import Builder from '@/Components/DataTable/Builder.vue'
import Th from '@/Components/DataTable/Th.vue'
import Button from '@/Components/Button.vue'
import BtnAttachment from '@/Components/Button/Attachment.vue'

const { user } = usePage().props.value


const { users, report, count_drafting, count_perbaikan,count_terkirim,count_feedback, count_pending, count_selesai,count_joblist,count_datalaporin,count_assign,data_laporin,count } = defineProps({
  users: Array,
  report: Object,
  count_drafting: Number,
  count_terkirim: Number,
  count_perbaikan: Number,
  count_feedback: Number,
  count_pending: Number,
  count_selesai: Number,
  count_joblist: Number,
  count_datalaporin: Number,
  count_assign: Number,
  data_laporin:Object,
  count : Number
}) // Access total count from props

const isDataFeedbackShow = ref(false)
const clickShowDataFeedback = () => isDataFeedbackShow.value = true

const isDataListKerjaanShow = ref(false)
const clickShowDataListKerjaan = () => isDataListKerjaanShow.value = true

const isDataAssignShow = ref(false)
const clickShowDataAssign = () => isDataAssignShow.value = true

const isLaporinShow = ref(false)
const clickShowLaporin = () => isLaporinShow.value = true

const isDataTerkirimShow = ref(false)
const clickShowDataTerkirim = () => isDataTerkirimShow.value = true

const isDataPerbaikanShow = ref(false)
const clickShowDataPerbaikan = () => isDataPerbaikanShow.value = true

const isDataPendingShow = ref(false)
const clickShowDataPending = () => isDataPendingShow.value = true

const isDataSelesaiShow = ref(false)
const clickShowDataSelesai = () => isDataSelesaiShow.value = true

const close = () => {
  nextTick(() => {
    nextTick(() => isDataFeedbackShow.value = false)
    nextTick(() => isDataListKerjaanShow.value = false)
    nextTick(() => isDataAssignShow.value = false)
    nextTick(() => isLaporinShow.value = false)
    nextTick(() => isDataTerkirimShow.value = false)
    nextTick(() => isDataPerbaikanShow.value = false)
    nextTick(() => isDataPendingShow.value = false)
    nextTick(() => isDataSelesaiShow.value = false)
  })
}


</script>

<template>
    <DashboardLayout title="Dashboard">
        <!-- Header Dashboard  -->
        <main class="p-0 py-0 mb-[1.25rem] ml-[1.25rem] mt-[1.25rem]">
            <h2 class="font-bold text-2xl">Dashboard</h2>
            <h3 class="text-base font-semibold pl-0 leading-6 text-gray-500">
            Selamat Datang, {{user.positions?.position}} - {{ user.name }} - ({{ user.divisions?.division_name }})
            </h3>        
        </main>
       <!-- Content  -->
       <!-- component -->
<div>

  <!-- component -->
<div class="min-h-screen bg-gray-50/50">
  <div class="p-4 ">
    <div class="mt-11">
      <!-- Card Components -->
      <div class="mb-12 grid gap-y-10 gap-x-6 md:grid-cols-2 xl:grid-cols-3">
        <!-- Card Feedback -->
        <div class="relative flex flex-col bg-clip-border rounded-xl bg-white text-gray-700 shadow-lg">
          <div class="bg-clip-border mx-4 rounded-xl overflow-hidden bg-gradient-to-tr from-blue-600 to-blue-400 text-white shadow-blue-500/40 shadow-lg absolute -mt-4 grid h-16 w-16 place-items-center">
            <img src="../../../public/assets/receive-message.png" class="w-8" alt="">
            <!-- <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" class="w-6 h-6 text-white">
              <path d="M12 7.5a2.25 2.25 0 100 4.5 2.25 2.25 0 000-4.5z"></path>
              <path fill-rule="evenodd" d="M1.5 4.875C1.5 3.839 2.34 3 3.375 3h17.25c1.035 0 1.875.84 1.875 1.875v9.75c0 1.036-.84 1.875-1.875 1.875H3.375A1.875 1.875 0 011.5 14.625v-9.75zM8.25 9.75a3.75 3.75 0 117.5 0 3.75 3.75 0 01-7.5 0zM18.75 9a.75.75 0 00-.75.75v.008c0 .414.336.75.75.75h.008a.75.75 0 00.75-.75V9.75a.75.75 0 00-.75-.75h-.008zM4.5 9.75A.75.75 0 015.25 9h.008a.75.75 0 01.75.75v.008a.75.75 0 01-.75.75H5.25a.75.75 0 01-.75-.75V9.75z" clip-rule="evenodd"></path>
              <path d="M2.25 18a.75.75 0 000 1.5c5.4 0 10.63.722 15.6 2.075 1.19.324 2.4-.558 2.4-1.82V18.75a.75.75 0 00-.75-.75H2.25z"></path>
            </svg> -->
          </div>
          <div class="p-4 text-right">
            <p class="block antialiased font-sans text-md leading-normal font-normal text-blue-gray-600 ">MTT CSM</p>
            <!-- Date Feedback -->
            <div class=" w-full flex justify-end pt-[20px]"> <h4 class="text-xl rounded-md font-bold "><span class=""> {{count_feedback}}</span> Data</h4></div>
          </div>
         
          <div class="border-t border-blue-gray-50 p-4">
            <p class="block antialiased font-sans text-base leading-relaxed font-normal text-blue-gray-600">
             <!-- <div class="w-full flex items-center justify-end"> <button  @click.prevent="clickShowDataFeedback()" class="middle none center w-20  mr-3 rounded-md bg-green-600 py-3 font-sans text-xs font-bold capitalize text-white shadow-md shadow-green-300 transition-all hover:shadow-lg hover:shadow-green-700 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none" data-ripple-light="true" >Button</button></div> -->
            </p>
          </div>
        </div >
        <!-- Date List Kerjaan -->
        <div v-if="hasRole(['admin', 'it'])" class="relative flex flex-col bg-clip-border rounded-xl bg-white text-gray-700 shadow-lg">
          <div class="bg-clip-border mx-4 rounded-xl overflow-hidden bg-gradient-to-tr bg-orange-600 text-white shadow-lg absolute -mt-4 grid h-16 w-16 place-items-center">
            <img src="../../../public/assets/list.png" class="w-8" alt="">
            <!-- <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" class="w-6 h-6 text-white">
              <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z" clip-rule="evenodd"></path>
            </svg> -->
          </div>
          <div class="p-4 text-right">
            <p class="block antialiased font-sans text-md leading-normal font-normal text-blue-gray-600">MTT UNIMAT</p>
            <div class=" w-full flex justify-end pt-[20px]"> <h4 class="text-xl rounded-md font-bold ">
              <span>{{ count_joblist }}</span> Data </h4></div>
          </div>
          <div class="border-t border-blue-gray-50 p-4">
            <!-- <div class="w-full flex items-center justify-end"> <button @click.prevent="clickShowDataListKerjaan()" class="middle none center w-20  mr-3 rounded-md bg-green-600 py-3 font-sans text-xs font-bold capitalize text-white shadow-md shadow-green-300 transition-all hover:shadow-lg hover:shadow-green-700 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none" data-ripple-light="true" >Button</button></div> -->
          </div>
        </div>
        <!-- Assign -->
        <div v-if="hasRole(['admin', 'it'])" class="relative flex flex-col bg-clip-border rounded-xl bg-white text-gray-700 shadow-lg">
          <div class="bg-clip-border mx-4 rounded-xl overflow-hidden bg-gradient-to-tr bg-red-500 to-red-300 text-white shadow-lg absolute -mt-4 grid h-16 w-16 place-items-center">
            <img src="../../../public/assets/check.png" class="w-9" alt="">
          </div>
          <div class="p-4 text-right">
            <p class="block antialiased font-sans text-md leading-normal font-normal text-blue-gray-600">PBR 400 URS</p>
            <div class=" w-full flex justify-end pt-[20px]"> <h4 class="text-xl rounded-md font-bold ">{{count_assign}} Data</h4></div>
          </div>
          <div class="border-t border-blue-gray-50 p-4">
            <!-- <div class="w-full flex items-center justify-end"> <button @click.prevent="clickShowDataAssign()" class="middle none center w-20  mr-3 rounded-md bg-green-600 py-3 font-sans text-xs font-bold capitalize text-white shadow-md shadow-green-300 transition-all hover:shadow-lg hover:shadow-green-700 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none" data-ripple-light="true" >Button</button></div> -->
          </div>
        </div>
      </div>
      
      <!-- <div class="w-full flex justify-center">
      <div class="bg-white lg:w-[90%] w-full h-full shadow-lg rounded-lg">
    <div class="w-full h-64 text-lg text-white flex pl-[70px] items-start justify-center rounded-t-lg bg-gradient-to-r from-green-400 to-emerald-800 text-3xl font-semibold flex-col">Laporin Notification
    <div class="text-sm pt-3 font-semibold"> Kamu memilik <span class="text-xl font-bold">5</span>  Di laporin</div>
    </div>
    <div class="flex flex-wrap">
      <div class="w-full flex flex-col lg:flex-row justify-center space-y-14 lg:space-y-0 lg:space-x-12 pb-[50px] px-8 lg:px-0">
        <div class="relative flex flex-col w-full lg:w-[30%] h-48 bg-clip-border rounded-xl bg-white text-gray-700 shadow-lg top-[-40px]">
          <div class="bg-clip-border mx-4 rounded-xl overflow-hidden bg-gradient-to-tr from-blue-600 to-blue-400 text-white shadow-blue-500/40 shadow-lg absolute -mt-4 grid h-16 w-16 place-items-center">
            <img src="../../../public/assets/report.png" class="w-8" alt="">
          </div>
          <div class="p-4 text-right">
            <p class="block antialiased font-sans text-md leading-normal font-normal text-blue-gray-600">Data Laporin</p>
            <h4 class="block antialiased tracking-normal font-sans text-2xl font-semibold leading-snug text-blue-gray-900 pt-[20px]"><span>{{ count_datalaporin }}</span> Data</h4>
          </div>
          <div class="border-t border-blue-gray-50 p-4">
            <div class="w-full flex items-center justify-end"> 
              <button @click.prevent="clickShowLaporin()"  class="middle none center w-20  mr-3 rounded-md bg-green-600 py-3 font-sans text-xs font-bold capitalize text-white shadow-md shadow-green-300 transition-all hover:shadow-lg hover:shadow-green-700 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none" data-ripple-light="true" >Button</button>
            </div>
          </div>
        </div>
        
        <div class="relative flex flex-col w-full lg:w-[30%] h-48 bg-clip-border rounded-xl bg-white text-gray-700 shadow-lg top-[-40px]">
          <div class="bg-clip-border mx-4 rounded-xl overflow-hidden bg-gradient-to-tr from-lime-600 to-lime-400 text-white shadow-lime-500/40 shadow-lg absolute -mt-4 grid h-16 w-16 place-items-center">
            <img src="../../../public/assets/send.png" class="w-9" alt="">
          </div>
          <div class="p-4 text-right">
            <p class="block antialiased font-sans text-md leading-normal font-normal text-blue-gray-600">Laporan Terkirim</p>
            <h4 class="block antialiased tracking-normal font-sans text-2xl font-semibold leading-snug text-blue-gray-900 pt-[20px]"><span>{{count_terkirim}}</span> Data</h4>
          </div>
          <div class="border-t border-blue-gray-50 p-4">
            <div class="w-full flex items-center justify-end"> <button @click.prevent="clickShowDataTerkirim()" class="middle none center w-20  mr-3 rounded-md bg-green-600 py-3 font-sans text-xs font-bold capitalize text-white shadow-md shadow-green-300 transition-all hover:shadow-lg hover:shadow-green-700 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none" data-ripple-light="true" >Button</button></div>
          </div>
        </div>
      </div>
      
      <div class="w-full flex flex-col lg:flex-row justify-center space-y-14 lg:space-y-0 lg:space-x-12 pb-[50px] px-8 lg:px-0">
        <div class="relative flex flex-col w-full lg:w-[30%] h-48 bg-clip-border rounded-xl bg-white text-gray-700 shadow-lg ">
          <div class="bg-clip-border mx-4 rounded-xl overflow-hidden bg-gradient-to-tr from-orange-700 to-orange-400 text-white shadow-orange-500/40 shadow-lg absolute -mt-4 grid h-16 w-16 place-items-center">
            <img src="../../../public/assets/software-application.png" class="w-9" alt="">
          </div>
          <div class="p-4 text-right">
            <p class="block antialiased font-sans text-md leading-normal font-normal text-blue-gray-600">Laporan Perbaikan</p>
            <h4 class="block antialiased tracking-normal font-sans text-2xl font-semibold leading-snug text-blue-gray-900 pt-[20px]"><span>{{ count_perbaikan }}</span> Data</h4>
          </div>
          <div class="border-t border-blue-gray-50 p-4">
            <div class="w-full flex items-center justify-end"> <button @click.prevent="clickShowDataPerbaikan()" class="middle none center w-20  mr-3 rounded-md bg-green-600 py-3 font-sans text-xs font-bold capitalize text-white shadow-md shadow-green-300 transition-all hover:shadow-lg hover:shadow-green-700 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none" data-ripple-light="true" >Button</button></div>
          </div>
        </div>
        
        <div class="relative flex flex-col w-full lg:w-[30%] h-48 bg-clip-border rounded-xl bg-white text-gray-700 shadow-lg ">
          <div class="bg-clip-border mx-4 rounded-xl overflow-hidden bg-gradient-to-tr from-red-600 to-red-400 text-white shadow-red--500/40 shadow-lg absolute -mt-4 grid h-16 w-16 place-items-center">
            <img src="../../../public/assets/mail.png" class="w-9" alt="">
          </div>
          <div class="p-4 text-right">
            <p class="block antialiased font-sans text-md leading-normal font-normal text-blue-gray-600">Laporan Pending</p>
            <h4 class="block antialiased tracking-normal font-sans text-2xl font-semibold leading-snug text-blue-gray-900 pt-[20px]"><span>{{ count_pending }}</span> Data</h4>
          </div>
          <div class="border-t border-blue-gray-50 p-4">
            <div class="w-full flex items-center justify-end"> <button @click.prevent="clickShowDataPending()" class="middle none center w-20  mr-3 rounded-md bg-green-600 py-3 font-sans text-xs font-bold capitalize text-white shadow-md shadow-green-300 transition-all hover:shadow-lg hover:shadow-green-700 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none" data-ripple-light="true" >Button</button></div>
          </div>
        </div>
      </div>

      
      <div class="w-full flex flex-col lg:flex-row justify-center space-y-14 lg:space-y-0 lg:space-x-12 pb-[50px] px-8 lg:px-0">
        <div class="relative flex flex-col w-full lg:w-[30%] h-48 bg-clip-border rounded-xl bg-white text-gray-700 shadow-lg">
          <div class="bg-clip-border mx-4 rounded-xl overflow-hidden bg-gradient-to-tr from-green-600 to-green-400 text-white shadow-green-500/40 shadow-lg absolute -mt-4 grid h-16 w-16 place-items-center">
              <img src="../../../public/assets/data-storage.png" class="w-9" alt="">
          </div>
          <div class="p-4 text-right">
            <p class="block antialiased font-sans text-md leading-normal font-normal text-blue-gray-600">Laporan Selesai</p>
            <h4 class="block antialiased tracking-normal font-sans text-2xl font-semibold leading-snug text-blue-gray-900 pt-[20px]"><span>{{count_selesai}}</span> Data</h4>
          </div>
          <div class="border-t border-blue-gray-50 p-4">
            <div class="w-full flex items-center justify-end"> <button @click.prevent="clickShowDataSelesai()" class="middle none center w-20  mr-3 rounded-md bg-green-600 py-3 font-sans text-xs font-bold capitalize text-white shadow-md shadow-green-300 transition-all hover:shadow-lg hover:shadow-green-700 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none" data-ripple-light="true" >Button</button></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


      <h3 class="text-base font-semibold mt-[5%] mb-8 leading-6 text-gray-900">Data Laporin</h3>
      
      <div class="bg-white  rounded-lg">
            <div class="flex items-center justify-end space-x-2 p-2 pt-[3rem] pr-[1.688rem] ">            
          <a href="/report"
            class="flex items-center text-white justify-center grid gap-1 w-[6.25rem] h-11 mr-[1.313rem] rounded-md text-center bg-sky-500 hover:bg-sky-600" 
            >
          
            <div class="flex items-center">
             
                <p class="capitalize font-semibold text-[0.938rem]">
                    {{ __('Laporin') }}
                </p>
            </div>
        </a>           
           
            </div>
       
            <div class="flex flex-col space-y-2">
            <Builder
                :url="route('report.paginate',{per_page:5 })"
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
                    {{ __('bagian sistem') }}
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
                    v-if="hasRole('it')"
                    :table="table"
                    :sort="true"
                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                    >
                    {{ __('dibuat oleh') }}
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
                    {{ __('bagian sistem') }}
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
                    v-if="hasRole('it')"
                    :table="table"
                    :sort="false"
                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                    >
                    {{ __('dibuat oleh') }}
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

                        <td class="capitalize font-semibold text-center border-b"
                                :class="{
                                    'text-cyan-600': report.kategori === 'Minor',
                                    'text-rose-600': report.kategori === 'Mayor',
                                    'text-orange-400': report.kategori === 'Critical',
                                }">
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
                            v-if="hasRole('it')"
                            class="font-semibold text-center capitalize border-b text-center ">
                            {{ (report.created_by.name) }} -
                            {{ new Date(report.created_at).toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' }) }}
                        </td>
                    </tr>
                    </template>
                </TransitionGroup>
                </template>
            </Builder>
            </div>
      </div> -->

    </div> 
  </div>
  </div>
</div>
    </DashboardLayout>

    <Modal :show="isDataFeedbackShow">
      <Card class="bg-white w-4/5">
      <template #header>
          <div class="flex items-center border-b h-[70px] mb-[30px] bg-white justify-end rounded-t-lg p-2 pb-[0.938rem] pt-[0.938rem]">
          <!-- <Close @click.prevent="close" /> --><h2 class="font-bold text-xl w-full ml-10">Data Feedback</h2>
          <button class="pr-5">
            <svg @click.prevent="close" class="w-6 h-6 text-gray-700 hover:text-cyan-500 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>
            </svg>
          </button>
          </div>
      </template>
      <template #body>
                <div class="flex flex-col space-y-2">
                    <Builder  :url="route('feedback.paginate')" ref="table">
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
                                        {{ (feedback.report && feedback.report.bagian_hardware) ? __(feedback.report.bagian_hardware) : (feedback.report.bagian ? __(feedback.report.bagian.nama) : '') }} 
                                        {{ (feedback.report && feedback.report.bagian_sistem) ? __(feedback.report.bagian_sistem) : '' }}  
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

                                        <td v-if="feedback.report.status !== 3" class="px-2 py-5 text-center pl-[1.25rem] pr-[1.25rem] border-b flex items-center justify-center">
                                         
                                            <div class="pt-[0.313rem] flex-row space-y-4">
                                            <Button class="bg-sky-500 hover:bg-sky-700 w-24 flex justify-center items-center text-center rounded-md">
                                                <a :href="route('feedback.index')" target="_blank" class="flex items-center">
                                                    
                                                    <p class="capitalize">
                                                    {{ __('detail') }}
                                                    </p>
                                                </a>
                                            </Button>
                                            
                                            <!-- <Button
                                                v-if="hasRole('user') && feedback.report.status !== 3 && !feedback.balasan1"
                                                @click.prevent="edit(feedback)"
                                                class="bg-green-500 w-[6.25rem] hover:bg-green-700 flex items-center justify-center rounded-md">
                                                
                                                <p class="capitalize">
                                                    {{ __('Balas') }}
                                                </p>
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
      <template #footer>
        <a href="/feedback" type="button" class="text-end font-bold text-xl mb-5 mr-[80px] text-sky-600 hover:text-sky-700">Read More..</a>
      </template>
    </Card>
    </Modal>

    <Modal :show="isDataListKerjaanShow">
      <Card class="bg-white w-4/5">
      <template #header>
          <div class="flex items-center border-b h-[70px] mb-[30px] bg-white justify-end rounded-t-lg p-2 pb-[0.938rem] pt-[0.938rem]">
          <!-- <Close @click.prevent="close" /> --> <h2 class="font-bold text-xl w-full ml-10">List Kerjaan</h2>
          <button class="pr-5">
            <svg @click.prevent="close" class="w-6 h-6 text-gray-700 hover:text-cyan-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>
            </svg>
          </button>
          </div>
      </template>
      <template #body>
            <div class="flex flex-col space-y-2">
            <Builder
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
    <template #footer>
        <a href="/joblist" type="button" class="text-end font-bold text-xl mb-5 mr-[80px] text-sky-600 hover:text-sky-700">Read More..</a>
      </template>
    </Card>
    </Modal>

    <Modal :show="isDataAssignShow">
      <Card class="bg-white w-4/5">
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
                <div class="flex flex-col space-y-2">
                    <Builder :url="route('job.paginate')" ref="table">
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
            <template #footer>
        <a href="/job" type="button" class="text-end font-bold text-xl mb-5 mr-[80px] text-sky-600 hover:text-sky-700">Read More..</a>
      </template>    
    </Card>
    </Modal>

    <Modal :show="isLaporinShow">
  
      <Card class="bg-white w-4/5">
      <template #header>
          <div class="flex items-center border-b h-[70px] mb-[30px] bg-white justify-end rounded-t-lg p-2 pb-[0.938rem] pt-[0.938rem]">
          <!-- <Close @click.prevent="close" /> --><h2 class="font-bold text-xl w-full ml-10">Data Laporin</h2>
          <button class="pr-5">
            <svg @click.prevent="close" class="w-6 h-6 text-gray-700 hover:text-cyan-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>
            </svg>
          </button>
          </div>
      </template>

      <template #body>
            <div class="flex flex-col space-y-2">
            <Builder
                :url="route('report.paginate',{per_page:5 })"
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
                    {{ __('bagian sistem') }}
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
                    v-if="hasRole('it')"
                    :table="table"
                    :sort="true"
                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                    >
                    {{ __('dibuat oleh') }}
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
                    {{ __('bagian sistem') }}
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
                    v-if="hasRole('it')"
                    :table="table"
                    :sort="false"
                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                    >
                    {{ __('dibuat oleh') }}
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
                                }">
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
                            v-if="hasRole('it')"
                            class="font-semibold text-center capitalize border-b text-center ">
                            {{ (report.created_by.name) }} -
                            {{ new Date(report.created_at).toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' }) }}
                        </td>
                    </tr>
                    </template>
                </TransitionGroup>
                </template>
            </Builder>
            </div>
        </template> 
        <template #footer>
        <a href="/report" type="button" class="text-end font-bold text-xl mb-5 mr-[80px] text-sky-600 hover:text-sky-700">Read More..</a>
      </template>
      </Card>
    </Modal>

    <Modal :show="isDataTerkirimShow">
  
      <Card class="bg-white w-4/5">
      <template #header>
          <div class="flex items-center border-b h-[70px] mb-[30px] bg-white justify-end rounded-t-lg p-2 pb-[0.938rem] pt-[0.938rem]">
          <!-- <Close @click.prevent="close" /> --><h2 class="font-bold text-xl w-full ml-10">Data Terkirim</h2>
          <button class="pr-5">
            <svg @click.prevent="close" class="w-6 h-6 text-gray-700 hover:text-cyan-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>
            </svg>
          </button>
          </div>
      </template>

      <template #body>
            <div class="flex flex-col space-y-2 ">
            <Builder
                :url="route('terkirim.paginate')"
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
                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold "
                    >
                    {{ __('kode') }}
                    </Th>

                    <Th
                    :table="table"
                    :sort="true"
                    name="bagian_sistem"
                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                    >
                    {{ __('bagian sistem') }}
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
                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold "
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
                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                    >
                    {{ __('status') }}
                    </Th>

                    <Th
                    v-if="hasRole('it')"
                    :table="table"
                    :sort="true"
                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                    >
                    {{ __('dibuat oleh') }}
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
                    :sort="false"
                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold "
                    >
                    {{ __('kode') }}
                    </Th>

                    <Th
                    :table="table"
                    :sort="false"
                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold "
                    >
                    {{ __('bagian sistem') }}
                    </Th>

                    <Th
                    :table="table"
                    :sort="false"
                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold "
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
                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold "
                    >
                    {{ __('status') }}
                    </Th>

                    <Th
                    v-if="hasRole('it')"
                    :table="table"
                    :sort="true"
                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold "
                    >
                    {{ __('dibuat oleh') }}
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
                        v-for="(report, i) in data"
                        :key="report.id"
                        :class="processing && 'bg-gray-100'"
                        class="transition-all h-[4.375rem] duration-300"
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

                        <td class="capitalize font-semibold text-center border-b">
                        {{ __(report.kategori) }}
                        </td>

                        <td class="capitalize font-semibold text-center border-b">
                        {{ __(report.kontak) }}
                        </td>


                        <td class="border-b pl-[5.25rem]">
                            <div v-if="report.status === 1" class="bg-sky-100 text-blue-600 text-center capitalize rounded-md font-semibold text-sm p-1 w-[5rem]">
                                Terkirim
                            </div>
                            <div v-else-if="report.status === 2" class="bg-orange-200 text-center text-orange-700 capitalize rounded-md font-semibold text-sm p-1 w-[5.625rem]">
                                Perbaikan
                            </div>
                            <div v-else-if="report.status === 3" class="bg-green-100 text-center text-emerald-600 capitalize text-center rounded-md font-semibold text-sm p-1 w-[5rem]">
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
                            v-if="hasRole('it')"
                            class="font-semibold text-center capitalize border-b text-center">
                            {{ (report.created_by.name) }} -
                            {{ new Date(report.created_at).toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' }) }}
                        </td>

                        
                    </tr>
                    </template>
                </TransitionGroup>
                </template>
            </Builder>
            </div>
        </template>
        <template #footer>
        <a href="/terkirim" type="button" class="text-end font-bold text-xl mb-5 mr-[80px] text-sky-600 hover:text-sky-700">Read More..</a>
      </template>
      <!-- <template #footer>
        <label>testdata dua</label>
      </template> -->
      </Card>
    </Modal>

    <Modal :show="isDataPerbaikanShow">
    
      <Card class="bg-white w-4/5">
      <template #header>
          <div class="flex items-center border-b h-[70px] mb-[30px] bg-white justify-end rounded-t-lg p-2 pb-[0.938rem] pt-[0.938rem]">
          <!-- <Close @click.prevent="close" /> --><h2 class="font-bold text-xl w-full ml-10">Data Perbaikan</h2>
          <button class="pr-5">
            <svg @click.prevent="close" class="w-6 h-6 text-gray-700 hover:text-cyan-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>
            </svg>
          </button>
          </div>
      </template>

      <template #body>
            <div class="flex flex-col space-y-2">
            <Builder
                :url="route('perbaikan.paginate')"
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
                    class="border px-3 py-2 text-center whitespace-nowrap"
                    >
                    {{ __('bagian bermasalah') }}
                    </Th>

                    <Th
                    :table="table"
                    :sort="true"
                    name="tanggal"
                    class="border px-3 py-2 text-center whitespace-nowrap"
                    >
                    {{ __('tanggal') }}
                    </Th>

                    <Th
                    :table="table"
                    :sort="true"
                    name="bagian_pelapor"
                    class="border px-3 py-2 text-center whitespace-nowrap"
                    >
                    {{ __('bagian pelapor') }}
                    </Th>

                    <Th
                    :table="table"
                    :sort="true"
                    name="kategori"
                    class="border px-3 py-2 text-center whitespace-nowrap"
                    >
                    {{ __('kategori') }}
                    </Th>

                    <Th
                    :table="table"
                    :sort="true"
                    name="kontak"
                    class="border px-3 py-2 text-center whitespace-nowrap"
                    >
                    {{ __('kontak') }}
                    </Th>

                    <Th
                    :table="table"
                    :sort="true"
                    class="border px-3 py-2 text-center whitespace-nowrap"
                    >
                    {{ __('status') }}
                    </Th>

                    <Th
                    v-if="hasRole('it')"
                    :table="table"
                    :sort="true"
                    class="border px-3 py-2 text-center whitespace-nowrap"
                    >
                    {{ __('dibuat oleh') }}
                    </Th>

                </tr>
                </template>

                <template #tfoot="table">
                <tr class="bg-gray-200 border-gray-300">
                    <Th
                    :table="table"
                    :sort="false"
                    class="border px-3 py-2 text-center pl-[2.75rem] pb-[1.5rem]"
                    >
                    {{ __('no') }}
                    </Th>

                    <Th
                    :table="table"
                    :sort="true"
                    class="border px-3 py-2 text-center whitespace-nowrap"
                    >
                    {{ __('kode') }}
                    </Th>

                    <Th
                    :table="table"
                    :sort="true"
                    class="border px-3 py-2 text-center whitespace-nowrap"
                    >
                    {{ __('bagian bermasalah') }}
                    </Th>

                    <Th
                    :table="table"
                    :sort="true"
                    class="border px-3 py-2 text-center whitespace-nowrap"
                    >
                    {{ __('tanggal') }}
                    </Th>

                    <Th
                    :table="table"
                    :sort="true"
                    class="border px-3 py-2 text-center whitespace-nowrap"
                    >
                    {{ __('bagian pelapor') }}
                    </Th>

                    <Th
                    :table="table"
                    :sort="true"
                    class="border px-3 py-2 text-center whitespace-nowrap"
                    >
                    {{ __('kategori') }}
                    </Th>

                    <Th
                    :table="table"
                    :sort="true"
                    class="border px-3 py-2 text-center whitespace-nowrap"
                    >
                    {{ __('kontak') }}
                    </Th>

                    <Th
                    :table="table"
                    :sort="true"
                    class="border px-3 py-2 text-center whitespace-nowrap"
                    >
                    {{ __('status') }}
                    </Th>

                    <Th
                    v-if="hasRole('it')"
                    :table="table"
                    :sort="true"
                    class="border px-3 py-2 text-center whitespace-nowrap"
                    >
                    {{ __('dibuat oleh') }}
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

                        <td class="capitalize font-semibold text-center border-b">
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
                            <div v-else class="bg-slate-200 text-slate-500 ml-[1.25rem] capitalize flex justify-center text-center rounded-md font-semibold p-1 w-[1.875rem]">
                                {{ __(report.status) }}
                            </div>
                        </td>



                        <td 
                            v-if="hasRole('it')"
                            class="font-semibold text-center capitalize border-b">
                            {{ (report.created_by.name) }} -
                            {{ new Date(report.created_at).toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' }) }}
                        </td>
                    </tr>
                    </template>
                </TransitionGroup>
                </template>
            </Builder>
            </div>
        </template>
        <template #footer>
        <a href="/perbaikan" type="button" class="text-end font-bold text-xl mb-5 mr-[80px] text-sky-600 hover:text-sky-700">Read More..</a>
      </template>
      </Card>

    </Modal>

    <Modal :show="isDataPendingShow">
      <Card class="bg-white w-4/5">
      <template #header>
          <div class="flex items-center border-b h-[70px] mb-[30px] bg-white justify-end rounded-t-lg p-2 pb-[0.938rem] pt-[0.938rem]">
          <!-- <Close @click.prevent="close" /> --><h2 class="font-bold text-xl w-full ml-10">Data Pending</h2>
          <button class="pr-5">
            <svg @click.prevent="close" class="w-6 h-6 text-gray-700 hover:text-cyan-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>
            </svg>
          </button>
          </div>
      </template>
      <template #body>
            <div class="flex flex-col space-y-2 ">
            <Builder
                :url="route('pending.paginate')"
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
                    {{ __('bagian sistem') }}
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
                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                    >
                    {{ __('status') }}
                    </Th>

                    <Th
                    v-if="hasRole('it')"
                    :table="table"
                    :sort="true"
                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                    >
                    {{ __('dibuat oleh') }}
                    </Th>

                </tr>
                </template>

                <template #tfoot="table">
                <tr class="bg-gray-200 border-gray-300">
                    <Th
                    :table="table"
                    :sort="false"
                    class="border px-3 py-2 text-center capitalize font-bold  pl-[2.75rem] pb-[1.5rem]"
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
                    {{ __('bagian sistem') }}
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
                    v-if="hasRole('it')"
                    :table="table"
                    :sort="true"
                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                    >
                    {{ __('dibuat oleh') }}
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
                        v-for="(report, i) in data"
                        :key="report.id"
                        :class="processing && 'bg-gray-100'"
                        class="h-[4.375rem] transition-all duration-300"
                    >
                        <td class="px-2 py-1 border-b text-center ">
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

                        <td class="capitalize font-semibold text-center border-b">
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
                            <div v-else class="bg-slate-200 text-slate-500 ml-[1.25rem] capitalize flex justify-center text-center rounded-md font-semibold p-1 w-[1.875rem]">
                                {{ __(report.status) }}
                            </div>
                        </td>

                        <td 
                            v-if="hasRole('it')"
                            class="font-semibold text-center capitalize border-b text-center">
                            {{ (report.created_by.name) }} -
                            {{ new Date(report.created_at).toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' }) }}
                        </td>
                    </tr>
                    </template>
                </TransitionGroup>
                </template>
            </Builder>
            </div>
        </template>
        <template #footer>
        <a href="/pending" type="button" class="text-end font-bold text-xl mb-5 mr-[80px] text-sky-600 hover:text-sky-700">Read More..</a>
      </template>
    </Card>
    </Modal>

    <Modal :show="isDataSelesaiShow">
      <Card class="bg-white w-4/5">
      <template #header>
          <div class="flex items-center border-b h-[70px] mb-[30px] bg-white justify-end rounded-t-lg p-2 pb-[0.938rem] pt-[0.938rem]">
          <!-- <Close @click.prevent="close" /> --><h2 class="font-bold text-xl w-full ml-10">Data selesai</h2>
          <button class="pr-5">
            <svg @click.prevent="close" class="w-6 h-6 text-gray-700 hover:text-cyan-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>
            </svg>
          </button>
          </div>
      </template>
          <template #body>
                <div class="flex flex-col space-y-2">
                <Builder
                    :url="route('verifikasi.paginate')"
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
                        {{ __('no.') }}
                        </Th>

                        <Th
                        :table="table"
                        :sort="true"
                        name="report_id"
                        class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                        >
                        {{ __('kode.') }}
                        </Th>

                        <Th
                        :table="table"
                        :sort="true"
                        name="report_id"
                        class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                        >
                        {{ __('bagian sistem.') }}
                        </Th>

                        <Th
                        :table="table"
                        :sort="true"
                        name="tanggal"
                        class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                        >
                        {{ __('tanggal laporan.') }}
                        </Th>

                        <Th
                        :table="table"
                        :sort="true"
                        name="tanggal"
                        class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                        >
                        {{ __('tanggal selesai.') }}
                        </Th>

                        <Th
                        :table="table"
                        :sort="true"
                        name="status"
                        class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                        >
                        {{ __('status.') }}
                        </Th>

                        <!-- <Th
                        :table="table"
                        :sort="true"
                        name="catatan"
                        class="border px-3 py-2 text-center whitespace-nowrap"
                        >
                        {{ __('catatan') }}
                        </Th> -->

                        <Th
                        v-if="hasRole('it')"
                        :table="table"
                        :sort="true"
                        class="border px-3 py-2 text-center whitespace-nowrap "
                        >
                        {{ __('diperbaiki oleh') }}
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

                    <template #tfoot="table">
                    <tr class="bg-gray-200 border-gray-300">
                        <Th
                        :table="table"
                        :sort="false"
                        class="border px-3 py-2 text-center capitalize font-bold pl-[2.75rem] pb-[1.5rem]"
                        >
                        {{ __('no.') }}
                        </Th>

                        <Th
                        :table="table"
                        :sort="true"
                        class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                        >
                        {{ __('kode.') }}
                        </Th>

                        <Th
                        :table="table"
                        :sort="true"
                        name="report_id"
                        class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                        >
                        {{ __('bagian sistem.') }}
                        </Th>

                        <Th
                        :table="table"
                        :sort="true"
                        class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                        >
                        {{ __('tanggal laporan.') }}
                        </Th>

                        <Th
                        :table="table"
                        :sort="true"
                        class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                        >
                        {{ __('tanggal selesai.') }}
                        </Th>

                        <Th
                        :table="table"
                        :sort="true"
                        class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                        >
                        {{ __('status.') }}
                        </Th>

                        <!-- <Th
                        :table="table"
                        :sort="true"
                        class="border px-3 py-2 text-center whitespace-nowrap"
                        >
                        {{ __('catatan') }}
                        </Th> -->

                        <Th
                        v-if="hasRole('it')"
                        :table="table"
                        :sort="true"
                        class="border px-3 py-2 text-center whitespace-nowrap"
                        >
                        {{ __('diperbaiki oleh') }}
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
                            <td class="text-5xl text-center p-4" colspan="1000">
                            <p class="lowercase first-letter:capitalize font-semibold">
                                {{ __('Tidak ada data untuk ditampilkan.') }}
                            </p>
                            </td>
                        </tr>
                        </template>

                        <template v-else>
                        <tr
                            v-for="(verifikasi, i) in data"
                            :key="verifikasi.id"
                            :class="processing && 'bg-gray-100'"
                            class="h-[4.375rem] transition-all duration-300"
                        >
                            <td class="px-2 py-1 text-center border-b">
                            {{ i + 1 }}
                            </td>

                            <td class="px-2 py-1 text-center border-b">
                            {{ __(verifikasi.report.kode) }}
                            </td>

                            <td class="capitalize font-semibold text-center border-b">
                            {{ (verifikasi.report && verifikasi.report.bagian_hardware) ? __(verifikasi.report.bagian_hardware) : (verifikasi.report.bagian ? __(verifikasi.report.bagian.nama) : '') }} 
                            {{ (verifikasi.report && verifikasi.report.bagian_sistem) ? __(verifikasi.report.bagian_sistem) : '' }} 
                            {{ (verifikasi.report && verifikasi.report.bagian) ? __(verifikasi.report.bagian.singkatan) : '' }}
                            </td>

                            <td class="px-2 py-1 text-center border-b">
                            {{ new Date(verifikasi.report.tanggal).toLocaleString('id-ID', { dateStyle: 'medium'}) }}
                            </td>

                            <td class="px-2 py-1 text-center border-b"> 
                            {{ new Date(verifikasi.tanggal).toLocaleString('id-ID', { dateStyle: 'medium'}) }}
                            </td>

                        
                            <td class="border-b pl-[5.25rem]">
                                <div v-if="verifikasi.report.status === 1" class="bg-sky-100 text-blue-600 text-center capitalize rounded-md font-semibold text-sm p-1 w-[5rem]">
                                    Terkirim
                                </div>
                                <div v-else-if="verifikasi.report.status === 2" class="bg-orange-200 text-center text-orange-700 capitalize rounded-md font-semibold text-sm p-1 w-[5.625]">
                                    Perbaikan
                                </div>
                                <div v-else-if="verifikasi.report.status === 3" class="bg-green-100 text-center text-emerald-600 capitalize text-center rounded-md font-semibold text-sm p-1 w-[5rem]">
                                    Selesai
                                </div>
                                <div v-else-if="verifikasi.report.status === 4" class="bg-red-200 text-center text-rose-700 capitalize rounded-md  font-semibold text-sm p-1 w-[5rem]">
                                    Pending
                                </div>
                                <div v-else-if="verifikasi.report.status === 5" class="bg-stone-100 text-center text-black capitalize font-semibold p-1 rounded-md text-sm w-[5rem]">
                                    Diterima
                                </div>
                                <div v-else class="bg-slate-200 text-slate-500 ml-[1.25rem] capitalize flex justify-center text-center rounded-md font-semibold p-1 w-[1.875rem]">
                                    {{ __(verifikasi.report.status) }}
                                </div>
                            </td>
                            <!-- </td> -->

                            <td 
                                v-if="hasRole('it')"
                                class="font-semibold text-center capitalize border-b text-center">
                                {{ (verifikasi.created_by.name) }}
                            </td>

                            <td class="px-2 py-2 text-center pl-[1.25rem] pr-[1.25rem] border-b">
                            <div class="grid w-full pt-[0.313rem] flex items-center justify-center">

                                <Button class="bg-sky-500 hover:bg-sky-700 w-24 flex justify-center items-center text-center rounded-md">
                                <a :href="route('verifikasi.detail', verifikasi.id)" target="_blank" class="flex justify-center items-center">
                                    <!-- <Icon name="circle-info" class="mr-2" /> -->
                                    <p class="capitalize ">
                                    {{ __('detail') }}
                                    </p>
                                </a>
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
          <template #footer>
        <a href="/verifikasi" type="button" class="text-end font-bold text-xl mb-5 mr-[80px] text-sky-600 hover:text-sky-700">Read More..</a>
      </template>
    </Card>
    </Modal>
</template>

 

