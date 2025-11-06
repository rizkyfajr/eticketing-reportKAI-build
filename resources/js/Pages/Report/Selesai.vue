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

const { users } = defineProps({
    users: Array,
})

const { user } = usePage().props.value

const form = useForm({
    id: null,
    kode: null,
    bagian_sistem: '',
    tanggal: '',
    bagian_pelapor: '',
    kategori: '',
    kendala: '',
    dampak: '',
    kontak: '',
    url: '',
    status: '',
    catatan: '',
    users: [],
});

const form1 = useForm({
    id: null,
    kode: null,
    bagian_sistem: '',
    tanggal: '',
    bagian_pelapor: '',
    kategori: '',
    kendala: '',
    dampak: '',
    kontak: '',
    url: '',
    status: '',
    balasan: '',
    catatan: '',
    users: [],
});

const render = ref(true)
const self = getCurrentInstance()
const regis = ref(false)
const table = ref(null)
const open = ref(false)
const balas = ref(false)
const show = () => open.value = true
const show1 = () => balas.value = true

const close = () => {
    form.reset()
    render.value = false
    nextTick(() => {
        render.value = true
        nextTick(() => open.value = false)
        nextTick(() => regis.value = false)
        nextTick(() => balas.value = false)
    })
}

const store = () => {
    return form.post(route('report.store'), {
        onSuccess: () => close(),
        onError: () => show(),
    })
}

const edit = (report) => {
    form.id = report.id
    form.kode = report.kode
    form.bagian_sistem = ![
        'E-KFPB',
        'E-SDM KFPB',
        'E-Dokumen KFPB',
        'E-Logbook',
        'Electronic Batch Record (EBR)',
        'Electronic Quality Management System (EQMS)',
        'Learning Management System (LMS)',
    ].includes(report.bagian_sistem) ? 'Lainnya' : report.bagian_sistem
    form.tanggal = report.tanggal
    form.bagian_pelapor = report.bagian_pelapor
    form.kategori = ![
        'Minor',
        'Mayor',
        'Critical',
    ].includes(report.kategori) ? 'Lainnya' : report.kategori
    form.kendala = report.kendala
    form.dampak = report.dampak
    form.kontak = report.kontak
    form.url = report.url
    form.catatan = report.catatan

    show();
}

const edit1 = (report) => {
    form.id = report.id
    form.kode = report.kode
    form.bagian_sistem = ![
        'E-KFPB',
        'E-SDM KFPB',
        'E-Dokumen KFPB',
        'E-Logbook',
        'Electronic Batch Record (EBR)',
        'Electronic Quality Management System (EQMS)',
        'Learning Management System (LMS)',
    ].includes(report.bagian_sistem) ? 'Lainnya' : report.bagian_sistem
    form.tanggal = report.tanggal
    form.bagian_pelapor = report.bagian_pelapor
    form.kategori = ![
        'Minor',
        'Mayor',
        'Critical',
    ].includes(report.kategori) ? 'Lainnya' : report.kategori
    form.kendala = report.kendala
    form.dampak = report.dampak
    form.kontak = report.kontak
    form.url = report.url
    form.catatan = report.catatan

    show1();
}

const update = () => {
    return form.patch(route('report.update', form.id), {
        onSuccess: () => close(),
        onError: () => show(),
    })
}
const update1 = () => {
    return form.patch(route('feedback.store', form.id), {
        onSuccess: () => close(),
        onError: () => show(),
    })
}


const destroy = async report => {
    const response = await Swal.fire({
        title: __('Apakah Anda Yakin') + '?',
        text: __('Anda tidak dapat mengembalikannya setelah dihapus'),
        icon: 'question',
        showCancelButton: true,
        showCloseButton: true,
    })

    if (response.isConfirmed) {
        return form.delete(route('report.destroy', report.id), {
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
    <DashboardLayout :title="__('laporin')">
        <div class="transition-all duration-300" :class="{
            'pl-1 md:pl-64': open,
        }">
            <main class="p-0 py-0">
                <h2 class="font-bold">Ticketing</h2>
                <p>Halaman Data Ticketing</p>
                <slot />
            </main>
        </div>
        <Card class="bg-gray-50">
            <template #header>
                <div class="flex items-center space-x-2 p-2 bg-gray-200">
                    <Button v-if="can('create report')" @click.prevent="form.id = null; show()"
                        class="grid md:grid-cols-2 gap-1 w-full text-center items-center bg-green-600 hover:bg-green-800">
                        <div class="flex items-center justify-center w-full"></div>
                        <div class="flex items-center justify-center w-full"></div>
                        <div class="flex items-center justify-center w-full"></div>
                        <div class="flex items-center justify-center w-full"></div>
                        <div class="flex items-center justify-center w-full"></div>
                        <div class="flex items-center justify-center w-full"></div>
                        <div class="flex items-center justify-center w-full"></div>
                        <div class="flex items-center justify-center w-full"></div>
                        <div class="flex items-center justify-center w-full"></div>
                        <div class="flex items-center justify-center w-full"></div>
                        <div class="flex items-center justify-center w-full"></div>
                        <div class="flex items-center justify-center w-full"></div>
                        <div class="flex items-center justify-center w-full"></div>
                        <div class="flex items-center justify-center w-full"></div>
                        <div class="flex items-center justify-center w-full"></div>
                        <div class="flex items-center justify-center w-full"></div>
                        <div class="flex items-center justify-center w-full"></div>
                        <div class="flex items-center justify-center w-full"></div>
                        <div class="flex items-center justify-center w-full"></div>
                        <div class="flex items-center justify-center w-full"></div>
                        <div class="flex items-center justify-center w-full"></div>
                        <div class="flex items-center justify-center w-full"></div>
                        <div class="flex items-center justify-center w-full"></div>
                        <div class="flex items-center justify-center w-full"></div>
                        <div class="flex items-center justify-center w-full"></div>
                        <div class="flex items-center justify-center w-full"></div>
                        <div class="flex items-center justify-center w-full"></div>
                        <div class="flex items-center justify-center w-full"></div>
                        <div class="flex items-center justify-center w-full"></div>
                        <div class="flex items-center justify-center w-full"></div>
                        <div class="flex items-center justify-center w-full"></div>
                        <div class="flex items-center justify-center w-full"></div>
                        <div class="flex items-center justify-center w-full"></div>
                        <Icon name="plus" class="w-full" />
                        <p class="uppercase font-bold">
                            {{ __('Tambah') }}
                        </p>
                    </Button>
                </div>
            </template>

            <template #body>
                <div class="flex flex-col space-y-2">
                    <Builder v-if="render" :url="route('selesai.paginate')" ref="table">
                        <template #thead="table">
                            <tr class="bg-gray-200 border-gray-300">
                                <Th :table="table" :sort="false" name="id" class="border px-3 py-2 text-center">
                                    {{ __('no') }}
                                </Th>

                                <Th :table="table" :sort="true" name="kode"
                                    class="border px-3 py-2 text-center whitespace-nowrap">
                                    {{ __('kode') }}
                                </Th>

                                <Th :table="table" :sort="true" name="bagian_sistem"
                                    class="border px-3 py-2 text-center whitespace-nowrap">
                                    {{ __('bagian bermasalah') }}
                                </Th>

                                <Th :table="table" :sort="true" name="tanggal"
                                    class="border px-3 py-2 text-center whitespace-nowrap">
                                    {{ __('tanggal') }}
                                </Th>

                                <Th :table="table" :sort="true" name="bagian_pelapor"
                                    class="border px-3 py-2 text-center whitespace-nowrap">
                                    {{ __('bagian pelapor') }}
                                </Th>

                                <Th :table="table" :sort="true" name="kategori"
                                    class="border px-3 py-2 text-center whitespace-nowrap">
                                    {{ __('kategori') }}
                                </Th>

                                <!-- <Th
                    :table="table"
                    :sort="true"
                    name="kendala"
                    class="border px-3 py-2 text-center whitespace-nowrap"
                    >
                    {{ __('kendala') }}
                    </Th>

                    <Th
                    :table="table"
                    :sort="true"
                    name="dampak"
                    class="border px-3 py-2 text-center whitespace-nowrap"
                    >
                    {{ __('dampak') }}
                    </Th> -->

                                <Th :table="table" :sort="true" name="kontak"
                                    class="border px-3 py-2 text-center whitespace-nowrap">
                                    {{ __('kontak') }}
                                </Th>

                                <!-- <Th
                    :table="table"
                    :sort="false"
                    name="catatan"
                    class="border px-3 py-2 text-center whitespace-nowrap"
                    >
                    {{ __('catatan') }}
                    </Th> -->

                                <Th :table="table" :sort="false" class="border px-3 py-2 text-center whitespace-nowrap">
                                    {{ __('status') }}
                                </Th>

                                <Th v-if="hasRole('it')" :table="table" :sort="false"
                                    class="border px-3 py-2 text-center whitespace-nowrap">
                                    {{ __('dibuat oleh') }}
                                </Th>

                                <!-- <Th
                    :table="table"
                    :sort="true"
                    name="url"
                    class="border px-3 py-2 text-center whitespace-nowrap"
                    >
                    {{ __('url') }}
                    </Th> -->

                                <Th :table="table" :sort="false" class="border px-3 py-2 text-center whitespace-nowrap">
                                    {{ __('#') }}
                                </Th>
                            </tr>
                        </template>

                        <template #tfoot="table">
                            <tr class="bg-gray-200 border-gray-300">
                                <Th :table="table" :sort="false" class="border px-3 py-2 text-center">
                                    {{ __('no') }}
                                </Th>

                                <Th :table="table" :sort="false" class="border px-3 py-2 text-center whitespace-nowrap">
                                    {{ __('kode') }}
                                </Th>

                                <Th :table="table" :sort="false" class="border px-3 py-2 text-center whitespace-nowrap">
                                    {{ __('bagian bermasalah') }}
                                </Th>

                                <Th :table="table" :sort="false" class="border px-3 py-2 text-center whitespace-nowrap">
                                    {{ __('tanggal') }}
                                </Th>

                                <Th :table="table" :sort="false" class="border px-3 py-2 text-center whitespace-nowrap">
                                    {{ __('bagian pelapor') }}
                                </Th>

                                <Th :table="table" :sort="false" class="border px-3 py-2 text-center whitespace-nowrap">
                                    {{ __('kategori') }}
                                </Th>

                                <!-- <Th
                    :table="table"
                    :sort="false"
                    class="border px-3 py-2 text-center whitespace-nowrap"
                    >
                    {{ __('kendala') }}
                    </Th>

                    <Th    
                    :table="table"
                    :sort="false"
                    class="border px-3 py-2 text-center whitespace-nowrap"
                    >
                    {{ __('dampak') }}
                    </Th> -->

                                <Th :table="table" :sort="false" class="border px-3 py-2 text-center whitespace-nowrap">
                                    {{ __('kontak') }}
                                </Th>

                                <!-- <Th
                    :table="table"
                    :sort="false"
                    class="border px-3 py-2 text-center whitespace-nowrap"
                    >
                    {{ __('catatan') }}
                    </Th> -->

                                <Th :table="table" :sort="false" class="border px-3 py-2 text-center whitespace-nowrap">
                                    {{ __('status') }}
                                </Th>

                                <Th v-if="hasRole('it')" :table="table" :sort="false"
                                    class="border px-3 py-2 text-center whitespace-nowrap">
                                    {{ __('dibuat oleh') }}
                                </Th>

                                <!-- <Th
                    :table="table"
                    :sort="false"
                    class="border px-3 py-2 text-center whitespace-nowrap"
                    >
                    {{ __('url') }}
                    </Th> -->

                                <Th :table="table" :sort="false" class="border px-3 py-2 text-center whitespace-nowrap">
                                    {{ __('#') }}
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
                                    <tr v-for="(report, i) in data" :key="report.id" :class="processing && 'bg-gray-800'"
                                        class="transition-all duration-300">
                                        <td class="px-2 py-1 border text-center">
                                            {{ i + 1 }}
                                        </td>

                                  
                                        <td class="capitalize font-semibold text-center border">
                                            {{ __(report.kode) }}
                                        </td>

                                        <td class="capitalize font-semibold text-center border">
                                            {{ __(report.bagian_sistem) }}
                                        </td>

                                        <td class="capitalize font-semibold text-center border">
                                            {{ new Date(report.tanggal).toLocaleString('id-ID', { dateStyle: 'medium' }) }}
                                        </td>

                                        <td class="capitalize font-semibold text-center border">
                                            {{ __(report.bagian_pelapor) }}
                                        </td>

                                        <td class="capitalize font-semibold text-center border">
                                            {{ __(report.kategori) }}
                                        </td>

                                        <!-- <td class="capitalize font-semibold text-center border">
                        {{ __(report.kendala) }}
                        </td>

                        <td class="capitalize font-semibold text-center border">
                        {{ __(report.dampak) }}
                        </td> -->

                                        <td class="capitalize font-semibold text-center border">
                                            {{ __(report.kontak) }}
                                        </td>

                                        <!-- <td class="capitalize font-semibold text-center border">
                        {{ __(report.catatan) }}
                        </td> -->

                                        <td :class="{
                                            'text-green-600': report.status === 1,
                                            'text-orange-600': report.status === 2,
                                            'text-blue-600': report.status === 3,
                                            'text-red-600': report.status === 4
                                        }" class="uppercase font-semibold text-center">
                                            {{
                                                report.status === 1 ? 'Terkirim' :
                                                report.status === 2 ? 'Perbaikan' :
                                                    report.status === 3 ? 'Selesai' :
                                                        report.status === 4 ? 'Pending' :
                                                            __(report.status)
                                            }}
                                        </td>


                                        <td v-if="hasRole('it')" class="font-semibold text-center capitalize border">
                                            {{ (report.created_by.name) }} -
                                            {{ new Date(report.created_at).toLocaleString('id-ID', {
                                                dateStyle: 'medium',
                                                timeStyle: 'short'
                                            }) }}
                                        </td>

                                        <td class="px-2 py-2 border text-center">
                                            <div class="grid md:grid-cols-2 gap-1 w-64">
                                                <!-- <ButtonGroup> -->

                                                <Button class="bg-gray-700 hover:bg-gray-600 rounded-md">
                                                    <a :href="route('report.detail', report.id)" target="_blank"
                                                        class="flex items-center">
                                                        <Icon name="circle-info" class="mr-2" />
                                                        <p class="uppercase">
                                                            {{ __('detail') }}
                                                        </p>
                                                    </a>
                                                </Button>
                                                <!-- <Button class="bg-cyan-700 hover:bg-blue-400 rounded-md">
                                <a :href="route('report.balas', report.id)" target="_blank" class="flex items-center">
                                    <Icon name="circle-info" class="mr-2" />
                                    <p class="uppercase">
                                    {{ __('Berikan Balasan Anda') }}
                                    </p>
                                </a>
                                </Button> -->

                                                <ButtonBlue
                                                    v-if="can('update1 report') && report.created_by_id === user.id && report.status !== 3"
                                                    @click.prevent="edit1(report)">
                                                    <Icon name="edit" />
                                                    <p class="uppercase">
                                                        {{ __('Berikan Balasan') }}
                                                    </p>
                                                </ButtonBlue>



                                                <ButtonBlue
                                                    v-if="can('update report') && report.created_by_id === user.id && report.status !== 3"
                                                    @click.prevent="edit(report)">
                                                    <Icon name="edit" />
                                                    <p class="uppercase">
                                                        {{ __('ubah') }}
                                                    </p>
                                                </ButtonBlue>




                                                <ButtonRed v-if="can('delete report') && !report.code && report.status !== 3"
                                                    @click.prevent="destroy(report)">
                                                    <Icon name="trash" />
                                                    <p class="uppercase">
                                                        {{ __('hapus') }}
                                                    </p>
                                                </ButtonRed>

                                                <BtnAttachment :model="report" type="Report"
                                                    :redaction="`Report Bug Sistem`" :closed="false" />

                                                <!-- </ButtonGroup> -->
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
                    <template #header>
                        <div class="flex items-center justify-end bg-gray-200 p-2">
                            <Close @click.prevent="close" />
                        </div>
                    </template>

                    <template #body>
                        <div class="flex flex-col space-y-4 p-4">

                            <template v-if="hasRole(['superuser', 'it']) && regis">
                                <div class="flex flex-col space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <label for="kode" class="w-1/3 capitalize">
                                            {{ __('kode') }}
                                        </label>

                                        <Input v-model="form.kode" :placeholder="__('kode')" type="text" />
                                    </div>

                                    <InputError :error="form.errors.kode" />
                                </div>
                            </template>

                            <template
                                v-if="hasRole(['superuser', 'user', 'asman', 'spv', 'ampr', 'mpm', 'it']) && !regis">
                                <div class="flex flex-col space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <label for="bagian_sistem" class="w-1/3 capitalize">
                                            {{ __('bagian sistem yang bermasalah') }}
                                        </label>

                                        <Select v-model="form.bagian_sistem"
                                            :placeholder="__('pilih bagian sistem yang bermasalah')" :options="[
                                                'E-KFPB',
                                                'E-SDM KFPB',
                                                'E-Dokumen KFPB',
                                                'E-Logbook',
                                                'Electronic Batch Record (EBR)',
                                                'Electronic Quality Management System (EQMS)',
                                                'Learning Management System (LMS)',
                                            ]" type="text" :searchable="true" requiered6 />
                                    </div>

                                    <InputError :error="form.errors.bagian_sistem" />
                                </div>

                                <div class="flex flex-col space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <label for="tanggal" class="w-1/3 capitalize">
                                            {{ __('Tanggal ditemukan kendala') }}
                                        </label>

                                        <Input v-model="form.tanggal" type="date" required />
                                    </div>

                                    <InputError :error="form.errors.tanggal" />
                                </div>

                                <div class="flex flex-col space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <label for="bagian_pelapor" class="w-1/3 capitalize">
                                            {{ __('bagian pembuat laporan') }}
                                        </label>

                                        <Select v-model="form.bagian_pelapor" :placeholder="__('Pilih bagian')" :options="[
                                            'SDM & UMUM',
                                            'K3L',
                                            'Pemastian Mutu',
                                            'Pemastian Operasional',
                                            'Pendukung Teknis',
                                            'Pemenuhan Regulasi',
                                            'Penyimpangan',
                                            'Produksi Herbal',
                                            'Produksi Farma I',
                                            'Produksi Farma II',
                                            'Utility',
                                            'Mekanik & Electrical',
                                        ]" type="text" :searchable="true" requiered />
                                    </div>

                                    <InputError :error="form.errors.bagian_pelapor" />
                                </div>

                                <div class="flex flex-col space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <label for="kategori" class="w-1/3 capitalize">
                                            {{ __('kategori kendala yang ditemukan') }}
                                        </label>

                                        <Select v-model="form.kategori" :placeholder="__('Pilih Kategori Bug')" :options="[
                                            'Minor',
                                            'Mayor',
                                            'Critikal',
                                        ]" type="text" :searchable="true" requiered />
                                    </div>

                                    <InputError :error="form.errors.kategori" />
                                </div>

                                <div class="flex flex-col space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <label for="kendala" class="w-1/3 capitalize">
                                            {{ __('uraian kendala yang ditemukan') }}
                                        </label>

                                        <TextArea v-model="form.kendala"
                                            :placeholder="__('isi uraian kendala yang ditemukan')" required />
                                    </div>

                                    <InputError :error="form.errors.kendala" />
                                </div>

                                <div class="flex flex-col space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <label for="dampak" class="w-1/3 capitalize">
                                            {{ __('dampak dari kendala') }}
                                        </label>

                                        <TextArea v-model="form.dampak" :placeholder="__('isi dampak dari kendala')"
                                            required />
                                    </div>

                                    <InputError :error="form.errors.dampak" />
                                </div>

                                <div class="flex flex-col space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <label for="url" class="w-1/3 capitalize">
                                            {{ __('Link url') }}
                                        </label>

                                        <Input v-model="form.url"
                                            :placeholder="__('Salin link/url yang mengalami bug/error')" type="text"
                                            required />
                                    </div>

                                    <InputError :error="form.errors.url" />
                                </div>

                                <div class="flex flex-col space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <label for="kontak" class="w-1/3 capitalize">
                                            {{ __('kontak yang dapat dihubungi') }}
                                        </label>

                                        <Input v-model="form.kontak" :placeholder="__('kontak yang dapat dihubungi')"
                                            type="text" required />
                                    </div>

                                    <InputError :error="form.errors.kontak" />
                                </div>
                            </template>
                        </div>
                    </template>

                    <template #footer>
                        <div class="flex items-center justify-end space-x-2 bg-gray-200 px-2 py-1">
                            <ButtonGreen type="submit" v-if="!regis">
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

        <Modal :show="balas">
            <form @submit.prevent="balas" class="w-full max-w-7xl h-fit shadow-xl">
                <Card class="bg-gray-50">
                    <template #header>
                        <div class="flex items-center justify-end bg-gray-200 p-2">
                            <Close @click.prevent="close" />
                        </div>
                    </template>

                    <template #body>
                        <div class="flex flex-col space-y-4 p-4">

                            <template v-if="hasRole(['superuser', 'it']) && regis">
                                <div class="flex flex-col space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <label for="kode" class="w-1/3 capitalize">
                                            {{ __('kode') }}
                                        </label>

                                        <Input v-model="form.kode" :placeholder="__('kode')" type="text" />
                                    </div>

                                    <InputError :error="form.errors.kode" />
                                </div>
                            </template>

                            <template v-if="hasRole(['superuser', 'user', 'asman', 'spv', 'ampr', 'mpm', 'it']) && !regis">
                                <div class="flex flex-col space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <label for="bagian_sistem" class="w-1/3 capitalize">
                                            {{ __('bagian sistem yang bermasalah') }}
                                        </label>


                                        <Input v-model="form.bagian_sistem"
                                            :placeholder="__('Salin link/url yang mengalami bug/error')" type="text"
                                            disabled required />
                                    </div>

                                    <InputError :error="form.errors.bagian_sistem" />
                                </div>

                                <div class="flex flex-col space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <label for="tanggal" class="w-1/3 capitalize">
                                            {{ __('Tanggal ditemukan kendala') }}
                                        </label>

                                        <Input v-model="form.tanggal"
                                            :placeholder="__('Salin link/url yang mengalami bug/error')" type="text"
                                            disabled required />
                                    </div>

                                    <InputError :error="form.errors.tanggal" />
                                </div>

                                <div class="flex flex-col space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <label for="bagian_pelapor" class="w-1/3 capitalize">
                                            {{ __('bagian pembuat laporan') }}
                                        </label>


                                        <Input v-model="form.bagian_pelapor"
                                            :placeholder="__('Salin link/url yang mengalami bug/error')" type="text"
                                            disabled required />
                                    </div>

                                    <InputError :error="form.errors.bagian_pelapor" />
                                </div>

                                <div class="flex flex-col space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <label for="kategori" class="w-1/3 capitalize">
                                            {{ __('kategori kendala yang ditemukan') }}
                                        </label>


                                        <Input v-model="form.kategori"
                                            :placeholder="__('Salin link/url yang mengalami bug/error')" type="text"
                                            disabled required />
                                    </div>

                                    <InputError :error="form.errors.kategori" />
                                </div>

                                <div class="flex flex-col space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <label for="kendala" class="w-1/3 capitalize">
                                            {{ __('uraian kendala yang ditemukan') }}
                                        </label>

                                        <TextArea v-model="form.kendala"
                                            :placeholder="__('isi uraian kendala yang ditemukan')" required disabled />
                                    </div>

                                    <InputError :error="form.errors.kendala" />
                                </div>

                                <div class="flex flex-col space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <label for="dampak" class="w-1/3 capitalize">
                                            {{ __('dampak dari kendala') }}
                                        </label>

                                        <TextArea v-model="form.dampak" :placeholder="__('isi dampak dari kendala')"
                                            required disabled />
                                    </div>

                                    <InputError :error="form.errors.dampak" />
                                </div>

                                <div class="flex flex-col space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <label for="url" class="w-1/3 capitalize">
                                            {{ __('Link url') }}
                                        </label>

                                        <Input v-model="form.url"
                                            :placeholder="__('Salin link/url yang mengalami bug/error')" type="text"
                                            disabled required />
                                    </div>

                                    <InputError :error="form.errors.url" />
                                </div>

                                <div class="flex flex-col space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <label for="kontak" class="w-1/3 capitalize">
                                            {{ __('kontak yang dapat dihubungi') }}
                                        </label>

                                        <Input v-model="form.kontak" :placeholder="__('kontak yang dapat dihubungi')"
                                            type="text" disabled required />
                                    </div>

                                    <InputError :error="form.errors.kontak" />
                                </div>
                                <div class="flex flex-col space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <label for="balasan" class="w-1/3 capitalize">
                                            {{ __('kirim balasan anda') }}
                                        </label>
                                        <Input v-model="form1.balasan" :placeholder="__('kirim balasan anda')" type="text"
                                            required />
                                    </div>


                                    <InputError :error="form.errors.kontak" />
                                </div>
                            </template>
                        </div>
                    </template>

                    <template #footer>
                        <div class="flex items-center justify-end space-x-2 bg-gray-200 px-2 py-1">
                            <ButtonGreen type="submit" v-if="!regis">
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