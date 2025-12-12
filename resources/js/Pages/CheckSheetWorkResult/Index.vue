<script setup>
import { ref } from 'vue'
import { Link, useForm } from '@inertiajs/inertia-vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Card from '@/Components/Card.vue'
import Builder from '@/Components/DataTable/Builder.vue'
import Th from '@/Components/DataTable/Th.vue'

const { results } = defineProps({
  results: Object
})
</script>

<template>
  <DashboardLayout :title="__('Check Sheet Work Result')">
    <Card class="bg-white shadow-lg">
      <template #header>
        <div class="flex items-center justify-between px-4 py-3">
          <h2 class="text-xl font-bold">Data Check Sheet Work Result</h2>
        </div>
      </template>

      <template #body>
        <div class="px-4 py-2">
          <p class="text-sm text-gray-600 mb-4">
            Halaman ini menampilkan hasil checksheet dari working report.
          </p>
        </div>

        <Builder :url="route('check-sheet.paginate')">
          <template #thead="table">
            <tr class="bg-gray-100 border-b">
              <Th :table="table" :sort="false" class="border px-3 py-1 text-center font-bold text-xs">
                NO
              </Th>
              <Th :table="table" :sort="false" class="border px-3 py-1 text-center font-bold text-xs">
                WORKING REPORT ID
              </Th>
              <Th :table="table" :sort="false" class="border px-3 py-1 text-center font-bold text-xs">
                CHECK SHEET DAY ID
              </Th>
              <Th :table="table" :sort="false" class="border px-3 py-1 text-center font-bold text-xs">
                MODE
              </Th>
              <Th :table="table" :sort="false" class="border px-3 py-1 text-center font-bold text-xs">
                CATATAN GANGGUAN
              </Th>
              <Th :table="table" :sort="false" class="border px-3 py-1 text-center font-bold text-xs">
                STATUS APPROVE
              </Th>
            </tr>
          </template>

          <template #tbody="{ data, processing, empty }">
            <template v-if="empty">
              <tr>
                <td class="text-center p-4" colspan="100">
                  <p class="text-xs font-semibold">Tidak ada data untuk ditampilkan.</p>
                </td>
              </tr>
            </template>

            <template v-else>
              <tr v-for="(item, i) in data" :key="item.id" :class="processing && 'bg-gray-100'">
                <td class="border px-4 py-1 text-center text-xs">{{ i + 1 }}</td>
                <td class="border px-4 py-1 text-center text-xs">{{ item.working_report_id }}</td>
                <td class="border px-4 py-1 text-center text-xs">{{ item.check_sheet_day_id || '-' }}</td>
                <td class="border px-4 py-1 text-center text-xs">
                  <span :class="{
                    'bg-blue-100 text-blue-800': item.mode === 'working',
                    'bg-orange-100 text-orange-800': item.mode === 'warmingup'
                  }" class="px-2 py-1 rounded text-xs font-semibold">
                    {{ item.mode || '-' }}
                  </span>
                </td>
                <td class="border px-4 py-1 text-xs">{{ item.catatan_gangguan || '-' }}</td>
                <td class="border px-4 py-1 text-center text-xs">
                  <div class="flex flex-col gap-1">
                    <span v-if="item.operator_at1" class="text-green-600">✓ Level 1</span>
                    <span v-if="item.operator_at2" class="text-green-600">✓ Level 2</span>
                    <span v-if="item.operator_at3" class="text-green-600">✓ Level 3</span>
                    <span v-if="item.operator_at4" class="text-green-600">✓ Level 4</span>
                    <span v-if="!item.operator_at1 && !item.operator_at2 && !item.operator_at3 && !item.operator_at4" class="text-gray-400">
                      Belum Approve
                    </span>
                  </div>
                </td>
              </tr>
            </template>
          </template>
        </Builder>
      </template>
    </Card>
  </DashboardLayout>
</template>
