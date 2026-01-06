<script setup>
import { ref, computed, onMounted, watch, nextTick, onUnmounted } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { useForm, Link, usePage } from '@inertiajs/inertia-vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Card from '@/Components/Card.vue'
import { Chart } from 'chart.js/auto'

const props = defineProps({
    machines: Array,
    classificationId: Number
})

const selectedMachineId = ref(null)

const selectedMachine = computed(() => {
    return props.machines.find(m => m.id === selectedMachineId.value)
})

/// grafik
const weeklyCanvas = ref(null)
const cumulativeCanvas = ref(null)
const bbmCanvas = ref(null)
const reliabilityCanvas = ref(null)
const fuelConsumptionCanvas = ref(null)

let weeklyChart = null
let cumulativeChart = null
let bbmChart = null
let reliabilityChart = null
let fuelConsumptionChart = null

const initWeeklyChart = () => {
  if (!weeklyCanvas.value) return

  weeklyChart?.destroy()

  weeklyChart = new Chart(weeklyCanvas.value, {
    type: 'bar',
    data: {
      labels: ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'],
      datasets: [{
        data: [0, 12, 38, 30, 32, 0, 0],
        backgroundColor: '#6FE7E7',
        borderRadius: 6
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } }
    }
  })
}

const initCumulativeChart = () => {
  if (!cumulativeCanvas.value) return

  cumulativeChart?.destroy()

  cumulativeChart = new Chart(cumulativeCanvas.value, {
    type: 'line',
    data: {
      labels: ['January','February','March','April','May','June','July','August','September','October','November', 'December'],
      datasets: [{
        data: [0,12,38,30,32,0,0,0,0,0,0,0],
        borderColor: '#6FE7E7',
        tension: 0.4,
        pointRadius: 4
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } }
    }
  })
}

const centerText = {
  id: 'centerText',
  afterDraw(chart) {
    const { ctx, width, height } = chart
    ctx.restore()
    ctx.font = 'bold 32px sans-serif'
    ctx.fillStyle = '#000'
    ctx.textAlign = 'center'
    ctx.textBaseline = 'middle'
    ctx.fillText('90%', width / 2, height / 2)
    ctx.save()
  }
}

const initBBMChart = () => {
  if (!bbmCanvas.value) return

  bbmChart?.destroy()

  bbmChart = new Chart(bbmCanvas.value, {
    type: 'doughnut',
    data: {
      datasets: [{
        data: [90, 10],
        backgroundColor: ['#6FE7E7', '#E5E7EB'],
        borderWidth: 0
      }]
    },
    options: {
      cutout: '75%',
      plugins: { legend: { display: false } }
    },
    plugins: [centerText]
  })
}

const initReliabilityChart = () => {
  if (!reliabilityCanvas.value) return

  reliabilityChart?.destroy()

  reliabilityChart = new Chart(reliabilityCanvas.value, {
    type: 'line',
    data: {
      labels: ['1','2','3','4','5','6','7','8','9'],
      datasets: [{
        data: [100,100,100,95,85,70,50,10,2],
        borderColor: '#71f1f1',
        backgroundColor: 'transparent',
        tension: 0.4,
        pointRadius: 3,
        pointBackgroundColor: '#71f1f1'
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        y: {
          beginAtZero: true,
          max: 100,
          ticks: { stepSize: 20 }
        },
        x: {
          grid: { display: false }
        }
      }
    }
  })
}

const initFuelConsumptionChart = () => {
  if (!fuelConsumptionCanvas.value) return
  fuelConsumptionChart?.destroy()

  fuelConsumptionChart = new Chart(fuelConsumptionCanvas.value, {
    type: 'line',
    data: {
      labels: ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'],
      datasets: [
        {
          label: 'HSD',
          data: [0, 12, 38, 30, 32, 0, 0, 0, 0, 0, 0, 0],
          borderColor: '#6FE7E7',
          backgroundColor: '#6FE7E7',
          tension: 0
        },
        {
          label: 'Thelus',
          data: [12, 4, 20, 15, 50, 0, 0, 0, 0, 0, 0, 0],
          borderColor: '#4FBCCF',
          backgroundColor: '#4FBCCF',
          tension: 0
        },
        {
          label: 'Pertalite',
          data: [18, 30, 25, 40, 42, 0, 0, 0, 0, 0, 0, 0],
          borderColor: '#2D81B1',
          backgroundColor: '#2D81B1',
          tension: 0
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { position: 'top', align: 'center' }
      },
      scales: {
        y: { beginAtZero: true, title: { display: true, text: "M'sp" } }
      }
    }
  })
}

watch(selectedMachineId, async (val) => {
  if (!val) return

  await nextTick()
  initWeeklyChart()
  initCumulativeChart()
  initBBMChart()
  initReliabilityChart()
  initFuelConsumptionChart()
})

</script>

<style src="@vueform/multiselect/themes/default.css"></style>
<style src="@/multiselect.css"></style>

  <template>
    <DashboardLayout :title="__('Detail KPJR')">
      <Card class="bg-white pt-[1.100rem] pb-[2.5rem] shadow-lg border border-solid border-slate-200" style="border-radius: 0.625rem;">
        <template #header>
          <div class="flex justify-center px-4">
              <div class="w-full max-w-xl">

                <div class="relative">
                    <select
                        v-model="selectedMachineId"
                        class="w-full
                              text-sm sm:text-base
                              border border-gray-300 rounded-lg
                              pl-10 pr-3 py-2.5
                              bg-white
                              shadow-sm
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                              transition"
                    >
                        <option :value="null">-- Pilih Mesin --</option>

                        <option
                            v-for="machine in machines"
                            :key="machine.id"
                            :value="machine.id"
                        >
                            [{{ machine.nomor }}] {{ machine.name }} - {{ machine.type }}
                        </option>
                    </select>
                </div>
              </div>
          </div>
        </template>

        <template #body>
          <div class="p-6">
            <div class="border border-gray-200 rounded-2xl" >
              <span class="font-extrabold flex justify-center">Informasi KPJR :</span>
            </div>

            <div
              v-if="selectedMachine"
              class="border border-gray-200
                    rounded-2xl p-8 shadow-sm mt-6"
            >
                <!-- <h2 class="text-2xl font-bold text-center mb-8 tracking-wide">
                    {{ selectedMachine.name }}
                </h2> -->

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-16 gap-y-4 text-sm">
                    <div class="space-y-2">
                        <div class="flex">
                            <span class="w-40 font-semibold text-gray-700">Klasifikasi</span>
                            <span>: {{ selectedMachine.classification?.name ?? '-' }}</span>
                        </div>
                        <div class="flex">
                            <span class="w-40 font-semibold text-gray-700">Type</span>
                            <span>: {{ selectedMachine.type }}</span>
                        </div>
                        <div class="flex">
                            <span class="w-40 font-semibold text-gray-700">No. Sarana</span>
                            <span>: {{ selectedMachine.no_sarana }}</span>
                        </div>
                        <div class="flex">
                            <span class="w-40 font-semibold text-gray-700">No. Mesin</span>
                            <span>: {{ selectedMachine.nomor ?? '-' }}</span>
                        </div>
                        <div class="flex">
                            <span class="w-40 font-semibold text-gray-700">Dimensi Mesin</span>
                            <span>: {{ selectedMachine.dimensi ?? '-' }}</span>
                        </div>
                        <div class="flex">
                            <span class="w-40 font-semibold text-gray-700">Berat Mesin</span>
                            <span>: {{ selectedMachine.berat ?? '-' }}</span>
                        </div>
                        <div class="flex">
                            <span class="w-40 font-semibold text-gray-700">Daya Mesin</span>
                            <span>: {{ selectedMachine.daya ?? '-' }}</span>
                        </div>
                        <div class="flex">
                            <span class="w-40 font-semibold text-gray-700">Kapasitas BBM</span>
                            <span>: {{ selectedMachine.kapasitas_bbm ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex">
                            <span class="w-40 font-semibold text-gray-700">Produktivitas</span>
                            <span>: {{ selectedMachine.produktivitas ?? '-' }}</span>
                        </div>
                        <div class="flex">
                            <span class="w-40 font-semibold text-gray-700">Kecepatan Izin</span>
                            <span>: {{ selectedMachine.kecepatan_izin ?? '-' }}</span>
                        </div>
                        <div class="flex">
                            <span class="w-40 font-semibold text-gray-700">Kecepatan Maks.</span>
                            <span>: {{ selectedMachine.kecepatan_maks ?? '-' }}</span>
                        </div>
                        <div class="flex">
                            <span class="w-40 font-semibold text-gray-700">Tahun Produksi</span>
                            <span>: {{ selectedMachine.tahun_md ?? '-' }}</span>
                        </div>
                        <div class="flex">
                            <span class="w-40 font-semibold text-gray-700">Umur Mesin</span>
                            <span>: {{ selectedMachine.umur ?? '-' }}</span>
                        </div>
                        <div class="flex">
                            <span class="w-40 font-semibold text-gray-700">Produsen</span>
                            <span>: {{ selectedMachine.produsen ?? '-' }}</span>
                        </div>
                        <div class="flex">
                            <span class="w-40 font-semibold text-gray-700">Lokasi Dinas</span>
                            <span>: {{ selectedMachine.region?.name ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DASHBOARD MESIN -->
            <div v-if="selectedMachine" class="mt-10 space-y-6">

                <!-- KPI CARDS -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white border rounded-xl p-4 shadow-sm">
                        <p class="text-xs text-gray-500">Last Maintenance</p>
                        <p class="text-2xl font-bold">P1</p>
                        <p class="text-xs text-gray-500 mt-1">10 Juni 2025</p>
                    </div>

                    <div class="bg-white border rounded-xl p-4 shadow-sm">
                        <p class="text-xs text-gray-500">Upcoming Maintenance</p>
                        <p class="text-2xl font-bold">P3</p>
                        <p class="text-xs text-gray-500 mt-1">1 Januari 2026</p>
                    </div>

                    <div class="bg-white border rounded-xl p-4 shadow-sm">
                        <p class="text-xs text-gray-500"></p>
                        <p class="text-2xl font-bold text-green-600"></p>
                    </div>

                    <div class="bg-white border rounded-xl p-4 shadow-sm">
                        <p class="text-xs text-gray-500"></p>
                        <p class="text-2xl font-bold"></p>
                    </div>
                </div>

                <!-- GRAFIK -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">

                  <!-- A Week Productivity -->
                  <div class="bg-white border-2 border-gray-300 rounded-2xl p-4 h-full flex flex-col">
                    <div class="flex items-center gap-2 mb-2">
                      <span class="text-gray-500">⋮⋮</span>
                      <h3 class="font-bold text-sm">A Week Productivity</h3>
                    </div>

                    <div class="flex-1 h-[240px]">
                      <canvas ref="weeklyCanvas"></canvas>
                    </div>
                  </div>

                  <!-- Cummulative Productivity -->
                  <div class="bg-white border-2 border-gray-300 rounded-2xl p-4 h-full flex flex-col">
                    <div class="flex items-center justify-between mb-2">
                      <div class="flex items-center gap-2">
                        <span class="text-gray-500">⋮⋮</span>
                        <h3 class="font-bold text-sm">Cummulative Productivity</h3>
                      </div>
                      <span class="text-xs text-gray-500">January – December</span>
                    </div>

                    <div class="flex-1 h-[240px]">
                      <canvas ref="cumulativeCanvas"></canvas>
                    </div>
                  </div>

                  <!-- Prosentase BBM -->
                  <div class="bg-white border-2 border-gray-300 rounded-2xl p-4 h-full flex flex-col">
                    <div class="flex items-center gap-2 mb-2">
                      <span class="text-gray-500">⋮⋮</span>
                      <h3 class="font-bold text-sm">Prosentase BBM</h3>
                    </div>

                    <div class="flex-1 flex items-center justify-center">
                      <div class="w-[220px] h-[220px]">
                        <canvas ref="bbmCanvas"></canvas>
                      </div>
                    </div>
                  </div>
                  
                </div>

                <!-- RELIABILITY + SPAREPART -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4">
                  <div class="bg-white border border-gray-300 rounded-2xl p-6 shadow-sm h-full">
                    <div class="flex items-center gap-2 mb-8">
                      <div class="grid grid-cols-2 gap-1 opacity-40">
                        <div class="w-1 h-1 bg-black rounded-full"></div><div class="w-1 h-1 bg-black rounded-full"></div>
                        <div class="w-1 h-1 bg-black rounded-full"></div><div class="w-1 h-1 bg-black rounded-full"></div>
                        <div class="w-1 h-1 bg-black rounded-full"></div><div class="w-1 h-1 bg-black rounded-full"></div>
                      </div>
                      <h3 class="text-xl font-bold text-gray-800">Reliability and Availability</h3>
                    </div>

                    <div class="flex gap-6">
                      <div class="flex flex-col justify-between mb-8 mt-2 font-bold text-2xl text-black">
                        <span class="leading-none">MTTR</span>
                        <span class="leading-none">MTTF</span>
                        <span class="leading-none">MTBF</span>
                      </div>

                      <div class="flex-grow h-64 relative">
                        <canvas ref="reliabilityCanvas"></canvas>
                      </div>
                    </div>
                  </div>

                  <div class="bg-white border border-gray-300 rounded-2xl p-6 shadow-sm">
                      <div class="flex items-center gap-2 mb-8">
                          <div class="grid grid-cols-2 gap-1 opacity-40">
                              <div class="w-1 h-1 bg-black rounded-full"></div><div class="w-1 h-1 bg-black rounded-full"></div>
                              <div class="w-1 h-1 bg-black rounded-full"></div><div class="w-1 h-1 bg-black rounded-full"></div>
                              <div class="w-1 h-1 bg-black rounded-full"></div><div class="w-1 h-1 bg-black rounded-full"></div>
                          </div>
                          <h3 class="text-xl font-bold text-gray-800">Rate of Sparepart Failure</h3>
                      </div>

                      <table class="w-full">
                          <thead>
                              <tr class="text-gray-400 text-sm">
                                  <th class="text-left font-semibold pb-4">Sparepart</th>
                                  <th class="text-center font-semibold pb-4">Level of Machinery</th>
                                  <th class="text-right font-semibold pb-4 pr-4">Failure Frequency</th>
                              </tr>
                          </thead>
                          <tbody class="text-gray-800 font-bold">
                              <tr class="border-y border-gray-200">
                                  <td class="py-5">Tamping Tyne</td>
                                  <td class="text-center">M.1.2</td>
                                  <td class="text-right pr-10">16</td>
                              </tr>
                              <tr class="border-b border-gray-200">
                                  <td class="py-5">Solenoid</td>
                                  <td class="text-center">P.1.2</td>
                                  <td class="text-right pr-10">15</td>
                              </tr>
                              <tr class="border-b border-gray-200">
                                  <td class="py-5">Valve</td>
                                  <td class="text-center">H.1.2</td>
                                  <td class="text-right pr-10">7</td>
                              </tr>
                              <tr class="border-b border-gray-200">
                                  <td class="py-5">Camshaft</td>
                                  <td class="text-center">En.1.2</td>
                                  <td class="text-right pr-10">6</td>
                              </tr>
                          </tbody>
                      </table>
                  </div>
                </div>

               <!-- Fuel Consumption -->
                <div class="bg-white border border-gray-300 rounded-2xl p-6 shadow-sm">
                  <div class="flex items-center gap-2 mb-4">
                      <div class="grid grid-cols-2 gap-1 opacity-40">
                          <div class="w-1 h-1 bg-black rounded-full"></div><div class="w-1 h-1 bg-black rounded-full"></div>
                          <div class="w-1 h-1 bg-black rounded-full"></div><div class="w-1 h-1 bg-black rounded-full"></div>
                      </div>
                      <h3 class="text-lg font-bold text-gray-800">Fuel Consumption</h3>
                  </div>
                  <div class="h-72 w-full">
                      <canvas ref="fuelConsumptionCanvas"></canvas>
                  </div>
              </div>

            </div>

            <div
              v-else
              class="mt-16 text-center text-gray-400 italic"
            >
              Silakan pilih mesin untuk menampilkan detail informasi
            </div>

          </div>
        </template>
      </Card>
    </DashboardLayout>
  </template>
