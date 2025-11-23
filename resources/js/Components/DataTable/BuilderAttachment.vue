<script setup>
import { useForm } from '@inertiajs/inertia-vue3'
import axios from 'axios'
import Swal from 'sweetalert2'
import { getCurrentInstance, nextTick, onMounted, ref, computed } from 'vue'
import { cloneDeep } from 'lodash'

const self = getCurrentInstance()

const { url, sticky, request } = defineProps({
  url: String,
  sticky: {
    type: Boolean,
    default: true,
  },
  request: Object,
})

const paginator = ref({
  data: [],
  current_page: 1,
  last_page: 1,
  per_page: 5,
  from: 0,
  to: 0,
  total: 0,
  prev_page_url: null,
  next_page_url: null,
})
const processing = ref(false)
const last = ref(null)

const config = useForm({
  page: 1,
  search: '',
  per_page: 5,
  order: {
    key: '',
    dir: 'asc',
  },
  sticky,
  request,
})

// Sinkronisasi perPage untuk UI
const perPage = ref(config.per_page)
const showOptions = ref(false)

/**
 * Navigasi ke halaman tertentu
 * Digunakan oleh pagination links.
 */
const goTo = link => {
  if (!link.url || link.label === '...') {
    return
  }
  
  // Karena struktur link Inertia tidak selalu ada jika menggunakan pagination Laravel bawaan,
  // kita ambil page number dari label link jika itu adalah nomor halaman.
  if (!isNaN(Number(link.label))) {
      config.page = Number(link.label);
  } else {
      // Jika menggunakan link URL penuh (misalnya untuk prev/next yang diklik),
      // kita ekstrak nomor halaman.
      const match = link.url.match(/page=([\d]+)/);
      if (match) {
          config.page = Number(match[1]);
      } else {
          return; // Batalkan jika URL tidak valid
      }
  }

  // Hanya refresh jika halaman berubah
  paginator.value.current_page !== config.page && refresh()
}


/**
 * Mengubah halaman (Digunakan oleh tombol Next/Previous dan link visibleLinks)
 */
const changePage = page => {
  if (page < 1 || page > paginator.value.last_page) return;
  config.page = page
  paginator.value.current_page !== config.page && refresh()
}

/**
 * Mengatur jumlah item per halaman.
 */
const setPerPage = per_page => {
  config.per_page = per_page
  config.page = 1 // Reset ke halaman 1
  perPage.value = per_page // Update UI
  showOptions.value = false
  // Hanya refresh jika per_page berubah
  if (paginator.value.per_page !== config.per_page) {
    refresh()
  } else {
    // Jika per_page tidak berubah tapi tombol diklik, tutup dropdown saja
    showOptions.value = false;
  }
}


/**
 * Mengambil data baru dari server.
 */
const refresh = async u => {
  try {
    processing.value = true
    const prev = last.value = u || url
    const { data } = await axios.post(prev || last.value, config.data())
    
    // Pastikan data yang dikembalikan adalah objek paginator Laravel
    if (data && data.data) {
        paginator.value = data
    } else {
        // Jika API mengembalikan data list saja, bungkus dalam struktur paginator minimal
        paginator.value.data = data || [];
        paginator.value.total = data.length || 0;
        // JANGAN MENGUPDATE PAGE NUMBER JIKA BUKAN DARI PAGINATOR LARAVEL
    }

    self.proxy.$forceUpdate()
  } catch (e) {
    const errorText = e.response?.data?.message || e.message || 'Terjadi kesalahan saat memuat data.';
    await Swal.fire({
      title: 'Error',
      text: errorText,
      icon: 'error',
      confirmButtonText: 'Tutup',
    })
  } finally {
    processing.value = false
  }

}

onMounted(refresh)

const all = {
  url,
  config,
  refresh,
  paginator: paginator.value,
  data: paginator.value.data,
  processing: !processing.value && !paginator.value?.data?.length,
}

defineExpose(all)

/**
 * Menghitung link pagination yang akan ditampilkan (maksimum 5 link).
 */
const visibleLinks = computed(() => {
  if (!paginator.value || !paginator.value.last_page) return []

  const total = paginator.value.last_page
  const current = paginator.value.current_page
  const maxVisible = 5
  const links = []

  let start = Math.max(1, current - 2)
  let end = Math.min(total, current + 2)
  
  // Penyesuaian agar selalu terlihat maxVisible link di tengah
  if (end - start + 1 < maxVisible) {
    if (current <= 3) {
      end = Math.min(total, maxVisible);
      start = 1;
    } else if (current >= total - 2) {
      start = Math.max(1, total - maxVisible + 1);
      end = total;
    }
  }


  if (start > 1) {
    if (start > 2) links.push({ label: 1, url: '#' })
    if (start > 3) links.push({ label: '...', url: '#' })
  }

  for (let i = start; i <= end; i++) {
    links.push({ label: i, url: '#' }) // URL diabaikan, hanya label yang digunakan
  }
  
  if (end < total) {
    if (end < total - 1) links.push({ label: '...', url: '#' })
    if (end < total) links.push({ label: total, url: '#' })
  }

  return links
})

</script>

<template>
  <div class="flex flex-col w-full bg-white rounded-lg">
    
    <div class="pt-0">
      <div class="overflow-auto rounded-b-lg max-h-[800px] thin-scrollbar border-b">
        <table class="w-full border-collapse">
          <thead ref="thead" class=" z-10" :class="config.sticky && 'sticky top-0 left-0'">
            <slot name="thead" :config="config" :paginator="paginator" :data="paginator.data" :refresh="refresh" />
          </thead>

          <tbody ref="tbody" class="text-sm text-gray-700">
            <tr v-if="processing">
              <td :colspan="100" class="text-center py-4 bg-white text-sky-500 text-sm italic">
                <svg class="animate-spin inline mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Memuat data...
              </td>
            </tr>
            <slot name="tbody" :config="config" :paginator="paginator" :data="paginator.data" :processing="processing" :empty="!processing && paginator?.data?.length === 0" />
          </tbody>

          <tfoot ref="tfoot" class=" z-10" :class="config.sticky && 'sticky bottom-0 left-0'">
            <slot name="tfoot" :config="config" :paginator="paginator" :data="paginator.data" :refresh="refresh" />
          </tfoot>
        </table>
      </div> 
    </div>
  </div>
</template>