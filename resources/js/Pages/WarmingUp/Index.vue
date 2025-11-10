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
import Button from '@/Components/Button.vue'
import ButtonBlue from '@/Components/Button/Blue.vue'
import ButtonRed from '@/Components/Button/Red.vue'

const props = defineProps({
    warmingup: Object,
    warmingup_user: Array,
})

const form = useForm({
    id: props.warmingup?.id || null,
    machine_id: props.warmingup?.machine_id || null,
    waktu_start_engine: props.warmingup?.waktu_start_engine || null,
    jam_kerja: props.warmingup?.jam_kerja || null,
    jam_mesin: props.warmingup?.jam_mesin || null,
    jam_genset: props.warmingup?.jam_genset || null,
    counter_pecok: props.warmingup?.counter_pecok || null,
    oddometer: props.warmingup?.oddometer || null,
    waktu_stop_engine: props.warmingup?.waktu_stop_engine || null,
    penggunaan_hsd: props.warmingup?.penggunaan_hsd || null,
    hsd_tersedia: props.warmingup?.hsd_tersedia || null,
    note: props.warmingup?.note || null,
    user_id: props.warmingup?.warmingup_user.map(warmingup_user => warmingup_user.user_id) || [],
});

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
  Swal.fire({
    title: 'Menyimpan data...',
    didOpen: () => Swal.showLoading(),
    allowOutsideClick: false,
  })

  form.post(route('warming-up.store', props.id), {
    onSuccess: () => {
      Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: 'Data berhasil disimpan.',
        timer: 1500,
        showConfirmButton: false,
      })
    },
    onError: () => {
      Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: 'Terjadi kesalahan saat menyimpan data.',
      })
    },
  })
}

const edit = (region) => {
    form.id = region.id
    form.name = region.name
    show();
}

const update = () => {
    return form.patch(route('master-regions.update', form.id), {
        onSuccess: () => close(),
        onError: () => show(),
    })
}

const destroy = async warmingup => {
    const response = await Swal.fire({
        title: __('Apakah Anda Yakin') + '?',
        text: __('Anda tidak dapat mengembalikannya setelah dihapus'),
        icon: 'question',
        showCancelButton: true,
        showCloseButton: true,
    })

    if (response.isConfirmed) {
        return form.delete(route('warming-up.destroy', props.warmingup.id), {
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
    <DashboardLayout :title="__('Warming Up')">
        <Card class="bg-white pt-[1.100rem] pb-[2.5rem] shadow-lg border border-solid border-slate-200" style="border-radius: 0.625rem;">
            <template #header>
                <div class="flex items-center justify-end px-4 py-1 rounded space-x-2 p-2 pr-[1.688rem]">
                    <Link :href="route('warming-up.create')">
                      <Button
                          v-if="can('create warmingup')"
                          class="grid md:grid-cols text-center items-center bg-green-600 hover:bg-green-800"
                      >
                        <p class="font-bold text-xs">
                          {{ __('Tambah') }}
                        </p>
                      </Button>
                    </Link>
                </div>
            </template>

            <template #body>
                <div class="flex flex-col space-y-1">
                    <Builder v-if="render" :url="route('warming-up.paginate')" ref="table">
                        <template #thead="table">
                            <tr class="bg-gray-200 border-gray-300">
                                <Th :table="table" :sort="false" name="id"
                                    class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                    {{ __('no') }}
                                </Th>

                                <Th :table="table" :sort="true" name="id"
                                    class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                    {{ __('Nama Mesin') }}
                                </Th>

                                <Th :table="table" :sort="true" name="name"
                                    class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                    {{ __('Waktu Start Engine') }}
                                </Th>

                                <Th :table="table" :sort="true" name="name"
                                    class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                    {{ __('Jam Kerja') }}
                                </Th>

                                <Th :table="table" :sort="true" name="name"
                                    class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                    {{ __('Jam Mesin') }}
                                </Th>

                                <Th :table="table" :sort="true" name="name"
                                    class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                    {{ __('Jam Genset') }}
                                </Th>

                                <Th :table="table" :sort="true" name="name"
                                    class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                    {{ __('Counter Pecok') }}
                                </Th>

                                <Th :table="table" :sort="true" name="name"
                                    class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                    {{ __('Oddometer') }}
                                </Th>

                                <Th :table="table" :sort="true" name="name"
                                    class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                    {{ __('Waktu Stop Engine') }}
                                </Th>

                                <Th :table="table" :sort="true" name="name"
                                    class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                    {{ __('Penggunaan HSD') }}
                                </Th>

                                <Th :table="table" :sort="true" name="name"
                                    class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                    {{ __('HSD Tersedia') }}
                                </Th>

                                <Th :table="table" :sort="true" name="name"
                                    class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                    {{ __('Keterangan') }}
                                </Th>

                                <Th :table="table" :sort="true"
                                    class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
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
                                            <p class="lowercase first-letter:capitalize font-semibold text-xs">
                                                {{ __('Tidak ada data untuk ditampilkan.') }}
                                            </p>
                                        </td>
                                    </tr>
                                </template>

                                <template v-else>
                                    <tr v-for="(warmingup, i) in data" :key="warmingup.id" :class="processing && 'bg-gray-100'"
                                        class="transition-all duration-300">
                                        <td class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                            {{ i + 1 }}
                                        </td>
                                        
                                        <td class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                              {{ warmingup.machine ? `${warmingup.machine.name} - ${warmingup.machine.type} - ${warmingup.machine.nomor} - ${warmingup.machine.no_sarana} (${warmingup.machine.region?.name})` : '-' }}
                                        </td>
                                        
                                        <td class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                            {{ __(warmingup.waktu_start_engine) }}
                                        </td>
                                        
                                        <td class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                            {{ __(warmingup.jam_kerja.slice(0, 5)) }}
                                        </td>
                                        
                                        <td class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                            {{ __(warmingup.jam_mesin.slice(0, 5)) }}
                                        </td>
                                        
                                        <td class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                            {{ __(warmingup.jam_genset.slice(0, 5)) }}
                                        </td>
                                        
                                        <td class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                            {{ __(warmingup.counter_pecok) }}
                                        </td>
                                        
                                        <td class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                            {{ __(warmingup.oddometer) }}
                                        </td>
                                        
                                        <td class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                            {{ __(warmingup.waktu_stop_engine) }}
                                        </td>
                                        
                                        <td class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                            {{ __(warmingup.penggunaan_hsd) }}
                                        </td>
                                        
                                        <td class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                            {{ __(warmingup.hsd_tersedia) }}
                                        </td>
                                        
                                        <td class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                            {{ __(warmingup.note) }}
                                        </td>

                                        <td class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                        <div class="flex justify-center gap-2">
                                                <Button class="bg-gray-600 text-white text-sm px-0 py-0 rounded-md hover:bg-gray-700">
                                                    <Link :href="route('warming-up.detail', warmingup.id)" class="bg-gray-600 text-white px-2 py-0.5 rounded hover:bg-gray-700 transition duration-150"> 
                                                        <Icon name="eye" class="w-4 h-4"/> 
                                                    </Link>
                                                </Button>

                                                <Button class="bg-blue-600 text-white text-sm px-0 py-0 rounded-md hover:bg-blue-700">
                                                    <Link v-if="can('update warmingup')" :href="route('warming-up.edit', warmingup.id)" class="bg-blue-600 text-white px-2 py-0.5 rounded hover:bg-blue-700 transition duration-150"> 
                                                        <Icon name="pen" class="w-4 h-4"/> 
                                                    </Link>
                                                </Button>

                                                <ButtonRed
                                                    v-if="can('delete warmingup')"
                                                    @click.prevent="destroy(warmingup)">
                                                    <Icon name="trash" />
                                                    <!-- <p class="font-bold text-xs">
                                                        {{ __('Hapus') }}
                                                    </p> -->
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
    </DashboardLayout>
</template>

