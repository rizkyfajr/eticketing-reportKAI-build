<script setup>
import { nextTick, getCurrentInstance, ref } from 'vue';
import Button from '@/Components/Button.vue'
import Icon from '@/Components/Icon.vue'
import Modal from '@/Components/Modal.vue'
import Card from '@/Components/Card.vue';
import ButtonGreen from '@/Components/Button/Green.vue'
import ButtonRed from '@/Components/Button/Red.vue'
import Close from '@/Components/Button/Close.vue'
import Builder from '@/Components/DataTable/BuilderAttachment.vue'
import Th from '@/Components/DataTable/Th.vue'
import FileUpload from 'vue-upload-component'
import { useForm } from '@inertiajs/inertia-vue3';
import Swal from 'sweetalert2'
import Input from '@/Components/Input.vue'

// Asumsi fungsi hasRole ada di global scope atau composable
// const { hasRole } = useAuth(); 

const { model, type, redaction, closed } = defineProps({
  model: Object,
  type: String,
  redaction: String,
  closed: Boolean,
})

const attachments = ref(model.attachments)
const self = getCurrentInstance()
const upload = ref(null)

const render = ref(true)
const refresh = async () => {
  render.value = false
  await nextTick()
  render.value = true
}

const form = useForm({
  model_id: model.id,
  model_type: type,
  attachment: [],
})

const open = ref(false)
const previewOpen = ref(false)
const currentPreview = ref(null)

const cancelFile = file => {
  form.attachment = form.attachment.filter(f => f.name !== file.name)
}

const destroyFile = async file => {
  const { isConfirmed } = await Swal.fire({
    title: 'Apakah anda yakin?',
    text: 'Anda tidak akan dapat mengembalikan file yang telah dihapus!',
    icon: 'question',
    showCloseButton: true,
    showCancelButton: true,
  })

  if (!isConfirmed) return

  if (isConfirmed) {
    try {
      const response = form.delete(route('attachment.destroy', file.id), {
        onSuccess: () => {
          refresh()
          self.emit('refresh')
        },
        onError: e => {
          console.log(e)
        },
      })
    } catch (e) {
      const { isConfirmed } = await Swal.fire({
        title: 'error',
        text: `${e}`,
        icon: 'error',
        showCancelButton: true,
        showCloseButton: true,
      })

      if (isConfirmed) {
        return await destroyFile(file)
      }
    }
  }
}

const submit = () => {
  Swal.showLoading()
  form.post(route('attachment.upload'), {
    forceFormData: true,
    onSuccess: () => {
      form.reset()
      form.model_id = model.id
    },
    onError: e => {
      console.log(e)
    },
    onFinish: () => {
      refresh()
      self.emit('refresh')
    }
  })
}

// Fungsi untuk membuat ukuran file lebih mudah dibaca (Asumsi, karena tidak ada di kode awal)
const humanizeFileSize = (bytes, decimals = 2) => {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}

// Fungsi untuk membuka pratinjau (preview)
// const openPreview = (attachment) => {
//     currentPreview.value = attachment;
//     previewOpen.value = true;
// }

// Fungsi bantu untuk menentukan apakah file adalah gambar (untuk pratinjau)
const isImage = (fileName) => {
    return /\.(jpe?g|png|gif|webp)$/i.test(fileName);
}

// Fungsi bantu untuk menentukan apakah file adalah PDF (untuk pratinjau)
const isPdf = (fileName) => {
    return /\.pdf$/i.test(fileName);
}

const previewFile = ref(null)
const previewVisible = ref(false)

const openPreview = (attachment) => {
  previewFile.value = {
    // Menghilangkan substring(17) agar nama file lebih lengkap
    name: attachment.filename.substring(17), 
    url: route('attachment.show', { attachment: attachment.id }),
    type: attachment.mime_type || attachment.filename.split('.').pop(),
  }
  previewVisible.value = true
}

const closePreview = () => {
  previewVisible.value = false
  previewFile.value = null
}
</script>

<style @scoped>
  /* Mengurangi ukuran font dan padding agar lebih kecil */
  tr > th  {
    @apply p-1 bg-gray-200 border-gray-300 border-2 uppercase text-xs; 
  }

  tr > td {
    @apply p-1 text-center text-xs; /* Mengurangi ukuran teks pada sel tabel lebih kecil lagi */
  }

  .file-upload {
    cursor: pointer !important;
  }
</style>

<template>
  
  <Button
    @click.prevent="open = ! open"
    class="
      flex items-center justify-center 
      px-2 py-1 
      text-xs font-semibold rounded-md 
      text-white 
      bg-blue-600 
      hover:bg-blue-700 
      focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none
      whitespace-nowrap
    "
  >
    <Icon name="cloud-upload" class="w-3 h-3 mr-1" />
    <span class="capitalize">
      {{ __('Upload') }}
    </span>
  </Button>

  <Modal :show="open">
    <div 
        class="bg-white w-full sm:w-10/12 md:w-7/12 lg:w-6/12 rounded-xl shadow-xl overflow-hidden"
      >
    <Card>
      <template #header>
        <div class="flex items-center border-b h-12 sm:h-14 bg-white justify-end rounded-t-lg p-2 pb-3 pt-3">
          <h2 class="font-bold text-sm sm:text-lg md:text-xl w-full ml-2 capitalize truncate">
            {{ redaction }}
          </h2>

          <button class="pr-3">
              <svg @click.prevent="open = false" class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 text-gray-700 hover:text-cyan-500 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>
              </svg>
          </button>
        </div>
      </template>

      <template #body>        
        <div class="w-full h-auto flex items-center justify-center py-2">
          <div class="rounded-md border-2 border-sky-400 border-dashed w-11/12 max-w-md h-full min-h-[120px] sm:min-h-[150px]"
            >
              <div v-if="form.attachment.length > 0" class="h-full flex-col flex items-center justify-center p-2">
                <div class="overflow-auto max-h-[8rem] sm:max-h-[10rem] w-full">
                  <table class="w-full">
                    <tbody class="text-xs">
                      <tr v-for="(file, i) in form.attachment" 
                      :key="file.name"
                      :index="i"
                      :class="{ 'text-red-600': file.size > (16 * 1048576) }"
                      >
                        <td class="text-left w-2/3 truncate">{{ file.name }}</td>
                        <td class="font-semibold text-right w-1/3">{{ humanizeFileSize(file.size, 2) }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="flex justify-center mt-2">
                  <p v-if="form.attachment.filter(f => f.size > (16 * 1048576)).length > 0" class="text-red-600 text-xs text-center">
                    {{ __('Terdapat file melebihi batas ukuran maksimal. Ukuran maksimal file = 16 MB.') }}
                  </p>
                </div>
              </div>
              <file-upload
                v-else
                class="file-upload w-full h-full rounded-md hover:bg-sky-100 min-h-[120px] flex items-center justify-center"
                :post-action="route('attachment.upload')"
                :multiple="true"
                :drop="true"
                v-model="form.attachment"
                :size="16 * 1048576"
                ref="upload"
              >
                <transition
                  enterActiveClass="transition-all duration-400"
                  leaveActiveClass="transition-all duration-200"
                  enterFromClass="opacity-100"
                  leaveToClass="opacity-0"
                >
                  <div v-if="$refs.upload && $refs.upload?.dropActive" class="flex flex-col items-center justify-center h-full drop-active bg-slate-100 p-3">
                    <Icon name="file-export" class="text-2xl" />
                    <p class="text-sm font-semibold text-center">
                      {{ __('Drop disini') }}
                    </p>
                  </div>
                  <div v-else class="flex flex-col items-center justify-center h-full p-3">
                    <img src="../../../../public/assets/folder.png" class="w-[30px]" alt="">
                    <p class="text-sm font-semibold pt-1 text-center">
                      {{ __('Klik atau drag file kesini') }}
                    </p>
                    <p class="text-xs text-slate-700">Max file adalah 16 mb</p>
                  </div>
                </transition>
              </file-upload>
            </div>
        </div>
        
        <div class="px-5 sm:px-10 mt-3 space-y-2">
          <div v-for="(file, i) in form.attachment" 
              :key="file.name"
              :index="i" class="flex flex-col items-center justify-center w-full border p-2 rounded-md border-gray-200" >
            <p class="w-full text-xs font-medium truncate mb-1 text-center">{{ file.name }}</p>
            <div class="flex flex-col w-full space-y-2">
              <Input class="w-full h-7 text-xs" v-model="form.attachment[i].description" type="text" :placeholder="__('Tambah Keterangan Bila dibutuhkan')" />
              <Button @click.prevent="cancelFile(file)"
                class="bg-red-500 hover:bg-red-700 w-full h-7 rounded-md flex items-center justify-center text-xs p-1" >
                <svg class="w-3 h-3 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd" d="M8.586 2.586A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4a2 2 0 0 1 .586-1.414ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z" clip-rule="evenodd"/>
                </svg>
                <p class="uppercase font-semibold text-white ml-1">{{ __('hapus') }}</p>
              </Button>
            </div>
          </div>
        </div>

        <div class="w-full h-auto flex items-center justify-center p-5 pt-3">
          <Button class="bg-green-500 hover:bg-green-700 w-full h-7 rounded-md flex items-center justify-center text-xs p-1" v-if="!$refs.upload || !$refs.upload.active" 
              @click.prevent="submit"
              :disabled="form.attachment.filter(f => f.size > (16 * 1048576)).length > 0 || form.attachment.length === 0"
              :class="{ 'opacity-50 cursor-not-allowed': form.attachment.filter(f => f.size > (16 * 1048576)).length > 0 || form.attachment.length === 0 }"
            >
              <Icon name="upload" />
              <p class="uppercase font-semibold">{{ __('upload') }}</p>
          </Button>
        </div>
        
        <div class="flex flex-col space-y-2 p-2">
          <div class="rounded-md">
            <Card>
              <template #body>
                <div class="flex flex-col p-1 overflow-x-auto">
                  <Builder v-if="render" :url="route('attachment.paginate')" :request="{ model_id : model.id, model_type : type}" ref="table" class="min-w-full">
                    <template #thead="table">
                      <tr class="text-xs">
                        <Th
                          :table="table"
                          :sort="false"
                          class="border px-1 py-1 text-center font-bold w-[40px]"
                          >
                          {{ __('no') }}
                         </Th>

                        <Th :table="table" :sort="false" name="description" class="px-1 py-1 text-xs w-[40%] sm:w-auto">
                          {{ __('deskripsi') }}
                        </Th>

                        <Th :table="table" :sort="false" name="uploader_id" class="px-1 py-1 text-xs hidden sm:table-cell">
                          {{ __('pengunggah') }}
                        </Th>

                        <Th :table="table" :sort="false" class="px-1 py-1 text-xs w-[40%] sm:w-auto">
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
                            <td class="py-4 text-center text-sm font-semibold" colspan="1000">
                              {{ __('Tidak ada lampiran yang di upload.') }}
                            </td>
                          </tr>
                        </template>

                        <template v-else>
                          <template v-for="(attachment, i) in data" :key="i" :class="processing && 'bg-gray-100'" class="transition-all duration-300 ">
                            <tr class="h-10 text-xs">

                              <td class="px-1 py-1 text-center border-b">
                               {{ i + 1 }}
                              </td>

                              <td class="px-1 border-b text-center capitalize truncate max-w-[150px]">
                                {{ attachment.description }}
                              </td>

                              <td class="px-1 border-b text-center truncate max-w-[100px] hidden sm:table-cell">
                                {{ attachment.uploader?.name }}
                              </td>

                              <td class="px-1 border-b text-center p-1">
                                <div class="flex flex-col space-y-1 py-1 items-center justify-center">

                                  <Button 
                                    @click.prevent="openPreview(attachment)"
                                    class="bg-sky-400 hover:bg-sky-500 w-full h-6 flex items-center justify-center text-xs p-1">
                                    <p class="capitalize font-semibold">{{ __('Preview') }}</p>
                                  </Button>

                                  <Button @click.prevent="destroyFile(attachment)" 
                                      v-if="hasRole(['change control', 'superuser', 'it']) && (! ['closed', 'rejected'].includes(model.status))" 
                                      class="bg-red-500 w-full h-6 flex items-center justify-center text-xs p-1">
                                    <p class="capitalize font-semibold ">
                                      {{ __('Hapus') }}
                                    </p>
                                  </Button>
                                </div>
                              </td>
                            </tr>
                          </template>
                        </template>
                      </TransitionGroup>
                    </template>
                  </Builder>
                </div>
              </template>
            </Card>
          </div>
        </div>
      </template>
    </Card> 
    </div>

    <!-- Preview -->
    <div
      v-if="previewVisible"
      class="fixed inset-0 bg-black bg-opacity-70 z-50 flex items-center justify-center p-4"
    >
      <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl relative">
        <div class="flex justify-between items-center p-3 border-b">
          <h3 class="font-bold text-gray-700 text-base sm:text-lg">
            Pratinjau: {{ previewFile?.name }}
          </h3>
          <button
            class="text-gray-500 hover:text-red-500 transition"
            @click="closePreview"
          >
            ✕
          </button>
        </div>

        <div class="p-4 overflow-auto max-h-[80vh]">
          <iframe
            v-if="previewFile && previewFile.name.endsWith('.pdf')"
            :src="previewFile.url"
            class="w-full h-[70vh] rounded-md"
          ></iframe>

          <img
            v-else-if="previewFile && ['jpg','jpeg','png','gif','bmp','webp'].some(ext => previewFile.name.toLowerCase().endsWith(ext))"
            :src="previewFile.url"
            alt="Preview"
            class="max-h-[70vh] mx-auto rounded-md"
          />

          <div v-else class="text-center text-gray-600 py-10">
            <p>Preview tidak tersedia untuk jenis file ini.</p>
            <a
              :href="previewFile?.url"
              target="_blank"
              class="text-sky-600 font-semibold hover:underline"
            >
              Klik di sini untuk membuka file
            </a>
          </div>
        </div>

        <div class="border-t p-3 flex justify-end space-x-2">
          <a
            :href="previewFile?.url"
            download
            class="bg-sky-500 hover:bg-sky-600 text-white px-4 py-2 rounded-md text-sm"
          >
            <Icon name="download" class="inline w-4 h-4 mr-1" /> Unduh
          </a>
          <button
            @click="closePreview"
            class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md text-sm"
          >
            Tutup
          </button>
        </div>
      </div>
    </div>    
    <!-- Preview -->

  </Modal>  
</template>