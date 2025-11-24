<script setup>
import { computed, ref } from 'vue'
import { Link, router } from '@inertiajs/inertia-vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Card from '@/Components/Card.vue'
import ButtonBlue from '@/Components/Button/Blue.vue'
import Button from '@/Components/Button.vue'

const props = defineProps({
  machines: Array,
  components: Array,
  filterMachine: String,
})

const selectedMachineType = ref(props.filterMachine || null)

function onFilterChange() {
  const params = {}
  if (selectedMachineType.value) {
    params.machine_type = selectedMachineType.value
  }
  router.get(route('machine-components.index'), params, { preserveScroll: true })
}

// buat tampilan indentasi level
function indent(level) {
  return { paddingLeft: `${(level - 1) * 1.5}rem` }
}
</script>

<template>
  <DashboardLayout title="Master Komponen Mesin">
    <main class="p-0 py-0 mb-[1.25rem] ml-[1.25rem] mt-[1.25rem]">
      <h2 class="font-bold text-2xl">Master Komponen Mesin</h2>
      <span class="text-sm text-gray-500 font-semibold">Daftar seluruh komponen dan hierarkinya</span>
    </main>

    <Card class="bg-white pt-[1.5rem] pb-[2rem] shadow-lg border border-solid border-slate-200" style="border-radius:.625rem;">
      <template #body>
        <div class="flex justify-between items-center mb-4">
          <div class="flex gap-2 items-center">
            <label class="text-sm font-semibold">Filter Mesin:</label>
            <select v-model="selectedMachineType" @change="onFilterChange" class="border rounded p-2 text-sm">
              <option :value="null">Semua</option>
              <option v-for="m in props.machines" :key="m.id" :value="m.hierarchy_code || m.type">
                {{ m.name }}
              </option>
            </select>
          </div>

          <ButtonBlue>
            <Link :href="route('machine-components.create')">Tambah Komponen</Link>
          </ButtonBlue>
        </div>

        <table class="min-w-full text-sm border-collapse">
          <thead>
            <tr class="bg-slate-100 border-b">
              <th class="text-left p-2">Kode</th>
              <th class="text-left p-2">Nama Komponen</th>
              <th class="text-left p-2">Level</th>
              <th class="text-left p-2">Mesin / Tipe</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="c in props.components" :key="c.id" class="border-b hover:bg-slate-50">
              <td class="p-2 text-slate-700" :style="indent(c.level)">
                {{ c.code || '-' }}
              </td>
              <td class="p-2">{{ c.name }}</td>
              <td class="p-2 text-center">{{ c.level }}</td>
              <td class="p-2 text-slate-500">{{ c.machine_type }}</td>
            </tr>
            <tr v-if="props.components.length === 0">
              <td colspan="4" class="text-center text-slate-500 py-6">Tidak ada data</td>
            </tr>
          </tbody>
        </table>
      </template>
    </Card>
  </DashboardLayout>
</template>
