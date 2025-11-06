<script setup>
import { useForm } from '@inertiajs/inertia-vue3'
import axios from 'axios'
import Swal from 'sweetalert2'
import { getCurrentInstance, nextTick, onMounted, onUnmounted, onUpdated, ref, computed } from 'vue'
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

const goTo = link => {
  if (!link.url) {
    return
  }
  
  config.page = Number(link.url.match(/page=([\d]+)/)[1])
  paginator.value.current_page !== config.page && refresh()
}

const changePage = page => {
  console.log(paginator)
  config.page = page
  paginator.value.current_page !== config.page && refresh()
}

let perPage = ref(5)
let showOptions = ref(false)

const setPerPage = per_page => {
  
  config.per_page = per_page
  config.page = 1
  perPage.value = per_page
  showOptions.value = false
  paginator.value.per_page !== config.per_page && refresh()
  
  
}


const refresh = async u => {
  try {
    processing.value = true
    const prev = last.value = u || url
    const { data } = await axios.post(prev || last.value, config.data())
    paginator.value = data
    self.proxy.$forceUpdate()
  } catch (e) {
    const { isConfirmed } = await Swal.fire({
      title: 'error',
      text: `${e}`,
      icon: 'error',
      showCancelButton: true,
      showCloseButton: true,
    })

    if (isConfirmed) {
      return await refresh(last.value)
    }
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

const visibleLinks = computed(() => {
  if (!paginator.value || !paginator.value.last_page) return []

  const total = paginator.value.last_page
  const current = paginator.value.current_page
  const maxVisible = 5
  const links = []

  let start = Math.max(1, current - 2)
  let end = Math.min(total, current + 2)

  if (total > maxVisible) {
    if (start > 2) links.push({ label: 1 })
    if (start > 3) links.push({ label: '...' })

    for (let i = start; i <= end; i++) {
      links.push({ label: i })
    }

    if (end < total - 2) links.push({ label: '...' })
    if (end < total) links.push({ label: total })
  } else {
    for (let i = 1; i <= total; i++) {
      links.push({ label: i })
    }
  }

  return links
})

</script>
<!-- per page -->

<template>
  <div class="flex flex-col w-full">
    <div class="flex flex-row items-center justify-between space-x-2 p-2">
        
        <div class="flex items-center justify-start flex-shrink-0">
            <div class="relative inline-block text-left" >
                <button @click.stop="showOptions = !showOptions" type="button" class="inline-flex justify-center w-full px-4 py-2 text-xs font-medium text-gray-700 bg-slate-100 rounded-md shadow-sm focus:bg-slate-200 focus:border-transparent focus:outline-none focus:ring-0 ">
                    {{ perPage }}
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-2 -mr-1" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M6.293 9.293a1 1 0 011.414 0L10 11.586l2.293-2.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>

                <div v-show="showOptions" class="origin-top-right absolute right-0 mt-1 w-[60px] rounded-md shadow-lg bg-white border ring-0 ring-black ring-opacity-5 z-50" >
                    <div class="py-1 flex flex-col pt-1 items-center" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                        <button @click.stop="setPerPage(5)" class="block px-4 py-1 text-xs text-gray-700 hover:bg-gray-100 w-14" role="menuitem">5</button>
                        <button @click.stop="setPerPage(10)" class="block px-4 py-1 text-xs text-gray-700 hover:bg-gray-100 w-14" role="menuitem">10</button>
                        <button @click.stop="setPerPage(25)" class="block px-4 py-1 text-xs text-gray-700 hover:bg-gray-100 w-14" role="menuitem">25</button>
                        <button @click.stop="setPerPage(100)" class="block px-4 py-1 text-xs text-gray-700 hover:bg-gray-100 w-14" role="menuitem">100</button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="w-full max-w-sm flex items-center space-x-2 justify-end">
            <div class="flex flex-row items-center bg-transparent rounded-md z-10 w-[30]">
                <input 
                    v-model="config.search" 
                    @input.prevent="refresh()" 
                    type="search" 
                    name="search" 
                    class="z-20 w-full bg-slate-100 h-[30px] rounded-md px-3 py-1 placeholder:capitalize border-transparent focus:bg-slate-200 focus:border-transparent focus:outline-none focus:ring-0 text-xs" 
                    placeholder="search" 
                    autofocus
                >
            </div>
        </div>
    </div>

    <div class=" pt-[9px]">
      <div class="overflow-auto rounded-md max-h-[800px] thin-scrollbar">
        <table class="w-full border-collapse">
          <thead ref="thead" class=" z-10" :class="config.sticky && 'sticky top-0 left-0'">
            <slot name="thead" :config="config" :paginator="paginator" :data="paginator.data" :refresh="refresh" />
          </thead>

          <tfoot ref="tfoot" class=" z-10" :class="config.sticky && 'sticky bottom-0 left-0'">
            <slot name="tfoot" :config="config" :paginator="paginator" :data="paginator.data" :refresh="refresh" />
          </tfoot>

          <tbody ref="tbody" class="text-[16px] text-slate-600 ">
            <slot name="tbody" :config="config" :paginator="paginator" :data="paginator.data" :processing="processing" :empty="!processing && !paginator?.data?.length" />
          </tbody>
        </table>
      </div> 
      <!-- Pagenation Belum jalan scriptnya -->
  <!-- component -->
        <!-- component -->
        <!-- Pagenation 1 -->
        <div class="pt-3">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between px-3 py-2 text-xs gap-2">
            <!-- Info kiri -->
            <div class="text-gray-600 text-center sm:text-left">
              Showing {{ paginator.from }} to {{ paginator.to }} of {{ paginator.total }} entries
            </div>

            <!-- Navigasi kanan -->
            <nav class="flex items-center justify-center flex-wrap gap-1 mt-2 text-xs">
              <!-- Tombol Previous -->
              <button
                v-if="paginator.prev_page_url"
                @click.prevent="changePage(paginator.current_page - 1)"
                class="px-3 py-1 bg-slate-100 rounded-md hover:bg-blue-100 hover:text-blue-700"
              >
                Previous
              </button>

              <!-- Nomor Halaman -->
              <template v-for="(link, i) in visibleLinks" :key="i">
                <span
                  v-if="link.label === '...'"
                  class="px-2 py-1 text-gray-400 select-none"
                >
                  ...
                </span>
                <button
                  v-else
                  @click.prevent="changePage(Number(link.label))"
                  :class="[
                    'px-3 py-1 rounded-md border shadow-sm transition',
                    paginator.current_page == link.label
                      ? 'bg-blue-600 text-white border-blue-600'
                      : 'bg-white text-gray-700 border-gray-200 hover:bg-blue-50 hover:text-blue-700'
                  ]"
                >
                  {{ link.label }}
                </button>
              </template>

              <!-- Tombol Next -->
              <button
                v-if="paginator.next_page_url"
                @click.prevent="changePage(paginator.current_page + 1)"
                class="px-3 py-1 bg-slate-100 rounded-md hover:bg-blue-100 hover:text-blue-700"
              >
                Next
              </button>
            </nav>
          </div>

    </div>
 </div>
  </div>
</template>