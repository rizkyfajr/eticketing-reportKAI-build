<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue'
import axios from 'axios'
import { Inertia } from '@inertiajs/inertia'
import { useForm, Link } from '@inertiajs/inertia-vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Card from '@/Components/Card.vue'
import ButtonBlue from '@/Components/Button/Blue.vue'
import ButtonRed from '@/Components/Button/Red.vue'
import Button from '@/Components/Button.vue'
import Input from '@/Components/Input.vue'
import InputError from '@/Components/InputError.vue'

const props = defineProps({
  order: Object,
  newOrder: Object, // Order yang baru dibuat untuk wizard flow
  machines: Array,
  reports: Array,
  pedoman: Array,
  users: Array, // tambahan untuk form follow up & repair
})

// === STATE WIZARD ===
const currentStep = ref(1) // Track step mana yang sudah selesai
const completedSteps = ref([]) // Array step yang sudah completed
const savedOrderId = ref(props.order?.id || props.newOrder?.id || null)
const savedOrderData = ref(props.order || props.newOrder || null)

// Debug - hapus setelah selesai
console.log('Props order:', props.order)
console.log('Props newOrder:', props.newOrder)
console.log('Saved Order ID:', savedOrderId.value)

// Jika ada newOrder dari response, mark step 1 as completed
if (props.newOrder && !props.order) {
  completedSteps.value = [1]
  currentStep.value = 2
  console.log('NewOrder detected, moving to step 2')
}

// Computed untuk cek apakah step sudah unlocked
const isStep1Unlocked = computed(() => true) // Step 1 selalu unlocked
const isStep2Unlocked = computed(() => savedOrderId.value !== null || completedSteps.value.includes(1))
const isStep3Unlocked = computed(() => completedSteps.value.includes(2))
const isStep4Unlocked = computed(() => completedSteps.value.includes(3))

const form = useForm({
  working_report_id: props.order?.working_report_id ?? (props.reports?.[0]?.id ?? null),
  master_machine_id: props.order?.master_machine_id ?? null,
  category: props.order?.category ?? 'unplanned',
  title: props.order?.title ?? '',

  lampiran: null, // <-- TAMBAHAN UNTUK FITUR LAMPIRAN

  // unplanned
  trouble_at: props.order?.trouble_at ?? '',
  location: props.order?.location ?? '',
  problem_note: props.order?.problem_note ?? '',
  // planned
  plan_start_at: props.order?.plan_start_at ?? '',
  action_plan: props.order?.action_plan ?? '',
  machine_hours: props.order?.machine_hours ?? null,
  master_pedoman_id: props.order?.master_pedoman_id ?? null,
  // komponen
  component_lv1_id: props.order?.component_lv1_id ?? null,
  component_lv2_id: props.order?.component_lv2_id ?? null,
  component_lv3_id: props.order?.component_lv3_id ?? null,
  component_lv4_id: props.order?.component_lv4_id ?? null,
  component_lv5_id: props.order?.component_lv5_id ?? null,
})

// === Logika hierarki (sudah benar) ===
const selectedMachineId = ref(form.master_machine_id)
const selectedLv1 = ref(form.component_lv1_id)
const selectedLv2 = ref(form.component_lv2_id)
const selectedLv3 = ref(form.component_lv3_id)
const selectedLv4 = ref(form.component_lv4_id)
const componentsLv1 = ref([])
const componentsLv2 = ref([])
const componentsLv3 = ref([])
const componentsLv4 = ref([])
const componentsLv5 = ref([])

const selectedMachine = computed(() => {
  if (!selectedMachineId.value) return null
  return props.machines.find(m => Number(m.id) === Number(selectedMachineId.value)) || null
})
const machineHasHierarchy = computed(() => {
  return !!(selectedMachine.value?.hierarchy_code || selectedMachine.value?.type)
})
const machineKey = computed(() => {
  return selectedMachine.value?.hierarchy_code || selectedMachine.value?.type || null
})

// === Watcher Hierarki (sudah benar) ===
watch(selectedMachineId, async (val) => {
  form.master_machine_id = val
  selectedLv1.value = null; componentsLv1.value = []
  selectedLv2.value = null; componentsLv2.value = []
  selectedLv3.value = null; componentsLv3.value = []
  selectedLv4.value = null; componentsLv4.value = []
  form.component_lv5_id = null; componentsLv5.value = []
  if (!val || !machineKey.value) return
  const { data } = await axios.get('/api/machine-components', { params: { machine_type: machineKey.value } })
  componentsLv1.value = data
})
watch(selectedLv1, async (val) => {
  form.component_lv1_id = val
  selectedLv2.value = null; componentsLv2.value = []
  selectedLv3.value = null; componentsLv3.value = []
  selectedLv4.value = null; componentsLv4.value = []
  form.component_lv5_id = null; componentsLv5.value = []
  if (!val || !machineKey.value) return
  const { data } = await axios.get('/api/machine-components', { params: { machine_type: machineKey.value, parent_id: val } })
  componentsLv2.value = data
})
watch(selectedLv2, async (val) => {
  form.component_lv2_id = val
  selectedLv3.value = null; componentsLv3.value = []
  selectedLv4.value = null; componentsLv4.value = []
  form.component_lv5_id = null; componentsLv5.value = []
  if (!val || !machineKey.value) return
  const { data } = await axios.get('/api/machine-components', { params: { machine_type: machineKey.value, parent_id: val } })
  componentsLv3.value = data
})
watch(selectedLv3, async (val) => {
  form.component_lv3_id = val
  selectedLv4.value = null; componentsLv4.value = []
  form.component_lv5_id = null; componentsLv5.value = []
  if (!val || !machineKey.value) return
  const { data } = await axios.get('/api/machine-components', { params: { machine_type: machineKey.value, parent_id: val } })
  componentsLv4.value = data
})
watch(selectedLv4, async (val) => {
  form.component_lv4_id = val
  form.component_lv5_id = null
  componentsLv5.value = []
  if (!val || !machineKey.value) return
  const { data } = await axios.get('/api/machine-components', { params: { machine_type: machineKey.value, parent_id: val } })
  componentsLv5.value = data
})

// === Perbaikan Bug Trigger Kategori (sudah benar) ===
watch(() => form.category, (newCategory) => {
  if (newCategory === 'unplanned') {
    form.plan_start_at = ''
    form.action_plan = ''
    form.machine_hours = null
    form.master_pedoman_id = null
  } else {
    form.trouble_at = ''
    form.location = ''
    form.problem_note = ''
  }
}, {
  immediate: true
})

// === Fungsi Lainnya (sudah benar) ===
async function loadHierarchyForEdit() {
  if (!form.master_machine_id) return
  selectedMachineId.value = form.master_machine_id
  if (!machineKey.value) return
  const { data: lv1 } = await axios.get('/api/machine-components', { params: { machine_type: machineKey.value } })
  componentsLv1.value = lv1
  if (form.component_lv1_id) {
    selectedLv1.value = form.component_lv1_id
    const { data: lv2 } = await axios.get('/api/machine-components', { params: { machine_type: machineKey.value, parent_id: form.component_lv1_id, } })
    componentsLv2.value = lv2
    if (form.component_lv2_id) {
      selectedLv2.value = form.component_lv2_id
      const { data: lv3 } = await axios.get('/api/machine-components', { params: { machine_type: machineKey.value, parent_id: form.component_lv2_id, } })
      componentsLv3.value = lv3
      if (form.component_lv3_id) {
        selectedLv3.value = form.component_lv3_id
        const { data: lv4 } = await axios.get('/api/machine-components', { params: { machine_type: machineKey.value, parent_id: form.component_lv3_id, } })
        componentsLv4.value = lv4
        if (form.component_lv4_id) {
          selectedLv4.value = form.component_lv4_id
          const { data: lv5 } = await axios.get('/api/machine-components', { params: { machine_type: machineKey.value, parent_id: form.component_lv4_id, } })
          componentsLv5.value = lv5
        }
      }
    }
  }
}

onMounted(() => {
  if (props.order) {
    loadHierarchyForEdit()
    currentStep.value = 1 // jika edit, tetap di step 1
  }
})

function submit() {
  if (props.order) {
    // Mode EDIT: update langsung dan redirect ke index
    form.put(route('maintenance-orders.update', props.order.id))
  } else {
    // Mode CREATE: simpan dulu, backend akan render ulang dengan newOrder
    form.post(route('maintenance-orders.store'), {
      preserveScroll: true,
      onSuccess: (page) => {
        console.log('Step 1 saved, page response:', page)

        // Set savedOrderId dan savedOrderData dari response newOrder
        if (page.props.newOrder && page.props.newOrder.id) {
          savedOrderId.value = page.props.newOrder.id
          savedOrderData.value = page.props.newOrder
          console.log('savedOrderId set to:', savedOrderId.value)
          console.log('savedOrderData:', savedOrderData.value)

          // Re-initialize checklist results jika ada pedoman
          if (savedOrderData.value.master_pedoman) {
            formChecklist.results = initializeChecklistResults()
          }
        }

        completedSteps.value.push(1)
        currentStep.value = 2
      }
    })
  }
}

function destroyOrder() {
  if (!props.order) return
  Inertia.delete(route('maintenance-orders.destroy', props.order.id))
}

// === FORM FOLLOW UP ===
const formFollowUp = useForm({
  follow_up_by_id: null,
  follow_up_plan: '',
  follow_up_estimate_at: '',
})

function submitFollowUp() {
  console.log('submitFollowUp called')
  console.log('savedOrderId:', savedOrderId.value)
  console.log('Form data:', formFollowUp)

  if (!savedOrderId.value) {
    console.error('savedOrderId is null! Cannot submit.')
    alert('Error: Order ID tidak ditemukan. Silakan refresh halaman.')
    return
  }

  formFollowUp.post(route('maintenance-orders.followUp', savedOrderId.value), {
    preserveScroll: true,
    onSuccess: () => {
      console.log('Follow up submitted successfully')
      completedSteps.value.push(2)
      currentStep.value = 3
    },
    onError: (errors) => {
      console.error('Submit failed:', errors)
    }
  })
}

function skipFollowUp() {
  completedSteps.value.push(2)
  currentStep.value = 3
}

// === FORM START REPAIR ===
const formStartRepair = useForm({
  start_repair_by_id: null,
  start_repair_notes: '',
  start_repair_photo: null,
})

function submitStartRepair() {
  if (!savedOrderId.value) return
  formStartRepair.post(route('maintenance-orders.startRepair', savedOrderId.value), {
    preserveScroll: true,
    onSuccess: () => {
      completedSteps.value.push(3)
      currentStep.value = 4
    }
  })
}

function skipStartRepair() {
  completedSteps.value.push(3)
  currentStep.value = 4
}

// === FORM COMPLETE ===
const formComplete = useForm({
  complete_repair_by_id: null,
  complete_repair_notes: '',
  complete_repair_photo: null,
})

function submitComplete() {
  if (!savedOrderId.value) return
  if (!confirm('Apakah Anda yakin ingin menyelesaikan pekerjaan ini?')) return
  formComplete.post(route('maintenance-orders.complete', savedOrderId.value), {
    onSuccess: () => {
      // Selesai, redirect ke index
      Inertia.visit(route('maintenance-orders.index'))
    }
  })
}

// === CHECKSHEET LOGIC (untuk Step 3) ===
const hasChecklist = computed(() => {
  const order = savedOrderData.value
  return order?.master_pedoman &&
         order.master_pedoman.categories &&
         order.master_pedoman.categories.length > 0
})

function initializeChecklistResults() {
  let resultsData = {}
  const order = savedOrderData.value

  if (!order?.master_pedoman?.categories) {
    return {}
  }

  order.master_pedoman.categories.forEach(cat => {
    cat.items.forEach(item => {
      let realisasiVal = ''
      let statusVal = 'OK'
      let catatanVal = ''

      // KHUSUS TIPE TABLE: buat object kosong
      if (item.tipe_input === 'table') {
        realisasiVal = {}
      }

      resultsData[item.id] = {
        realisasi: realisasiVal,
        status: statusVal,
        catatan: catatanVal,
        tipe_input: item.tipe_input
      }
    })
  })

  return resultsData
}

const formChecklist = useForm({
  results: initializeChecklistResults()
})

// Auto-save state
const checklistSaveStatus = ref('') // '', 'saving', 'saved'
let checklistSaveTimeout = null
let isNavigating = ref(false) // Flag untuk mencegah auto-save saat navigasi

function submitChecklist() {
  if (!savedOrderId.value) {
    console.warn('Cannot save checklist: savedOrderId is null')
    return
  }

  // Jangan save jika sedang navigating
  if (isNavigating.value) {
    console.warn('Navigation in progress, skipping auto-save')
    return
  }

  let payload = JSON.parse(JSON.stringify(formChecklist.results))

  // Convert table objects to JSON strings
  Object.keys(payload).forEach(itemId => {
    if (payload[itemId].tipe_input === 'table') {
      payload[itemId].realisasi = JSON.stringify(payload[itemId].realisasi)
    }
  })

  checklistSaveStatus.value = 'saving'
  console.log('Saving checklist...', payload)

  Inertia.post(route('maintenance-orders.saveChecklist', savedOrderId.value),
    { results: payload },
    {
      preserveScroll: true,
      preserveState: true, // Tambahkan ini agar state tidak hilang
      onSuccess: () => {
        checklistSaveStatus.value = 'saved'
        console.log('Checklist saved successfully')
        setTimeout(() => {
          checklistSaveStatus.value = ''
        }, 2000)
      },
      onError: (errors) => {
        checklistSaveStatus.value = ''
        console.error('Failed to save checklist:', errors)
      }
    }
  )
}

// Debounced auto-save function yang dipanggil dari @input event
function triggerAutoSave() {
  if (!savedOrderId.value || !hasChecklist.value) {
    console.warn('Auto-save skipped: no savedOrderId or no checklist')
    return
  }

  // Clear timeout sebelumnya
  if (checklistSaveTimeout) {
    clearTimeout(checklistSaveTimeout)
  }

  // Set timeout baru
  checklistSaveTimeout = setTimeout(() => {
    submitChecklist()
  }, 1500) // Auto-save setelah 1.5 detik tidak ada perubahan
}

// Cleanup saat component unmount untuk menghindari memory leak dan blocking navigation
onUnmounted(() => {
  if (checklistSaveTimeout) {
    clearTimeout(checklistSaveTimeout)
    checklistSaveTimeout = null
  }
  // Remove Inertia event listeners
  Inertia.off('start', handleNavigationStart)
  Inertia.off('finish', handleNavigationFinish)
})

// Handle Inertia navigation events
const handleNavigationStart = () => {
  isNavigating.value = true
  // Clear any pending auto-save
  if (checklistSaveTimeout) {
    clearTimeout(checklistSaveTimeout)
    checklistSaveTimeout = null
  }
  console.log('Navigation started, auto-save disabled')
}

const handleNavigationFinish = () => {
  // Delay reset agar tidak langsung trigger auto-save
  setTimeout(() => {
    isNavigating.value = false
    console.log('Navigation finished, auto-save enabled')
  }, 500)
}

// Setup event listeners saat component mounted
onMounted(() => {
  Inertia.on('start', handleNavigationStart)
  Inertia.on('finish', handleNavigationFinish)
})


function finishLater() {
  // Clear pending auto-save sebelum navigate
  if (checklistSaveTimeout) {
    clearTimeout(checklistSaveTimeout)
    checklistSaveTimeout = null
  }
  // User bisa keluar kapan saja dan melanjutkan nanti dari halaman detail
  Inertia.visit(route('maintenance-orders.index'))
}
</script>

<template>
  <DashboardLayout :title="props.order ? __('Edit Maintenance Order') : __('Tambah Maintenance Order')">
    <main class="p-0 py-0 mb-[1.25rem] ml-[1.25rem] mt-[1.25rem]">
      <h2 class="font-bold text-2xl">
        {{ props.order ? 'Edit' : (currentStep === 1 ? 'Tambah' : 'Lanjutkan') }} Maintenance Order
      </h2>
      <a :href="route('maintenance-orders.index')" class="text-sm text-gray-500 font-semibold hover:text-sky-600">
        Maintenance Order
      </a>
      <span class="font-semibold text-sm px-2">-</span>
      <span class="text-sm text-gray-500 font-semibold">
        {{ props.order ? 'Edit' : (currentStep === 1 ? 'Tambah' : 'Step ' + currentStep + ' dari 4') }}
      </span>
    </main>

    <!-- STEP INDICATOR (hanya tampil saat create dan bukan edit) -->
    <div v-if="!props.order" class="mb-4 px-6">
      <div class="flex items-center justify-center space-x-2">
        <div class="flex items-center cursor-pointer" @click="currentStep = 1">
          <div :class="completedSteps.includes(1) ? 'bg-green-600 text-white' : (currentStep >= 1 ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-600')"
               class="w-10 h-10 rounded-full flex items-center justify-center font-bold">
            <span v-if="completedSteps.includes(1)">✓</span>
            <span v-else>1</span>
          </div>
          <span class="ml-2 text-sm font-semibold">Order</span>
        </div>
        <div class="w-12 h-1" :class="completedSteps.includes(1) ? 'bg-green-600' : 'bg-gray-300'"></div>
        <div class="flex items-center cursor-pointer" @click="isStep2Unlocked && (currentStep = 2)">
          <div :class="completedSteps.includes(2) ? 'bg-green-600 text-white' : (currentStep >= 2 && isStep2Unlocked ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-600')"
               class="w-10 h-10 rounded-full flex items-center justify-center font-bold">
            <span v-if="completedSteps.includes(2)">✓</span>
            <span v-else>2</span>
          </div>
          <span class="ml-2 text-sm font-semibold">Follow Up</span>
        </div>
        <div class="w-12 h-1" :class="completedSteps.includes(2) ? 'bg-green-600' : 'bg-gray-300'"></div>
        <div class="flex items-center cursor-pointer" @click="isStep3Unlocked && (currentStep = 3)">
          <div :class="completedSteps.includes(3) ? 'bg-green-600 text-white' : (currentStep >= 3 && isStep3Unlocked ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-600')"
               class="w-10 h-10 rounded-full flex items-center justify-center font-bold">
            <span v-if="completedSteps.includes(3)">✓</span>
            <span v-else>3</span>
          </div>
          <span class="ml-2 text-sm font-semibold">Start</span>
        </div>
        <div class="w-12 h-1" :class="completedSteps.includes(3) ? 'bg-green-600' : 'bg-gray-300'"></div>
        <div class="flex items-center cursor-pointer" @click="isStep4Unlocked && (currentStep = 4)">
          <div :class="currentStep >= 4 && isStep4Unlocked ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-600'"
               class="w-10 h-10 rounded-full flex items-center justify-center font-bold">4</div>
          <span class="ml-2 text-sm font-semibold">Complete</span>
        </div>
      </div>
    </div>

    <!-- STEP 1: FORM ORDER (UNPLANNED/PLANNED) - SELALU TAMPIL -->
    <Card class="bg-white pt-[1.875rem] pb-[2.5rem] px-6 md:px-8 shadow-lg border border-solid"
          :class="currentStep === 1 ? 'border-blue-500' : 'border-slate-200 opacity-60'"
          style="border-radius:.625rem; margin-bottom: 1rem;">
      <template #body>
        <div class="flex justify-between items-center mb-4">
          <h3 class="font-bold text-lg">
            <span v-if="completedSteps.includes(1)" class="text-green-600">✓</span>
            {{ props.order ? 'Edit' : 'Step 1: Buat' }} Maintenance Order
          </h3>
          <button v-if="!props.order && completedSteps.includes(1)"
                  @click="currentStep = 1"
                  class="text-sm text-blue-600 hover:underline">
            Edit
          </button>
        </div>

        <!-- Form hanya bisa diisi jika current step = 1 ATAU sudah selesai (untuk re-edit) -->
        <div :class="{'pointer-events-none opacity-40': !props.order && currentStep !== 1 && !completedSteps.includes(1)}">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

          <div>
            <label class="block text-sm font-semibold mb-1">Kategori</label>
            <select v-model="form.category" class="w-full border rounded p-2">
              <option value="unplanned">Unplanned (Gangguan)</option>
              <option value="planned">Planned (Perawatan)</option>
            </select>
            <InputError :message="form.errors.category" class="mt-1" />
          </div>

          <div>
            <label class="block text-sm font-semibold mb-1">Judul</label>
            <Input v-model="form.title" placeholder="Contoh: Gangguan motor kanan / Perawatan berkala pompa" />
            <InputError :message="form.errors.title" class="mt-1" />
          </div>

          <div>
            <label class="block text-sm font-semibold mb-1">Lampiran (Foto/PDF)</label>
            <input
              type="file"
              @input="form.lampiran = $event.target.files[0]"
              accept="image/jpeg,image/png,application/pdf"
              capture="environment"
              class="w-full border rounded p-2 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
            />
            <progress v-if="form.progress" :value="form.progress.percentage" max="100">
              {{ form.progress.percentage }}%
            </progress>
            <InputError :message="form.errors.lampiran" class="mt-1" />
          </div>

          <div v-if="props.reports && props.reports.length > 0">
            <label class="block text-sm font-semibold mb-1">Working Report (Opsional)</label>
            <select v-model="form.working_report_id" class="w-full border rounded p-2">
              <option :value="null">-- Tidak Ada --</option>
              <option v-for="r in props.reports" :key="r.id" :value="r.id">WR #{{ r.id }}</option>
            </select>
            <InputError :message="form.errors.working_report_id" class="mt-1" />
          </div>
          <div v-else class="text-sm text-gray-500 italic bg-gray-50 p-3 rounded border border-dashed">
            ℹ️ Working Report belum tersedia. Anda bisa langsung membuat Maintenance Order tanpa Working Report.
          </div>

          <div>
            <label class="block text-sm font-semibold mb-1">Mesin</label>
            <select v-model="selectedMachineId" class="w-full border rounded p-2">
              <option :value="null" disabled>Pilih Mesin</option>
              <option v-for="m in props.machines" :key="m.id" :value="m.id">
                [{{ m.nomor }}] {{ m.name }} - {{ m.type }} - SR {{ m.no_sarana }} ({{ m.region?.name || 'N/A' }})
              </option>
            </select>
            <InputError :message="form.errors.master_machine_id" class="mt-1" />
            <small v-if="selectedMachine" class="block text-[11px] text-slate-500 mt-1">
              key API: {{ selectedMachine.hierarchy_code || selectedMachine.type }}
            </small>
          </div>

          <div v-if="machineHasHierarchy">
            <label class="block text-sm font-semibold mb-1">Level 1 (System)</label>
            <select v-model="selectedLv1" class="w-full border rounded p-2">
              <option :value="null" disabled>
                {{ componentsLv1.length ? 'Pilih Level 1' : 'Tidak ada komponen level 1' }}
              </option>
              <option v-for="c in componentsLv1" :key="c.id" :value="c.id">
                {{ c.code ? c.code + ' - ' : '' }}{{ c.name }}
              </option>
            </select>
            <InputError :message="form.errors.component_lv1_id" class="mt-1" />
          </div>
          <div v-if="selectedLv1">
            <label class="block text-sm font-semibold mb-1">Level 2 (Sub System)</label>
            <select v-model="selectedLv2" class="w-full border rounded p-2">
              <option :value="null" disabled>
                {{ componentsLv2.length ? 'Pilih Level 2' : 'Tidak ada komponen level 2' }}
              </option>
              <option v-for="c in componentsLv2" :key="c.id" :value="c.id">
                {{ c.code ? c.code + ' - ' : '' }}{{ c.name }}
              </option>
            </select>
            <InputError :message="form.errors.component_lv2_id" class="mt-1" />
          </div>
          <div v-if="selectedLv2">
            <label class="block text-sm font-semibold mb-1">Level 3 (Component)</label>
            <select v-model="selectedLv3" class="w-full border rounded p-2">
              <option :value="null" disabled>
                {{ componentsLv3.length ? 'Pilih Level 3' : 'Tidak ada komponen level 3' }}
              </option>
              <option v-for="c in componentsLv3" :key="c.id" :value="c.id">
                {{ c.code ? c.code + ' - ' : '' }}{{ c.name }}
              </option>
            </select>
            <InputError :message="form.errors.component_lv3_id" class="mt-1" />
          </div>
          <div v-if="selectedLv3">
            <label class="block text-sm font-semibold mb-1">Level 4</label>
            <select v-model="selectedLv4" class="w-full border rounded p-2">
              <option :value="null" disabled>
                {{ componentsLv4.length ? 'Pilih Level 4' : 'Tidak ada komponen level 4' }}
              </option>
              <option v-for="c in componentsLv4" :key="c.id" :value="c.id">
                {{ c.code ? c.code + ' - ' : '' }}{{ c.name }}
              </option>
            </select>
            <InputError :message="form.errors.component_lv4_id" class="mt-1" />
          </div>
          <div v-if="selectedLv4">
            <label class="block text-sm font-semibold mb-1">Level 5</label>
            <select v-model="form.component_lv5_id" class="w-full border rounded p-2">
              <option :value="null" disabled>
                {{ componentsLv5.length ? 'Pilih Level 5' : 'Tidak ada komponen level 5' }}
              </option>
              <option v-for="c in componentsLv5" :key="c.id" :value="c.id">
                {{ c.code ? c.code + ' - ' : '' }}{{ c.name }}
              </option>
            </select>
            <InputError :message="form.errors.component_lv5_id" class="mt-1" />
          </div>

          <div v-if="form.category === 'unplanned'" class="md:col-span-2">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-semibold mb-1">Waktu Gangguan</label>
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

          <div v-else-if="form.category === 'planned'" class="md:col-span-2">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

              <div>
                <label class="block text-sm font-semibold mb-1">Pedoman Perawatan (Checklist)</label>
                <select v-model="form.master_pedoman_id" class="w-full border rounded p-2">
                    <option :value="null" disabled>Pilih Pedoman Checklist</option>
                    <option v-for="p in props.pedoman" :key="p.id" :value="p.id">
                      {{ p.kode_pedoman }}
                    </option>
                </select>
                <InputError :message="form.errors.master_pedoman_id" class="mt-1" />
              </div>

              <div>
                <label class="block text-sm font-semibold mb-1">Rencana Mulai</label>
                <input type="datetime-local" v-model="form.plan_start_at" class="w-full border rounded p-2" />
                <InputError :message="form.errors.plan_start_at" class="mt-1" />
              </div>

              <div>
                <label class="block text-sm font-semibold mb-1">Machine Hours (Opsional)</label>
                <Input type="number" v-model="form.machine_hours" placeholder="Contoh: 1250" />
                <InputError :message="form.errors.machine_hours" class="mt-1" />
              </div>

              <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-1">Rencana Tindak Lanjut / Catatan Tambahan</label>
                <textarea v-model="form.action_plan" rows="4" class="w-full border rounded p-2"
                  placeholder="Catatan tambahan, parts khusus yang disiapkan, dsb."></textarea>
                <InputError :message="form.errors.action_plan" class="mt-1" />
              </div>
            </div>
          </div>
        </div>

        <div class="flex gap-2 mt-6" v-if="!completedSteps.includes(1)">
          <ButtonBlue :disabled="form.processing" @click.prevent="submit">
            {{ form.processing ? 'Menyimpan…' : (props.order ? 'Simpan' : 'Simpan & Lanjutkan') }}
          </ButtonBlue>
          <Button class="border">
            <Link :href="route('maintenance-orders.index')">Batal</Link>
          </Button>
          <ButtonRed v-if="props.order" @click.prevent="destroyOrder">
            Hapus
          </ButtonRed>
        </div>
        </div>
      </template>
    </Card>

    <!-- STEP 2: FOLLOW UP PLAN - SELALU TAMPIL -->
    <Card class="bg-white pt-[1.875rem] pb-[2.5rem] px-6 md:px-8 shadow-lg border border-solid"
          :class="currentStep === 2 ? 'border-blue-500' : (isStep2Unlocked ? 'border-slate-200' : 'border-slate-200 opacity-50')"
          style="border-radius:.625rem; margin-bottom: 1rem;">
      <template #body>
        <div class="flex justify-between items-center mb-4">
          <h3 class="font-bold text-lg text-blue-700">
            <span v-if="completedSteps.includes(2)" class="text-green-600">✓</span>
            Step 2: Follow Up Plan
          </h3>
        </div>

        <!-- Alert jika belum unlock -->
        <div v-if="!isStep2Unlocked" class="p-4 bg-gray-50 border border-gray-200 rounded text-center">
          <p class="text-gray-600">🔒 Selesaikan Step 1 terlebih dahulu untuk membuka form ini</p>
        </div>

        <!-- Alert Sukses -->
        <div v-else-if="props.newOrder && !completedSteps.includes(2)" class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
          <p class="text-green-800 font-semibold">✓ Maintenance Order #{{ props.newOrder.id }} berhasil dibuat!</p>
          <p class="text-sm text-green-700 mt-1">Silakan lanjutkan dengan Follow Up Plan atau lewati step ini.</p>
        </div>

        <!-- Info jika sudah completed -->
        <div v-else-if="completedSteps.includes(2)" class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
          <p class="text-green-800 font-semibold">✓ Follow Up Plan sudah diselesaikan!</p>
        </div>

        <!-- Form - tampil jika unlocked dan belum completed -->
        <form v-if="isStep2Unlocked && !completedSteps.includes(2)" @submit.prevent="submitFollowUp" class="space-y-4">
          <div>
            <label class="block text-sm font-semibold mb-1">Nama Teknisi / KAOP</label>
            <select v-model="formFollowUp.follow_up_by_id" class="w-full border rounded p-2">
              <option :value="null">Pilih User</option>
              <option v-for="u in props.users" :key="u.id" :value="u.id">{{ u.name }}</option>
            </select>
            <InputError :message="formFollowUp.errors.follow_up_by_id" class="mt-1" />
          </div>
          <div>
            <label class="block text-sm font-semibold mb-1">Rencana Tindak Lanjut</label>
            <textarea v-model="formFollowUp.follow_up_plan" rows="3" class="w-full border rounded p-2" placeholder="Jelaskan rencana tindak lanjut..."></textarea>
            <InputError :message="formFollowUp.errors.follow_up_plan" class="mt-1" />
          </div>
          <div>
            <label class="block text-sm font-semibold mb-1">Estimasi Waktu</label>
            <input type="datetime-local" v-model="formFollowUp.follow_up_estimate_at" class="w-full border rounded p-2" />
            <InputError :message="formFollowUp.errors.follow_up_estimate_at" class="mt-1" />
          </div>
          <div class="flex gap-2 justify-end">
            <ButtonBlue :disabled="formFollowUp.processing">
              {{ formFollowUp.processing ? 'Menyimpan…' : 'Simpan & Lanjutkan' }}
            </ButtonBlue>
            <Button class="border" @click.prevent="skipFollowUp">Lewati Step Ini</Button>
            <Button class="border" @click.prevent="finishLater">Selesaikan Nanti</Button>
          </div>
        </form>
      </template>
    </Card>

    <!-- STEP 3: START REPAIR - SELALU TAMPIL -->
    <Card class="bg-white pt-[1.875rem] pb-[2.5rem] px-6 md:px-8 shadow-lg border border-solid"
          :class="currentStep === 3 ? 'border-cyan-500' : (isStep3Unlocked ? 'border-slate-200' : 'border-slate-200 opacity-50')"
          style="border-radius:.625rem; margin-bottom: 1rem;">
      <template #body>
        <div class="flex justify-between items-center mb-4">
          <h3 class="font-bold text-lg text-cyan-700">
            <span v-if="completedSteps.includes(3)" class="text-green-600">✓</span>
            Step 3: Mulai Perbaikan
          </h3>
        </div>

        <!-- Alert jika belum unlock -->
        <div v-if="!isStep3Unlocked" class="p-4 bg-gray-50 border border-gray-200 rounded text-center">
          <p class="text-gray-600">🔒 Selesaikan Step 2 terlebih dahulu untuk membuka form ini</p>
        </div>

        <!-- Info jika sudah completed -->
        <div v-else-if="completedSteps.includes(3)" class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
          <p class="text-green-800 font-semibold">✓ Perbaikan sudah dimulai!</p>
        </div>

        <!-- Form - tampil jika unlocked dan belum completed -->
        <form v-if="isStep3Unlocked && !completedSteps.includes(3)" @submit.prevent="submitStartRepair" class="space-y-4">

          <!-- CHECKSHEET - jika ada pedoman -->
          <div v-if="hasChecklist" class="mb-6 p-4 bg-yellow-50 border border-yellow-300 rounded-lg">
            <div class="flex justify-between items-center mb-4">
              <h4 class="font-bold text-lg text-yellow-800">📋 Checklist: {{ savedOrderData.master_pedoman.nama_pedoman }}</h4>

              <!-- Auto-save indicator -->
              <div class="text-sm">
                <span v-if="checklistSaveStatus === 'saving'" class="text-blue-600 flex items-center gap-1">
                  <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  Menyimpan...
                </span>
                <span v-else-if="checklistSaveStatus === 'saved'" class="text-green-600 flex items-center gap-1">
                  <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                  </svg>
                  Tersimpan otomatis
                </span>
                <span v-else class="text-gray-500 text-xs">
                  💡 Auto-save aktif
                </span>
              </div>
            </div>

            <div class="space-y-6">
              <div v-for="category in savedOrderData.master_pedoman.categories" :key="category.id">
                <h5 class="font-bold text-md bg-yellow-100 p-2 rounded mb-3 border-l-4 border-yellow-600">{{ category.name }}</h5>

                <div class="space-y-4">
                  <div v-for="item in category.items" :key="item.id" class="border rounded-lg p-4 bg-white shadow-sm">

                    <!-- Tipe IMAGE -->
                    <div v-if="item.tipe_input === 'image'" class="text-center">
                      <p class="font-semibold mb-2">{{ item.deskripsi }}</p>
                      <img v-if="item.gambar_referensi_path" :src="`/storage/${item.gambar_referensi_path}`" alt="Diagram Referensi" class="mx-auto max-w-full h-auto rounded border" />
                    </div>

                    <!-- Tipe TABLE -->
                    <div v-else-if="item.tipe_input === 'table'">
                      <p class="font-semibold mb-2"><span class="font-bold mr-2">{{ item.nomor_poin }}</span>{{ item.deskripsi }}</p>

                      <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left border">
                          <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                              <th class="px-2 py-2 border">Posisi</th>
                              <th v-for="(col, idx) in item.extra_config.columns" :key="idx" class="px-2 py-2 border text-center">
                                {{ col.name }}
                                <br><span class="text-[10px] text-gray-500">Std: {{ col.std }}</span>
                              </th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(rowLabel, rIdx) in item.extra_config.rows_label" :key="rIdx" class="border-b">
                              <td class="px-2 py-2 font-medium border bg-gray-50">{{ rowLabel }}</td>
                              <td v-for="(col, cIdx) in item.extra_config.columns" :key="cIdx" class="p-1 border">
                                <input
                                  type="text"
                                  class="w-full border-gray-300 rounded text-xs p-1 focus:ring-yellow-500 focus:border-yellow-500"
                                  v-model="formChecklist.results[item.id].realisasi[rowLabel + '_' + cIdx]"
                                  :placeholder="col.std"
                                  @input="triggerAutoSave"
                                />
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>

                      <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                          <label class="block text-xs font-semibold mb-1">Status</label>
                          <select v-model="formChecklist.results[item.id].status" class="w-full border-gray-300 rounded text-sm p-1" @change="triggerAutoSave">
                            <option value="OK">OK</option>
                            <option value="NOT OK">NOT OK</option>
                          </select>
                        </div>
                        <div>
                          <label class="block text-xs font-semibold mb-1">Catatan</label>
                          <input type="text" v-model="formChecklist.results[item.id].catatan" class="w-full border-gray-300 rounded text-sm p-1" placeholder="Catatan tambahan..." @input="triggerAutoSave" />
                        </div>
                      </div>
                    </div>

                    <!-- Tipe TEXT / LAINNYA -->
                    <div v-else>
                      <p class="font-semibold mb-2"><span class="font-bold mr-2">{{ item.nomor_poin }}</span>{{ item.deskripsi }}</p>
                      <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div>
                          <label class="block text-xs font-semibold mb-1">Realisasi</label>
                          <input type="text" v-model="formChecklist.results[item.id].realisasi" class="w-full border-gray-300 rounded text-sm p-2" @input="triggerAutoSave" />
                        </div>
                        <div>
                          <label class="block text-xs font-semibold mb-1">Status</label>
                          <select v-model="formChecklist.results[item.id].status" class="w-full border-gray-300 rounded text-sm p-2" @change="triggerAutoSave">
                            <option value="OK">OK</option>
                            <option value="NOT OK">NOT OK</option>
                          </select>
                        </div>
                        <div>
                          <label class="block text-xs font-semibold mb-1">Catatan</label>
                          <input type="text" v-model="formChecklist.results[item.id].catatan" class="w-full border-gray-300 rounded text-sm p-2" placeholder="Catatan..." @input="triggerAutoSave" />
                        </div>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- END CHECKSHEET -->

          <hr v-if="hasChecklist" class="my-6" />

          <div>
            <label class="block text-sm font-semibold mb-1">Nama Teknisi</label>
            <select v-model="formStartRepair.start_repair_by_id" class="w-full border rounded p-2">
              <option :value="null">Pilih Teknisi</option>
              <option v-for="u in props.users" :key="u.id" :value="u.id">{{ u.name }}</option>
            </select>
            <InputError :message="formStartRepair.errors.start_repair_by_id" class="mt-1" />
          </div>
          <div>
            <label class="block text-sm font-semibold mb-1">Tindak Lanjut / Keterangan</label>
            <textarea v-model="formStartRepair.start_repair_notes" rows="3" class="w-full border rounded p-2" placeholder="Catatan mulai perbaikan..."></textarea>
            <InputError :message="formStartRepair.errors.start_repair_notes" class="mt-1" />
          </div>
          <div>
            <label class="block text-sm font-semibold mb-1">Upload Foto</label>
            <input type="file" @input="formStartRepair.start_repair_photo = $event.target.files[0]" class="w-full border rounded p-2" accept="image/*" capture="environment" />
          </div>
          <div class="flex gap-2 justify-end">
            <ButtonBlue :disabled="formStartRepair.processing">
              {{ formStartRepair.processing ? 'Menyimpan…' : 'Simpan & Lanjutkan' }}
            </ButtonBlue>
            <Button class="border" @click.prevent="skipStartRepair">Lewati Step Ini</Button>
            <Button class="border" @click.prevent="finishLater">Selesaikan Nanti</Button>
          </div>
        </form>
      </template>
    </Card>

    <!-- STEP 4: COMPLETE REPAIR - SELALU TAMPIL -->
    <Card class="bg-white pt-[1.875rem] pb-[2.5rem] px-6 md:px-8 shadow-lg border border-solid"
          :class="currentStep === 4 ? 'border-green-500' : (isStep4Unlocked ? 'border-slate-200' : 'border-slate-200 opacity-50')"
          style="border-radius:.625rem; margin-bottom: 1rem;">
      <template #body>
        <div class="flex justify-between items-center mb-4">
          <h3 class="font-bold text-lg text-green-700">
            Step 4: Selesaikan Perbaikan
          </h3>
        </div>

        <!-- Alert jika belum unlock -->
        <div v-if="!isStep4Unlocked" class="p-4 bg-gray-50 border border-gray-200 rounded text-center">
          <p class="text-gray-600">🔒 Selesaikan Step 3 terlebih dahulu untuk membuka form ini</p>
        </div>

        <!-- Form - tampil jika unlocked -->
        <form v-if="isStep4Unlocked" @submit.prevent="submitComplete" class="space-y-4">
          <div>
            <label class="block text-sm font-semibold mb-1">Nama Teknisi</label>
            <select v-model="formComplete.complete_repair_by_id" class="w-full border rounded p-2">
              <option :value="null">Pilih Teknisi</option>
              <option v-for="u in props.users" :key="u.id" :value="u.id">{{ u.name }}</option>
            </select>
            <InputError :message="formComplete.errors.complete_repair_by_id" class="mt-1" />
          </div>
          <div>
            <label class="block text-sm font-semibold mb-1">Hasil Tindak Lanjut</label>
            <textarea v-model="formComplete.complete_repair_notes" rows="3" class="w-full border rounded p-2" placeholder="Hasil dan catatan penyelesaian..."></textarea>
            <InputError :message="formComplete.errors.complete_repair_notes" class="mt-1" />
          </div>
          <div>
            <label class="block text-sm font-semibold mb-1">Upload Foto Selesai</label>
            <input type="file" @input="formComplete.complete_repair_photo = $event.target.files[0]" class="w-full border rounded p-2" accept="image/*" capture="environment" />
          </div>
          <div class="flex gap-2 justify-end">
            <ButtonBlue :disabled="formComplete.processing">
              {{ formComplete.processing ? 'Menyelesaikan…' : 'Selesaikan Pekerjaan' }}
            </ButtonBlue>
            <Button class="border" @click.prevent="finishLater">Selesaikan Nanti</Button>
          </div>
        </form>
      </template>
    </Card>

  </DashboardLayout>
</template>
