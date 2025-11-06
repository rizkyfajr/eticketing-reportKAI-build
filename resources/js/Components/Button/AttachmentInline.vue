<script setup>
import { nextTick, getCurrentInstance, ref } from 'vue';
import Button from '@/Components/Button.vue'
import Icon from '@/Components/Icon.vue'
import Card from '@/Components/Card.vue'
import ButtonGreen from '@/Components/Button/Green.vue'
import ButtonRed from '@/Components/Button/Red.vue'
import Builder from '@/Components/DataTable/Builder.vue'
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

<template>
  <div class="w-full space-y-6 mt-3 sm:mt-5 bg-white rounded-2xl shadow-md p-4 sm:p-6 overflow-x-hidden">
    <div class="pb-3 border-b border-gray-200">
      <h2 class="font-extrabold text-lg sm:text-xl flex items-center text-gray-800">
        <Icon name="paperclip" class="h-5 w-5 sm:h-6 sm:w-6 mr-2 text-sky-500" />
        <span class="text-sky-600 ml-1">{{ redaction }}</span>
      </h2>
      <p class="text-xs sm:text-sm text-gray-500 mt-1">
        Unggah file yang diperlukan di sini. Pastikan ukuran file tidak melebihi 5MB.
      </p>
    </div>

    <!-- AREA UPLOAD -->
    <div class="border-2 border-dashed border-sky-400 rounded-xl p-4 sm:p-6 transition duration-300 ease-in-out hover:border-sky-600 hover:bg-sky-50">
      <file-upload
        class="file-upload w-full h-32 sm:h-36 rounded-lg cursor-pointer"
        :post-action="route('attachment.upload')"
        :multiple="true"
        :drop="true"
        v-model="form.attachment"
        :size="5 * 1048576"
        ref="upload"
      >
        <transition
          enterActiveClass="transition-all duration-300"
          leaveActiveClass="transition-all duration-200"
          enterFromClass="opacity-0 scale-95"
          leaveToClass="opacity-0 scale-95"
        >
          <div
            v-if="$refs.upload && $refs.upload.dropActive"
            class="flex flex-col items-center justify-center h-full text-blue-700 space-y-2"
          >
            <Icon name="cloud-upload" class="text-4xl sm:text-5xl animate-bounce" />
            <p class="font-semibold text-base sm:text-lg">Lepaskan file di sini...</p>
          </div>
          <div
            v-else
            class="flex flex-col items-center justify-center h-full text-gray-600 space-y-1"
          >
            <Icon name="file-upload" class="text-3xl sm:text-4xl text-sky-500" />
            <p class="font-semibold text-sm sm:text-base text-center">Klik untuk memilih file atau seret dan lepas di sini</p>
            <p class="text-xs text-gray-500 text-center">
              Maksimum 5MB per file. Format: PDF, DOCX, JPG, PNG, dll.
            </p>
          </div>
        </transition>
      </file-upload>
    </div>

    <div
      v-if="form.attachment.length > 0"
      class="space-y-3 p-4 sm:p-5 bg-gray-50 rounded-xl shadow-inner overflow-x-auto"
    >
      <h3 class="font-semibold text-gray-700 border-b pb-2 text-sm sm:text-base">
        File Siap Diupload ({{ form.attachment.length }})
      </h3>

      <div
        v-for="(file, i) in form.attachment"
        :key="file.name"
        class="flex flex-col sm:flex-row sm:items-center justify-between bg-white p-3 sm:p-4 rounded-xl border shadow-sm hover:shadow-md transition"
      >
        <div class="flex-1 text-sm sm:text-base text-gray-700 break-words">
          <div class="font-medium">{{ file.name }}</div>
          <div
            :class="[
              'text-xs sm:text-sm',
              file.size > (5 * 1048576) ? 'text-red-500' : 'text-gray-500'
            ]"
          >
            {{ humanizeFileSize(file.size) }}
            <span v-if="file.size > (5 * 1048576)" class="font-bold ml-1">(Terlalu Besar)</span>
          </div>
        </div>

        <div class="w-full sm:w-64 my-2 sm:my-0 sm:mx-4">
          <Input
            v-model="form.attachment[i].description"
            class="w-full text-xs sm:text-sm"
            type="text"
            placeholder="Keterangan (opsional)"
          />
        </div>

        <ButtonRed
          class="text-xs sm:text-sm px-3 py-1 sm:py-2 flex items-center justify-center"
          @click.prevent="cancelFile(file)"
        >
          <Icon name="trash" class="w-4 h-4 mr-1" /> Hapus
        </ButtonRed>
      </div>

      <div class="flex justify-end border-t pt-3 mt-4">
        <ButtonGreen
          @click.prevent="submit"
          :disabled="form.processing || form.attachment.some(f => f.size > (5 * 1048576))"
          class="flex items-center space-x-2 px-4 sm:px-6 py-2 text-sm sm:text-base"
        >
          <Icon name="cloud-upload" class="w-4 h-4 sm:w-5 sm:h-5" />
          <span>Unggah ({{ form.attachment.length }})</span>
        </ButtonGreen>
      </div>
    </div>

    <Card class="mt-6 w-full">
      <template #header>
        <h3 class="font-bold text-gray-700 text-base sm:text-lg">
          Daftar Foto Silang yang Telah Diunggah
        </h3>
      </template>

      <template #body>
        <div class="w-full overflow-x-auto rounded-xl shadow-xl border border-gray-200"> 
          <Builder
                v-if="render"
                :url="route('attachment.paginate')"
                :request="{ model_id: model.id, model_type: type }"
                ref="table"
                class="min-w-[600px] sm:min-w-full"
            >
                <template #thead>
                    <tr class="bg-sky-700 text-black text-xs sm:text-sm uppercase tracking-wider shadow-md">
                        <th class="px-3 py-3 w-8 border-r text-center text-xs font-semibold">No</th> 
                        <th class="px-3 py-3 text-center border-r text-xs font-semibold">Nama File</th>
                        <th class="px-3 py-3 text-center border-r text-xs font-semibold">Ukuran</th>
                        <th class="px-3 py-3 text-center border-r text-xs font-semibold">Deskripsi</th>
                        <th class="px-3 py-3 text-center border-r text-xs font-semibold">Pengunggah</th>
                        <th class="px-3 py-3 text-center text-xs font-semibold">Aksi</th> </tr>
                </template>

                <template #tbody="{ data, empty }">
                    <tr v-if="empty">
                        <td colspan="6" class="text-center py-5 text-gray-500 bg-white text-sm italic">
                            Belum ada lampiran diunggah.
                        </td>
                    </tr>
                    <tr
                        v-for="(attachment, i) in data"
                        :key="attachment.id"
                        class="text-xs sm:text-sm border-b border-gray-200 hover:bg-sky-50 transition duration-150 ease-in-out" >
                        <td class="px-3 py-2 text-center border-r border-gray-200 text-gray-600 font-medium">{{ i + 1 }}</td> <td class="px-3 py-2 break-all border-r border-gray-200">
                            <Icon name="document" class="inline w-4 h-4 mr-1 text-sky-600" />
                            {{ attachment.filename.substring(17) }}
                        </td>
                        <td class="px-3 py-2 text-center border-r border-gray-200 text-gray-700">{{ humanizeFileSize(attachment.size) }}</td>
                        <td class="px-3 py-2 italic border-r border-gray-200 text-gray-500">{{ attachment.description || '-' }}</td>
                        <td class="px-3 py-2 text-center border-r border-gray-200 text-gray-700">{{ attachment.uploader?.name || 'Sistem' }}</td>
                        <td class="px-3 py-2 text-center flex justify-center space-x-2"> 
                          <Button class="bg-sky-600 hover:bg-sky-700 text-white text-xs px-2 py-1 rounded-md transition duration-150" @click.prevent="openPreview(attachment)"> 
                            <Icon name="eye" class="w-4 h-4" />
                            </Button>
                            <ButtonRed class="text-xs px-2 py-1 rounded-md transition duration-150" @click.prevent="destroyFile(attachment)"> 
                              <Icon name="trash" class="w-4 h-4" />
                            </ButtonRed>
                        </td>
                    </tr>
                </template>
            </Builder>
        </div>
    </template>
    </Card>
  </div>
  <!-- PREVIEW MODAL -->
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


