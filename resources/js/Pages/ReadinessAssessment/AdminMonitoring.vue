<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';

const props = defineProps({
  userAssessments: Array,
  users: Array,
  selectedDate: String,
  selectedUserId: [Number, String],
  summary: Object,
});

const selectedDate = ref(props.selectedDate);
const selectedUserId = ref(props.selectedUserId || '');

const applyFilter = () => {
  Inertia.get('/readiness-assessment/admin-monitoring', {
    date: selectedDate.value,
    user_id: selectedUserId.value || undefined,
  }, {
    preserveState: true,
    preserveScroll: true,
  });
};

const resetFilter = () => {
  selectedDate.value = new Date().toISOString().split('T')[0];
  selectedUserId.value = '';
  applyFilter();
};

const viewUserDetail = (userId) => {
  Inertia.get('/readiness-assessment/user/' + userId, {
    date: selectedDate.value,
  });
};

const formatDate = (date) => {
  const d = new Date(date);
  return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
};

const getStatusBadge = (status) => {
  return status === 'complete'
    ? 'bg-green-100 text-green-800'
    : 'bg-yellow-100 text-yellow-800';
};

const getStatusText = (status) => {
  return status === 'complete' ? 'Lengkap' : 'Belum Lengkap';
};
</script>

<template>
  <DashboardLayout title="Monitoring Assessment">
    <Head title="Monitoring Assessment" />

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <!-- Header -->
        <div class="bg-white shadow-sm sm:rounded-lg">
          <div class="p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">
              Monitoring Daily Readiness Assessment
            </h2>
            <p class="text-gray-600">Pantau dan kelola pengisian assessment seluruh pengguna</p>
          </div>
        </div>

        <!-- Summary Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
          <div class="bg-white shadow-sm sm:rounded-lg">
            <div class="p-5">
              <dt class="text-sm font-medium text-gray-500 mb-1">
                Total User
              </dt>
              <dd class="text-2xl font-bold text-gray-900">
                {{ summary.total_users }}
              </dd>
            </div>
          </div>

          <div class="bg-white shadow-sm sm:rounded-lg">
            <div class="p-5">
              <dt class="text-sm font-medium text-gray-500 mb-1">
                Lengkap
              </dt>
              <dd class="text-2xl font-bold text-green-600">
                {{ summary.users_completed }}
              </dd>
            </div>
          </div>

          <div class="bg-white shadow-sm sm:rounded-lg">
            <div class="p-5">
              <dt class="text-sm font-medium text-gray-500 mb-1">
                Belum Lengkap
              </dt>
              <dd class="text-2xl font-bold text-yellow-600">
                {{ summary.users_incomplete }}
              </dd>
            </div>
          </div>

          <div class="bg-white shadow-sm sm:rounded-lg">
            <div class="p-5">
              <dt class="text-sm font-medium text-gray-500 mb-1">
                Belum Mulai
              </dt>
              <dd class="text-2xl font-bold text-red-600">
                {{ summary.users_not_started }}
              </dd>
            </div>
          </div>

          <div class="bg-white shadow-sm sm:rounded-lg">
            <div class="p-5">
              <dt class="text-sm font-medium text-gray-500 mb-1">
                Tingkat Kepatuhan
              </dt>
              <dd class="text-2xl font-bold" :class="summary.completion_rate === 100 ? 'text-green-600' : 'text-blue-600'">
                {{ summary.completion_rate }}%
              </dd>
            </div>
          </div>
        </div>

        <!-- Filter -->
        <div class="bg-white shadow-sm sm:rounded-lg">
          <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Pilih Tanggal
                </label>
                <input
                  type="date"
                  v-model="selectedDate"
                  class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Filter User
                </label>
                <select
                  v-model="selectedUserId"
                  class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                >
                  <option value="">Semua User</option>
                  <option
                    v-for="user in users"
                    :key="user.id"
                    :value="user.id"
                  >
                    [{{ user.username }}] {{ user.name }}
                  </option>
                </select>
              </div>
              <div class="flex items-end gap-2">
                <button
                  @click="applyFilter"
                  class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition"
                >
                  Terapkan Filter
                </button>
                <button
                  @click="resetFilter"
                  class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md text-sm font-medium transition"
                >
                  Reset
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Data Table -->
        <div class="bg-white shadow-sm sm:rounded-lg">
          <div class="p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">
              Data Assessment - {{ formatDate(selectedDate) }}
            </h3>

            <div v-if="userAssessments.length > 0" class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      No
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      User
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Total Pertanyaan
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Terjawab
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Kelengkapan
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Ya
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Tidak
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Status
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Aksi
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="(assessment, index) in userAssessments" :key="assessment.user_id" class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ index + 1 }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm font-medium text-gray-900">
                        {{ assessment.user_name }}
                      </div>
                      <div class="text-sm text-gray-500">
                        [{{ assessment.username }}]
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                      {{ assessment.total_questions }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                      {{ assessment.answered }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                      <div class="flex items-center justify-center">
                        <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                          <div
                            class="h-2 rounded-full transition-all"
                            :class="assessment.completion_percentage === 100 ? 'bg-green-500' : 'bg-blue-500'"
                            :style="`width: ${assessment.completion_percentage}%`"
                          ></div>
                        </div>
                        <span class="text-sm font-semibold">{{ assessment.completion_percentage }}%</span>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                      <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        {{ assessment.yes_count }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                      <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        {{ assessment.no_count }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                      <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                        :class="getStatusBadge(assessment.status)"
                      >
                        {{ getStatusText(assessment.status) }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                      <button
                        @click="viewUserDetail(assessment.user_id)"
                        class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none transition"
                      >
                        Detail
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Empty State -->
            <div v-else class="text-center py-12">
              <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
              </svg>
              <h3 class="mt-4 text-xl font-semibold text-gray-900">Tidak Ada Data</h3>
              <p class="mt-2 text-gray-600">
                Belum ada user yang mengisi assessment untuk tanggal {{ formatDate(selectedDate) }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>
