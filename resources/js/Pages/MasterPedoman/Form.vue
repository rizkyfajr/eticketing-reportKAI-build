<script setup>
import { ref } from 'vue' // Tambahkan ref
import { useForm, Link } from '@inertiajs/inertia-vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Card from '@/Components/Card.vue'
import ButtonBlue from '@/Components/Button/Blue.vue'
import Button from '@/Components/Button.vue'
import Input from '@/Components/Input.vue'
import InputError from '@/Components/InputError.vue'

const props = defineProps({
  pedoman: Object
})

// Kunci untuk memaksa update tampilan (Force Re-render)
const componentKey = ref(0);

function initCategories() {
    if (props.pedoman && Array.isArray(props.pedoman.categories)) {
      // Ensure we copy existing category/item fields but also
      // provide a `gambar_file` slot for new uploads while
      // preserving any existing `gambar_referensi_path` so the
      // backend can keep the old image if the user doesn't upload a new one.
      return props.pedoman.categories.map(cat => ({
         ...cat,
         items: Array.isArray(cat.items) ? cat.items.map(item => ({
            ...item,
            // file input placeholder (File instance) - will be set when user selects a file
            gambar_file: null,
            // ensure existing reference path is present on the frontend model
            gambar_referensi_path: item.gambar_referensi_path || null,
         })) : []
      }));
    }
    return [];
}

const form = useForm({
  kode_pedoman: props.pedoman?.kode_pedoman || '',
  nama_pedoman: props.pedoman?.nama_pedoman || '',
  categories: initCategories()
})

// --- ACTIONS ---

const addCategory = () => {
  console.log('Klik Tambah Kategori');

  // 1. Pastikan array terinisialisasi
  if (!form.categories) form.categories = [];

  // 2. Push data baru
  form.categories.push({
      name: '',
      items: []
  });

  // 3. Paksa update tampilan dengan mengubah key
  componentKey.value++;
}

const removeCategory = (index) => {
  if(confirm('Hapus kategori ini?')) {
      form.categories.splice(index, 1);
      componentKey.value++;
  }
}

const addItem = (categoryIndex) => {
  console.log('Klik Tambah Item di index:', categoryIndex);

  // Pastikan items array ada
  if (!form.categories[categoryIndex].items) {
      form.categories[categoryIndex].items = [];
  }

  form.categories[categoryIndex].items.push({
    nomor_poin: '',
    deskripsi: '',
    tipe_input: 'checkbox',
    standar_nilai: '',
    satuan: '',
    gambar_file: null,
    extra_config: null
  });

  componentKey.value++;
}

const removeItem = (categoryIndex, itemIndex) => {
  form.categories[categoryIndex].items.splice(itemIndex, 1);
  componentKey.value++;
}

// Setup config Table
const setupTableConfig = (categoryIndex, itemIndex) => {
   const current = form.categories[categoryIndex].items[itemIndex].extra_config
   if (!current) {
      form.categories[categoryIndex].items[itemIndex].extra_config = {
         columns: [{name: 'Kolom 1', std: ''}, {name: 'Kolom 2', std: ''}],
         rows_label: ['Baris 1', 'Baris 2']
      }
   }
   alert("Mode Tabel aktif. Konfigurasi kolom saat ini default.")
}

const submit = () => {
  if (props.pedoman) {
      // When editing include _method=PUT and force FormData so nested File objects are preserved
      form.transform((data) => ({ ...data, _method: 'PUT' }))
            .post(route('master-pedoman.update', props.pedoman.id), { forceFormData: true })
  } else {
      // For create, force FormData to support file uploads inside nested arrays
      form.post(route('master-pedoman.store'), { forceFormData: true })
  }
}


const kodeOptions = [
  'P1 CSM WITH GEN',
  'P1 PBR U-RS',
  'P1 UNIMAT',
  'P3 CSM',
  'P3 PBR U-RS',
  'P3 UNIMAT',
  'P6 CSM',
  'P6 CSM WITH GEN',
  'P6 PBR U-RS',
  'P6 UNIMAT',
  // Tambahan P12, P24, P48 di sini
  'P12 CSM', 'P12 PBR U-RS', 'P12 UNIMAT',
  'P24 CSM', 'P24 PBR U-RS', 'P24 UNIMAT',
  'P48 CSM', 'P48 PBR U-RS', 'P48 UNIMAT',
];
</script>

<template>
  <DashboardLayout :title="props.pedoman ? 'Edit Master Pedoman' : 'Buat Master Pedoman'">
    <div class="p-6 max-w-5xl mx-auto">

      <div class="mb-6 flex justify-between items-center">
          <h2 class="text-2xl font-bold text-gray-800">
              {{ props.pedoman ? 'Edit' : 'Buat' }} Pedoman Checksheet
          </h2>
      </div>

      <Card class="bg-white p-6 shadow mb-6 rounded-lg">
        <template #body>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
               <div>
                  <label class="font-bold text-sm text-gray-700 mb-2 block">Kode Pedoman</label>
                  <select
                        v-model="form.kode_pedoman"
                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm p-2"
                    >
                        <option value="" disabled>Pilih Kode Pedoman</option>
                        <option v-for="opt in kodeOptions" :key="opt" :value="opt">
                            {{ opt }}
                        </option>
                    </select>
                  <InputError v-if="form.errors.kode_pedoman" :message="form.errors.kode_pedoman" />
               </div>
               <div>
                  <label class="font-bold text-sm text-gray-700 mb-2 block">Nama Pedoman</label>
                  <Input v-model="form.nama_pedoman" class="w-full" placeholder="Contoh: Perawatan 6 Bulanan Unimat" />
                  <InputError v-if="form.errors.nama_pedoman" :message="form.errors.nama_pedoman" />
               </div>
            </div>

            <hr class="my-6 border-gray-200">

            <div class="space-y-6" :key="componentKey">

                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-700">Isi Checksheet</h3>
                    <button
                        @click.prevent="addCategory"
                        type="button"
                        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 font-bold shadow flex items-center gap-2 text-sm"
                    >
                       <span>+ Tambah Kategori Baru</span>
                    </button>
                </div>

                <div v-if="!form.categories || form.categories.length === 0" class="text-center py-10 bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg text-gray-400">
                    Belum ada kategori. Klik tombol hijau di atas untuk memulai.
                </div>

                <div v-for="(cat, cIdx) in form.categories" :key="cIdx" class="bg-gray-50 p-4 rounded-lg border border-gray-200 shadow-sm transition-all hover:shadow-md mb-4">

                   <div class="flex justify-between items-end mb-4 pb-2 border-b border-gray-200">
                      <div class="w-2/3">
                         <label class="text-xs font-bold uppercase text-gray-500 mb-1 block">Nama Kategori (Grup)</label>
                         <Input v-model="cat.name" class="w-full font-bold text-lg bg-white" placeholder="Contoh: Engine / Mekanik" />
                      </div>
                      <button @click.prevent="removeCategory(cIdx)" type="button" class="text-red-500 text-sm hover:text-red-700 font-semibold px-3 py-1">
                          Hapus Grup Ini
                      </button>
                   </div>

                   <div class="space-y-3 pl-2">
                      <div v-for="(item, iIdx) in cat.items" :key="iIdx" class="bg-white p-4 rounded border border-gray-200 flex flex-col gap-3 relative">

                         <div class="flex gap-3 items-start">
                            <div class="w-20">
                               <label class="text-[10px] font-bold text-gray-400 block mb-1">No.</label>
                               <Input v-model="item.nomor_poin" class="w-full text-sm" placeholder="E.1" />
                            </div>
                            <div class="flex-1">
                               <label class="text-[10px] font-bold text-gray-400 block mb-1">Deskripsi</label>
                               <Input v-model="item.deskripsi" class="w-full text-sm" placeholder="Deskripsi pekerjaan..." />
                            </div>
                            <div class="w-40">
                               <label class="text-[10px] font-bold text-gray-400 block mb-1">Tipe Input</label>
                               <select v-model="item.tipe_input" class="w-full border-gray-300 rounded text-sm p-2">
                                  <option value="checkbox">✅ Checkbox</option>
                                  <option value="numeric">123 Angka</option>
                                  <option value="image">🖼️ Gambar</option>
                                  <option value="table">▦ Tabel</option>
                               </select>
                            </div>
                            <button @click.prevent="removeItem(cIdx, iIdx)" type="button" class="text-gray-400 hover:text-red-600 font-bold p-2 self-center">✕</button>
                         </div>

                         <div v-if="item.tipe_input === 'numeric' || item.tipe_input === 'checkbox'" class="flex gap-3 bg-gray-50 p-2 rounded">
                            <div class="w-1/2">
                               <label class="text-[10px] font-bold text-gray-400">Nilai Standar</label>
                               <Input v-model="item.standar_nilai" class="w-full text-xs" />
                            </div>
                            <div class="w-1/2">
                               <label class="text-[10px] font-bold text-gray-400">Satuan</label>
                               <Input v-model="item.satuan" class="w-full text-xs" />
                            </div>
                         </div>

                         <div v-if="item.tipe_input === 'image'" class="bg-blue-50 p-3 rounded border border-blue-100">
                            <label class="text-xs font-bold text-blue-700 block mb-2">Upload Gambar Diagram:</label>
                            <input type="file" @input="item.gambar_file = $event.target.files[0]" accept="image/*" class="text-xs" />
                               <div v-if="item.gambar_referensi_path && !item.gambar_file" class="mt-2">
                                  <p class="text-[10px] text-blue-600 mb-1">Gambar saat ini:</p>
                                  <img :src="`/storage/${item.gambar_referensi_path}`" alt="Diagram Referensi" class="max-w-xs rounded border" />
                               </div>
                         </div>

                         <div v-if="item.tipe_input === 'table'" class="bg-yellow-50 p-3 rounded border border-yellow-100 flex justify-between items-center">
                            <p class="text-xs text-yellow-800 font-medium">Item ini bertipe Tabel Kompleks.</p>
                            <button type="button" @click="setupTableConfig(cIdx, iIdx)" class="text-xs bg-yellow-200 px-3 py-1 rounded hover:bg-yellow-300 text-yellow-900 font-bold">Config</button>
                         </div>

                      </div>

                      <button @click.prevent="addItem(cIdx)" type="button" class="w-full py-2 border border-dashed border-gray-300 text-gray-500 text-sm hover:bg-white hover:text-blue-600 hover:border-blue-300 transition-all rounded">
                         + Tambah Item di "{{ cat.name }}"
                      </button>
                   </div>

                </div>
            </div>

            <div class="mt-10 pt-6 border-t border-gray-200 flex gap-2">
               <ButtonBlue :disabled="form.processing" @click.prevent="submit">
                  {{ form.processing ? 'Menyimpan...' : 'Simpan' }}
               </ButtonBlue>
               <Button class="border">
                  <Link :href="route('master-pedoman.index')">Batal</Link>
               </Button>
            </div>

        </template>
      </Card>
    </div>
  </DashboardLayout>
</template>
