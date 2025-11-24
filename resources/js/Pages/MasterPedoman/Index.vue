<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Card from '@/Components/Card.vue'
import ButtonBlue from '@/Components/Button/Blue.vue'
import ButtonRed from '@/Components/Button/Red.vue'
import { Link } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'

defineProps({ pedomans: Object })

const deletePedoman = (id) => {
  if(confirm('Hapus pedoman ini? Semua data terkait akan hilang.')) {
    Inertia.delete(route('master-pedoman.destroy', id))
  }
}
</script>

<template>
  <DashboardLayout title="Master Data Pedoman">
    <div class="p-6">
      <div class="flex justify-between mb-4">
        <h2 class="text-2xl font-bold">Master Data Checksheet</h2>
        <Link :href="route('master-pedoman.create')">
           <ButtonBlue>+ Tambah Pedoman Baru</ButtonBlue>
        </Link>
      </div>

      <Card class="bg-white p-4 shadow">
         <template #body>
            <table class="w-full text-left border">
               <thead class="bg-gray-100">
                  <tr>
                     <th class="p-3">Kode</th>
                     <th class="p-3">Nama Pedoman</th>
                     <th class="p-3 text-center">Aksi</th>
                  </tr>
               </thead>
               <tbody>
                  <tr v-for="p in pedomans.data" :key="p.id" class="border-b hover:bg-gray-50">
                     <td class="p-3 font-bold">{{ p.kode_pedoman }}</td>
                     <td class="p-3">{{ p.nama_pedoman }}</td>
                     <td class="p-3 text-center space-x-2">
                        <Link :href="route('master-pedoman.edit', p.id)" class="text-blue-600 hover:underline">Edit</Link>
                        <button @click="deletePedoman(p.id)" class="text-red-600 hover:underline">Hapus</button>
                     </td>
                  </tr>
               </tbody>
            </table>
         </template>
      </Card>
    </div>
  </DashboardLayout>
</template>
