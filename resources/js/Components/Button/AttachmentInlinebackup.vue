<script setup>
import { nextTick, getCurrentInstance, ref } from 'vue';
import Button from '@/Components/Button.vue'
import Icon from '@/Components/Icon.vue'
import Card from '@/Components/Card.vue' // Tetap diimpor, tapi penggunaannya dihilangkan di template
import ButtonGreen from '@/Components/Button/Green.vue'
import ButtonRed from '@/Components/Button/Red.vue'
import Builder from '@/Components/DataTable/BuilderAttachment.vue'
import FileUpload from 'vue-upload-component'
import { useForm } from '@inertiajs/inertia-vue3';
import Swal from 'sweetalert2'
import Input from '@/Components/Input.vue'

const props = defineProps({
  model: Object,
  type: String,
  redaction: String,
})
const { model, type, redaction } = props

const render = ref(true)
const upload = ref(null)

// Memaksa rebuild tabel untuk memuat data baru
const refresh = async () => {
  render.value = false
  await nextTick()
  render.value = true
}

// ... Fungsi form, cancelFile, destroyFile, submit, humanizeFileSize, preview (TIDAK BERUBAH) ...
const form = useForm({
  model_id: model.id,
  model_type: type,
  attachment: [],
})

const cancelFile = file => {
  form.attachment = form.attachment.filter(f => f.name !== file.name)
}

const destroyFile = async file => {
  const { isConfirmed } = await Swal.fire({
    title: 'Hapus Lampiran?',
    text: 'File ini akan dihapus permanen. Apakah Anda yakin?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Ya, Hapus!',
    cancelButtonText: 'Batal',
    confirmButtonColor: '#ef4444',
    cancelButtonColor: '#6b7280',
  })

  if (!isConfirmed) return

  await form.delete(route('attachment.destroy', file.id), {
    onSuccess: refresh,
  })
}

const submit = () => {
  Swal.fire({
    title: 'Mengunggah File...',
    text: 'Mohon tunggu sebentar.',
    allowOutsideClick: false,
    didOpen: () => Swal.showLoading(),
  })

  if (!form.model_id || !form.model_type) {
    Swal.fire('Gagal!', 'Data model tidak lengkap.', 'error');
    return;
  }

  form.post(route('attachment.upload'), {
    forceFormData: true,
    onSuccess: () => {
      form.reset()
      form.model_id = model.id
      refresh()
      Swal.fire('Berhasil!', 'Lampiran berhasil diunggah.', 'success')
    },
    onError: () => Swal.fire('Gagal!', 'Terjadi kesalahan saat mengunggah file.', 'error'),
  })
}

const humanizeFileSize = (size, decimals = 2) => {
  if (!size) return '0 B'
  const k = 1024
  const dm = decimals < 0 ? 0 : decimals
  const sizes = ['B', 'KB', 'MB', 'GB']
  const i = Math.floor(Math.log(size) / Math.log(k))
  return `${parseFloat((size / Math.pow(k, i)).toFixed(dm))} ${sizes[i]}`
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
// -----------------------------------------------------------------------

</script>

<template>
  <div class="w-full bg-white rounded">
    <!-- <div class="pb-2 border-b border-gray-200">
      <h2 class="font-extrabold text-sm flex items-center text-gray-800">
        <Icon name="paperclip" class="h-4 w-4 mr-1 text-sky-500" />
        <span class="text-sky-600 ">{{ redaction }}</span>
      </h2>
      <p class="text-xs text-gray-500">
        Unggah file yang diperlukan. Maksimum 5MB per file.
      </p>
    </div> -->

    <div class="border-2 border border-sky-400 rounded-lg p-2 transition duration-300 ease-in-out hover:border-sky-600 hover:bg-sky-50">
      <file-upload
        class="file-upload w-full rounded-lg cursor-pointer"
        :post-action="route('attachment.upload')"
        :multiple="true"
        :drop="true"
        v-model="form.attachment"
        :size="5 * 1048576"
        ref="upload"
      >
        <transition
        >
          <div
            v-if="$refs.upload && $refs.upload.dropActive"
            class="flex items-center justify-center h-full text-blue-700 space-x-2"
          >
            <Icon name="cloud-upload" class="w-5 h-5 animate-bounce" />
            <p class="font-semibold text-xs">Lepaskan file di sini...</p>
          </div>
          <div
            v-else
            class="flex items-center justify-center h-full text-gray-600 space-x-1"
          >
            <Icon name="file-upload" class="text-sky-500 w-4 h-4" />
            <p class="font-semibold text-xs text-center">Klik untuk memilih file atau seret dan lepas</p>
          </div>
        </transition>
      </file-upload>
    </div>

    <div
      v-if="form.attachment.length > 0"
      class="p-1 bg-white rounded-lg "
    >

      <div
        v-for="(file, i) in form.attachment"
        :key="file.name"
        class="flex flex-col sm:flex-row sm:items-center justify-between bg-white p-1"
      >
        <div class="flex-1 text-xs text-gray-700 break-words flex items-center">
            <Icon name="document" class="w-1 h-1 mr-1 text-sky-600" />
            <div class="truncate">
                <div class="font-medium truncate">{{ file.name }}</div>
                <div
                    :class="[
                        'text-[10px]',
                        file.size > (5 * 1048576) ? 'text-red-500 font-bold' : 'text-gray-500'
                    ]"
                >
                    {{ humanizeFileSize(file.size, 1) }}
                    <span v-if="file.size > (5 * 1048576)" class="ml-1">(Terlalu Besar)</span>
                </div>
            </div>
        </div>

        <div class="w-full sm:w-20 my-1 sm:my-0 sm:mx-2">
          <Input
            v-model="form.attachment[i].description"
            class="w-full text-[10px] py-1"
            type="text"
            placeholder="Keterangan (opsional)"
          />
        </div>

        <ButtonRed
          class="flex items-center space-x-1 px-3 py-1 text-xs"
          @click.prevent="cancelFile(file)"
        >
          <Icon name="trash" class="w-3 h-3 mr-1" /> Hapus
        </ButtonRed>
      </div>

      <div class="flex justify-end  pt-2 sm:w-20 my-1 sm:my-0 sm:mx-2">
        <ButtonGreen
          @click.prevent="submit"
          :disabled="form.processing || form.attachment.some(f => f.size > (5 * 1048576))"
          class="flex items-center  text-xs"
        >
          <Icon name="cloud-upload" class="w-3 h-3 mr-1" />
          <span>Unggah ({{ form.attachment.length }})</span>
        </ButtonGreen>
      </div>
    </div>

    <div class="w-full pt-2">
      <div class="w-full overflow-x-auto rounded-lg border border-gray-200 mt-2"> 
        <Builder
            v-if="render"
            :url="route('attachment.paginate')"
            :request="{ model_id: model.id, model_type: type }"
          >

          <template #thead>
                    <tr class="bg-gray-100 text-black text-xs sm:text-sm">
                        <th class="w-8 border text-center text-xs font-semibold">No</th> 
                        <th class="text-center border text-xs font-semibold">Nama File</th>
                        <th class="text-center border text-xs font-semibold">Ukuran</th>
                        <th class="text-center border text-xs font-semibold">Deskripsi</th>
                        <th class="text-center border text-xs font-semibold">Pengunggah</th>
                        <th class="text-center border text-xs font-semibold">Aksi</th> </tr>
                </template>

          <template #tbody="{ data, empty }">
            <tr v-if="empty">
              <td colspan="6" class="text-center py-3 text-gray-500 bg-white text-xs italic">
                  Belum ada lampiran diunggah.
              </td>
            </tr>
            <tr
              v-for="(attachment, i) in data"
              :key="attachment.id"
              class="text-xs border border-gray-200 hover:bg-sky-50 transition duration-150 ease-in-out" >
              <td class="text-center border border-gray-200 text-gray-600 font-medium p-1 text-[10px]">{{ i + 1 }}</td> 
              <td class="text-center break-all border border-gray-200 p-1 text-[10px]">
                <Icon name="document" class="inline w-3 h-3 mr-1 text-sky-600" />
                {{ attachment.filename.substring(17) }}
              </td>
              <td class="text-center border border-gray-200 text-gray-700 p-1 text-[10px]">{{ humanizeFileSize(attachment.size, 1) }}</td>
              <td class="text-center italic border border-gray-200 text-gray-500 p-1 text-[10px] truncate max-w-[120px]">{{ attachment.description || '-' }}</td>
              <td class="text-center border border-gray-200 text-gray-700 p-1 text-[10px]">{{ attachment.uploader?.name || 'Sistem' }}</td>
              <td class="text-center flex justify-center space-x-1 p-1"> 
                <Button class="bg-sky-600 hover:bg-sky-700 text-white w-5 h-5 flex items-center justify-center p-0.5 rounded transition duration-150" @click.prevent="openPreview(attachment)"> 
                  <Icon name="eye" class="w-4 h-4" />
                </Button>
                <ButtonRed class="w-5 h-5 flex items-center justify-center p-0.5 rounded transition duration-150" @click.prevent="destroyFile(attachment)"> 
                  <Icon name="trash" class="w-4 h-4" />
                </ButtonRed>
              </td>
            </tr>
          </template>
        </Builder>
      </div>
    </div>
  </div>
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

</template>