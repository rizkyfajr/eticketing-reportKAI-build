<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Card from '@/Components/Card.vue'
import Button from '@/Components/Button.vue'
import ButtonBlue from '@/Components/Button/Blue.vue'
import ButtonRed from '@/Components/Button/Red.vue'
import { Link } from '@inertiajs/inertia-vue3'

const { order } = defineProps({ order: Object })

function fmt(dt) {
  if (!dt) return '-'
  return new Date(dt).toLocaleString('id-ID')
}
</script>

<template>
  <DashboardLayout :title="__('Detail Maintenance Order')">
    <main class="p-0 py-0 mb-[1.25rem] ml-[1.25rem] mt-[1.25rem]">
      <h2 class="font-bold text-2xl">Detail Maintenance Order</h2>
      <a :href="route('maintenance-orders.index')" class="text-sm text-gray-500 font-semibold hover:text-sky-600">Maintenance Order</a>
      <span class="font-semibold text-sm px-2">-</span>
      <span class="text-sm text-gray-500 font-semibold">Detail</span>
    </main>

    <Card class="bg-white pt-[1.875rem] pb-[2.5rem] shadow-lg border border-solid border-slate-200" style="border-radius:.625rem;">
      <template #body>
        <div class="grid md:grid-cols-2 gap-4">
          <div>
            <div class="text-xs text-slate-500">Judul</div>
            <div class="font-semibold">{{ order.title ?? '-' }}</div>
          </div>

          <div>
            <div class="text-xs text-slate-500">Kategori</div>
            <div class="font-semibold capitalize">{{ order.category ?? '-' }}</div>
          </div>

          <div>
            <div class="text-xs text-slate-500">Mesin</div>
            <div class="font-semibold">
              {{ order.machine ? `${order.machine.name}${order.machine.type ? ' - ' + order.machine.type : ''}` : '-' }}
              <span v-if="order.machine?.nomor"> • {{ order.machine.nomor }}</span>
              <span v-if="order.machine?.no_sarana"> • {{ order.machine.no_sarana }}</span>
            </div>
          </div>

          <div>
            <div class="text-xs text-slate-500">Working Report</div>
            <div class="font-semibold">WR #{{ order.working_report_id }}</div>
          </div>

          <div>
            <div class="text-xs text-slate-500">Waktu Gangguan</div>
            <div class="font-semibold">{{ fmt(order.trouble_at) }}</div>
          </div>

          <div>
            <div class="text-xs text-slate-500">Dibuat</div>
            <div class="font-semibold">{{ fmt(order.created_at) }}</div>
          </div>

          <div class="md:col-span-2">
            <div class="text-xs text-slate-500">Catatan Gangguan</div>
            <div class="font-semibold whitespace-pre-wrap">{{ order.problem_note ?? '-' }}</div>
          </div>
        </div>

        <div class="flex gap-2 mt-6">
          <Button class="bg-gray-600 text-white">
            <Link :href="route('maintenance-orders.index')">Kembali</Link>
          </Button>

          <ButtonBlue v-if="can('update maintenance order')">
            <Link :href="route('maintenance-orders.edit', order.id)">Edit</Link>
          </ButtonBlue>

          <ButtonRed v-if="can('delete maintenance order')" @click.prevent="$inertia.delete(route('maintenance-orders.destroy', order.id))">
            Hapus
          </ButtonRed>
        </div>
      </template>
    </Card>
  </DashboardLayout>
</template>
