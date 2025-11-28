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
import Button from '@/Components/Button.vue'
import ButtonBlue from '@/Components/Button/Blue.vue'
import ButtonGreen from '@/Components/Button/Green.vue'
import ButtonRed from '@/Components/Button/Red.vue'
import Input from '@/Components/Input.vue'
import InputError from '@/Components/InputError.vue'
import Select from '@vueform/multiselect'
import TextArea from '@/Components/TextArea.vue'

const { readines } = defineProps({
    readines: Object
})

const form = useForm({
  group_name: '',
  urutan: '',
  komponen: '',
  pertanyaan: '',
})

const render = ref(true)
const self = getCurrentInstance()
const table = ref(null)
const open = ref(false)
const show = () => open.value = true

const close = () => {
    form.reset()
    render.value = false
    nextTick(() => {
        render.value = true
        nextTick(() => open.value = false)
    })
}

const store = () => {
    return form.post(route('master-readiness-assessment.store'), {
        onSuccess: () => close(),
        onError: () => show(),
    })
}

const edit = (readines) => {
    form.id = readines.id
    form.group_name = readines.group_name
    form.urutan = readines.urutan
    form.komponen = readines.komponen
    form.pertanyaan = readines.pertanyaan
    show()
}

const update = () => {
    return form.patch(route('master-readiness-assessment.update', form.id), {
        onSuccess: () => close(),
        onError: () => show(),
    })
}

const destroy = async readines => {
    const response = await Swal.fire({
        title: __('Apakah Anda Yakin') + '?',
        text: __('Anda tidak dapat mengembalikannya setelah dihapus'),
        icon: 'question',
        showCancelButton: true,
        showCloseButton: true,
    })

    if (response.isConfirmed) {
        return form.delete(route('master-readiness-assessment.destroy', readines.id), {
            onFinish: close,
        })
    }
}

const submit = () => form.id ? update() : store()

const esc = e => e.key === 'Escape' && close()
onMounted(() => window.addEventListener('keydown', esc))
onUnmounted(() => window.removeEventListener('keydown', esc))

</script>

<style src="@vueform/multiselect/themes/default.css"></style>
<style src="@/multiselect.css"></style>

<template>
    <DashboardLayout :title="__('Master Readiness Assessment')">
        <Card class="bg-white pt-[1.100rem] pb-[2.5rem] shadow-lg border border-solid border-slate-200" style="border-radius: 0.625rem;">
            <template #header>
                <!-- <h1 class="w-full flex justify-center items-center h-[80px] text-2xl font-bold">Data <span class="ml-2 mr-2"
                        style="font-family: taviraj;"></span>Divison</h1> -->
                <div class="flex items-center justify-end px-4 py-1 rounded space-x-2 p-2 pr-[1.688rem]">
                    <Button v-if="can('create readiness assessment')" @click.prevent="form.id = null; show()"
                        class="grid md:grid-cols text-center items-center bg-green-600 hover:bg-green-800"
                       >
                        <!-- <Icon name="plus" class="w-full" /> -->
                        <p class="font-bold text-xs">
                            {{ __('Tambah') }}
                        </p>
                    </Button>
                </div>
            </template>

            <template #body>
                <div class="flex flex-col space-y-1">
                    <Builder v-if="render" :url="route('master-readiness-assessment.paginate')" ref="table">
                        <template #thead="table">
                            <tr class="bg-gray-100 border-b border-gray-300">
                                <Th :table="table" :sort="false" name="id"
                                    class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                    {{ __('no').toUpperCase() }}
                                </Th>

                                <Th :table="table" :sort="false" name="group_name"
                                    class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                    {{ __('nama grup').toUpperCase() }}
                                </Th>                       

                                <Th :table="table" :sort="false" name="urutan"
                                    class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                    {{ __('urutan').toUpperCase() }}
                                </Th>  

                                <Th :table="table" :sort="false" name="komponen"
                                    class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                    {{ __('komponen').toUpperCase() }}
                                </Th>    

                                <Th :table="table" :sort="false" name="pertanyaan"
                                    class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                    {{ __('pertanyaan').toUpperCase() }}
                                </Th>                   

                                <Th :table="table" :sort="false"
                                    class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                    {{ __('Action').toUpperCase() }}
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
                                  <p class="lowercase first-letter:capitalize font-semibold text-xs">
                                    {{ __('Tidak ada data untuk ditampilkan.') }}
                                  </p>
                                </td>
                              </tr>
                            </template>

                            <template v-else>
                              <tr
                                v-for="(readiness, i) in data"
                                :key="readiness.id"
                                :class="processing && 'bg-gray-100'"
                                class="transition-all duration-300"
                              >
                                <td class="border-b uppercase border-gray-300 px-4 py-1 text-center text-xs">
                                  {{ i + 1 }}
                                </td>

                                <td class="border-b uppercase border-gray-300 px-4 py-1 text-center text-xs">
                                  {{ readiness.group_name }}
                                </td>

                                <td class="border-b uppercase border-gray-300 px-4 py-1 text-center text-xs">
                                  {{ readiness.urutan}}
                                </td>

                                <td class="border-b uppercase border-gray-300 px-4 py-1 text-center text-xs">
                                  {{ readiness.komponen }}
                                </td>

                                <td class="border-b uppercase border-gray-300 px-4 py-1 text-center text-xs">
                                  {{ readiness.pertanyaan}}
                                </td>

                                <td class="px-2 py-1 border-b text-center">
                                <div class="flex justify-center gap-2">
                                    <ButtonBlue
                                      v-if="can('update readiness assessment')"
                                      @click.prevent="edit(readiness)"
                                    >
                                      <Icon name="edit" />
                                      <p class="font-bold text-xs">{{ __('Ubah') }}</p>
                                    </ButtonBlue>

                                    <ButtonRed
                                      v-if="can('delete readiness assessment')"
                                      @click.prevent="destroy(readiness)"
                                    >
                                      <Icon name="trash" />
                                      <p class="font-bold text-xs">{{ __('Hapus') }}</p>
                                    </ButtonRed>
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
            <form @submit.prevent="submit" class="w-full max-w-4xl h-fit shadow-xl">
                <Card class="bg-gray-50">
                    <!-- Close icon -->
                    <template #header>
                        <div
                            class="flex items-center justify-end bg-gray-200 rounded-lg p-2 pb-[5px] pt-[5px]">
                            <Close @click.prevent="close" />
                        </div>
                    </template>

                    <template #body>
                        <div class="flex flex-col space-y-4 p-4">
                            <template v-if="hasRole(['superuser', 'user', 'it', 'admin']) && !regis">
                                <div class="flex flex-col space-y-2">
                                  <div class="flex items-center space-x-2">
                                      <label for="group_name" class="w-1/3 capitalize text-sm">
                                          {{ __('Nama Grup') }}
                                      </label>

                                      <Input v-model="form.group_name"
                                          :placeholder="__('Nama Grup (Engine, Mechanical dll)')" 
                                          type="text"
                                          required
                                          class="text-sm" 
                                        />
                                  </div>

                                  <InputError :error="form.errors.group_name" />
                                </div>

                                <div class="flex flex-col space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <label for="urutan" class="w-1/3 capitalize text-sm">
                                            {{ __('urutan') }}
                                        </label>

                                        <Input v-model="form.urutan"
                                            :placeholder="__('urutan')" 
                                            type="number"
                                            required
                                            class="text-sm" 
                                        />
                                    </div>

                                    <InputError :error="form.errors.urutan" />
                                </div>

                                <div class="flex flex-col space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <label for="komponen" class="w-1/3 capitalize text-sm">
                                            {{ __('Komponen') }}
                                        </label>

                                        <Input v-model="form.komponen"
                                            :placeholder="__('Komponen')" 
                                            type="text"
                                            class="text-sm"
                                        />
                                    </div>

                                    <InputError :error="form.errors.komponen" />
                                </div>

                                <div class="flex flex-col space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <label for="pertanyaan" class="w-1/3 capitalize text-sm">
                                            {{ __('pertanyaan') }}
                                        </label>

                                        <TextArea v-model="form.pertanyaan"
                                            :placeholder="__('pertanyaan')" 
                                            type="text"
                                            class="text-sm"
                                        />
                                    </div>

                                    <InputError :error="form.errors.pertanyaan" />
                                </div>
                                
                            </template>
                        </div>
                    </template>

                    <template #footer>
                        <div
                            class="flex items-center justify-end space-x-2 bg-gray-200 rounded-lg px-2 py-1 pb-[5px] pt-[5px]">
                            <Button type="submit" v-if="!regis" class="grid md:grid-cols text-center items-center bg-green-600 hover:bg-green-800">
                                <p class="capitalize font-semibold">
                                    {{ __(form.id ? 'simpan' : 'simpan') }}
                                </p>
                            </Button>

                            <Button type="submit" v-if="regis" @click.prevent="update" class="grid md:grid-cols text-center items-center bg-green-600 hover:bg-green-800">
                                <Icon name="check" />
                                <p class="uppercase font-semibold">
                                    {{ __('regis') }}
                                </p>
                            </Button>
                        </div>
                    </template>
                </Card>
            </form>
        </Modal>

    </DashboardLayout>
</template>
