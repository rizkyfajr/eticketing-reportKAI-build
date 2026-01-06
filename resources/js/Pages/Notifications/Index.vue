<script setup>
import { ref, computed } from 'vue'
import { Link, useForm } from '@inertiajs/inertia-vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Card from '@/Components/Card.vue'
import { Inertia } from '@inertiajs/inertia'

const props = defineProps({
  notifications: Object,
  unreadCount: Number,
  notificationWorkingReport: Array
})

function markAsRead(notificationId) {
  Inertia.post(route('notifications.markAsRead', notificationId), {}, {
    preserveScroll: true,
    onSuccess: () => {
      // Reload data
      Inertia.reload({ only: ['notifications', 'unreadCount'] })
    }
  })
}

function markAllAsRead() {
  Inertia.post(route('notifications.markAllAsRead'), {}, {
    preserveScroll: true,
    onSuccess: () => {
      Inertia.reload({ only: ['notifications', 'unreadCount'] })
    }
  })
}

function deleteNotification(notificationId) {
  if (!confirm('Hapus notifikasi ini?')) return

  Inertia.delete(route('notifications.destroy', notificationId), {
    preserveScroll: true,
    onSuccess: () => {
      Inertia.reload({ only: ['notifications', 'unreadCount'] })
    }
  })
}

function getTypeIcon(type) {
  const icons = {
    'input_failure': '🔴',
    'follow_up_plan': '📋',
    'start_repair': '🔧',
    'repair_complete': '✅'
  }
  return icons[type] || '🔔'
}

function getStatusColor(status) {
  const colors = {
    'BARU': 'bg-blue-100 text-blue-800',
    'DIPROSES': 'bg-cyan-100 text-cyan-800',
    'DIKERJAKAN': 'bg-yellow-100 text-yellow-800',
    'SELESAI': 'bg-green-100 text-green-800'
  }
  return colors[status] || 'bg-gray-100 text-gray-800'
}

function formatDate(dateString) {
  if (!dateString) return '-'
  const date = new Date(dateString)
  const now = new Date()
  const diff = now - date
  const minutes = Math.floor(diff / 60000)
  const hours = Math.floor(diff / 3600000)
  const days = Math.floor(diff / 86400000)

  if (minutes < 1) return 'Baru saja'
  if (minutes < 60) return `${minutes} menit yang lalu`
  if (hours < 24) return `${hours} jam yang lalu`
  if (days < 7) return `${days} hari yang lalu`

  return date.toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'short',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const filteredWorkingReport = computed(() => {
  return props.notificationWorkingReport?.filter(wr =>
    wr.operator_at3 !== null && wr.kupt_at1 === null
  ) ?? []
})
</script>

<template>
  <DashboardLayout title="Notifikasi">
    <main class="p-0 py-0 mb-[1.25rem] ml-[1.25rem] mt-[1.25rem]">
      <h2 class="font-bold text-2xl">Notifikasi Maintenance Order</h2>
      <span class="text-sm text-gray-500 font-semibold">Daftar Notifikasi</span>
    </main>

    <Card class="bg-white pt-[1.875rem] pb-[2.5rem] px-6 md:px-8 shadow-lg border border-solid border-slate-200" style="border-radius:.625rem;">
      <template #body>
        <div class="flex justify-between items-center mb-4">
          <div>
            <h3 class="font-bold text-lg">Semua Notifikasi</h3>
            <p class="text-sm text-gray-500" v-if="unreadCount > 0">{{ unreadCount }} notifikasi belum dibaca</p>
          </div>
          <button
            v-if="unreadCount > 0"
            @click="markAllAsRead"
            class="px-4 py-2 text-sm bg-blue-500 text-white rounded hover:bg-blue-600">
            Tandai Semua Dibaca
          </button>
        </div>

        <div v-if="notifications.data && notifications.data.length > 0" class="space-y-3">
          <div
            v-for="notification in notifications.data"
            :key="notification.id"
            class="border rounded-lg p-4 hover:bg-gray-50 transition-colors"
            :class="notification.read_at ? 'bg-white' : 'bg-blue-50 border-blue-200'">

            <div class="flex items-start justify-between">
              <div class="flex-1">
                <div class="flex items-center gap-2 mb-2">
                  <span class="text-2xl">{{ getTypeIcon(notification.type) }}</span>
                  <h4 class="font-semibold text-base" :class="notification.read_at ? 'text-gray-700' : 'text-gray-900'">
                    {{ notification.title }}
                  </h4>
                  <span
                    class="px-2 py-1 text-xs rounded-full font-semibold"
                    :class="getStatusColor(notification.status)">
                    {{ notification.status }}
                  </span>
                </div>

                <p class="text-sm text-gray-600 mb-2">{{ notification.message }}</p>

                <div class="flex items-center gap-4 text-xs text-gray-500">
                  <span>🕐 {{ formatDate(notification.created_at) }}</span>
                  <span v-if="notification.maintenance_order">
                    🔧 {{ notification.maintenance_order.machine?.name || '-' }} - {{ notification.maintenance_order.machine?.nomor || '-' }}
                  </span>
                </div>
              </div>

              <div class="flex gap-2 ml-4">
                <Link
                  v-if="notification.maintenance_order"
                  :href="route('maintenance-orders.show', notification.maintenance_order.id)"
                  class="px-3 py-1 text-xs bg-blue-500 text-white rounded hover:bg-blue-600">
                  Lihat
                </Link>

                <button
                  v-if="!notification.read_at"
                  @click="markAsRead(notification.id)"
                  class="px-3 py-1 text-xs bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                  Tandai Dibaca
                </button>

                <button
                  @click="deleteNotification(notification.id)"
                  class="px-3 py-1 text-xs bg-red-100 text-red-700 rounded hover:bg-red-200">
                  Hapus
                </button>
              </div>
            </div>
          </div>
        </div>

        <div v-if="filteredWorkingReport.length > 0" class="mb-6 space-y-3">
          <h3 class="font-bold text-lg text-orange-600">
            ⏳ Working Report Menunggu Approve KUPT
          </h3>

          <div
            v-for="wr in filteredWorkingReport"
            :key="wr.id"
            class="border border-orange-200 bg-orange-50 rounded-lg p-4"
          >
            <div class="font-semibold text-gray-800">
              📄 WR #{{ wr.id }}
            </div>

            <div class="text-sm text-gray-600">
              Mesin: {{ wr.machine?.name ?? '-' }} ({{ wr.machine?.nomor ?? '-' }})
            </div>

            <div class="text-xs text-gray-500">
              Dibuat: {{ formatDate(wr.created_at) }}
            </div>

            <Link
              :href="route('working-reports.index', wr.id)"
              class="inline-block mt-2 text-xs bg-orange-500 text-white px-3 py-1 rounded hover:bg-orange-600"
            >
              Lihat Working Report
            </Link>
          </div>
        </div>

        <div v-else class="text-center py-12 text-gray-500">
          <span class="text-4xl block mb-2">🔔</span>
          <p>Belum ada notifikasi</p>
        </div>

        <!-- Pagination -->
        <div v-if="notifications.data && notifications.data.length > 0" class="mt-6 flex justify-between items-center">
          <div class="text-sm text-gray-600">
            Menampilkan {{ notifications.from }} - {{ notifications.to }} dari {{ notifications.total }} notifikasi
          </div>
          <div class="flex gap-2">
            <Link
              v-for="link in notifications.links"
              :key="link.label"
              :href="link.url"
              :class="[
                'px-3 py-1 text-sm rounded',
                link.active ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300',
                !link.url ? 'opacity-50 cursor-not-allowed' : ''
              ]"
              v-html="link.label"
              :disabled="!link.url">
            </Link>
          </div>
        </div>
      </template>
    </Card>
  </DashboardLayout>
</template>
