<script setup>
import { nextTick, getCurrentInstance, ref } from 'vue';
import Button from '@/Components/Button.vue'
import Icon from '@/Components/Icon.vue'
import Modal from '@/Components/Modal.vue'
import Card from '@/Components/Card.vue';
import ButtonGreen from '@/Components/Button/Green.vue'
import ButtonRed from '@/Components/Button/Red.vue'
import Close from '@/Components/Button/Close.vue'
import Builder from '@/Components/DataTable/Builder.vue'
import Th from '@/Components/DataTable/Th.vue'
import FileUpload from 'vue-upload-component'
import { useForm } from '@inertiajs/inertia-vue3';
import Swal from 'sweetalert2'
import Input from '@/Components/Input.vue'

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
</script>

<style @scoped>
  tr > th  {
    @apply p-1 bg-gray-200 border-gray-300 border-2 uppercase
  }

  tr > td {
    @apply p-1 text-center
  }

  .file-upload {
    cursor: pointer !important;
  }
</style>

<template>
 
  <Button
    @click.prevent="open = ! open"
    class="text-slate-700 text-sm font-semibold hover:bg-blue-100 hover:text-blue-700 hover:border-blue-100 focus:bg-blue-200 focus:text-blue-800 h-8"
  >

    <p class="capitalize font-semibold whitespace-nowrap">
      {{ __('Lampiran') }}
    </p>
  </Button>

  <Modal :show="open">
    <Card class="bg-white w-10/12 max-w-7xl shadow-xl">
      <template #header>
        <div class="flex items-center border-b h-[70px] mb-[30px] bg-white justify-end rounded-t-lg p-2 pb-[0.938rem] pt-[0.938rem]">
          <h2 class="font-bold text-xl w-full ml-10 capitalize">
            {{ __('Lampiran terkait ') }} "{{ redaction }}"
          </h2>

          <!-- <Close @click.prevent="open = false" /> -->
          <button class="pr-5">
                <svg @click.prevent="open = false" class="w-6 h-6 text-gray-700 hover:text-cyan-500 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>
                </svg>
          </button>
        </div>
      </template>

      <template #body>
        <!-- Drag List --> 
        <div class="w-full pb-8 ml-[50px]"><h2 class="text-xl font-bold capitalize">  Uplod File
        </h2><h3 class="text-md text-slate-600">Silahkan pilih file yang ingin di uplod</h3></div>
        <div class="w-full h-[20%] flex items-center justify-center">
        <div class="rounded-md border-2 border-sky-400 border-dashed w-[70%] h-full"
          >
            <div v-if="form.attachment.length > 0" class="h-full flex-col flex items-center justify-center">
              <div class="overflow-auto max-h-[14rem]">
                <table>
                  <tbody>
                    <tr v-for="(file, i) in form.attachment" 
                    :key="file.name"
                    :index="i"
                    :class="{ 'text-red-600': file.size > (16 * 1048576) }"
                    >
                      <td>{{ file.name }}</td>
                      <td class="font-semibold">{{ humanizeFileSize(file.size, 2) }}</td>
                     
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="flex justify-center">
                <p v-if="form.attachment.filter(f => f.size > (16 * 1048576)).length > 0" class="text-red-600">
                  {{ __('Terdapat file melebihi batas ukuran maksimal. Ukuran maksimal file = 16 MB.') }}
                </p>
              </div>
            </div>
            <file-upload
              v-else
              class="file-upload w-full h-full rounded-md hover:bg-sky-100"
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
                <div v-if="$refs.upload && $refs.upload?.dropActive" class="flex flex-col items-center justify-center h-full drop-active bg-slate-100">
                  <Icon name="file-export" class="text-4xl" />
                  <p class="text-lg font-semibold">
                    {{ __('Drop disini') }}
                  </p>
                </div>
                <div v-else class="flex flex-col items-center justify-center h-full">
                  <!-- <Icon name="file-upload" class="text-4xl" /> -->
                  <img src="../../../../public/assets/folder.png" class="w-[50px]" alt="">
                  <p class="text-lg font-semibold pt-2">
                    {{ __('Klik atau drag file kesini') }}
                  </p>
                  <p class="text-md text-slate-700">Max file adalah 16 mb</p>
                </div>
              </transition>
            </file-upload>
          </div>
        </div>
        <!-- Text Area keterangan -->
        <div v-for="(file, i) in form.attachment" 
                    :key="file.name"
                    :index="i" class="flex items-center justify-center w-full mt-5" >
        <td> <Input class="w-[400px] h-[50px]" v-model="form.attachment[i].description" type="text" :placeholder="__('Tambah Keterangan Bila dibutuhkan')" /></td>
        </div>
        <!-- button close & uplod -->
        <div class="w-full h-[130px] flex items-center justify-end space-x-5 pr-[160px]">
        <div v-for="(file, i) in form.attachment" 
                    :key="file.name"
                    :index="i">
        <Button @click.prevent="cancelFile(file)"
         class="bg-red-500 hover:bg-red-700 w-[120px] h-[35px] rounded-md flex items-center justify-center" >
         <!-- <Icon name="trash" /> -->
         <svg class="w-4 h-4 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
         <path fill-rule="evenodd" d="M8.586 2.586A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4a2 2 0 0 1 .586-1.414ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z" clip-rule="evenodd"/>
         </svg>
         <p class="uppercase font-semibold text-white">{{ __('hapus') }}</p>
         </Button>
        </div>
         <ButtonGreen class="w-fit" v-if="!$refs.upload || !$refs.upload.active" 
                  @click.prevent="submit"
                  :disabled="form.attachment.filter(f => f.size > (16 * 1048576)).length > 0"
                  :class="{ 'opacity-50 cursor-not-allowed': form.attachment.filter(f => f.size > (16 * 1048576)).length > 0 }"
                >
                  <Icon name="upload" />
                  <p class="uppercase font-semibold">{{ __('upload') }}</p>
           </ButtonGreen>
          </div>
        <!-- End Drag  -->
        <div class="flex flex-col space-y-2 p-3 h-full overflow-auto rounded-md">
          <!-- List Section -->
          <div class="rounded-md">
            <Card>
              <template #header>
                <div class="flex-row items-center space-x-1 ml-[50px] border-b-gray-400 ">
                  <h2 class="text-xl font-bold capitalize">
                    {{ __('Daftar Lampiran yang telah di upload') }}
                  </h2>
                  <h3 class="text-md text-slate-600">Daftar tabel yang telah di uplod</h3>
                </div>
              </template>

              <template #body>
                <div class="flex flex-col p-2">
                  <Builder v-if="render" :url="route('attachment.paginate')" :request="{ model_id : model.id, model_type : type}" ref="table">
                    <template #thead="table">
                      <tr>
                        <Th
                          :table="table"
                          :sort="false"
                          class="border px-3 py-2 text-center capitalize font-bold pl-[2.75rem] pb-[1.5rem]"
                          >
                          {{ __('no') }}
                         </Th>
                        <Th :table="table" :sort="true" name="filename" class="px-3 py-2">
                          {{ __('nama') }}
                        </Th>

                        <Th :table="table" :sort="true" name="size" class="px-3 py-2">
                          {{ __('ukuran') }}
                        </Th>

                        <Th :table="table" :sort="true" name="description" class="px-3 py-2">
                          {{ __('deskripsi') }}
                        </Th>

                        <Th :table="table" :sort="true" name="uploader_id" class="px-3 py-2">
                          {{ __('pengunggah') }}
                        </Th>

                        <Th :table="table" :sort="false" class="px-3 py-2">
                          {{ __('Action') }}
                        </Th>
                      </tr>
                    </template>

                    <template #tfoot="table">
                      <tr>
                        <Th
                          :table="table"
                          :sort="false"
                           class="border px-3 py-2 text-center capitalize font-bold pl-[2.75rem] pb-[1.5rem]"
                          >
                    {{ __('no') }}
                    </Th>

                        <Th :table="table" :sort="false" class="px-3 py-2">
                          {{ __('nama') }}
                        </Th>

                        <Th :table="table" :sort="false" class="px-3 py-2 whitespace-nowrap">
                          {{ __('ukuran') }}
                        </Th>

                        <Th :table="table" :sort="false" class="px-3 py-2">
                          {{ __('deskripsi') }}
                        </Th>

                        <Th :table="table" :sort="false" class="px-3 py-2">
                          {{ __('pengunggah') }}
                        </Th>

                        <Th :table="table" :sort="false" class="px-3 py-2">
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
                            <td class="py-4 text-center text-3xl font-semibold" colspan="1000">
                              {{ __('Tidak ada lampiran yang di upload.') }}
                            </td>
                          </tr>
                        </template>

                        <template v-else>
                          <template v-for="(attachment, i) in data" :key="i" :class="processing && 'bg-gray-100'" class="transition-all duration-300 ">
                            <tr class="h-[4.375rem]">

                              <td class="px-2 py-1 text-center border-b">
                               {{ i + 1 }}
                              </td>

                              <td class="px-2 border-b whitespace-nowrap">
                                {{ new String(attachment.filename).substring(17) }}
                              </td>

                              <td class="px-2 border-b whitespace-nowrap w-full text-center">
                                {{ humanizeFileSize(attachment.size, 2) }}
                              </td>

                              <td class="px-2 border-b whitespace-nowrap text-center capitalize">
                                {{ attachment.description }}
                              </td>

                              <td class="px-2 border-b whitespace-nowrap text-center">
                                {{ attachment.uploader?.name }}
                              </td>

                              <td class="px-2 border-b whitespace-nowrap p-1">
                                <div class="flex-row space-y-5 pb-5 pt-5">

                                  <a :href="route('attachment.show', { attachment : attachment.id })" target="_blank">
                                    <Button class="bg-sky-400 hover:bg-sky-500 w-[6.25rem] flex items-center justify-center">
                                    <!-- <Icon name="eye" /> -->
                                      <p class="capitalize font-semibold">{{ __('Lihat') }}</p>
                                    </Button>
                                  </a>

                                  <Button @click.prevent="destroyFile(attachment)" v-if="hasRole(['change control', 'superuser', 'it']) && (! ['closed', 'rejected'].includes(model.status))" class="bg-red-500 w-[6.25rem] flex items-center justify-center">
                                    <!-- <Icon name="trash" /> -->
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

          <!-- Upload Section -->
          <!-- <div class="rounded-md"
          >
            <div v-if="form.attachment.length > 0" class="p-2">
              <div class="overflow-auto max-h-[14rem]">
                <table>
                  <thead>
                    <tr>
                      <th>{{ __('Nama File') }}</th>
                      <th>{{ __('Ukuran') }}</th>
                      <th>{{ __('Keterangan') }}</th>
                      <th>#</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(file, i) in form.attachment" 
                    :key="file.name"
                    :index="i"
                    :class="{ 'bg-red-300': file.size > (16 * 1048576) }"
                    >
                      <td>{{ file.name }}</td>
                      <td>{{ humanizeFileSize(file.size, 2) }}</td>
                      <td>
                        <Input v-model="form.attachment[i].description" type="text" :placeholder="__('keterangan tambahan (jika dibutuhkan)')" />
                      </td>
                      <td>
                        <Button class="bg-yellow-400 hover:bg-yellow-500 text-black" @click.prevent="cancelFile(file)">
                          <Icon name="trash" />
                          <p class="uppercase font-semibold">{{ __('hapus') }}</p>
                        </Button>
                      </td>
                    </tr>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th>{{ __('Nama File') }}</th>
                      <th>{{ __('Ukuran') }}</th>
                      <th>{{ __('Keterangan') }}</th>
                      <th>#</th>
                    </tr>
                  </tfoot>
                </table>
              </div>
              <div class="flex flex-col justify-center items-end space-x-2 mt-4">
                <ButtonGreen class="w-fit" v-if="!$refs.upload || !$refs.upload.active" 
                  @click.prevent="submit"
                  :disabled="form.attachment.filter(f => f.size > (16 * 1048576)).length > 0"
                  :class="{ 'opacity-50 cursor-not-allowed': form.attachment.filter(f => f.size > (16 * 1048576)).length > 0 }"
                >
                  <Icon name="upload" />
                  <p class="uppercase font-semibold">{{ __('upload') }}</p>
                </ButtonGreen>
                <ButtonRed class="w-fit" v-else @click.prevent="$refs.upload.active = false">
                  <Icon name="ban" />
                  <p class="uppercase font-semibold">{{ __('hentikan') }}</p>
                </ButtonRed>
              </div>
              <div>
                <p v-if="form.attachment.filter(f => f.size > (16 * 1048576)).length > 0" class="text-red-500">
                  {{ __('Terdapat file melebihi batas ukuran maksimal. Ukuran maksimal file = 16 MB.') }}
                </p>
              </div>
            </div>
            <file-upload
              v-else
              class="file-upload w-full h-full rounded-md hover:bg-slate-200"
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
                <div v-if="$refs.upload && $refs.upload?.dropActive" class="flex flex-col items-center justify-center h-full drop-active bg-slate-100">
                  <Icon name="file-export" class="text-4xl" />
                  <p class="text-lg font-semibold">
                    {{ __('Drop disini') }}
                  </p>
                </div>
                <div v-else class="flex flex-col items-center justify-center h-full">
                  <Icon name="file-upload" class="text-4xl" />
                  <p class="text-lg font-semibold">
                    {{ __('Klik atau drag file kesini') }}
                  </p>
                </div>
              </transition>
            </file-upload>
          </div> -->
        </div>
      </template>
    </Card> 
  </Modal>
</template>