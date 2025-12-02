<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';

const props = defineProps({
  groupedData: Object,
  selectedDate: String,
  filledDates: Array,
  hasAssessment: Boolean,
  healthCertificate: Object,
});

// Debug log
onMounted(() => {
  console.log('MyHistory Component Mounted');
  console.log('Props:', props);
  console.log('GroupedData:', props.groupedData);
  console.log('HasAssessment:', props.hasAssessment);
});

const selectedDate = ref(props.selectedDate);

const changeDate = (date) => {
  selectedDate.value = date;
  Inertia.get('/readiness-assessment/my-history', { date: date }, {
    preserveState: true,
    preserveScroll: true,
  });
};

const totalQuestions = computed(() => {
  if (!props.groupedData) return 0;
  try {
    let total = 0;
    Object.values(props.groupedData).forEach(questions => {
      if (Array.isArray(questions)) {
        total += questions.length;
      }
    });
    return total;
  } catch (e) {
    console.error('Error in totalQuestions:', e);
    return 0;
  }
});

const answeredCount = computed(() => {
  if (!props.groupedData) return 0;
  try {
    let count = 0;
    Object.values(props.groupedData).forEach(questions => {
      if (Array.isArray(questions)) {
        questions.forEach(q => {
          if (q && q.jawaban && q.jawaban !== 'Belum Diisi') count++;
        });
      }
    });
    return count;
  } catch (e) {
    console.error('Error in answeredCount:', e);
    return 0;
  }
});

const yesCount = computed(() => {
  if (!props.groupedData) return 0;
  try {
    let count = 0;
    Object.values(props.groupedData).forEach(questions => {
      if (Array.isArray(questions)) {
        questions.forEach(q => {
          if (q && q.jawaban === 'Ya') count++;
        });
      }
    });
    return count;
  } catch (e) {
    console.error('Error in yesCount:', e);
    return 0;
  }
});

const noCount = computed(() => {
  if (!props.groupedData) return 0;
  try {
    let count = 0;
    Object.values(props.groupedData).forEach(questions => {
      if (Array.isArray(questions)) {
        questions.forEach(q => {
          if (q && q.jawaban === 'Tidak') count++;
        });
      }
    });
    return count;
  } catch (e) {
    console.error('Error in noCount:', e);
    return 0;
  }
});

const completionPercentage = computed(() => {
  if (totalQuestions.value === 0) return 0;
  return Math.round((answeredCount.value / totalQuestions.value) * 100);
});

const formatDate = (date) => {
  const d = new Date(date);
  return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
};
</script>

<template>
  <DashboardLayout title="History Assessment Saya">
    <Head title="History Assessment Saya" />

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <!-- Header -->
        <div class="bg-white shadow-sm sm:rounded-lg">
          <div class="p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">
              History Daily Readiness Assessment
            </h2>
            <p class="text-gray-600">Lihat riwayat pengisian assessment harian Anda</p>
          </div>
        </div>

        <!-- Filter Tanggal -->
        <div class="bg-white shadow-sm sm:rounded-lg">
          <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Pilih Tanggal
                </label>
                <input
                  type="date"
                  v-model="selectedDate"
                  @change="changeDate(selectedDate)"
                  class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Tanggal yang Pernah Diisi (30 Hari Terakhir)
                </label>
                <div v-if="filledDates && filledDates.length > 0" class="flex flex-wrap gap-2">
                  <button
                    v-for="date in filledDates"
                    :key="date"
                    @click="changeDate(date)"
                    :class="[
                      'px-3 py-1 text-sm rounded-md transition-colors',
                      date === selectedDate
                        ? 'bg-blue-600 text-white'
                        : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
                    ]"
                  >
                    {{ new Date(date).toLocaleDateString('id-ID', { day: '2-digit', month: 'short' }) }}
                  </button>
                </div>
                <div v-else class="flex flex-wrap gap-2">
                  <p class="text-gray-500 text-sm italic">
                    Belum ada data
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Statistik -->
        <div v-if="hasAssessment" class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div class="bg-white shadow-sm sm:rounded-lg">
            <div class="p-5">
              <dt class="text-sm font-medium text-gray-500 mb-1">
                Total Pertanyaan
              </dt>
              <dd class="text-2xl font-bold text-gray-900">
                {{ totalQuestions }}
              </dd>
            </div>
          </div>

          <div class="bg-white shadow-sm sm:rounded-lg">
            <div class="p-5">
              <dt class="text-sm font-medium text-gray-500 mb-1">
                Jawaban "Ya"
              </dt>
              <dd class="text-2xl font-bold text-green-600">
                {{ yesCount }}
              </dd>
            </div>
          </div>

          <div class="bg-white shadow-sm sm:rounded-lg">
            <div class="p-5">
              <dt class="text-sm font-medium text-gray-500 mb-1">
                Jawaban "Tidak"
              </dt>
              <dd class="text-2xl font-bold text-red-600">
                {{ noCount }}
              </dd>
            </div>
          </div>

          <div class="bg-white shadow-sm sm:rounded-lg">
            <div class="p-5">
              <dt class="text-sm font-medium text-gray-500 mb-1">
                Kelengkapan
              </dt>
              <dd class="text-2xl font-bold" :class="completionPercentage === 100 ? 'text-green-600' : 'text-blue-600'">
                {{ completionPercentage }}%
              </dd>
            </div>
          </div>
        </div>

        <!-- Info Sertifikat Kesehatan -->
        <div v-if="healthCertificate" class="bg-white shadow-sm sm:rounded-lg">
          <div class="p-6">
            <div class="flex items-center justify-between flex-wrap gap-4">
              <div>
                <h3 class="text-lg font-semibold text-gray-800">Sertifikat Kesehatan</h3>
                <p class="text-sm text-gray-600 mt-1">
                  Berlaku: {{ healthCertificate.valid_from }} - {{ healthCertificate.valid_until }}
                </p>
                <p class="text-sm mt-1" :class="healthCertificate.days_remaining <= 1 ? 'text-red-600 font-semibold' : 'text-gray-600'">
                  Sisa waktu: {{ healthCertificate.days_remaining }} hari
                </p>
              </div>
              <a
                :href="healthCertificate.file_url"
                target="_blank"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition"
              >
                Lihat Sertifikat
              </a>
            </div>
          </div>
        </div>

        <!-- Data Assessment -->
        <div v-if="hasAssessment && groupedData" class="bg-white shadow-sm sm:rounded-lg">
          <div class="p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">
              Detail Assessment - {{ formatDate(selectedDate) }}
            </h3>

            <div v-for="(questions, groupName) in groupedData" :key="groupName" class="mb-6 last:mb-0">
              <h4 class="text-lg font-semibold text-gray-700 mb-3 bg-gray-100 px-4 py-2 rounded-md">
                {{ groupName }}
              </h4>

              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">
                        No
                      </th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Komponen
                      </th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Pertanyaan
                      </th>
                      <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                        Jawaban
                      </th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Catatan
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="question in questions" :key="question.id">
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ question.urutan }}
                      </td>
                      <td class="px-6 py-4 text-sm text-gray-900">
                        {{ question.komponen }}
                      </td>
                      <td class="px-6 py-4 text-sm text-gray-900">
                        {{ question.pertanyaan }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span
                          v-if="question.jawaban === 'Ya'"
                          class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800"
                        >
                          ✓ Ya
                        </span>
                        <span
                          v-else-if="question.jawaban === 'Tidak'"
                          class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-800"
                        >
                          ✗ Tidak
                        </span>
                        <span
                          v-else
                          class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-gray-100 text-gray-500"
                        >
                          -
                        </span>
                      </td>
                      <td class="px-6 py-4 text-sm text-gray-600">
                        {{ question.note || '-' }}
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <!-- Tidak Ada Data -->
        <div v-else class="bg-white shadow-sm sm:rounded-lg">
          <div class="p-12 text-center">
            <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="mt-4 text-xl font-semibold text-gray-900">Tidak Ada Data</h3>
            <p class="mt-2 text-gray-600">
              Anda belum mengisi assessment untuk tanggal {{ formatDate(selectedDate) }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>
