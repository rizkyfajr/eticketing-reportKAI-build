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
import ButtonRed from '@/Components/Button/Red.vue'
import Input from '@/Components/Input.vue'
import InputError from '@/Components/InputError.vue'

const { classification } = defineProps({
    classification: Array,
})

const form = useForm({
  name: '',
})

const render = ref(true)
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
    return form.post(route('master-classifications.store'), {
        onSuccess: () => close(),
        onError: () => show(),
    })
}

const edit = (classification) => {
    form.id = classification.id
    form.name = classification.name
    show()
}

const update = () => {
    return form.patch(route('master-classifications.update', form.id), {
        onSuccess: () => close(),
        onError: () => show(),
    })
}

const destroy = async classification => {
    const response = await Swal.fire({
        title: __('Apakah Anda Yakin') + '?',
        text: __('Anda tidak dapat mengembalikannya setelah dihapus'),
        icon: 'question',
        showCancelButton: true,
        showCloseButton: true,
    })

    if (response.isConfirmed) {
        return form.delete(route('master-classifications.destroy', classification.id), {
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
    <DashboardLayout :title="__('Klasifikasi')">
        <Card class="bg-white pt-[1.100rem] pb-[2.5rem] shadow-lg border border-solid border-slate-200" style="border-radius: 0.625rem;">
            <template #header>
                <div class="flex items-center justify-end px-4 py-1 rounded space-x-2 p-2 pr-[1.688rem]">
                    <Button v-if="can('create classification')" @click.prevent="form.id = null; show()"
                        class="grid md:grid-cols text-center items-center bg-green-600 hover:bg-green-800"
                       >
                        <p class="font-bold text-xs">
                            {{ __('Tambah') }}
                        </p>
                    </Button>
                </div>
            </template>

            <template #body>
                <div class="flex flex-col space-y-2">
                    <Builder v-if="render" :url="route('master-classifications.paginate')" ref="table">
                        <template #thead="table">
                            <tr class="bg-gray-100 border-b border-gray-300">
                                <Th :table="table" :sort="false" name="id"
                                    class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                    {{ __('no').toUpperCase() }}
                                </Th>

                                <Th :table="table" :sort="false" name="name"
                                    class="border border-gray-300 px-3 py-1 text-center capitalize font-extrabold text-xs">
                                    {{ __('Nama Klasifikasi').toUpperCase() }}
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
                                v-for="(classification, i) in data"
                                :key="classification.id"
                                :class="processing && 'bg-gray-100'"
                                class="transition-all duration-300"
                              >
                                <td class="border-b uppercase border-gray-300 px-4 py-1 text-center text-xs">
                                  {{ i + 1 }}
                                </td>

                                <td class="border-b uppercase border-gray-300 px-4 py-1 text-center text-xs">
                                  {{ classification.name }}
                                </td>

                                <td class="ppx-2 py-1 border-b text-center">
                                  <div class="flex justify-center gap-2">
                                    <ButtonBlue
                                      v-if="can('update classification')"
                                      @click.prevent="edit(classification)"
                                    >
                                      <Icon name="edit" />
                                      <p class="font-bold text-xs">{{ __('Ubah') }}</p>
                                    </ButtonBlue>

                                    <ButtonRed
                                      v-if="can('delete classification')"
                                      @click.prevent="destroy(classification)"
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
                    <template #header>
                        <div class="flex items-center justify-end bg-gray-200 rounded-lg p-2 pb-[5px] pt-[5px]">
                            <Close @click.prevent="close" />
                        </div>
                    </template>

                    <template #body>
                        <div class="flex flex-col space-y-4 p-4">
                            <div class="flex flex-col space-y-2">
                                <div class="flex items-center space-x-2">
                                    <label for="name" class="w-1/3 capitalize text-sm">
                                        {{ __('Nama Klasifikasi') }}
                                    </label>

                                    <Input v-model="form.name"
                                        :placeholder="__('Nama Klasifikasi')"
                                        type="text"
                                        required
                                        class="text-sm"
                                    />
                                </div>

                                <InputError :error="form.errors.name" />
                            </div>
                        </div>
                    </template>

                    <template #footer>
                        <div class="flex items-center justify-end space-x-2 bg-gray-200 rounded-lg px-2 py-1 pb-[5px] pt-[5px]">
                            <Button type="submit" class="grid md:grid-cols text-center items-center bg-green-600 hover:bg-green-800">
                                <p class="capitalize font-semibold">
                                    {{ __(form.id ? 'simpan' : 'simpan') }}
                                </p>
                            </Button>
                        </div>
                    </template>
                </Card>
            </form>
        </Modal>

    </DashboardLayout>
</template>
