<script setup>
import { ref, watch, computed } from 'vue'
import { useForm, Link } from '@inertiajs/inertia-vue3'
import axios from 'axios'

// layout & komponen kamu
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Card from '@/Components/Card.vue'
import ButtonBlue from '@/Components/Button/Blue.vue'
import Button from '@/Components/Button.vue'
import Input from '@/Components/Input.vue'
import InputError from '@/Components/InputError.vue'

const props = defineProps({
  machines: Array, // dikirim dari controller
})

const selectedMachineId = ref(null)
const machineKey = ref(null)

// parent bertingkat
const parentsLv1 = ref([])
const parentsLv2 = ref([])
const parentsLv3 = ref([])
const parentsLv4 = ref([])

// pilihan parent yang dipilih user
const selectedParentLv1 = ref(null)
const selectedParentLv2 = ref(null)
const selectedParentLv3 = ref(null)
const selectedParentLv4 = ref(null)

const form = useForm({
  master_machine_id: null,
  machine_type: '',
  level: 1,
  parent_id: null,
  code: '',
  name: '',
})

const selectedMachine = computed(() => {
  if (!selectedMachineId.value) return null
  return props.machines.find(m => Number(m.id) === Number(selectedMachineId.value)) || null
})

// mesin berubah → set master_machine_id, machine_type, load parent level 1
watch(selectedMachineId, async (val) => {
  form.master_machine_id = val
  form.parent_id = null
  form.level = 1
  selectedParentLv1.value = null
  selectedParentLv2.value = null
  selectedParentLv3.value = null
  selectedParentLv4.value = null
  parentsLv1.value = []
  parentsLv2.value = []
  parentsLv3.value = []
  parentsLv4.value = []

  if (!val) return

  const m = props.machines.find(mm => Number(mm.id) === Number(val))
  const key = m?.hierarchy_code || m?.type || null
  machineKey.value = key
  form.machine_type = key

  if (!key) return

  try {
    const { data } = await axios.get('/api/machine-components', {
      params: { machine_type: key }
    })
    parentsLv1.value = data
  } catch (e) {
    // boleh kamu log
  }
})

// pilih parent lv1 → load lv2
watch(selectedParentLv1, async (val) => {
  selectedParentLv2.value = null
  selectedParentLv3.value = null
  selectedParentLv4.value = null
  parentsLv2.value = []
  parentsLv3.value = []
  parentsLv4.value = []

  if (!val) {
    form.parent_id = null
    form.level = 1
    return
  }

  form.parent_id = val
  form.level = 2

  if (!machineKey.value) return

  try {
    const { data } = await axios.get('/api/machine-components', {
      params: {
        machine_type: machineKey.value,
        parent_id: val,
      }
    })
    parentsLv2.value = data
  } catch (e) {}
})

// pilih parent lv2 → load lv3
watch(selectedParentLv2, async (val) => {
  selectedParentLv3.value = null
  selectedParentLv4.value = null
  parentsLv3.value = []
  parentsLv4.value = []

  if (!val) {
    // balik ke posisi level 2
    form.parent_id = selectedParentLv1.value
    form.level = 2
    return
  }

  form.parent_id = val
  form.level = 3

  if (!machineKey.value) return
  try {
    const { data } = await axios.get('/api/machine-components', {
      params: {
        machine_type: machineKey.value,
        parent_id: val,
      }
    })
    parentsLv3.value = data
  } catch (e) {}
})

// pilih parent lv3 → load lv4
watch(selectedParentLv3, async (val) => {
  selectedParentLv4.value = null
  parentsLv4.value = []

  if (!val) {
    form.parent_id = selectedParentLv2.value
    form.level = 3
    return
  }

  form.parent_id = val
  form.level = 4

  if (!machineKey.value) return
  try {
    const { data } = await axios.get('/api/machine-components', {
      params: {
        machine_type: machineKey.value,
        parent_id: val,
      }
    })
    parentsLv4.value = data
  } catch (e) {}
})

// pilih parent lv4 → artinya kita bikin level 5
watch(selectedParentLv4, (val) => {
  if (!val) {
    form.parent_id = selectedParentLv3.value
    form.level = 4
    return
  }
  form.parent_id = val
  form.level = 5
})

function submit() {
  form.post(route('machine-components.store'))
}
</script>

<template>
  <DashboardLayout title="Tambah Komponen Mesin">
    <main class="p-0 py-0 mb-[1.25rem] ml-[1.25rem] mt-[1.25rem]">
      <h2 class="font-bold text-2xl">Tambah Komponen Mesin</h2>
      <Link :href="route('machine-components.index')" class="text-sm text-gray-500 font-semibold hover:text-sky-600">
        Master Komponen Mesin
      </Link>
      <span class="font-semibold text-sm px-2">-</span>
      <span class="text-sm text-gray-500 font-semibold">Tambah</span>
    </main>

    <Card class="bg-white pt-[1.875rem] pb-[2.5rem] shadow-lg border border-solid border-slate-200" style="border-radius:.625rem;">
      <template #body>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <!-- PILIH MESIN -->
          <div class="md:col-span-2">
            <label class="block text-sm font-semibold mb-1">Mesin</label>
            <select v-model="selectedMachineId" class="w-full border rounded p-2">
              <option :value="null" disabled>Pilih Mesin</option>
              <option v-for="m in props.machines" :key="m.id" :value="m.id">
                {{ m.name }} — {{ m.nomor }}
              </option>
            </select>
            <InputError :message="form.errors.master_machine_id" class="mt-1" />
            <small v-if="selectedMachine" class="block text-[11px] text-slate-500 mt-1">
              machine_type: {{ selectedMachine.hierarchy_code || selectedMachine.type }}
            </small>
          </div>

          <!-- PARENT BERTINGKAT -->
          <div>
            <label class="block text-sm font-semibold mb-1">Parent Level 1 (System)</label>
            <select v-model="selectedParentLv1" class="w-full border rounded p-2">
              <option :value="null">[tanpa parent → level 1]</option>
              <option v-for="c in parentsLv1" :key="c.id" :value="c.id">
                {{ c.code ? c.code + ' - ' : '' }}{{ c.name }}
              </option>
            </select>
          </div>

          <div v-if="selectedParentLv1">
            <label class="block text-sm font-semibold mb-1">Parent Level 2 (Sub System)</label>
            <select v-model="selectedParentLv2" class="w-full border rounded p-2">
              <option :value="null">[taruh di level 2]</option>
              <option v-for="c in parentsLv2" :key="c.id" :value="c.id">
                {{ c.code ? c.code + ' - ' : '' }}{{ c.name }}
              </option>
            </select>
          </div>

          <div v-if="selectedParentLv2">
            <label class="block text-sm font-semibold mb-1">Parent Level 3 (Component)</label>
            <select v-model="selectedParentLv3" class="w-full border rounded p-2">
              <option :value="null">[taruh di level 3]</option>
              <option v-for="c in parentsLv3" :key="c.id" :value="c.id">
                {{ c.code ? c.code + ' - ' : '' }}{{ c.name }}
              </option>
            </select>
          </div>

          <div v-if="selectedParentLv3">
            <label class="block text-sm font-semibold mb-1">Parent Level 4</label>
            <select v-model="selectedParentLv4" class="w-full border rounded p-2">
              <option :value="null">[taruh di level 4]</option>
              <option v-for="c in parentsLv4" :key="c.id" :value="c.id">
                {{ c.code ? c.code + ' - ' : '' }}{{ c.name }}
              </option>
            </select>
          </div>

          <!-- DETAIL KOMPONEN -->
          <div>
            <label class="block text-sm font-semibold mb-1">Kode Komponen</label>
            <Input v-model="form.code" placeholder="A.1.1.2 / E.5.2.2.1" />
            <InputError :message="form.errors.code" class="mt-1" />
          </div>

          <div>
            <label class="block text-sm font-semibold mb-1">Nama Komponen</label>
            <Input v-model="form.name" placeholder="Electric Fuel Pump" />
            <InputError :message="form.errors.name" class="mt-1" />
          </div>

          <div>
            <label class="block text-sm font-semibold mb-1">Level</label>
            <input :value="form.level" disabled class="w-full border rounded p-2 bg-slate-100 text-slate-600" />
          </div>
        </div>

        <div class="flex gap-2 mt-6">
          <ButtonBlue :disabled="form.processing" @click.prevent="submit">
            {{ form.processing ? 'Menyimpan…' : 'Simpan' }}
          </ButtonBlue>
          <Button class="border">
            <Link :href="route('machine-components.index')">Batal</Link>
          </Button>
        </div>
      </template>
    </Card>
  </DashboardLayout>
</template>
