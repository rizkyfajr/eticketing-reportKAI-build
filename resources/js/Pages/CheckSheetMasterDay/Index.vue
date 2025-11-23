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

const { check_sheet_master_day } = defineProps({
    check_sheet_master_day: Object
})

const form = useForm({
  group_name: '',
  komponen: '',
  rujukan: '',
  nilai_rujukan: '',
  satuan: '',
  urutan: '',
  jenis_mesin: '',
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
    return form.post(route('master-checksheet-day.store'), {
        onSuccess: () => close(),
        onError: () => show(),
    })
}

const edit = (check_sheet_master_day) => {
    form.id = check_sheet_master_day.id
    form.group_name = check_sheet_master_day.group_name
    form.komponen = check_sheet_master_day.komponen
    form.rujukan = check_sheet_master_day.rujukan
    form.nilai_rujukan = check_sheet_master_day.nilai_rujukan
    form.satuan = check_sheet_master_day.satuan
    form.urutan = check_sheet_master_day.urutan
    form.jenis_mesin = check_sheet_master_day.jenis_mesin
    show()
}

const update = () => {
    return form.patch(route('master-checksheet-day.update', form.id), {
        onSuccess: () => close(),
        onError: () => show(),
    })
}

const destroy = async machien => {
    const response = await Swal.fire({
        title: __('Apakah Anda Yakin') + '?',
        text: __('Anda tidak dapat mengembalikannya setelah dihapus'),
        icon: 'question',
        showCancelButton: true,
        showCloseButton: true,
    })

    if (response.isConfirmed) {
        return form.delete(route('master-checksheet-day.destroy', machien.id), {
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
    <DashboardLayout :title="__('Master Checksheet Harian')">
        <!-- <div class="transition-all duration-300" :class="{
            'pl-1 md:pl-64': open,
        }">
        <main class="p-0 py-0 mb-[1.25rem] ml-[1.25rem] mt-[1.25rem]">
            <h2 class="font-bold text-2xl">Master Data Check Sheet Harian</h2>
           <a type="button" href="/" class="text-sm text-gray-500 font-semibold hover:text-sky-600 focus:text-sky-600">Home</a> 
           <span class="font-semibold text-sm pl-2 pr-2">-</span>
           <span class="text-sm text-gray-500 font-semibold hover:text-sky-600 focus:text-sky-700">Master</span> 
            <slot />
        </main>
        </div> -->
        <Card class="bg-white pt-[1.100rem] pb-[2.5rem] shadow-lg border border-solid border-slate-200" style="border-radius: 0.625rem;">
            <template #header>
                <!-- <h1 class="w-full flex justify-center items-center h-[80px] text-2xl font-bold">Data <span class="ml-2 mr-2"
                        style="font-family: taviraj;"></span>Divison</h1> -->
                <div class="flex items-center justify-end px-4 py-1 rounded space-x-2 p-2 pr-[1.688rem]">
                    <Button v-if="can('create check sheet master')" @click.prevent="form.id = null; show()"
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
                    <Builder v-if="render" :url="route('master-checksheet-day.paginate')" ref="table">
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

                                <Th :table="table" :sort="false" name="rujukan"
                                    class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                    {{ __('rujukan').toUpperCase() }}
                                </Th>   

                                <Th :table="table" :sort="false" name="nilai_rujukan"
                                    class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                    {{ __('nilai rujukan').toUpperCase() }}
                                </Th>  

                                <Th :table="table" :sort="false" name="satuan"
                                    class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                    {{ __('satuan').toUpperCase() }}
                                </Th>    

                                <Th :table="table" :sort="false" name="jenis_mesin"
                                    class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                    {{ __('jenis mesin').toUpperCase() }}
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
                                v-for="(check_sheet_master_day, i) in data"
                                :key="check_sheet_master_day.id"
                                :class="processing && 'bg-gray-100'"
                                class="transition-all duration-300"
                              >
                                <td class="border-b uppercase border-gray-300 px-4 py-1 text-center text-xs">
                                  {{ i + 1 }}
                                </td>

                                <td class="border-b uppercase border-gray-300 px-4 py-1 text-center text-xs">
                                  {{ check_sheet_master_day.group_name }}
                                </td>

                                <td class="border-b uppercase border-gray-300 px-4 py-1 text-center text-xs">
                                  {{ check_sheet_master_day.urutan}}
                                </td>

                                <td class="border-b uppercase border-gray-300 px-4 py-1 text-center text-xs">
                                  {{ check_sheet_master_day.komponen }}
                                </td>

                                <td class="border-b uppercase border-gray-300 px-4 py-1 text-center text-xs">
                                  {{ check_sheet_master_day.rujukan}}
                                </td>

                                <td class="border-b uppercase border-gray-300 px-4 py-1 text-center text-xs">
                                  {{ check_sheet_master_day.nilai_rujukan}}
                                </td>

                                <td class="border-b uppercase border-gray-300 px-4 py-1 text-center text-xs">
                                  {{ check_sheet_master_day.satuan}}
                                </td>

                                <td class="border-b uppercase border-gray-300 px-4 py-1 text-center text-xs">
                                  {{ check_sheet_master_day.jenis_mesin}}
                                </td>

                                <td class="px-2 py-1 border-b text-center">
                                <div class="flex justify-center gap-2">
                                    <ButtonBlue
                                      v-if="can('update check sheet master')"
                                      @click.prevent="edit(check_sheet_master_day)"
                                    >
                                      <Icon name="edit" />
                                      <p class="font-bold text-xs">{{ __('Ubah') }}</p>
                                    </ButtonBlue>

                                    <ButtonRed
                                      v-if="can('delete check sheet master')"
                                      @click.prevent="destroy(check_sheet_master_day)"
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
                                            type="text"
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
                                        <label for="rujukan" class="w-1/3 capitalize text-sm">
                                            {{ __('rujukan') }}
                                        </label>

                                        <Input v-model="form.rujukan"
                                            :placeholder="__('rujukan')" 
                                            type="text"
                                            class="text-sm"
                                        />
                                    </div>

                                    <InputError :error="form.errors.rujukan" />
                                </div>

                                <div class="flex flex-col space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <label for="nilai_rujukan" class="w-1/3 capitalize text-sm">
                                            {{ __('nilai rujukan') }}
                                        </label>

                                        <Input v-model="form.nilai_rujukan"
                                            :placeholder="__('nilai rujukan')" 
                                            type="text"
                                            class="text-sm"
                                        />
                                    </div>

                                    <InputError :error="form.errors.nilai_rujukan" />
                                </div>

                                <div class="flex flex-col space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <label for="satuan" class="w-1/3 capitalize text-sm">
                                            {{ __('satuan') }}
                                        </label>

                                        <Input v-model="form.satuan"
                                            :placeholder="__('satuan (%, Bar, °C, dll)')" 
                                            type="text"
                                            class="text-sm"
                                        />
                                    </div>

                                    <InputError :error="form.errors.satuan" />
                                </div>

                                <div class="flex flex-col space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <label for="jenis_mesin" class="w-1/3 capitalize text-sm">
                                        {{ __('jenis mesin') }}
                                        </label>

                                        <select
                                        v-model="form.jenis_mesin"
                                        id="jenis_mesin"
                                        class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm w-full text-sm"
                                        >
                                        <option disabled value="">{{ __('Pilih jenis mesin') }}</option>
                                        <option value="MTT">MTT</option>
                                        <option value="PBR">PBR</option>
                                        </select>
                                    </div>

                                    <InputError :error="form.errors.jenis_mesin" />
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
