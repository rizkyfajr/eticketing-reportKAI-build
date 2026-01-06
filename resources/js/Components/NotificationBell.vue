<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { Link } from '@inertiajs/inertia-vue3'
import axios from 'axios'

const showDropdown = ref(false)
const notifications = ref([])
const unreadCount = ref(0)
const dropdownRef = ref(null)
let interval = null

async function fetchNotifications() {
  try {
    const response = await axios.get(route('notifications.recent'))
    notifications.value = response.data.notifications
    unreadCount.value = response.data.unreadCount
    workingReports.value = response.data.workingReports ?? []
  } catch (error) {
    console.error('Error fetching notifications:', error)
  }
}

const workingReports = ref([])

function formatTime(dateString) {
  if (!dateString) return ''
  const date = new Date(dateString)
  const now = new Date()
  const diff = now - date
  const minutes = Math.floor(diff / 60000)
  const hours = Math.floor(diff / 3600000)
  const days = Math.floor(diff / 86400000)

  if (minutes < 1) return 'Baru'
  if (minutes < 60) return `${minutes}m`
  if (hours < 24) return `${hours}j`
  if (days < 7) return `${days}h`
  return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' })
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

function toggleDropdown() {
  showDropdown.value = !showDropdown.value
}

function closeDropdown() {
  showDropdown.value = false
}

async function markAsRead(notificationId) {
  try {
    await axios.post(route('notifications.markAsRead', notificationId))
    await fetchNotifications()
  } catch (error) {
    console.error('Error marking notification as read:', error)
  }
}

// Handle click outside
function handleClickOutside(event) {
  if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
    closeDropdown()
  }
}

onMounted(() => {
  fetchNotifications()
  // Refresh setiap 30 detik
  interval = setInterval(fetchNotifications, 30000)
  // Add click outside listener
  document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
  if (interval) {
    clearInterval(interval)
  }
  // Remove click outside listener
  document.removeEventListener('click', handleClickOutside)
})
</script>

<template>
  <div class="relative" ref="dropdownRef">
    <!-- Bell Icon -->
    <button
      @click="toggleDropdown"
      class="relative p-2 text-white hover:text-gray-200 focus:outline-none">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
      </svg>

      <!-- Badge untuk unread count -->
      <span
        v-if="unreadCount > 0"
        class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full">
        {{ unreadCount > 99 ? '99+' : unreadCount }}
      </span>
    </button>

    <!-- Dropdown Notifikasi -->
    <div
      v-show="showDropdown"
      class="absolute right-0 mt-2 w-96 bg-white rounded-lg shadow-xl border border-gray-200 z-50">

      <!-- Header -->
      <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-center">
        <h3 class="font-semibold text-gray-900">Notifikasi</h3>
        <Link
          :href="route('notifications.index')"
          @click="closeDropdown"
          class="text-sm text-blue-600 hover:text-blue-800">
          Lihat Semua
        </Link>
      </div>

      <div v-if="workingReports.length > 0">
        <div class="px-4 py-2 text-xs font-semibold text-orange-600 bg-orange-50">
          ⏳ Working Report
        </div>

        <Link
          v-for="wr in workingReports"
          :key="'wr-' + wr.id"
          :href="route('working-reports.detail', wr.id)"
          class="block px-4 py-3 border-b border-gray-100 hover:bg-gray-50"
        >
          <p class="text-sm font-semibold text-gray-900">
            {{ wr.title }}
          </p>
          <p class="text-xs text-gray-600 mt-1">
            {{ wr.message }}
          </p>
          <p class="text-xs text-gray-500 mt-1">
            {{ formatTime(wr.created_at) }}
          </p>
        </Link>
      </div>

      <!-- Notification List -->
      <div class="max-h-96 overflow-y-auto">
        <div v-if="notifications.length > 0">
          <Link
            v-for="notification in notifications.slice(0, 5)"
            :key="notification.id"
            :href="route('maintenance-orders.show', notification.maintenance_order_id)"
            @click="markAsRead(notification.id); closeDropdown()"
            class="block px-4 py-3 border-b border-gray-100 hover:bg-gray-50 transition-colors"
            :class="notification.read_at ? 'bg-white' : 'bg-blue-50'">

            <div class="flex items-start gap-3">
              <span class="text-xl flex-shrink-0">{{ getTypeIcon(notification.type) }}</span>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-900 truncate">{{ notification.title }}</p>
                <p class="text-xs text-gray-600 line-clamp-2 mt-1">{{ notification.message }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ formatTime(notification.created_at) }}</p>
              </div>
              <span
                v-if="!notification.read_at"
                class="w-2 h-2 bg-blue-500 rounded-full flex-shrink-0 mt-1"></span>
            </div>
          </Link>
        </div>

        <div v-if="workingReports.length === 0 && notifications.length === 0" class="px-4 py-8 text-center text-gray-500">
          <span class="text-3xl block mb-2">🔔</span>
          <p class="text-sm">Belum ada notifikasi</p>
        </div>
      </div>

      <!-- Footer -->
      <div v-if="notifications.length > 0" class="px-4 py-3 border-t border-gray-200 text-center">
        <Link
          :href="route('notifications.index')"
          @click="closeDropdown"
          class="text-sm text-blue-600 hover:text-blue-800 font-semibold">
          Lihat Semua Notifikasi ({{ unreadCount }} belum dibaca)
        </Link>
      </div>
    </div>
  </div>
</template>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
