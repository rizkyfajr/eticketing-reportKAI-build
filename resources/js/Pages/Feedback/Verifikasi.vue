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

const { report, verifikasi, users } = defineProps({
  report: Object,
  verifikasi: Array,
  users: Array,
})

const { user } = usePage().props.value

const form = useForm({
  id: null,
  report_id: report.id,
  tanggal: '',
  catatan: '',
});

const render = ref(true)
const self = getCurrentInstance()
const regis = ref(false)
const registering = ref(false)
const table = ref(null)
const open = ref(false)
const show = () => open.value = true

const close = () => {
  form.reset()
  render.value = false
  nextTick(() => {
    render.value = true
    nextTick(() => open.value = false)
    nextTick(() => regis.value = false)
  })
}

const store = () => {
  return form.post(route('verifikasi.store'), {
    onSuccess: () => close(),
    onError: () => show(),
  })
}

const edit = (verifikasi)=> {
  form.id = verifikasi.id
  form.tanggal = verifikasi.tanggal
  form.catatan = verifikasi.catatan

  show();
}

const update = () => {
  return form.patch(route('verifikasi.update', form.id), {
    onSuccess: () => close(),
    onError: () => show(),
  })
}

const destroy = async verifikasi => {
  const response = await Swal.fire({
    title: __('Apakah Anda Yakin') + '?',
    text: __('Anda tidak dapat mengembalikannya setelah dihapus'),
    icon: 'question',
    showCancelButton: true,
    showCloseButton: true,
  })

  if (response.isConfirmed) {
    return form.delete(route('verifikasi.destroy', verifikasi.id), {
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
    <DashboardLayout :title="__('closed')">
        <div
        class="transition-all duration-300"
        :class="{
            'pl-1 md:pl-64': open,
        }"
        >
        <main class="p-0 py-0">
            <h2 class="font-bold">Laporin Closed</h2>
            <p>Halaman Data Laporin CLose</p>
            <slot />
        </main>
        </div>
        <Card class="bg-gray-50">
        <template #header>
            <div class="flex items-center space-x-2 p-2 bg-gray-200">            
                <h2 class="font-semibold">Laporin CLosed</h2>
            </div>
        </template>

        <template #body>
            <div class="flex flex-col space-y-2">
            <Builder
                v-if="render"
                :url="route('verifikasi.paginate')"
                ref="table"
            >
                <template #thead="table">
                <tr class="bg-gray-200 border-gray-300">
                    <Th
                    :table="table"
                    :sort="false"
                    name="id"
                    class="border px-3 py-2 text-center"
                    >
                    {{ __('no') }}
                    </Th>

                    <Th
                    :table="table"
                    :sort="true"
                    name="report_id"
                    class="border px-3 py-2 text-center whitespace-nowrap"
                    >
                    {{ __('report_id') }}
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
                    name="catatan"
                    class="border px-3 py-2 text-center whitespace-nowrap"
                    >
                    {{ __('catatan') }}
                    </Th>

                    <Th
                    v-if="hasRole('it')"
                    :table="table"
                    :sort="false"
                    class="border px-3 py-2 text-center whitespace-nowrap"
                    >
                    {{ __('dibuat oleh') }}
                    </Th>

                    <Th
                    :table="table"
                    :sort="false"
                    class="border px-3 py-2 text-center whitespace-nowrap"
                    >
                    {{ __('#') }}
                    </Th>
                </tr>
                </template>

                <template #tfoot="table">
                <tr class="bg-gray-200 border-gray-300">
                    <Th
                    :table="table"
                    :sort="false"
                    class="border px-3 py-2 text-center"
                    >
                    {{ __('no') }}
                    </Th>

                    <Th
                    :table="table"
                    :sort="true"
                    class="border px-3 py-2 text-center whitespace-nowrap"
                    >
                    {{ __('report_id') }}
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
                    {{ __('catatan') }}
                    </Th>

                    <Th
                    v-if="hasRole('it')"
                    :table="table"
                    :sort="false"
                    class="border px-3 py-2 text-center whitespace-nowrap"
                    >
                    {{ __('dibuat oleh') }}
                    </Th>

                    <Th
                    :table="table"
                    :sort="false"
                    class="border px-3 py-2 text-center whitespace-nowrap"
                    >
                    {{ __('#') }}
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
                        :class="processing && 'bg-gray-800'"
                        class="transition-all duration-300"
                    >
                        <td class="px-2 py-1 border text-center">
                        {{ i + 1 }}
                        </td>

                        <td class="capitalize font-semibold text-center border">
                        {{ __(verifikasi.report_id) }}
                        </td>

                        <td class="capitalize font-semibold text-center border"> 
                        {{ new Date(verifikasi.tanggal).toLocaleString('id-ID', { dateStyle: 'medium'}) }}
                        </td>

                        <td class="capitalize font-semibold text-center border">
                        {{ __(verifikasi.catatan) }}
                        </td>

                        <td 
                            v-if="hasRole('it')"
                            class="font-semibold text-center capitalize border">
                            {{ (report.created_by.name) }} -
                            {{ new Date(report.created_at).toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' }) }}
                        </td>

                        <td class="px-2 py-2 border text-center">
                        <div class="grid md:grid-cols-2 gap-1 w-64">

                            <!-- <ButtonBlue
                            v-if="can('update report') && report.created_by_id === user.id"
                            @click.prevent="edit(report)"
                            >
                            <Icon name="edit" />
                            <p class="uppercase">
                                {{ __('ubah') }}
                            </p>
                            </ButtonBlue>
                            
                            <ButtonRed
                            v-if="can('delete report') && !report.code"
                            @click.prevent="destroy(report)"
                            >
                            <Icon name="trash" />
                            <p class="uppercase">
                                {{ __('hapus') }}
                            </p>
                            </ButtonRed>

                            <BtnAttachment
                                :model="report"
                                type="Report"
                                :redaction="`Report Bug Sistem`"
                                :closed="false"
                            /> -->
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
        <form
            @submit.prevent="submit"
            class="w-full max-w-7xl h-fit shadow-xl" 
        >
            <Card class="bg-gray-50">
            <template #header>
                <div class="flex items-center justify-end bg-gray-200 p-2">
                <Close @click.prevent="close" />
                </div>
            </template>

            <template #body>
                <div class="flex flex-col space-y-4 p-4">
                <template v-if="hasRole (['superuser', 'it'])">              

                <div class="flex flex-col space-y-2">
                    <div class="flex items-center space-x-2">
                    <label for="tanggal" class="w-1/3 capitalize">
                        {{ __('Tanggal Selesai') }}
                    </label>

                    <Input
                        v-model="form.tanggal"
                        type="date"
                        required
                    />
                    </div>

                    <InputError
                    :error="form.errors.tanggal"
                    />
                </div>                      

                <div class="flex flex-col space-y-2">
                    <div class="flex items-center space-x-2">
                    <label for="catatans" class="w-1/3 capitalize">
                        {{ __('catatan') }}
                    </label>

                    <Input
                        v-model="form.catatans"
                        :placeholder="__('catatan')"
                        type="text"
                        required
                    />
                    </div>

                    <InputError
                    :error="form.errors.catatans"
                    />
                </div>    
                </template>
                </div>
            </template>

            <template #footer>
                <div class="flex items-center justify-end space-x-2 bg-gray-200 px-2 py-1">
                <ButtonGreen type="submit" v-if="! regis">
                    <p class="uppercase font-semibold">
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