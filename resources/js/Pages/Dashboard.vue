<script setup>
import { ref,nextTick, computed, onMounted } from 'vue'
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import Welcome from '@/Jetstream/Welcome.vue';
import { usePage, useForm } from '@inertiajs/inertia-vue3';
import Modal from '@/Components/Modal.vue'
import Close from '@/Components/Button/Close.vue'
import Card from '@/Components/Card.vue'
import Builder from '@/Components/DataTable/Builder.vue'
import Th from '@/Components/DataTable/Th.vue'
import Button from '@/Components/Button.vue'
import BtnAttachment from '@/Components/Button/Attachment.vue'
import axios from 'axios'

const { user } = usePage().props.value

const { users, hasCompletedAssessment, assessmentData, data_laporin_full, mesin_totals, formatted_mesin_total, formatted_generator_total, formatted_counter_total, formatted_oddometer_total, formatted_hsd_total, maintenanceStats, recentMaintenanceOrders, isAdminOrSupervisor } = defineProps({
  users: Array,
  hasCompletedAssessment: Boolean,
  assessmentData: Object,
  data_laporin:Object,
  data_laporin_full: Array,
  mesin_totals: Array,
  formatted_mesin_total: Array,
  formatted_generator_total: Array,
  formatted_counter_total: Array,
  formatted_oddometer_total: Array,
  formatted_hsd_total: Array,
  maintenanceStats: Object,
  recentMaintenanceOrders: Array,
  count : Number,
  isAdminOrSupervisor: Boolean, // Flag dari controller
})

const showAssessmentModal = ref(false);
const showHealthCertModal = ref(false);
const healthCertStatus = ref(null);

// Form untuk upload surat keterangan sehat
const healthCertForm = useForm({
  health_certificate: null,
  valid_from: new Date().toISOString().split('T')[0], // Default hari ini
  notes: '',
});

const submitHealthCertificate = () => {
  healthCertForm.post(route('health-certificate.upload'), {
    preserveScroll: true,
    onSuccess: () => {
      showHealthCertModal.value = false;
      checkHealthCertificateStatus();
      // Setelah upload sukses, buka modal assessment
      setTimeout(() => {
        showAssessmentModal.value = true;
      }, 500);
    },
  });
};

const checkHealthCertificateStatus = async () => {
  try {
    const response = await axios.get(route('health-certificate.status'));
    healthCertStatus.value = response.data;
  } catch (error) {
    console.error('Failed to check health certificate status:', error);
  }
};

const initialAnswers = {};
if (assessmentData && assessmentData.groupedQuestions) {
    for (const groupName in assessmentData.groupedQuestions) {
        assessmentData.groupedQuestions[groupName].forEach(question => {
            initialAnswers[question.id] = question.answer === null ? null : (question.answer ? 1 : 0);
        });
    }
}

const assessmentForm = useForm({
    answers: initialAnswers,
});

const totalQuestions = computed(() => {
    if (!assessmentData || !assessmentData.groupedQuestions) return 0;
    return Object.values(assessmentData.groupedQuestions).flat().length;
});

const answeredQuestions = computed(() => {
    return Object.values(assessmentForm.answers).filter(answer => answer !== null).length;
});

const isComplete = computed(() => answeredQuestions.value === totalQuestions.value);

const submitAssessment = () => {
    assessmentForm.post(route('readiness.store'), {
        preserveScroll: true,
        onSuccess: () => {
            showAssessmentModal.value = false;
            window.location.reload();
        },
        onError: (errors) => {
            console.error('Submission failed with errors:', errors);
            // Jika error karena belum upload sertifikat, buka modal upload
            if (errors.health_certificate) {
              showAssessmentModal.value = false;
              showHealthCertModal.value = true;
            }
        }
    });
};

let currentCount = 0;
const getQuestionNumber = (reset = false) => {
    if (reset) currentCount = 0;
    return ++currentCount;
};

onMounted(async () => {
    // Jika user adalah admin/supervisor, jangan tampilkan modal assessment sama sekali
    if (isAdminOrSupervisor) {
        return; // Langsung keluar, tidak perlu cek sertifikat atau tampilkan modal
    }

    // Cek status sertifikat kesehatan dulu (hanya untuk user biasa)
    await checkHealthCertificateStatus();

    if (!hasCompletedAssessment && assessmentData) {
        // Jika belum punya sertifikat valid, tampilkan modal upload dulu
        if (!healthCertStatus.value?.has_valid_certificate) {
          showHealthCertModal.value = true;
        } else {
          showAssessmentModal.value = true;
        }
    }

});

const showModal = ref(false);
const selectedReport = ref(null);

const pbrFormattedTotals = computed(() => {
    if (!formatted_mesin_total) return {};

    const filtered = {};

    for (const machineName in formatted_mesin_total) {
        if (formatted_mesin_total.hasOwnProperty(machineName)) {

            if (machineName.includes('U-RS') || machineName.includes('Ballast Regulator Machine')) {
                filtered[machineName] = formatted_mesin_total[machineName];
            }
        }
    }
    return filtered;
});

const mttFormattedTotals = computed(() => {
    if (!formatted_mesin_total) return {};

    const filtered = {};

    for (const machineName in formatted_mesin_total) {
        if (formatted_mesin_total.hasOwnProperty(machineName)) {
            if (machineName.includes('CSM') || machineName.includes('Tamping Machine')) {
                filtered[machineName] = formatted_mesin_total[machineName];
            }
        }
    }
    return filtered;
});

const pbrFormattedGeneratorTotals = computed(() => {
    if (!formatted_generator_total) return {};

    const filtered = {};

    for (const machineName in formatted_generator_total) {
        if (formatted_generator_total.hasOwnProperty(machineName)) {

            if (machineName.includes('U-RS') || machineName.includes('Ballast Regulator Machine')) {
                filtered[machineName] = formatted_generator_total[machineName];
            }
        }
    }
    return filtered;
});

const mttFormattedGeneratorTotals = computed(() => {
    if (!formatted_generator_total) return {};

    const filtered = {};

    for (const machineName in formatted_generator_total) {
        if (formatted_generator_total.hasOwnProperty(machineName)) {
            if (machineName.includes('CSM') || machineName.includes('Tamping Machine')) {
                filtered[machineName] = formatted_generator_total[machineName];
            }
        }
    }
    return filtered;
});

const pbrFormattedCounterTotals = computed(() => {
    if (!formatted_counter_total) return {};

    const filtered = {};

    for (const machineName in formatted_counter_total) {
        if (formatted_counter_total.hasOwnProperty(machineName)) {

            if (machineName.includes('U-RS') || machineName.includes('Ballast Regulator Machine')) {
                filtered[machineName] = formatted_counter_total[machineName];
            }
        }
    }
    return filtered;
});

const mttFormattedCounterTotals = computed(() => {
    if (!formatted_counter_total) return {};

    const filtered = {};

    for (const machineName in formatted_counter_total) {
        if (formatted_counter_total.hasOwnProperty(machineName)) {
            if (machineName.includes('CSM') || machineName.includes('Tamping Machine')) {
                filtered[machineName] = formatted_counter_total[machineName];
            }
        }
    }
    return filtered;
});

const pbrFormattedOddometerTotals = computed(() => {
    if (!formatted_oddometer_total) return {};

    const filtered = {};

    for (const machineName in formatted_oddometer_total) {
        if (formatted_oddometer_total.hasOwnProperty(machineName)) {

            if (machineName.includes('U-RS') || machineName.includes('Ballast Regulator Machine')) {
                filtered[machineName] = formatted_oddometer_total[machineName];
            }
        }
    }
    return filtered;
});

const mttFormattedOddometerTotals = computed(() => {
    if (!formatted_oddometer_total) return {};

    const filtered = {};

    for (const machineName in formatted_oddometer_total) {
        if (formatted_oddometer_total.hasOwnProperty(machineName)) {
            if (machineName.includes('CSM') || machineName.includes('Tamping Machine')) {
                filtered[machineName] = formatted_oddometer_total[machineName];
            }
        }
    }
    return filtered;
});

const pbrFormattedHsdTotals = computed(() => {
    if (!formatted_hsd_total) return {};

    const filtered = {};

    for (const machineName in formatted_hsd_total) {
        if (formatted_hsd_total.hasOwnProperty(machineName)) {

            if (machineName.includes('U-RS') || machineName.includes('Ballast Regulator Machine')) {
                filtered[machineName] = formatted_hsd_total[machineName];
            }
        }
    }
    return filtered;
});

const mttFormattedHsdTotals = computed(() => {
    if (!formatted_hsd_total) return {};

    const filtered = {};

    for (const machineName in formatted_hsd_total) {
        if (formatted_hsd_total.hasOwnProperty(machineName)) {
            if (machineName.includes('CSM') || machineName.includes('Tamping Machine')) {
                filtered[machineName] = formatted_hsd_total[machineName];
            }
        }
    }
    return filtered;
});

const hasRole = (roles) => {
    if (!user.roles) return false;
    return user.roles.some(role => roles.includes(role.name));
};

</script>

<template>
    <DashboardLayout title="Dashboard">
        <!-- <main class="p-0 py-0 mb-[1.25rem] ml-[1.25rem] mt-[1.25rem]"> -->
        <main class="p-0 py-0 ">
            <h2 class="font-bold text-2xl">Dashboard</h2>
            <h3 class="text-base font-semibold pl-0 leading-6 text-gray-500">
            Selamat Datang, {{user.positions?.position}} - {{ user.name }} - ({{ user.divisions?.division_name }})
            </h3>
        </main>
        <div>

        <div class="min-h-screen bg-gray-50/50">
        <div class="p-1 ">
            <div class="">
            <!-- Card Components -->
            <div class="mb-12 grid gap-y-10 gap-x-6 md:grid-cols-2 xl:grid-cols-3">

                <div class="relative flex flex-col bg-white rounded-xl shadow-2xl overflow-hidden border border-gray-100 transition-shadow duration-300 hover:shadow-3xl">

                    <div class="p-4 flex justify-between items-center bg-blue-500">
                        <div class="flex items-center space-x-3">
                            <div class="bg-red-500 rounded-lg p-3 text-white shadow-md shadow-red-500/50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.526.323 1.028.53 1.572.684.51-.157 1.012-.364 1.538-.684z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-xl font-extrabold text-white text-center flex-grow">
                            Tamping Machine
                        </h3>
                        <button class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition duration-150">
                            <img src="../../../public/assets/receive-message.png" class="w-6" alt="Laporan">
                        </button>
                    </div>

                    <div class="p-4 divide-y divide-gray-200">

                        <div
                            v-for="(duration, machineName) in mttFormattedTotals"
                            :key="machineName"
                        >
                            <div class="w-full border-b-2 border-gray-700 ">
                                <span class="text-base font-bold text-black text-xs">
                                    {{ machineName }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="flex items-center space-x-2 text-sm font-medium text-gray-700">
                                    <span class="text-blue-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    </span>
                                    <span>Engine Hours</span>
                                </span>
                                <span class="font-extrabold text-base text-blue-800">{{ duration }}</span>
                            </div>

                            <div
                                v-if="mttFormattedGeneratorTotals[machineName]"
                                class="flex justify-between items-center py-2"
                            >
                                <span class="flex items-center space-x-2 text-sm font-medium text-gray-700">
                                    <span class="text-green-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                    </span>
                                    <span>Generator Hours (Meter)</span>
                                </span>
                                <span class="font-bold text-base text-green-700">
                                    {{ mttFormattedGeneratorTotals[machineName] }}
                                </span>
                            </div>

                            <div
                                v-if="mttFormattedCounterTotals[machineName]"
                                class="flex justify-between items-center py-2"
                            >
                                <span class="flex items-center space-x-2 text-sm font-medium text-gray-700">
                                    <span class="text-red-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                                    </span>
                                    <span>Tamping Counter</span>
                                </span>
                                <span class="font-bold text-base text-red-700">
                                    {{ mttFormattedCounterTotals[machineName] }}
                                </span>
                            </div>

                            <div
                                v-if="mttFormattedOddometerTotals[machineName]"
                                class="flex justify-between items-center py-2"
                            >
                                <span class="flex items-center space-x-2 text-sm font-medium text-gray-700">
                                    <span class="text-indigo-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    </span>
                                    <span>Odometer</span>
                                </span>
                                <span class="font-bold text-base text-indigo-700">
                                    {{ mttFormattedOddometerTotals[machineName] }}
                                </span>
                            </div>

                            <div
                                v-if="mttFormattedHsdTotals[machineName]"
                                class="flex justify-between items-center py-2"
                            >
                                <span class="flex items-center space-x-2 text-sm font-medium text-gray-700">
                                    <span class="text-orange-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    </span>
                                    <span>HSD (Liter)</span>
                                </span>
                                <span class="font-bold text-base text-orange-700">
                                    {{ mttFormattedHsdTotals[machineName] }}
                                </span>
                            </div>

                            <hr v-if="Object.keys(mttFormattedTotals).length > 1 && machineName !== Object.keys(mttFormattedTotals)[Object.keys(mttFormattedTotals).length - 1]" class="my-3 border-dashed border-gray-300" />

                        </div>

                        <div v-if="Object.keys(mttFormattedTotals).length === 0" class="col-span-3 text-center text-sm text-gray-500 p-4">
                            Tidak ada data untuk Tamping Machine.
                        </div>
                    </div>
                </div>

                <div class="relative flex flex-col bg-white rounded-xl shadow-2xl overflow-hidden border border-gray-100 transition-shadow duration-300 hover:shadow-3xl">

                    <div class="p-4 flex justify-between items-center bg-blue-500">
                        <div class="flex items-center space-x-3">
                            <div class="bg-red-500 rounded-lg p-3 text-white shadow-md shadow-red-500/50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9h2M5 12h2M12 5v2M12 17v2M17 12h2M5 12h2" />
                                </svg>
                            </div>
                        </div>

                        <h3 class="text-xl font-extrabold text-white text-center flex-grow">
                            Ballast Regulator Machine
                        </h3>

                        <button class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition duration-150">
                            <img src="../../../public/assets/receive-message.png" class="w-6" alt="Laporan">
                        </button>
                    </div>

                    <div class="p-4 divide-y divide-gray-200">

                        <div
                            v-for="(duration, machineName) in pbrFormattedTotals"
                            :key="machineName"
                        >
                            <div class="w-full border-b-2 border-gray-700 ">
                                <span class="text-base font-bold text-black text-xs">
                                    {{ machineName }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="flex items-center space-x-2 text-sm font-medium text-gray-700">
                                    <span class="text-blue-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    </span>
                                    <span>Engine Hours</span>
                                </span>
                                <span class="font-extrabold text-base text-blue-800">{{ duration }}</span>
                            </div>

                            <div
                                v-if="pbrFormattedGeneratorTotals[machineName]"
                                class="flex justify-between items-center py-2"
                            >
                                <span class="flex items-center space-x-2 text-sm font-medium text-gray-700">
                                    <span class="text-green-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                    </span>
                                    <span>Generator Hours (Meter)</span>
                                </span>
                                <span class="font-bold text-base text-green-700">
                                    {{ pbrFormattedGeneratorTotals[machineName] }}
                                </span>
                            </div>

                            <div
                                v-if="pbrFormattedCounterTotals[machineName]"
                                class="flex justify-between items-center py-2"
                            >
                                <span class="flex items-center space-x-2 text-sm font-medium text-gray-700">
                                    <span class="text-red-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                                    </span>
                                    <span>Tamping Counter</span>
                                </span>
                                <span class="font-bold text-base text-red-700">
                                    {{ pbrFormattedCounterTotals[machineName] }}
                                </span>
                            </div>

                            <div
                                v-if="pbrFormattedOddometerTotals[machineName]"
                                class="flex justify-between items-center py-2"
                            >
                                <span class="flex items-center space-x-2 text-sm font-medium text-gray-700">
                                    <span class="text-indigo-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    </span>
                                    <span>Odometer</span>
                                </span>
                                <span class="font-bold text-base text-indigo-700">
                                    {{ pbrFormattedOddometerTotals[machineName] }}
                                </span>
                            </div>

                            <div
                                v-if="pbrFormattedHsdTotals[machineName]"
                                class="flex justify-between items-center py-2"
                            >
                                <span class="flex items-center space-x-2 text-sm font-medium text-gray-700">
                                    <span class="text-orange-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    </span>
                                    <span>HSD (Liter)</span>
                                </span>
                                <span class="font-bold text-base text-orange-700">
                                    {{ pbrFormattedHsdTotals[machineName] }}
                                </span>
                            </div>

                            <hr v-if="Object.keys(pbrFormattedTotals).length > 1 && machineName !== Object.keys(pbrFormattedTotals)[Object.keys(pbrFormattedTotals).length - 1]" class="my-3 border-dashed border-gray-300" />

                        </div>

                        <div v-if="Object.keys(pbrFormattedTotals).length === 0" class="col-span-3 text-center text-sm text-gray-500 p-4">
                            Tidak ada data untuk Ballast Regulator Machine.
                        </div>
                    </div>
                </div>

                <div class="relative flex flex-col bg-white rounded-xl shadow-2xl overflow-hidden border border-gray-100 transition-shadow duration-300 hover:shadow-3xl">

                    <div class="p-4 flex justify-between items-center bg-blue-500">
                        <div class="flex items-center space-x-3">
                            <div class="bg-red-500 rounded-lg p-3 text-white shadow-md shadow-red-500/50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9h2M5 12h2M12 5v2M12 17v2M17 12h2M5 12h2" />
                                </svg>
                            </div>
                        </div>

                        <h3 class="text-xl font-extrabold text-white text-center flex-grow">
                            Material Logistic And Inspection Machine
                        </h3>

                        <button class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition duration-150">
                            <img src="../../../public/assets/receive-message.png" class="w-6" alt="Laporan">
                        </button>
                    </div>

                    <div class="p-4 divide-y divide-gray-200">

                        <!-- <div
                            v-for="(duration, machineName) in pbrFormattedTotals"
                            :key="machineName"
                        >
                            <div class="w-full border-b-2 border-gray-700 ">
                                <span class="text-base font-bold text-black text-xs">
                                    {{ machineName }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="flex items-center space-x-2 text-sm font-medium text-gray-700">
                                    <span class="text-blue-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    </span>
                                    <span>Engine Hours</span>
                                </span>
                                <span class="font-extrabold text-base text-blue-800">{{ duration }}</span>
                            </div>

                            <div
                                v-if="pbrFormattedGeneratorTotals[machineName]"
                                class="flex justify-between items-center py-2"
                            >
                                <span class="flex items-center space-x-2 text-sm font-medium text-gray-700">
                                    <span class="text-green-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                    </span>
                                    <span>Generator Hours (Meter)</span>
                                </span>
                                <span class="font-bold text-base text-green-700">
                                    {{ pbrFormattedGeneratorTotals[machineName] }}
                                </span>
                            </div>

                            <div
                                v-if="pbrFormattedCounterTotals[machineName]"
                                class="flex justify-between items-center py-2"
                            >
                                <span class="flex items-center space-x-2 text-sm font-medium text-gray-700">
                                    <span class="text-red-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                                    </span>
                                    <span>Tamping Counter</span>
                                </span>
                                <span class="font-bold text-base text-red-700">
                                    {{ pbrFormattedCounterTotals[machineName] }}
                                </span>
                            </div>

                            <div
                                v-if="pbrFormattedOddometerTotals[machineName]"
                                class="flex justify-between items-center py-2"
                            >
                                <span class="flex items-center space-x-2 text-sm font-medium text-gray-700">
                                    <span class="text-indigo-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    </span>
                                    <span>Odometer</span>
                                </span>
                                <span class="font-bold text-base text-indigo-700">
                                    {{ pbrFormattedOddometerTotals[machineName] }}
                                </span>
                            </div>

                            <div
                                v-if="pbrFormattedHsdTotals[machineName]"
                                class="flex justify-between items-center py-2"
                            >
                                <span class="flex items-center space-x-2 text-sm font-medium text-gray-700">
                                    <span class="text-orange-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    </span>
                                    <span>HSD (Liter)</span>
                                </span>
                                <span class="font-bold text-base text-orange-700">
                                    {{ pbrFormattedHsdTotals[machineName] }}
                                </span>
                            </div>

                            <hr v-if="Object.keys(pbrFormattedTotals).length > 1 && machineName !== Object.keys(pbrFormattedTotals)[Object.keys(pbrFormattedTotals).length - 1]" class="my-3 border-dashed border-gray-300" />

                        </div>

                        <div v-if="Object.keys(pbrFormattedTotals).length === 0" class="col-span-3 text-center text-sm text-gray-500 p-4">
                            Tidak ada data untuk Ballast Regulator Machine.
                        </div> -->
                        <p class="text-center">Belum ada</p>
                    </div>
                </div>

            </div>

            <!-- Dashboard Maintenance Order -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Maintenance Order Performance
                </h2>

                <!-- Statistics Cards -->
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-5 mb-6">
                    <!-- Total Kerusakan -->
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-100 text-sm font-medium">Total Kerusakan</p>
                                <p class="text-3xl font-bold mt-2">{{ maintenanceStats?.total_failures || 0 }}</p>
                            </div>
                            <div class="bg-blue-400/30 rounded-full p-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Menunggu Follow Up -->
                    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-yellow-100 text-sm font-medium">Menunggu Follow Up</p>
                                <p class="text-3xl font-bold mt-2">{{ maintenanceStats?.pending_followup || 0 }}</p>
                            </div>
                            <div class="bg-yellow-400/30 rounded-full p-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Sedang Diperbaiki -->
                    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-orange-100 text-sm font-medium">Sedang Diperbaiki</p>
                                <p class="text-3xl font-bold mt-2">{{ maintenanceStats?.in_progress || 0 }}</p>
                            </div>
                            <div class="bg-orange-400/30 rounded-full p-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Selesai Diperbaiki -->
                    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-green-100 text-sm font-medium">Selesai Diperbaiki</p>
                                <p class="text-3xl font-bold mt-2">{{ maintenanceStats?.completed || 0 }}</p>
                            </div>
                            <div class="bg-green-400/30 rounded-full p-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Kerusakan Kritis -->
                    <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-red-100 text-sm font-medium">Kerusakan Kritis</p>
                                <p class="text-3xl font-bold mt-2">{{ maintenanceStats?.critical_failures || 0 }}</p>
                            </div>
                            <div class="bg-red-400/30 rounded-full p-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MTTR & Response Time & Recent Orders -->
                <div class="grid gap-6 lg:grid-cols-3">
                    <!-- MTTR Card -->
                    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                            MTTR
                        </h3>
                        <div class="text-center">
                            <div class="text-4xl font-bold text-indigo-600">
                                {{ maintenanceStats?.avg_repair_hours || 0 }}
                                <span class="text-xl text-gray-500">j</span>
                                {{ maintenanceStats?.avg_repair_minutes || 0 }}
                                <span class="text-xl text-gray-500">m</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Waktu rata-rata perbaikan</p>
                            <p class="text-xs text-gray-400">(Mulai → Selesai)</p>
                        </div>
                    </div>

                    <!-- Response Time Card -->
                    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Response Time
                        </h3>
                        <div class="text-center">
                            <div class="text-4xl font-bold text-purple-600">
                                {{ maintenanceStats?.avg_response_hours || 0 }}
                                <span class="text-xl text-gray-500">j</span>
                                {{ maintenanceStats?.avg_response_minutes || 0 }}
                                <span class="text-xl text-gray-500">m</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Waktu rata-rata respon</p>
                            <p class="text-xs text-gray-400">(Kerusakan → Mulai)</p>
                        </div>
                    </div>

                    <!-- Recent Maintenance Orders -->
                    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Recent Maintenance Orders
                            </h3>
                            <a :href="route('maintenance-orders.index')" class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition">
                                Lihat Semua →
                            </a>
                        </div>

                        <div v-if="recentMaintenanceOrders && recentMaintenanceOrders.length > 0" class="space-y-3">
                            <div v-for="order in recentMaintenanceOrders" :key="order.id"
                                class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-800 text-sm">{{ order.machine_name }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ order.failure_description }}</p>
                                    <p class="text-xs text-gray-400 mt-1">{{ order.created_by }} • {{ order.created_at }}</p>
                                </div>
                                <div class="ml-4 flex flex-col items-end space-y-1">
                                    <span :class="{
                                        'bg-blue-100 text-blue-800': !order.status || order.status === 'OPEN',
                                        'bg-yellow-100 text-yellow-800': order.status === 'DIPROSES',
                                        'bg-orange-100 text-orange-800': order.status === 'DIKERJAKAN',
                                        'bg-green-100 text-green-800': order.status === 'SELESAI'
                                    }" class="px-2 py-1 rounded-full text-xs font-semibold">
                                        {{ !order.status || order.status === 'OPEN' ? 'Baru' : order.status === 'DIPROSES' ? 'Diproses' : order.status === 'DIKERJAKAN' ? 'Dikerjakan' : 'Selesai' }}
                                    </span>
                                    <span v-if="order.severity" :class="{
                                        'bg-red-100 text-red-800': order.severity === 'critical',
                                        'bg-orange-100 text-orange-800': order.severity === 'high',
                                        'bg-yellow-100 text-yellow-800': order.severity === 'medium',
                                        'bg-blue-100 text-blue-800': order.severity === 'low'
                                    }" class="px-2 py-1 rounded-full text-xs font-semibold">
                                        {{ order.severity }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div v-else class="text-center py-8 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-2 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <p class="text-sm">Belum ada maintenance order</p>
                        </div>
                    </div>
                </div>
            </div>

            </div>
        </div>
        </div>
        </div>
    </DashboardLayout>

    <Modal v-if="!hasRole(['admin', 'superuser', 'Kepala UPT Mekanik', 'Kepala Operator KPJR','user'])" :show="showAssessmentModal" max-width="full" closeable>
        <div class="p-6 bg-white rounded-lg">

            <div class="flex justify-between items-center border-b pb-3 mb-4">
                <h2 class="uppercase text-center font-bold text-emerald-700">
                    Daily Readiness Assessment ({{ assessmentData?.today }})
                </h2>
                <!-- Tombol Close, hanya muncul jika user boleh menutupnya (biasanya tidak untuk assessment wajib) -->
                <Close @click="showAssessmentModal = false" v-if="hasCompletedAssessment"/>
            </div>

            <form @submit.prevent="submitAssessment">
                <div class="overflow-x-auto shadow-md rounded-lg mb-6 max-h-[70vh] overflow-y-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-emerald-600 sticky top-0 z-10 text-white">
                            <tr>
                                <th class="w-[5%] px-4 py-3 text-center text-xs uppercase tracking-wider text-black font-bold"></th>
                                <th class="w-[20%] px-4 py-3 text-left text-xs uppercase tracking-wider text-black font-bold">Komponen</th>
                                <th class="w-[45%] px-4 py-3 text-center text-xs uppercase tracking-wider text-black font-bold">Pertanyaan</th>
                                <th class="w-[15%] px-4 py-3 text-center text-xs uppercase tracking-wider text-black font-bold">YA</th>
                                <th class="w-[15%] px-4 py-3 text-center text-xs uppercase tracking-wider text-black font-bold">TIDAK</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 bg-white">
                            <template v-for="(group, groupName) in assessmentData?.groupedQuestions" :key="groupName">
                                <tr class="group-header">
                                    <td :colspan="5" class="px-4 py-2 bg-gray-100 text-left  font-bold text-gray-800 border-t border-b border-gray-300 uppercase tracking-wider text-xs">
                                        {{ groupName }}
                                    </td>
                                </tr>

                                <tr v-for="(question, index) in group" :key="question.id" class="hover:bg-green-50/50 transition duration-150">

                                    <template v-if="index === 0 || question.urutan !== group[index - 1].urutan">
                                        <td class="px-4 py-3 text-sm text-gray-500 text-center align-top" :rowspan="group.filter(q => q.urutan === question.urutan).length">
                                            {{ question.urutan }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600 font-semibold text-left align-top" :rowspan="group.filter(q => q.komponen === question.komponen && q.urutan === question.urutan).length">
                                            {{ question.komponen }}
                                        </td>
                                    </template>

                                    <td class="px-4 py-3 text-sm text-gray-600 text-left">
                                        {{ question.pertanyaan }}
                                    </td>

                                    <td class="px-4 py-3 text-center">
                                        <input
                                            type="radio"
                                            :id="'q_' + question.id + '_ya'"
                                            :name="'q_' + question.id"
                                            value="ya"
                                            v-model="assessmentForm.answers[question.id]"
                                            class="form-radio h-5 w-5 text-green-600 border-gray-300 focus:ring-green-500 cursor-pointer"
                                        />
                                    </td>

                                    <td class="px-4 py-3 text-center">
                                        <input
                                            type="radio"
                                            :id="'q_' + question.id + '_tidak'"
                                            :name="'q_' + question.id"
                                            value="tidak"
                                            v-model="assessmentForm.answers[question.id]"
                                            class="form-radio h-5 w-5 text-red-600 border-gray-300 focus:ring-red-500 cursor-pointer"
                                        />
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-between items-center pt-4 border-t">
                    <div class="text-sm font-medium text-gray-600">
                        Progress: {{ answeredQuestions }} / {{ totalQuestions }} pertanyaan terjawab.
                    </div>

                    <button
                        type="submit"
                        :disabled="assessmentForm.processing || !isComplete"
                        class="px-6 py-2 bg-emerald-600 text-white font-semibold rounded-lg shadow-lg hover:bg-emerald-700 transition duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span v-if="assessmentForm.processing">Menyimpan...</span>
                        <span v-else>Simpan & Lanjutkan</span>
                    </button>
                </div>

                <div v-if="assessmentForm.errors.submission" class="mt-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg text-sm">
                    {{ assessmentForm.errors.submission }}
                </div>
            </form>
        </div>
    </Modal>

    <!-- MODAL UPLOAD SURAT KETERANGAN SEHAT -->
    <Modal v-if="!hasRole(['admin', 'superuser', 'Kepala UPT Mekanik', 'Kepala Operator KPJR', 'user'])" :show="showHealthCertModal" max-width="2xl" :closeable="false">
        <div class="p-6 bg-white rounded-lg max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center border-b pb-3 mb-4">
                <h2 class="text-xl font-bold text-blue-700">
                    Upload Surat Keterangan Sehat
                </h2>
            </div>

            <div class="mb-4 p-4 bg-blue-50 border-l-4 border-blue-500 rounded">
                <h3 class="font-semibold text-blue-800 mb-2">⚠️ Perhatian:</h3>
                <ul class="text-sm text-blue-700 space-y-1 list-disc list-inside">
                    <li>Wajib upload Surat Keterangan Sehat dari Unit Kesehatan sebelum mengisi Daily Readiness Assessment</li>
                    <li>Surat berlaku selama <strong>4 hari</strong> (hari ini + 3 hari berikutnya)</li>
                    <li>Setelah masa berlaku habis, wajib upload surat baru untuk melanjutkan pengisian assessment</li>
                    <li>Format file: PDF, JPG, JPEG, PNG (Max 5MB)</li>
                </ul>
            </div>

            <!-- Info Sertifikat Aktif (jika ada) -->
            <div v-if="healthCertStatus?.has_valid_certificate" class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 rounded">
                <h3 class="font-semibold text-green-800 mb-2">✓ Sertifikat Aktif</h3>
                <div class="text-sm text-green-700 space-y-1">
                    <p>Berlaku: {{ new Date(healthCertStatus.certificate.valid_from).toLocaleDateString('id-ID') }} - {{ new Date(healthCertStatus.certificate.valid_until).toLocaleDateString('id-ID') }}</p>
                    <p>Sisa waktu: <strong>{{ healthCertStatus.certificate.days_remaining }} hari</strong></p>
                    <a :href="healthCertStatus.certificate.file_url" target="_blank" class="text-blue-600 hover:underline">📎 Lihat Sertifikat</a>
                </div>
            </div>

            <form @submit.prevent="submitHealthCertificate" class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold mb-2">Tanggal Mulai Berlaku <span class="text-red-600">*</span></label>
                    <input
                        type="date"
                        v-model="healthCertForm.valid_from"
                        :min="new Date().toISOString().split('T')[0]"
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500"
                        required
                    />
                    <p class="text-xs text-gray-500 mt-1">
                        Sertifikat akan berlaku sampai: {{ healthCertForm.valid_from ? new Date(new Date(healthCertForm.valid_from).getTime() + 3 * 24 * 60 * 60 * 1000).toLocaleDateString('id-ID') : '-' }}
                    </p>
                    <div v-if="healthCertForm.errors.valid_from" class="text-red-600 text-sm mt-1">{{ healthCertForm.errors.valid_from }}</div>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2">Upload Surat Keterangan Sehat <span class="text-red-600">*</span></label>
                    <input
                        type="file"
                        @input="healthCertForm.health_certificate = $event.target.files[0]"
                        accept="image/*,.pdf"
                        capture="environment"
                        class="w-full border rounded-lg p-2"
                        required
                    />
                    <p class="text-xs text-gray-500 mt-1">📷 Foto langsung atau pilih file: PDF, JPG, JPEG, PNG (Maksimal 5MB)</p>
                    <div v-if="healthCertForm.errors.health_certificate" class="text-red-600 text-sm mt-1">{{ healthCertForm.errors.health_certificate }}</div>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2">Catatan (Opsional)</label>
                    <textarea
                        v-model="healthCertForm.notes"
                        rows="2"
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500"
                        placeholder="Catatan tambahan..."
                    ></textarea>
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t">
                    <button
                        v-if="healthCertStatus?.has_valid_certificate"
                        type="button"
                        @click="showHealthCertModal = false; showAssessmentModal = true"
                        class="px-6 py-2 bg-gray-500 text-white font-semibold rounded-lg hover:bg-gray-600 transition"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        :disabled="healthCertForm.processing"
                        class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow-lg hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span v-if="healthCertForm.processing">Mengupload...</span>
                        <span v-else>Upload & Lanjutkan</span>
                    </button>
                </div>

                <div v-if="healthCertForm.errors.upload" class="mt-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg text-sm">
                    {{ healthCertForm.errors.upload }}
                </div>
            </form>
        </div>
    </Modal>

</template>



