<script setup>
import { ref } from 'vue'
import { Link, useForm } from '@inertiajs/inertia-vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Card from '@/Components/Card.vue'
import Builder from '@/Components/DataTable/Builder.vue'
import Th from '@/Components/DataTable/Th.vue'

const { checksheets } = defineProps({
  checksheets: Object
})
</script>

<template>
  <DashboardLayout :title="__('Check Sheet')">
    <Card class="bg-white shadow-lg">
      <template #header>
        <div class="flex items-center justify-between px-4 py-3">
          <h2 class="text-xl font-bold">Data Check Sheet</h2>
        </div>
      </template>

      <template #body>
        <Builder :url="route('check-sheet.paginate')">
          <template #thead="table">
            <tr class="bg-gray-100 border-b">
              <Th :table="table" :sort="false" class="border px-3 py-1 text-center font-bold text-xs">
                NO
              </Th>
              <Th :table="table" :sort="false" class="border px-3 py-1 text-center font-bold text-xs">
                WORKING REPORT
              </Th>
              <Th :table="table" :sort="false" class="border px-3 py-1 text-center font-bold text-xs">
                TANGGAL
              </Th>
              <Th :table="table" :sort="false" class="border px-3 py-1 text-center font-bold text-xs">
                REGION
              </Th>
              <Th :table="table" :sort="false" class="border px-3 py-1 text-center font-bold text-xs">
                TIPE KPJR
              </Th>
              <Th :table="table" :sort="false" class="border px-3 py-1 text-center font-bold text-xs">
                NOMOR SERI
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
                <td class="border px-4 py-1 text-center text-xs">{{ item.tanggal }}</td>
                <td class="border px-4 py-1 text-center text-xs">{{ item.region?.name || '-' }}</td>
                <td class="border px-4 py-1 text-center text-xs">{{ item.tipe_kpjr || '-' }}</td>
                <td class="border px-4 py-1 text-center text-xs">{{ item.nomor_seri || '-' }}</td>
              </tr>
            </template>
          </template>
        </Builder>
      </template>
    </Card>
  </DashboardLayout>
</template>
