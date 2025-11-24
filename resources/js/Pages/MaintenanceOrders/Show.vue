<script setup>
import { computed, ref } from 'vue'
import { useForm, Link } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Card from '@/Components/Card.vue'
import ButtonBlue from '@/Components/Button/Blue.vue'
import ButtonRed from '@/Components/Button/Red.vue'
import Input from '@/Components/Input.vue'
import InputError from '@/Components/InputError.vue'

const props = defineProps({
  order: Object,
  users: Array,
})

// === 1. FORM FOLLOW UP ===
const formFollowUp = useForm({
  follow_up_by_id: null,
  follow_up_plan: '',
  follow_up_estimate_at: '',
})

// === 2. FORM START REPAIR ===
const formStartRepair = useForm({
  start_repair_by_id: null,
  start_repair_notes: '',
  start_repair_photo: null,
})

// === 3. FORM CHECKLIST (LOGIKA BARU YANG LEBIH CANGGIH) ===
function initializeResults() {
  let resultsData = {}

  // Cek apakah ada pedoman dan kategori (struktur baru)
  if (!props.order.master_pedoman || !props.order.master_pedoman.categories) {
    return {}
  }

  // Loop Kategori -> Loop Items
  props.order.master_pedoman.categories.forEach(cat => {
    cat.items.forEach(item => {

      // Cari data lama di database
      const existingResult = props.order.results?.find(r => r.master_pedoman_item_id === item.id)

      // Default values
      let realisasiVal = existingResult?.realisasi || ''
      let statusVal = existingResult?.status || 'OK'
      let catatanVal = existingResult?.catatan || ''

      // KHUSUS TIPE TABLE: Kita harus parsing JSON string kembali ke Object
      if (item.tipe_input === 'table') {
        try {
          // Jika ada data lama, parse JSON-nya. Jika tidak, buat object kosong
          realisasiVal = realisasiVal ? JSON.parse(realisasiVal) : {}
        } catch (e) {
          realisasiVal = {}
        }
      }

      resultsData[item.id] = {
        realisasi: realisasiVal,
        status: statusVal,
        catatan: catatanVal,
        // Simpan tipe input agar nanti mudah saat submit (opsional)
        tipe_input: item.tipe_input
      }
    })
  })

  return resultsData
}

const formChecklist = useForm({
  results: initializeResults()
})

// === 4. FORM COMPLETE ===
const formComplete = useForm({
  complete_repair_by_id: null,
  complete_repair_notes: '',
  complete_repair_photo: null,
})

// === FUNGSI SUBMIT ===
function submitFollowUp() {
  formFollowUp.post(route('maintenance-orders.followUp', props.order.id), { preserveScroll: true })
}
function submitStartRepair() {
  formStartRepair.post(route('maintenance-orders.startRepair', props.order.id), { preserveScroll: true })
}

function submitChecklist() {
  // KHUSUS TABLE: Sebelum kirim, kita harus stringify JSON object kembali ke string
  // agar bisa disimpan di kolom 'realisasi' (varchar) database
  let payload = JSON.parse(JSON.stringify(formChecklist.results)); // Deep copy

  for (const itemId in payload) {
    if (payload[itemId].tipe_input === 'table' && typeof payload[itemId].realisasi === 'object') {
      payload[itemId].realisasi = JSON.stringify(payload[itemId].realisasi);
    }
  }

  // Kita gunakan helper manual karena kita memodifikasi payload
  Inertia.post(route('maintenance-orders.storeResults', props.order.id), { results: payload }, {
    preserveScroll: true,
    onStart: () => formChecklist.processing = true,
    onFinish: () => formChecklist.processing = false,
  })
}

function submitComplete() {
  if (!confirm('Apakah Anda yakin ingin menyelesaikan pekerjaan ini?')) return;
  formComplete.post(route('maintenance-orders.complete', props.order.id));
}

// === COMPUTED & HELPER ===
const hasChecklist = computed(() => {
  return props.order.master_pedoman &&
         props.order.master_pedoman.categories &&
         props.order.master_pedoman.categories.length > 0
})
const isBaru = computed(() => props.order.status === 'BARU')
const isDiproses = computed(() => props.order.status === 'DIPROSES')
const isDikerjakan = computed(() => props.order.status === 'DIKERJAKAN')
const isSelesai = computed(() => props.order.status === 'SELESAI')

const formatDate = (dateString) => {
  if (!dateString) return '-';
  return new Date(dateString).toLocaleString('id-ID', {
    day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit'
  });
}
</script>

<template>
  <DashboardLayout title="Detail Maintenance Order">
    <main class="p-0 py-0 mb-[1.25rem] ml-[1.25rem] mt-[1.25rem]">
      <h2 class="font-bold text-2xl">Detail Maintenance Order #{{ order.id }}</h2>
      <a :href="route('maintenance-orders.index')" class="text-sm text-gray-500 font-semibold hover:text-sky-600">Maintenance Order</a>
      <span class="font-semibold text-sm px-2">-</span>
      <span class="text-sm text-gray-500 font-semibold">Detail</span>
    </main>

    <Card class="bg-white pt-[1.875rem] pb-[2.5rem] px-6 md:px-8 shadow-lg border border-solid border-slate-200 mb-4" style="border-radius:.625rem;">
      <template #body>
        <h3 class="font-bold text-lg mb-4">Informasi Order</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="md:col-span-2">
            <label class="block text-sm font-semibold">Status Pekerjaan:</label>
            <span class="font-bold px-3 py-1 rounded-full text-white text-sm"
                :class="{'bg-blue-500': isBaru, 'bg-cyan-500': isDiproses, 'bg-yellow-500': isDikerjakan, 'bg-green-600': isSelesai}">
                {{ order.status }}
            </span>
          </div>
          <div><label class="block text-sm font-semibold">Judul:</label><p>{{ order.title }}</p></div>
          <div>
            <label class="block text-sm font-semibold">Kategori:</label>
            <p class="font-bold" :class="order.category === 'planned' ? 'text-blue-600' : 'text-red-600'">
              {{ order.category === 'planned' ? 'Planned (Perawatan)' : 'Unplanned (Gangguan)' }}
            </p>
          </div>
          <div><label class="block text-sm font-semibold">Mesin:</label><p>{{ order.machine.name }} - {{ order.machine.nomor }}</p></div>
          <div v-if="order.lampiran_path">
            <label class="block text-sm font-semibold">Lampiran Order:</label>
            <a :href="`/storage/${order.lampiran_path}`" target="_blank" class="text-blue-600 hover:underline font-semibold">📎 Lihat Lampiran</a>
          </div>
          <template v-if="order.category === 'planned'">
            <div><label class="block text-sm font-semibold">Pedoman Checklist:</label><p>{{ order.master_pedoman?.kode_pedoman || '-' }}</p></div>
            <div><label class="block text-sm font-semibold">Rencana Mulai:</label><p>{{ formatDate(order.plan_start_at) }}</p></div>
            <div><label class="block text-sm font-semibold">Machine Hours:</label><p>{{ order.machine_hours || '-' }}</p></div>
            <div class="md:col-span-2"><label class="block text-sm font-semibold">Rencana Tindak Lanjut:</label><p>{{ order.action_plan || '-' }}</p></div>
          </template>
          <template v-else>
            <div><label class="block text-sm font-semibold">Lokasi:</label><p>{{ order.location }}</p></div>
            <div><label class="block text-sm font-semibold">Waktu Gangguan:</label><p>{{ formatDate(order.trouble_at) }}</p></div>
            <div class="md:col-span-2"><label class="block text-sm font-semibold">Catatan Gangguan:</label><p class="whitespace-pre-wrap">{{ order.problem_note }}</p></div>
          </template>
          <template v-if="!isBaru">
            <hr class="md:col-span-2 my-2 border-slate-200">
            <div><label class="block text-sm font-semibold">Follow Up Plan:</label><p>{{ order.follow_up_plan || '-' }}</p></div>
            <div><label class="block text-sm font-semibold">Estimasi Selesai:</label><p>{{ formatDate(order.follow_up_estimate_at) }}</p></div>
          </template>
          <template v-if="isDikerjakan || isSelesai">
            <hr class="md:col-span-2 my-2 border-slate-200">
            <div><label class="block text-sm font-semibold">Catatan Mulai Kerja:</label><p>{{ order.start_repair_notes || '-' }}</p></div>
            <div><label class="block text-sm font-semibold">Mulai Dikerjakan:</label><p>{{ formatDate(order.start_repair_at) }}</p></div>
            <div v-if="order.start_repair_photo"><label class="block text-sm font-semibold">Foto Mulai:</label><a :href="`/storage/${order.start_repair_photo}`" target="_blank" class="text-blue-600 hover:underline">📷 Lihat Foto</a></div>
          </template>
          <template v-if="isSelesai">
            <hr class="md:col-span-2 my-2 border-slate-200">
            <div><label class="block text-sm font-semibold">Hasil Tindak Lanjut:</label><p>{{ order.complete_repair_notes || '-' }}</p></div>
            <div><label class="block text-sm font-semibold">Selesai Dikerjakan:</label><p>{{ formatDate(order.complete_repair_at) }}</p></div>
            <div v-if="order.complete_repair_photo"><label class="block text-sm font-semibold">Foto Selesai:</label><a :href="`/storage/${order.complete_repair_photo}`" target="_blank" class="text-blue-600 hover:underline">📷 Lihat Foto</a></div>
          </template>
        </div>
      </template>
    </Card>

    <Card v-if="isBaru" class="bg-white pt-[1.875rem] pb-[2.5rem] px-6 md:px-8 shadow-lg border border-solid border-slate-200 mb-4" style="border-radius:.625rem;">
      <template #body>
        <h3 class="font-bold text-lg mb-4 text-blue-700">Formulir Follow Up Plan</h3>
        <form @submit.prevent="submitFollowUp" class="space-y-4">
          <div><label class="block text-sm font-semibold mb-1">Nama Teknisi / KAOP</label>
            <select v-model="formFollowUp.follow_up_by_id" class="w-full border rounded p-2"><option :value="null">Pilih User</option><option v-for="u in props.users" :key="u.id" :value="u.id">{{ u.name }}</option></select>
            <InputError :message="formFollowUp.errors.follow_up_by_id" class="mt-1" /></div>
          <div><label class="block text-sm font-semibold mb-1">Rencana Tindak Lanjut</label><textarea v-model="formFollowUp.follow_up_plan" rows="3" class="w-full border rounded p-2"></textarea><InputError :message="formFollowUp.errors.follow_up_plan" class="mt-1" /></div>
          <div><label class="block text-sm font-semibold mb-1">Estimasi Waktu</label><input type="datetime-local" v-model="formFollowUp.follow_up_estimate_at" class="w-full border rounded p-2" /><InputError :message="formFollowUp.errors.follow_up_estimate_at" class="mt-1" /></div>
          <div class="flex justify-end"><ButtonBlue :disabled="formFollowUp.processing">Simpan Follow Up</ButtonBlue></div>
        </form>
      </template>
    </Card>

    <Card v-if="isDiproses" class="bg-white pt-[1.875rem] pb-[2.5rem] px-6 md:px-8 shadow-lg border border-solid border-slate-200 mb-4" style="border-radius:.625rem;">
      <template #body>
        <h3 class="font-bold text-lg mb-4 text-cyan-700">Formulir Mulai Perbaikan</h3>
        <form @submit.prevent="submitStartRepair" class="space-y-4">
          <div><label class="block text-sm font-semibold mb-1">Nama Teknisi</label><select v-model="formStartRepair.start_repair_by_id" class="w-full border rounded p-2"><option :value="null">Pilih Teknisi</option><option v-for="u in props.users" :key="u.id" :value="u.id">{{ u.name }}</option></select><InputError :message="formStartRepair.errors.start_repair_by_id" class="mt-1" /></div>
          <div><label class="block text-sm font-semibold mb-1">Tindak Lanjut / Keterangan</label><textarea v-model="formStartRepair.start_repair_notes" rows="3" class="w-full border rounded p-2"></textarea><InputError :message="formStartRepair.errors.start_repair_notes" class="mt-1" /></div>
          <div><label class="block text-sm font-semibold mb-1">Upload Foto</label><input type="file" @input="formStartRepair.start_repair_photo = $event.target.files[0]" class="w-full" /></div>
          <div class="flex justify-end"><ButtonBlue :disabled="formStartRepair.processing">Mulai Pekerjaan</ButtonBlue></div>
        </form>
      </template>
    </Card>

    <template v-if="isDikerjakan">

      <Card v-if="hasChecklist" class="bg-white pt-[1.875rem] pb-[2.5rem] px-6 md:px-8 shadow-lg border border-solid border-slate-200 mb-4" style="border-radius:.625rem;">
        <template #body>
          <div class="flex justify-between items-center mb-6 border-b pb-2">
             <h3 class="font-bold text-xl text-yellow-700">Checklist: {{ order.master_pedoman.nama_pedoman }}</h3>
          </div>

          <form @submit.prevent="submitChecklist">
            <div class="space-y-8">

              <div v-for="category in order.master_pedoman.categories" :key="category.id">
                <h4 class="font-bold text-lg bg-gray-100 p-2 rounded mb-3 border-l-4 border-yellow-500">{{ category.name }}</h4>

                <div class="space-y-4">
                  <div v-for="item in category.items" :key="item.id" class="border rounded-lg p-4 bg-white shadow-sm">

                    <div v-if="item.tipe_input === 'image'" class="text-center">
                      <p class="font-semibold mb-2">{{ item.deskripsi }}</p>
                      <img :src="`/storage/${item.gambar_referensi_path}`" alt="Diagram Referensi" class="mx-auto max-w-full h-auto rounded border" />
                    </div>

                    <div v-else-if="item.tipe_input === 'table'">
                      <p class="font-semibold mb-2"><span class="font-bold mr-2">{{ item.nomor_poin }}</span>{{ item.deskripsi }}</p>

                      <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left border">
                          <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                              <th class="px-2 py-2 border">Posisi</th>
                              <th v-for="(col, idx) in item.extra_config.columns" :key="idx" class="px-2 py-2 border text-center">
                                {{ col.name }}
                                <br><span class="text-[10px] text-gray-500">Std: {{ col.std }}</span>
                              </th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(rowLabel, rIdx) in item.extra_config.rows_label" :key="rIdx" class="border-b">
                              <td class="px-2 py-2 font-medium border bg-gray-50">{{ rowLabel }}</td>

                              <td v-for="(col, cIdx) in item.extra_config.columns" :key="cIdx" class="p-1 border">
                                <input
                                  type="text"
                                  class="w-full border-gray-300 rounded text-xs p-1 focus:ring-yellow-500 focus:border-yellow-500"
                                  v-model="formChecklist.results[item.id].realisasi[rowLabel + '_' + cIdx]"
                                  :placeholder="col.std"
                                />
                                </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>

                      <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-4">
                         <div>
                           <label class="text-xs font-bold">Status Keseluruhan:</label>
                           <select v-model="formChecklist.results[item.id].status" class="w-full border-gray-300 rounded text-sm p-1">
                             <option value="OK">OK</option><option value="NG">NG</option>
                           </select>
                         </div>
                         <div>
                           <label class="text-xs font-bold">Catatan:</label>
                           <Input v-model="formChecklist.results[item.id].catatan" type="text" class="w-full text-sm" />
                         </div>
                      </div>
                    </div>

                    <div v-else class="grid grid-cols-1 md:grid-cols-5 gap-4 items-center">
                       <div class="col-span-2">
                         <p class="font-medium"><span class="font-bold mr-2">{{ item.nomor_poin }}</span>{{ item.deskripsi }}</p>
                         <small class="text-gray-500">Std: {{ item.standar_nilai || '-' }} {{ item.satuan }}</small>
                       </div>
                       <div>
                         <label class="md:hidden text-xs font-bold">Realisasi</label>
                         <Input v-model="formChecklist.results[item.id].realisasi" type="text" class="w-full text-sm" :placeholder="item.standar_nilai" />
                       </div>
                       <div>
                         <label class="md:hidden text-xs font-bold">Status</label>
                         <select v-model="formChecklist.results[item.id].status" class="w-full border-gray-300 rounded-md shadow-sm p-2 text-sm">
                           <option value="OK">OK</option><option value="NG">NG</option><option value="N/A">N/A</option>
                         </select>
                       </div>
                       <div>
                         <label class="md:hidden text-xs font-bold">Catatan</label>
                         <Input v-model="formChecklist.results[item.id].catatan" type="text" class="w-full text-sm" placeholder="Catatan..." />
                       </div>
                    </div>

                  </div>
                </div>
              </div>

            </div>
            <div class="flex justify-end mt-6">
              <ButtonBlue :disabled="formChecklist.processing">Simpan Progress Checklist</ButtonBlue>
            </div>
          </form>
        </template>
      </Card>

      <Card class="bg-white pt-[1.875rem] pb-[2.5rem] px-6 md:px-8 shadow-lg border border-solid border-red-200 mb-4" style="border-radius:.625rem;">
        <template #body>
          <h3 class="font-bold text-lg mb-4 text-red-700">Formulir Selesaikan Perbaikan</h3>
          <form @submit.prevent="submitComplete" class="space-y-4">
            <div><label class="block text-sm font-semibold mb-1">Nama Teknisi</label><select v-model="formComplete.complete_repair_by_id" class="w-full border rounded p-2"><option :value="null">Pilih Teknisi</option><option v-for="u in props.users" :key="u.id" :value="u.id">{{ u.name }}</option></select><InputError :message="formComplete.errors.complete_repair_by_id" class="mt-1" /></div>
            <div><label class="block text-sm font-semibold mb-1">Hasil Tindak Lanjut</label><textarea v-model="formComplete.complete_repair_notes" rows="3" class="w-full border rounded p-2"></textarea><InputError :message="formComplete.errors.complete_repair_notes" class="mt-1" /></div>
            <div><label class="block text-sm font-semibold mb-1">Upload Foto Selesai</label><input type="file" @input="formComplete.complete_repair_photo = $event.target.files[0]" class="w-full" /></div>
            <div class="flex justify-end"><ButtonRed :disabled="formComplete.processing">Selesaikan Pekerjaan</ButtonRed></div>
          </form>
        </template>
      </Card>
    </template>

    <!-- HASIL CHECKLIST (READ-ONLY) - Tampil jika SELESAI dan ada checklist -->
    <Card v-if="isSelesai && hasChecklist" class="bg-white pt-[1.875rem] pb-[2.5rem] px-6 md:px-8 shadow-lg border border-solid border-slate-200 mb-4" style="border-radius:.625rem;">
      <template #body>
        <div class="flex justify-between items-center mb-6 border-b pb-2">
          <h3 class="font-bold text-xl text-green-700">📋 Hasil Checklist: {{ order.master_pedoman.nama_pedoman }}</h3>
          <a :href="route('maintenance-orders.printPDF', order.id)" target="_blank" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2 rounded flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            Cetak PDF
          </a>
        </div>

        <div class="space-y-8">
          <div v-for="category in order.master_pedoman.categories" :key="category.id">
            <h4 class="font-bold text-lg bg-green-100 p-2 rounded mb-3 border-l-4 border-green-600">{{ category.name }}</h4>

            <div class="space-y-4">
              <div v-for="item in category.items" :key="item.id" class="border rounded-lg p-4 bg-gray-50 shadow-sm">

                <div v-if="item.tipe_input === 'image'" class="text-center">
                  <p class="font-semibold mb-2">{{ item.deskripsi }}</p>
                  <img v-if="item.gambar_referensi_path" :src="`/storage/${item.gambar_referensi_path}`" alt="Diagram Referensi" class="mx-auto max-w-full h-auto rounded border" />
                </div>

                <div v-else-if="item.tipe_input === 'table'">
                  <p class="font-semibold mb-2"><span class="font-bold mr-2">{{ item.nomor_poin }}</span>{{ item.deskripsi }}</p>

                  <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left border">
                      <thead class="text-xs text-gray-700 uppercase bg-gray-200">
                        <tr>
                          <th class="px-2 py-2 border">Posisi</th>
                          <th v-for="(col, idx) in item.extra_config.columns" :key="idx" class="px-2 py-2 border text-center">
                            {{ col.name }}
                            <br><span class="text-[10px] text-gray-500">Std: {{ col.std }}</span>
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(rowLabel, rIdx) in item.extra_config.rows_label" :key="rIdx" class="border-b">
                          <td class="px-2 py-2 font-medium border bg-gray-100">{{ rowLabel }}</td>
                          <td v-for="(col, cIdx) in item.extra_config.columns" :key="cIdx" class="p-2 border text-center">
                            {{ formChecklist.results[item.id]?.realisasi?.[rowLabel + '_' + cIdx] || '-' }}
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                  <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                      <span class="font-bold">Status:</span>
                      <span class="ml-2 px-2 py-1 rounded" :class="formChecklist.results[item.id]?.status === 'OK' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800'">
                        {{ formChecklist.results[item.id]?.status || 'OK' }}
                      </span>
                    </div>
                    <div>
                      <span class="font-bold">Catatan:</span>
                      <span class="ml-2">{{ formChecklist.results[item.id]?.catatan || '-' }}</span>
                    </div>
                  </div>
                </div>

                <div v-else class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
                  <div class="col-span-2">
                    <p class="font-medium"><span class="font-bold mr-2">{{ item.nomor_poin }}</span>{{ item.deskripsi }}</p>
                    <small class="text-gray-500">Std: {{ item.standar_nilai || '-' }} {{ item.satuan }}</small>
                  </div>
                  <div>
                    <span class="font-bold">Realisasi:</span>
                    <span class="ml-2">{{ formChecklist.results[item.id]?.realisasi || '-' }}</span>
                  </div>
                  <div>
                    <span class="font-bold">Status:</span>
                    <span class="ml-2 px-2 py-1 rounded" :class="formChecklist.results[item.id]?.status === 'OK' ? 'bg-green-200 text-green-800' : formChecklist.results[item.id]?.status === 'NG' ? 'bg-red-200 text-red-800' : 'bg-gray-200 text-gray-800'">
                      {{ formChecklist.results[item.id]?.status || 'OK' }}
                    </span>
                    <span v-if="formChecklist.results[item.id]?.catatan" class="block mt-1 text-xs text-gray-600">
                      💬 {{ formChecklist.results[item.id].catatan }}
                    </span>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>
      </template>
    </Card>

    <Card v-if="isSelesai" class="bg-green-50 border-green-300 pt-[1.875rem] pb-[2.5rem] px-6 md:px-8 shadow-lg" style="border-radius:.625rem;">
      <template #body>
        <h3 class="font-bold text-lg text-green-800">✅ Pekerjaan Telah Selesai</h3>
        <p class="text-green-700">Order ini telah ditutup pada {{ formatDate(order.complete_repair_at) }}.</p>
      </template>
    </Card>

  </DashboardLayout>
</template>
