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
import Select from '@vueform/multiselect'
import Modal from '@/Components/Modal.vue'
import Close from '@/Components/Button/Close.vue'
import Button from '@/Components/Button.vue'
import ButtonBlue from '@/Components/Button/Blue.vue'
import ButtonGreen from '@/Components/Button/Green.vue'
import ButtonRed from '@/Components/Button/Red.vue'
import Input from '@/Components/Input.vue'
import InputError from '@/Components/InputError.vue'
import BtnAttachment from '@/Components/Button/Attachment.vue'
import TextArea from '@/Components/TextArea.vue'

const { users, report, feedback,system } = defineProps({
    users: Array,
    report: Object,
    feedback: Object,
    system: Object,
})

const { user } = usePage().props.value

const form = useForm({
    id: null,
    singkatan: '',
    nama: '',
    level: '',
   
    users: [],
});


const render = ref(true)
const self = getCurrentInstance()
const regis = ref(false)
const table = ref(null)
const open = ref(false)
// const balas = ref(false)
const show = () => open.value = true

const balas = ref(false)
const list = ref(false)



const close = () => {
    form.reset()
    render.value = false
    nextTick(() => {
        render.value = true
        nextTick(() => open.value = false)
        nextTick(() => regis.value = false)
        // nextTick(() => balas.value = false)
        nextTick(() => balas.value = false)
        nextTick(() => list.value = false)
    })
}

const store = () => {
    return form.post(route('system.store'), {
        onSuccess: () => close(),
        onError: () => show(),
    })
}

const edit = (system) => {
    form.id = system.id
    form.singkatan = system.singkatan
  
    form.nama = system.nama
 
    form.level = system.level

    show();
}





const update = () => {
    return form.patch(route('system.update', form.id), {
        onSuccess: () => close(),
        onError: () => show(),
    })
}

const destroy = async system => {
    const response = await Swal.fire({
        title: __('Apakah Anda Yakin') + '?',
        text: __('Anda tidak dapat mengembalikannya setelah dihapus'),
        icon: 'question',
        showCancelButton: true,
        showCloseButton: true,
    })

    if (response.isConfirmed) {
        return form.delete(route('system.destroy', system.id), {
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
    <DashboardLayout :title="__('Laporin')">
        <div class="transition-all duration-300" :class="{
            'pl-1 md:pl-64': open,
        }">
           <main class="p-0 py-0 mb-[1.25rem] ml-[1.25rem] mt-[1.25rem]">
            <h2 class="font-bold text-2xl">Sistem</h2>
           <a type="button" href="/" class="text-sm text-gray-500 font-semibold hover:text-sky-600 focus:text-sky-600">Home</a> 
           <span class="font-semibold text-sm pl-2 pr-2">-</span>
           <span class="text-sm text-gray-500 font-semibold hover:text-sky-600 focus:text-sky-700">Builtin</span> 
            <slot />
        </main>
        </div>
        <Card
            class="bg-white pt-[1.875rem] pb-[2.5rem] shadow-lg border border-solid border-slate-200" style="border-radius: 0.625rem;">
        
            <template #header>
                <!-- <h1 class="w-full flex justify-center items-center h-[80px] text-2xl font-bold">Data <span class="ml-2 mr-2"
                        style="font-family: taviraj;">E-</span>Sistem</h1> -->
                <div class="flex items-center justify-end space-x-2 p-2 pr-[1.688rem]">
                    <Button v-if="can('create system')" @click.prevent="form.id = null; show()"
                        class="flex items-center justify-center grid gap-1 w-auto h-11 mr-[1.313rem] rounded-md text-center bg-green-600 hover:bg-green-700"
                        >
                      
                        <!-- <Icon name="plus" class="w-full" /> -->
                        <p class="capitalize font-bold">
                            {{ __('Tambah') }}
                        </p>
                    </Button>
                </div>
            </template>

            <template #body>
                <div class="flex flex-col space-y-2">
                    <Builder v-if="render" :url="route('system.paginate')" ref="table">
                        <template #thead="table">
                            <tr class="bg-gray-200 border-gray-300">
                                <Th :table="table" :sort="false" name="id"
                                    class="border px-3 py-2 text-center capitalize font-bold">
                                    {{ __('no') }}
                                </Th>

                                <Th :table="table" :sort="true" name="singkatan"
                                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('singkatan') }}
                                </Th>

                                <Th :table="table" :sort="true" name="nama"
                                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('nama') }}
                                </Th>

                                <Th :table="table" :sort="true" name="level"
                                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('level') }}
                                </Th>




                                <Th :table="table" :sort="false"
                                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('Action') }}
                                </Th>
                            </tr>
                        </template>

                        <template #tfoot="table">
                            <tr class="bg-gray-200 border-gray-300">
                                <Th :table="table" :sort="false"
                                    class="border px-3 py-2 text-center capitalize font-bold pl-[2.75rem] pb-[1.5rem]">
                                    {{ __('no') }}
                                </Th>

                                <Th :table="table" :sort="false"
                                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('singkatan.') }}
                                </Th>

                                <Th :table="table" :sort="false"
                                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('nama') }}
                                </Th>

                                <Th :table="table" :sort="false"
                                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
                                    {{ __('level') }}
                                </Th>



                                <Th :table="table" :sort="false"
                                    class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold">
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
                                    <tr v-for="(system, i) in data" :key="system.id" :class="processing && 'bg-gray-100'"
                                        class=" transition-all duration-300 h-[4.375rem]">
                                        <td class="px-2 py-1 border-b text-center">
                                            {{ i + 1 }}
                                        </td>

                                      
                                        <td class="capitalize font-semibold text-center border-b">
                                            {{ __(system.singkatan) }}
                                        </td>

                                        <td class="capitalize font-semibold text-center border-b">
                                            {{ __(system.nama) }}
                                        </td>

                                        <td class="capitalize font-semibold text-center border-b">
                                            {{ __(system.level) }}
                                        </td>


                                        <td class="px-2 py-2 border-b text-center">
                                            <div class="flex justify-center gap-2">
                                              


                                                <ButtonBlue
                                                    v-if="can('update system')"
                                                    @click.prevent="edit(system)">
                                                    <Icon name="edit" />
                                                    <p class="uppercase">
                                                        {{ __('ubah') }}
                                                    </p>
                                                </ButtonBlue>

                                                <ButtonRed
                                                    v-if="can('delete system')"
                                                    @click.prevent="destroy(system)">
                                                    <Icon name="trash" />
                                                    <p class="uppercase">
                                                        {{ __('hapus') }}
                                                    </p>
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
            <form @submit.prevent="submit" class="w-full max-w-7xl h-fit shadow-xl">
                <Card class="bg-gray-50">
                    <!-- Close icon -->
                    <template #header>
                        <div
                            class="flex items-center justify-end bg-gray-200 rounded-lg p-2 pb-[15px] pt-[15px]">
                            <Close @click.prevent="close" />
                        </div>
                    </template>

                    <template #body>
               <div class="flex flex-col space-y-4 p-4">
                 <template
                      v-if="hasRole(['superuser', 'user', 'asman', 'spv', 'ampr', 'mpm', 'it']) && !regis">
                                  <div class="flex flex-col space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <label for="singkatan" class="w-1/3 capitalize">
                                            {{ __('singkatan') }}
                                        </label>

                                        <Input v-model="form.singkatan"
                                            :placeholder="__('singkatan')" type="text"
                                            required />
                                    </div>

                                    <InputError :error="form.errors.singkatan" />
                                </div>

                                <div class="flex flex-col space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <label for="nama" class="w-1/3 capitalize">
                                            {{ __('nama') }}
                                        </label>

                                        <Input v-model="form.nama"
                                            :placeholder="__('nama')" type="text"
                                            required />
                                    </div>

                                    <InputError :error="form.errors.nama" />
                                </div>

                                <div class="flex flex-col space-y-2">
                    <div class="flex items-center space-x-2">
                    <label for="level" class="w-1/3 capitalize">
                        {{ __('level kendala yang ditemukan') }}
                    </label>

                    <Select
                        v-model="form.level"
                        :placeholder="__('Pilih level Bug')"
                        :options="[
                            'Minor',
                            'Mayor',
                            'Critical',
                        ]"
                        type="text"
                        :searchable="true"
                        requiered
                    />
                    </div>

                    <InputError
                    :error="form.errors.level"
                    />
                </div>
                            </template>
                        </div>
                    </template>

                    <template #footer>
                        <div
                            class="flex items-center justify-end space-x-2 bg-gray-200 px-2 py-1 pb-[15px] pt-[15px]">
                            <ButtonGreen type="submit" v-if="!regis">
                                <p class="capitalize font-semibold">
                                    {{ __(form.id ? 'simpan' : 'simpan') }}
                                </p>
                            </ButtonGreen>

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
