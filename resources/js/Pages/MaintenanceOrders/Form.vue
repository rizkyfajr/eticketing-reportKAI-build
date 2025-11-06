<script setup>
import { ref, watch } from 'vue'
import { useForm, Link } from '@inertiajs/inertia-vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Card from '@/Components/Card.vue'
import Button from '@/Components/Button.vue'
import ButtonBlue from '@/Components/Button/Blue.vue'
import ButtonRed from '@/Components/Button/Red.vue'
import Input from '@/Components/Input.vue'
import InputError from '@/Components/InputError.vue'

const props = defineProps({
  order: Object,
  machines: Array,
  reports: Array,
})

const form = useForm({
  working_report_id: props.order?.working_report_id ?? (props.reports?.[0]?.id ?? null),
  master_machine_id: props.order?.master_machine_id ?? null,
  category: (props.order?.category ?? 'unplanned'),   // <- default string
  title: props.order?.title ?? '',

  // UNPLANNED
  trouble_at: props.order?.trouble_at ?? '',
  location: props.order?.location ?? '',
  problem_note: props.order?.problem_note ?? '',

  // PLANNED
  plan_start_at: props.order?.plan_start_at ?? '',
  action_plan: props.order?.action_plan ?? '',
})

const forceKey = ref(0)

watch(() => form.category, (val) => {
  if (val === 'unplanned') {
    form.plan_start_at = ''
    form.action_plan   = ''
  } else if (val === 'planned') {
    form.trouble_at   = ''
    form.location     = ''
    form.problem_note = ''
  }
})

function onCategoryChange(e) {
  const v = String(e.target.value || '').trim().toLowerCase()
  form.category = (v === 'planned' || v === 'unplanned') ? v : 'unplanned'
  forceKey.value++
}

function submit() {
  console.log('data yang dikirim:', form.data())
  const action = props.order
    ? form.put(route('maintenance-orders.update', props.order.id), {
        onSuccess: () => {
          console.log('Berhasil simpan')
          window.location = route('maintenance-orders.index')
        },
        onError: (err) => console.error('Error validasi:', err),
      })
    : form.post(route('maintenance-orders.store'), {
        onSuccess: () => {
          console.log('Berhasil tambah')
          window.location = route('maintenance-orders.index')
        },
        onError: (err) => console.error('Error validasi:', err),
      })
}


const unplannedStore = ref({
  trouble_at: form.trouble_at,
  location: form.location,
  problem_note: form.problem_note,
})

const plannedStore = ref({
  plan_start_at: form.plan_start_at,
  action_plan: form.action_plan,
})

watch(() => form.category, (val, oldVal) => {
  if (oldVal === 'unplanned') {
    unplannedStore.value = {
      trouble_at: form.trouble_at,
      location: form.location,
      problem_note: form.problem_note,
    }
  } else if (oldVal === 'planned') {
    plannedStore.value = {
      plan_start_at: form.plan_start_at,
      action_plan: form.action_plan,
    }
  }

  if (val === 'unplanned') {
    form.trouble_at   = unplannedStore.value.trouble_at ?? ''
    form.location     = unplannedStore.value.location ?? ''
    form.problem_note = unplannedStore.value.problem_note ?? ''
    // kosongkan field planned di UI saja (opsional)
    form.plan_start_at = ''
    form.action_plan   = ''
  } else if (val === 'planned') {
    form.plan_start_at = plannedStore.value.plan_start_at ?? ''
    form.action_plan   = plannedStore.value.action_plan ?? ''
    // kosongkan field unplanned di UI saja (opsional)
    form.trouble_at   = ''
    form.location     = ''
    form.problem_note = ''
  }
}, { immediate: true })
</script>

<template>
  <DashboardLayout :title="props.order ? __('Edit Maintenance Order') : __('Tambah Maintenance Order')">
    <main class="p-0 py-0 mb-[1.25rem] ml-[1.25rem] mt-[1.25rem]">
      <h2 class="font-bold text-2xl">{{ props.order ? 'Edit' : 'Tambah' }} Maintenance Order</h2>
      <a :href="route('maintenance-orders.index')" class="text-sm text-gray-500 font-semibold hover:text-sky-600">Maintenance Order</a>
      <span class="font-semibold text-sm px-2">-</span>
      <span class="text-sm text-gray-500 font-semibold">{{ props.order ? 'Edit' : 'Tambah' }}</span>
    </main>

    <Card class="bg-white pt-[1.875rem] pb-[2.5rem] shadow-lg border border-solid border-slate-200" style="border-radius:.625rem;">
      <template #body>
        <!-- :key memaksa Vue rebuild isi ketika kategori ganti -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4" :key="forceKey">

          <!-- Working Report -->
          <div>
            <label class="block text-sm font-semibold mb-1">Working Report</label>
            <select v-model="form.working_report_id" class="w-full border rounded p-2">
              <option :value="null" disabled>Pilih Working Report</option>
              <option v-for="r in props.reports" :key="r.id" :value="r.id">WR #{{ r.id }}</option>
            </select>
            <InputError :message="form.errors.working_report_id" class="mt-1" />
          </div>

          <!-- Mesin -->
          <div>
            <label class="block text-sm font-semibold mb-1">Mesin</label>
            <select v-model="form.master_machine_id" class="w-full border rounded p-2">
              <option :value="null" disabled>Pilih Mesin</option>
              <option v-for="m in props.machines" :key="m.id" :value="m.id">
                {{ m.name }} — {{ m.nomor }} ({{ m.no_sarana }})
              </option>
            </select>
            <InputError :message="form.errors.master_machine_id" class="mt-1" />
          </div>

          <!-- Kategori -->
          <div>
            <label class="block text-sm font-semibold mb-1">Kategori</label>
            <select :value="form.category" @change="onCategoryChange" class="w-full border rounded p-2">
              <option value="unplanned">Unplanned (Gangguan)</option>
              <option value="planned">Planned (Perawatan)</option>
            </select>
            <InputError :message="form.errors.category" class="mt-1" />
            <small class="text-[11px] text-slate-500">kategori (live): {{ JSON.stringify(form.category) }}</small>
          </div>

          <!-- Judul -->
          <div>
            <label class="block text-sm font-semibold mb-1">Judul</label>
            <Input v-model="form.title" placeholder="Contoh: Gangguan motor kanan / Perawatan berkala pompa" />
            <InputError :message="form.errors.title" class="mt-1" />
          </div>

          <!-- === UNPLANNED (Gangguan) === -->
          <div v-if="form.category === 'unplanned'" class="md:col-span-2">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-semibold mb-1">Waktu Gangguan <span class="text-red-600">*</span></label>
                <input type="datetime-local" v-model="form.trouble_at" class="w-full border rounded p-2" />
                <InputError :message="form.errors.trouble_at" class="mt-1" />
              </div>

              <div>
                <label class="block text-sm font-semibold mb-1">Lokasi</label>
                <Input v-model="form.location" placeholder="Contoh: Binjai KM 12" />
                <InputError :message="form.errors.location" class="mt-1" />
              </div>

              <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-1">Catatan Gangguan</label>
                <textarea v-model="form.problem_note" rows="4" class="w-full border rounded p-2"
                          placeholder="Gejala, indikator, kondisi awal, dsb."></textarea>
                <InputError :message="form.errors.problem_note" class="mt-1" />
              </div>
            </div>
          </div>

          <!-- === PLANNED (Perawatan) === -->
          <div v-else-if="form.category === 'planned'" class="md:col-span-2">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-semibold mb-1">Rencana Mulai <span class="text-red-600">*</span></label>
                <input type="datetime-local" v-model="form.plan_start_at" class="w-full border rounded p-2" />
                <InputError :message="form.errors.plan_start_at" class="mt-1" />
              </div>

              <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-1">Rencana Tindak Lanjut / Deskripsi Perawatan</label>
                <textarea v-model="form.action_plan" rows="4" class="w-full border rounded p-2"
                          placeholder="Checklist pekerjaan, interval, parts disiapkan, dsb."></textarea>
                <InputError :message="form.errors.action_plan" class="mt-1" />
              </div>
            </div>
          </div>
        </div>

        <div class="flex gap-2 mt-6">
          <ButtonBlue :disabled="form.processing" @click.prevent="submit">
            {{ form.processing ? 'Menyimpan…' : 'Simpan' }}
          </ButtonBlue>

          <Button class="border">
            <Link :href="route('maintenance-orders.index')">Batal</Link>
          </Button>

          <ButtonRed v-if="props.order" @click.prevent="$inertia.delete(route('maintenance-orders.destroy', props.order.id))">
            Hapus
          </ButtonRed>
        </div>
      </template>
    </Card>
  </DashboardLayout>
</template>
