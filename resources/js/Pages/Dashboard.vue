<script setup>
import { ref,nextTick, computed, onMounted, watch  } from 'vue'
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import Welcome from '@/Jetstream/Welcome.vue';
import { usePage, useForm, Link } from '@inertiajs/inertia-vue3';
import Modal from '@/Components/Modal.vue'
import Close from '@/Components/Button/Close.vue'
import Card from '@/Components/Card.vue'
import Builder from '@/Components/DataTable/Builder.vue'
import Th from '@/Components/DataTable/Th.vue'
import Button from '@/Components/Button.vue'
import BtnAttachment from '@/Components/Button/Attachment.vue'
import axios from 'axios'
import { Chart } from "chart.js/auto";

const { user } = usePage().props.value

const { users, hasCompletedAssessment, assessmentData, data_laporin_full, mesin_totals, formatted_mesin_total, formatted_generator_total, formatted_counter_total, formatted_oddometer_total, formatted_hsd_total, maintenanceStats, recentMaintenanceOrders, isAdminOrSupervisor, report } = defineProps({
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
  report: Array,
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

const openMachines = ref({});

const pbrFormattedTotals = computed(() => {
    if (!formatted_mesin_total) return {};

    const filtered = {};

    for (const machineName in formatted_mesin_total) {
        if (formatted_mesin_total.hasOwnProperty(machineName)) {

            if (machineName.includes('Profiling') || machineName.includes('Distributing and Profiling') || machineName.includes('Distributing and Cleaning')) {
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
            if (machineName.includes('Plain Line Tamping') || machineName.includes('Rail Switch Tamping')) {
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

            if (machineName.includes('Profiling') || machineName.includes('Distributing and Profiling') || machineName.includes('Distributing and Cleaning')) {
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
            if (machineName.includes('Plain Line Tamping') || machineName.includes('Rail Switch Tamping')) {
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

            if (machineName.includes('Profiling') || machineName.includes('Distributing and Profiling') || machineName.includes('Distributing and Cleaning')) {
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
            if (machineName.includes('Plain Line Tamping') || machineName.includes('Rail Switch Tamping')) {
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

            if (machineName.includes('Profiling') || machineName.includes('Distributing and Profiling') || machineName.includes('Distributing and Cleaning')) {
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
            if (machineName.includes('Plain Line Tamping') || machineName.includes('Rail Switch Tamping')) {
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

            if (machineName.includes('Profiling') || machineName.includes('Distributing and Profiling') || machineName.includes('Distributing and Cleaning')) {
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
            if (machineName.includes('Plain Line Tamping') || machineName.includes('Rail Switch Tamping')) {
                filtered[machineName] = formatted_hsd_total[machineName];
            }
        }
    }
    return filtered;
});

const mlimFormattedTotals = computed(() => {
    if (!formatted_mesin_total) return {};
    const filtered = {};
    for (const machineName in formatted_mesin_total) {
        if (formatted_mesin_total.hasOwnProperty(machineName)) {
            if (machineName.includes('Material Transport Wagon') || machineName.includes('Material Handling Crane') || machineName.includes('Material Handling Carrier') || machineName.includes('Inspection Bridge')) {
                filtered[machineName] = formatted_mesin_total[machineName];
            }
        }
    }
    return filtered;
});

const mlimFormattedGeneratorTotals = computed(() => {
    if (!formatted_generator_total) return {};
    const filtered = {};
    for (const machineName in formatted_generator_total) {
        if (formatted_generator_total.hasOwnProperty(machineName)) {
            if (machineName.includes('Material Transport Wagon') || machineName.includes('Material Handling Crane') || machineName.includes('Material Handling Carrier') || machineName.includes('Inspection Bridge')) {
                filtered[machineName] = formatted_generator_total[machineName];
            }
        }
    }
    return filtered;
});

const mlimFormattedCounterTotals = computed(() => {
    if (!formatted_counter_total) return {};
    const filtered = {};
    for (const machineName in formatted_counter_total) {
        if (formatted_counter_total.hasOwnProperty(machineName)) {
            if (machineName.includes('Material Transport Wagon') || machineName.includes('Material Handling Crane') || machineName.includes('Material Handling Carrier') || machineName.includes('Inspection Bridge')) {
                filtered[machineName] = formatted_counter_total[machineName];
            }
        }
    }
    return filtered;
});

const mlimFormattedOddometerTotals = computed(() => {
    if (!formatted_oddometer_total) return {};
    const filtered = {};
    for (const machineName in formatted_oddometer_total) {
        if (formatted_oddometer_total.hasOwnProperty(machineName)) {
            if (machineName.includes('Material Transport Wagon') || machineName.includes('Material Handling Crane') || machineName.includes('Material Handling Carrier') || machineName.includes('Inspection Bridge')) {
                filtered[machineName] = formatted_oddometer_total[machineName];
            }
        }
    }
    return filtered;
});

const mlimFormattedHsdTotals = computed(() => {
    if (!formatted_hsd_total) return {};
    const filtered = {};
    for (const machineName in formatted_hsd_total) {
        if (formatted_hsd_total.hasOwnProperty(machineName)) {
            if (machineName.includes('Material Transport Wagon') || machineName.includes('Material Handling Crane') || machineName.includes('Material Handling Carrier') || machineName.includes('Inspection Bridge')) {
                filtered[machineName] = formatted_hsd_total[machineName];
            }
        }
    }
    return filtered;
});

const rmeFormattedTotals = computed(() => {
    if (!formatted_mesin_total) return {};
    const filtered = {};
    for (const machineName in formatted_mesin_total) {
        if (formatted_mesin_total.hasOwnProperty(machineName)) {
            if (machineName.includes('Dynamic Track Stabilization') || machineName.includes('Lateral Dynamic Impact')) {
                filtered[machineName] = formatted_mesin_total[machineName];
            }
        }
    }
    return filtered;
});

const rmeFormattedGeneratorTotals = computed(() => {
    if (!formatted_generator_total) return {};
    const filtered = {};
    for (const machineName in formatted_generator_total) {
        if (formatted_generator_total.hasOwnProperty(machineName)) {
            if (machineName.includes('Dynamic Track Stabilization') || machineName.includes('Lateral Dynamic Impact')) {
                filtered[machineName] = formatted_generator_total[machineName];
            }
        }
    }
    return filtered;
});

const rmeFormattedCounterTotals = computed(() => {
    if (!formatted_counter_total) return {};
    const filtered = {};
    for (const machineName in formatted_counter_total) {
        if (formatted_counter_total.hasOwnProperty(machineName)) {
            if (machineName.includes('Dynamic Track Stabilization') || machineName.includes('Lateral Dynamic Impact')) {
                filtered[machineName] = formatted_counter_total[machineName];
            }
        }
    }
    return filtered;
});

const rmeFormattedOddometerTotals = computed(() => {
    if (!formatted_oddometer_total) return {};
    const filtered = {};
    for (const machineName in formatted_oddometer_total) {
        if (formatted_oddometer_total.hasOwnProperty(machineName)) {
            if (machineName.includes('Dynamic Track Stabilization') || machineName.includes('Lateral Dynamic Impact')) {
                filtered[machineName] = formatted_oddometer_total[machineName];
            }
        }
    }
    return filtered;
});

const rmeFormattedHsdTotals = computed(() => {
    if (!formatted_hsd_total) return {};
    const filtered = {};
    for (const machineName in formatted_hsd_total) {
        if (formatted_hsd_total.hasOwnProperty(machineName)) {
            if (machineName.includes('Dynamic Track Stabilization') || machineName.includes('Lateral Dynamic Impact')) {
                filtered[machineName] = formatted_hsd_total[machineName];
            }
        }
    }
    return filtered;
});

const hasValidData = (machineName, type) => {
    let totals;
    if (type === 'PBR') {
        totals = pbrFormattedTotals.value;
    } else if (type === 'MTT') {
        totals = mttFormattedTotals.value;
    } else if (type === 'MLIM') {
        totals = mlimFormattedTotals.value;
    } else {
        return false;
    }
    
    return totals[machineName] !== null && totals[machineName] !== undefined;
};

const hasRole = (roles) => {
    if (!user.roles) return false;
    return user.roles.some(role => roles.includes(role.name));
};

const chartColors = {
    engine: 'rgba(59, 130, 246, 0.7)', 
    generator: 'rgba(16, 185, 129, 0.7)', 
    counter: 'rgba(239, 68, 68, 0.7)', 
    odometer: 'rgba(124, 58, 237, 0.7)', 
    hsd: 'rgba(249, 115, 22, 0.7)', 
};

const charts = {}; // Menyimpan instance Chart

const renderMachineCharts = async (machineName) => {
    // 1. PENTING: Tunggu DOM siap setelah v-show diubah
    await nextTick();

    // 2. AMBIL DATA DENGAN MENGGUNAKAN ALIAS COMPONENT/PROPS YANG BENAR
    const machineData = {
        engine: parseFloat(formatted_mesin_total[machineName]) || 0,
        generator: parseFloat(formatted_generator_total[machineName]) || 0,
        counter: parseFloat(formatted_counter_total[machineName]) || 0,
        odometer: parseFloat(formatted_oddometer_total[machineName]) || 0,
        hsd: parseFloat(formatted_hsd_total[machineName]) || 0,
    };

    const chartConfigs = [
        { id: `engineChart-${machineName}`, label: "Engine Hours", value: machineData.engine, unit: 'Jam', color: chartColors.engine },
        { id: `generatorChart-${machineName}`, label: "Generator Hours", value: machineData.generator, unit: 'Jam', color: chartColors.generator },
        { id: `counterChart-${machineName}`, label: "Tamping Counter", value: machineData.counter, unit: 'Kali', color: chartColors.counter },
        { id: `odometerChart-${machineName}`, label: "Odometer", value: machineData.odometer, unit: 'KM', color: chartColors.odometer },
        { id: `hsdChart-${machineName}`, label: "HSD (Liter)", value: machineData.hsd, unit: 'Liter', color: chartColors.hsd },
    ];

    chartConfigs.forEach(cfg => {
        const canvas = document.getElementById(cfg.id);
        
        if (!canvas) {
            console.warn(`Canvas element with ID ${cfg.id} not found.`);
            return; 
        }

        // Hancurkan instance Chart lama
        if (charts[cfg.id]) {
            charts[cfg.id].destroy();
        }
        
        // Cek jika nilainya 0 atau positif (biarkan 0 dirender)
        if (cfg.value >= 0) {
             charts[cfg.id] = new Chart(canvas, {
                type: "bar",
                data: {
                    labels: [cfg.label],
                    datasets: [{
                        label: cfg.label,
                        data: [cfg.value],
                        backgroundColor: cfg.color,
                        borderColor: cfg.color.replace('0.7', '1'),
                        borderWidth: 1,
                        barPercentage: 0.8,
                        categoryPercentage: 0.9 
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false, 
                    indexAxis: 'y',
                    scales: {
                        x: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: `${cfg.label} (${cfg.unit})`
                            }
                        },
                        y: {
                            display: false 
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.x !== null) {
                                        // Gunakan toLocaleString atau logic pemformatan sesuai kebutuhan Anda
                                        label += context.parsed.x + ' ' + cfg.unit; 
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        }
    });
};


const toggleMachine = (machineName) => {
    openMachines.value[machineName] = !openMachines.value[machineName];

    if (openMachines.value[machineName]) {
        nextTick(() => {
            renderMachineCharts(machineName);
        });
    }
};

</script>

<template>
    <DashboardLayout title="Dashboard">
        <!-- <main class="p-0 py-0 mb-[1.25rem] ml-[1.25rem] mt-[1.25rem]"> -->
        <header class="mb-6 p-4 md:p-6 bg-white shadow-md rounded-xl border-l-4 border-blue-500">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                
                <div class="mb-3 sm:mb-0">
                    <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">
                        Dashboard
                    </h2>
                    
                    <p class="text-lg text-gray-600 mt-1">
                        Selamat Datang, <span class="font-semibold">{{ user.name }}</span>!
                    </p>
                </div>
                
                <div class="
                    text-right 
                    bg-blue-50 
                    p-3 rounded-lg 
                    border border-blue-200 
                    text-blue-700 
                    font-medium 
                    text-sm 
                    sm:text-base
                ">
                    <p class="font-bold">
                        {{ user.positions?.position }}
                    </p>
                    <p class="text-xs text-blue-600 mt-0.5">
                        ({{ user.divisions?.division_name }})
                    </p>
                </div>
            </div>
        </header>
        <div>

        <div class="min-h-screen bg-gray-50/50">
        <div class="p-1 ">
            <div class="">
            <div class="mb-4">
                <div class="grid grid-cols-1">
                    <Link 
                        :href="route('working-reports.create')"
                        class="block w-full"
                    >
                        <Button
                            v-if="can('create working report')"
                            class="
                                w-full                h-12 
                                text-white 
                                bg-green-600 
                                hover:bg-green-700 
                                focus:ring-4 focus:ring-green-300 
                                font-extrabold 
                                text-sm md:text-base 
                                rounded-lg 
                                shadow-lg shadow-green-500/50 
                                transition-all duration-200 
                                flex items-center justify-center space-x-2 
                            "
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 md:h-6 md:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-3-3v6m-4 2H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2h-4" />
                            </svg>
                            
                            <p class="uppercase tracking-wider">
                                {{ __('Report Working Order') }}
                            </p>
                        </Button>
                    </Link>
                </div>   
            </div>
                
            <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-5 lg:grid-cols-5 mb-6">
    
                <div class="
                    bg-gray-700 hover:bg-gray-800 rounded-xl shadow-xl p-5 text-white 
                    transition duration-300 ease-in-out transform hover:scale-105 hover:shadow-2xl 
                    cursor-pointer
                ">
                    <div class="flex items-start justify-between">
                        <div class="w-2/3">
                            <p class="text-gray-300 text-sm font-medium uppercase tracking-wider">
                                Process Draft
                            </p>
                            <p class="text-4xl font-extrabold mt-1">
                                {{ report.draft || 0}}
                            </p>
                        </div>
                        <div class="bg-gray-600/50 rounded-full p-3 flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="
                    bg-yellow-600 hover:bg-yellow-700 rounded-xl shadow-xl p-5 text-white 
                    transition duration-300 ease-in-out transform hover:scale-105 hover:shadow-2xl 
                    cursor-pointer
                ">
                    <div class="flex items-start justify-between">
                        <div class="w-2/3">
                            <p class="text-yellow-100 text-sm font-medium uppercase tracking-wider">
                                Process Checksheet
                            </p>
                            <p class="text-4xl font-extrabold mt-1">
                                {{ report.checksheet_done || 0 }}
                            </p>
                        </div>
                        <div class="bg-yellow-500/50 rounded-full p-3 flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="
                    bg-blue-600 hover:bg-blue-700 rounded-xl shadow-xl p-5 text-white 
                    transition duration-300 ease-in-out transform hover:scale-105 hover:shadow-2xl 
                    cursor-pointer
                ">
                    <div class="flex items-start justify-between">
                        <div class="w-2/3">
                            <p class="text-blue-100 text-sm font-medium uppercase tracking-wider">
                                Process Working
                            </p>
                            <p class="text-4xl font-extrabold mt-1">
                                {{ report.work_done || 0 }}
                            </p>
                        </div>
                        <div class="bg-blue-500/50 rounded-full p-3 flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="
                    bg-orange-600 hover:bg-orange-700 rounded-xl shadow-xl p-5 text-white 
                    transition duration-300 ease-in-out transform hover:scale-105 hover:shadow-2xl 
                    cursor-pointer
                ">
                    <div class="flex items-start justify-between">
                        <div class="w-2/3">
                            <p class="text-orange-100 text-sm font-medium uppercase tracking-wider">
                                Process Warming Up
                            </p>
                            <p class="text-4xl font-extrabold mt-1">
                                {{ report.warming_up_done || 0 }}
                            </p>
                        </div>
                        <div class="bg-orange-500/50 rounded-full p-3 flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 18.657A8 8 0 0118 10a8 8 0 10-15.657 8.657M18 12h2a2 2 0 012 2v2a2 2 0 01-2 2h-2m-4-2h-4m0 0v-4m0 4h-4m-4-2H2a2 2 0 01-2-2v-2a2 2 0 012-2h2" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="
                    bg-green-600 hover:bg-green-700 rounded-xl shadow-xl p-5 text-white 
                    transition duration-300 ease-in-out transform hover:scale-105 hover:shadow-2xl 
                    cursor-pointer
                ">
                    <div class="flex items-start justify-between">
                        <div class="w-2/3">
                            <p class="text-green-100 text-sm font-medium uppercase tracking-wider">
                                Approve KUPT
                            </p>
                            <p class="text-4xl font-extrabold mt-1">
                                {{ report.finished || 0 }}
                            </p>
                        </div>
                        <div class="bg-green-500/50 rounded-full p-3 flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Card Components -->
            <!-- <div class="mb-12 grid gap-y-10 gap-x-6 md:grid-cols-2 xl:grid-cols-3"> -->
                <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-4 xl:grid-cols-4">
                    
                <div class="relative flex flex-col bg-white rounded-xl shadow-2xl overflow-hidden border border-gray-100 transition-shadow duration-300 hover:shadow-2xl">

                    <div class="p-3 flex justify-center items-center bg-blue-700">
                        <h3 class="text-xs font-medium uppercase tracking-wider text-white text-center">
                            Tamping Machine
                        </h3>
                    </div>

                    <div class="p-0 divide-y divide-gray-100">
                        
                        <div
                            v-for="(duration, machineName) in mttFormattedTotals"
                            :key="machineName"
                            class="transition duration-150 ease-in-out"
                        >
                            <div 
                                @click="toggleMachine(machineName)" 
                                class="w-full py-3 px-3 flex justify-between items-center cursor-pointer 
                                        hover:bg-blue-50 border-b border-gray-100"
                            >
                                <span class="font-semibold text-black text-xs">
                                    {{ machineName }}
                                </span>
                                
                                <span class="flex items-center space-x-2">
                                    <svg v-if="hasValidData(machineName, 'MTT')" class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <svg v-else class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    
                                    <svg :class="{'rotate-180 text-blue-600': openMachines[machineName], 'text-gray-400': !openMachines[machineName]}" 
                                        class="w-4 h-4 transform transition-transform duration-200" 
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </span>
                            </div>

                            <div v-show="openMachines[machineName]" class="p-4 bg-gray-50/50">
                                
                                <div v-if="hasValidData(machineName, 'MTT')" class="grid grid-cols-1 gap-4">
                                    
                                    <div class="bg-white p-3 rounded-lg shadow-sm border border-blue-100">
                                        <div class="flex justify-between items-center pb-2 mb-2 border-b border-gray-200">
                                            <h5 class="text-sm font-semibold text-blue-700 flex items-center space-x-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                <span class="text-xs">Engine Hours</span>
                                            </h5>
                                            <span class="font-extrabold text-base text-blue-700 text-xs">{{ duration }}</span> 
                                        </div>
                                        <div class="w-full h-[120px] pt-2"> 
                                            <canvas :id="`engineChart-${machineName}`"></canvas>
                                        </div>
                                    </div>

                                    <div v-if="mttFormattedGeneratorTotals[machineName] !== null" class="bg-white p-3 rounded-lg shadow-sm border border-green-100">
                                        <div class="flex justify-between items-center pb-2 mb-2 border-b border-gray-200">
                                            <h5 class="text-sm font-semibold text-green-700 flex items-center space-x-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                                <span class="text-xs">Generator Hours</span>
                                            </h5>
                                            <span class="font-extrabold text-base text-green-700 text-xs">{{ mttFormattedGeneratorTotals[machineName] }}</span>
                                        </div>
                                        <div class="w-full h-[120px] pt-2">
                                            <canvas :id="`generatorChart-${machineName}`"></canvas>
                                        </div>
                                    </div>
                                    
                                    <div v-if="mttFormattedCounterTotals[machineName] !== null" class="bg-white p-3 rounded-lg shadow-sm border border-red-100">
                                        <div class="flex justify-between items-center pb-2 mb-2 border-b border-gray-200">
                                            <h5 class="text-sm font-semibold text-red-700 flex items-center space-x-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                                                <span class="text-xs">Tamping Counter</span>
                                            </h5>
                                            <span class="font-extrabold text-base text-red-700 text-xs">{{ mttFormattedCounterTotals[machineName] }}</span>
                                        </div>
                                        <div class="w-full h-[120px] pt-2"> 
                                            <canvas :id="`counterChart-${machineName}`"></canvas>
                                        </div>
                                    </div>

                                    <div v-if="mttFormattedOddometerTotals[machineName] !== null" class="bg-white p-3 rounded-lg shadow-sm border border-indigo-100">
                                        <div class="flex justify-between items-center pb-2 mb-2 border-b border-gray-200">
                                            <h5 class="text-sm font-semibold text-indigo-700 flex items-center space-x-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                <span class="text-xs">Odometer</span>
                                            </h5>
                                            <span class="font-extrabold text-base text-indigo-700 text-xs">{{ mttFormattedOddometerTotals[machineName] }}</span>
                                        </div>
                                        <div class="w-full h-[120px] pt-2"> 
                                            <canvas :id="`odometerChart-${machineName}`"></canvas>
                                        </div>
                                    </div>

                                    <div v-if="mttFormattedHsdTotals[machineName] !== null" class="bg-white p-3 rounded-lg shadow-sm border border-orange-100">
                                        <div class="flex justify-between items-center pb-2 mb-2 border-b border-gray-200">
                                            <h5 class="text-sm font-semibold text-orange-700 flex items-center space-x-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                                <span class="text-xs">HSD (Liter)</span>
                                            </h5>
                                            <span class="font-extrabold text-base text-orange-700 text-xs">{{ mttFormattedHsdTotals[machineName] }}</span>
                                        </div>
                                        <div class="w-full h-[120px] pt-2"> 
                                            <canvas :id="`hsdChart-${machineName}`"></canvas>
                                        </div>
                                    </div>

                                </div>
                                
                                <div v-else class="text-center text-xs text-red-600 font-semibold p-2 bg-red-100 rounded-lg">
                                    ⚠️ Mesin ini belum memiliki data perhitungan.
                                </div>
                            </div>
                            
                        </div>

                        <div v-if="Object.keys(mttFormattedTotals).length === 0" class="text-center text-xs text-gray-500 p-4">
                            Tidak ada Tamping Machine yang terdaftar.
                        </div>
                    </div>
                </div>

                <div class="relative flex flex-col bg-white rounded-xl shadow-2xl overflow-hidden border border-gray-100 transition-shadow duration-300 hover:shadow-2xl">

                    <div class="p-3 flex justify-center items-center bg-blue-700">
                        <h3 class="text-xs font-medium uppercase tracking-wider text-white text-center">
                            Ballast Regulator Machine
                        </h3>
                    </div>

                    <div class="p-0 divide-y divide-gray-100">
                        
                        <div
                            v-for="(duration, machineName) in pbrFormattedTotals"
                            :key="machineName"
                            class="transition duration-150 ease-in-out"
                        >
                            <div 
                                @click="toggleMachine(machineName)" 
                                class="w-full py-3 px-3 flex justify-between items-center cursor-pointer 
                                        hover:bg-blue-50 border-b border-gray-100"
                            >
                                <span class="font-semibold text-black text-xs">
                                    {{ machineName }}
                                </span>
                                
                                <span class="flex items-center space-x-2">
                                    <svg v-if="hasValidData(machineName, 'PBR')" class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <svg v-else class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    
                                    <svg :class="{'rotate-180 text-blue-600': openMachines[machineName], 'text-gray-400': !openMachines[machineName]}" 
                                        class="w-4 h-4 transform transition-transform duration-200" 
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </span>
                            </div>

                            <div v-show="openMachines[machineName]" class="p-4 bg-gray-50/50">
                                
                                <div v-if="hasValidData(machineName, 'PBR')" class="grid grid-cols-1 gap-4">
                                    
                                    <!-- ENGINE HOURS -->
                                    <div class="bg-white p-3 rounded-lg shadow-sm border border-blue-100">
                                        <div class="flex justify-between items-center pb-2 mb-2 border-b border-gray-200">
                                            <h5 class="text-sm font-semibold text-blue-700 flex items-center space-x-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                <span class="text-xs">Engine Hours</span>
                                            </h5>
                                            <span class="font-extrabold text-base text-blue-700 text-xs">{{ duration }}</span>
                                        </div>
                                        <div class="w-full h-[120px] pt-2"> 
                                            <canvas :id="`engineChart-${machineName}`"></canvas>
                                        </div>
                                    </div>

                                    <!-- GENERATOR HOURS -->
                                    <div v-if="pbrFormattedGeneratorTotals[machineName] !== null" class="bg-white p-3 rounded-lg shadow-sm border border-green-100">
                                        <div class="flex justify-between items-center pb-2 mb-2 border-b border-gray-200">
                                            <h5 class="text-sm font-semibold text-green-700 flex items-center space-x-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                                <span class="text-xs">Generator Hours</span>
                                            </h5>
                                            <span class="font-extrabold text-base text-green-700 text-xs">{{ pbrFormattedGeneratorTotals[machineName] }}</span>
                                        </div>
                                        <div class="w-full h-[120px] pt-2">
                                            <canvas :id="`generatorChart-${machineName}`"></canvas>
                                        </div>
                                    </div>
                                    
                                    <!-- TAMPING COUNTER -->
                                    <div v-if="pbrFormattedCounterTotals[machineName] !== null" class="bg-white p-3 rounded-lg shadow-sm border border-red-100">
                                        <div class="flex justify-between items-center pb-2 mb-2 border-b border-gray-200">
                                            <h5 class="text-sm font-semibold text-red-700 flex items-center space-x-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                                                <span class="text-xs">Tamping Counter</span>
                                            </h5>
                                            <span class="font-extrabold text-base text-red-700 text-xs">{{ pbrFormattedCounterTotals[machineName] }}</span>
                                        </div>
                                        <div class="w-full h-[120px] pt-2"> 
                                            <canvas :id="`counterChart-${machineName}`"></canvas>
                                        </div>
                                    </div>

                                    <!-- ODOMETER -->
                                    <div v-if="pbrFormattedOddometerTotals[machineName] !== null" class="bg-white p-3 rounded-lg shadow-sm border border-indigo-100">
                                        <div class="flex justify-between items-center pb-2 mb-2 border-b border-gray-200">
                                            <h5 class="text-sm font-semibold text-indigo-700 flex items-center space-x-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                <span class="text-xs">Odometer</span>
                                            </h5>
                                            <span class="font-extrabold text-base text-indigo-700 text-xs">{{ pbrFormattedOddometerTotals[machineName] }}</span>
                                        </div>
                                        <div class="w-full h-[120px] pt-2"> 
                                            <canvas :id="`odometerChart-${machineName}`"></canvas>
                                        </div>
                                    </div>

                                    <!-- HSD (LITER) -->
                                    <div v-if="pbrFormattedHsdTotals[machineName] !== null" class="bg-white p-3 rounded-lg shadow-sm border border-orange-100">
                                        <div class="flex justify-between items-center pb-2 mb-2 border-b border-gray-200">
                                            <h5 class="text-sm font-semibold text-orange-700 flex items-center space-x-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                                <span class="text-xs">HSD (Liter)</span>
                                            </h5>
                                            <span class="font-extrabold text-base text-orange-700 text-xs">{{ pbrFormattedHsdTotals[machineName] }}</span>
                                        </div>
                                        <div class="w-full h-[120px] pt-2"> 
                                            <canvas :id="`hsdChart-${machineName}`"></canvas>
                                        </div>
                                    </div>

                                </div>
                                
                                <div v-else class="text-center text-xs text-red-600 font-semibold p-2 bg-red-100 rounded-lg">
                                    ⚠️ Mesin ini belum memiliki data perhitungan.
                                </div>
                            </div>
                            
                        </div>

                        <div v-if="Object.keys(pbrFormattedTotals).length === 0" class="text-center text-xs text-gray-500 p-4">
                            Tidak ada Ballast Regulator Machine yang terdaftar.
                        </div>
                    </div>
                </div>

                <div class="relative flex flex-col bg-white rounded-xl shadow-2xl overflow-hidden border border-gray-100 transition-shadow duration-300 hover:shadow-2xl">

                    <div class="p-3 flex justify-center items-center bg-blue-700">
                        <h3 class="text-xs font-medium uppercase tracking-wider text-white text-center">
                            Stabilization & Consolidation 
                        </h3>
                    </div>

                    <div class="p-0 divide-y divide-gray-100">
                        
                        <div
                            v-for="(duration, machineName) in rmeFormattedTotals"
                            :key="machineName"
                            class="transition duration-150 ease-in-out"
                        >
                            <div 
                                @click="toggleMachine(machineName)" 
                                class="w-full py-3 px-3 flex justify-between items-center cursor-pointer 
                                        hover:bg-blue-50 border-b border-gray-100"
                            >
                                <span class="font-semibold text-black text-xs">
                                    {{ machineName }}
                                </span>
                                
                                <span class="flex items-center space-x-2">
                                    <svg v-if="hasValidData(machineName, 'RME')" class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <svg v-else class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    
                                    <svg :class="{'rotate-180 text-blue-600': openMachines[machineName], 'text-gray-400': !openMachines[machineName]}" 
                                        class="w-4 h-4 transform transition-transform duration-200" 
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </span>
                            </div>

                            <div v-show="openMachines[machineName]" class="p-4 bg-gray-50/50">
                                
                                <div v-if="hasValidData(machineName, 'RME')" class="grid grid-cols-1 gap-4">
                                    
                                    <div class="bg-white p-3 rounded-lg shadow-sm border border-blue-100">
                                        <div class="flex justify-between items-center pb-2 mb-2 border-b border-gray-200">
                                            <h5 class="text-sm font-semibold text-blue-700 flex items-center space-x-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                <span class="text-xs">Engine Hours</span>
                                            </h5>
                                            <span class="font-extrabold text-base text-blue-700 text-xs">{{ duration }}</span> 
                                        </div>
                                        <div class="w-full h-[120px] pt-2"> 
                                            <canvas :id="`engineChart-${machineName}`"></canvas>
                                        </div>
                                    </div>

                                    <div v-if="rmeFormattedGeneratorTotals[machineName] !== null" class="bg-white p-3 rounded-lg shadow-sm border border-green-100">
                                        <div class="flex justify-between items-center pb-2 mb-2 border-b border-gray-200">
                                            <h5 class="text-sm font-semibold text-green-700 flex items-center space-x-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                                <span class="text-xs">Generator Hours</span>
                                            </h5>
                                            <span class="font-extrabold text-base text-green-700 text-xs">{{ rmeFormattedGeneratorTotals[machineName] }}</span>
                                        </div>
                                        <div class="w-full h-[120px] pt-2">
                                            <canvas :id="`generatorChart-${machineName}`"></canvas>
                                        </div>
                                    </div>
                                    
                                    <div v-if="rmeFormattedCounterTotals[machineName] !== null" class="bg-white p-3 rounded-lg shadow-sm border border-red-100">
                                        <div class="flex justify-between items-center pb-2 mb-2 border-b border-gray-200">
                                            <h5 class="text-sm font-semibold text-red-700 flex items-center space-x-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                                                <span class="text-xs">Tamping Counter</span>
                                            </h5>
                                            <span class="font-extrabold text-base text-red-700 text-xs">{{ rmeFormattedCounterTotals[machineName] }}</span>
                                        </div>
                                        <div class="w-full h-[120px] pt-2"> 
                                            <canvas :id="`counterChart-${machineName}`"></canvas>
                                        </div>
                                    </div>

                                    <div v-if="rmeFormattedOddometerTotals[machineName] !== null" class="bg-white p-3 rounded-lg shadow-sm border border-indigo-100">
                                        <div class="flex justify-between items-center pb-2 mb-2 border-b border-gray-200">
                                            <h5 class="text-sm font-semibold text-indigo-700 flex items-center space-x-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                <span class="text-xs">Odometer</span>
                                            </h5>
                                            <span class="font-extrabold text-base text-indigo-700 text-xs">{{ rmeFormattedOddometerTotals[machineName] }}</span>
                                        </div>
                                        <div class="w-full h-[120px] pt-2"> 
                                            <canvas :id="`odometerChart-${machineName}`"></canvas>
                                        </div>
                                    </div>

                                    <div v-if="rmeFormattedHsdTotals[machineName] !== null" class="bg-white p-3 rounded-lg shadow-sm border border-orange-100">
                                        <div class="flex justify-between items-center pb-2 mb-2 border-b border-gray-200">
                                            <h5 class="text-sm font-semibold text-orange-700 flex items-center space-x-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                                <span class="text-xs">HSD (Liter)</span>
                                            </h5>
                                            <span class="font-extrabold text-base text-orange-700 text-xs">{{ rmeFormattedHsdTotals[machineName] }}</span>
                                        </div>
                                        <div class="w-full h-[120px] pt-2"> 
                                            <canvas :id="`hsdChart-${machineName}`"></canvas>
                                        </div>
                                    </div>

                                </div>
                                
                                <div v-else class="text-center text-xs text-red-600 font-semibold p-2 bg-red-100 rounded-lg">
                                    ⚠️ Mesin ini belum memiliki data perhitungan.
                                </div>
                            </div>
                            
                        </div>

                        <div v-if="Object.keys(rmeFormattedTotals).length === 0" class="text-center text-xs text-gray-500 p-4">
                            Tidak ada Stabilization & Consolidation yang terdaftar.
                        </div>
                    </div>
                </div>

                <div class="relative flex flex-col bg-white rounded-xl shadow-2xl overflow-hidden border border-gray-100 transition-shadow duration-300 hover:shadow-2xl">

                    <div class="p-3 flex justify-center items-center bg-blue-700">
                        <h3 class="text-xs font-medium uppercase tracking-wider text-white text-center">
                            Material & Logistic Machine
                        </h3>
                    </div>

                    <div class="p-0 divide-y divide-gray-100">
                        
                        <div
                            v-for="(duration, machineName) in mlimFormattedTotals"
                            :key="machineName"
                            class="transition duration-150 ease-in-out"
                        >
                            <div 
                                @click="toggleMachine(machineName)" 
                                class="w-full py-3 px-3 flex justify-between items-center cursor-pointer 
                                        hover:bg-blue-50 border-b border-gray-100"
                            >
                                <span class="font-semibold text-black text-xs">
                                    {{ machineName }}
                                </span>
                                
                                <span class="flex items-center space-x-2">
                                    <svg v-if="hasValidData(machineName, 'MLIM')" class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <svg v-else class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    
                                    <svg :class="{'rotate-180 text-blue-600': openMachines[machineName], 'text-gray-400': !openMachines[machineName]}" 
                                        class="w-4 h-4 transform transition-transform duration-200" 
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </span>
                            </div>

                            <div v-show="openMachines[machineName]" class="p-4 bg-gray-50/50">
                                
                                <div v-if="hasValidData(machineName, 'MLIM')" class="grid grid-cols-1 gap-4">
                                    
                                    <div class="bg-white p-3 rounded-lg shadow-sm border border-blue-100">
                                        <div class="flex justify-between items-center pb-2 mb-2 border-b border-gray-200">
                                            <h5 class="text-sm font-semibold text-blue-700 flex items-center space-x-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                <span class="text-xs">Engine Hours</span>
                                            </h5>
                                            <span class="font-extrabold text-base text-blue-700 text-xs">{{ duration }}</span> 
                                        </div>
                                        <div class="w-full h-[120px] pt-2"> 
                                            <canvas :id="`engineChart-${machineName}`"></canvas>
                                        </div>
                                    </div>

                                    <div v-if="mlimFormattedGeneratorTotals[machineName] !== null" class="bg-white p-3 rounded-lg shadow-sm border border-green-100">
                                        <div class="flex justify-between items-center pb-2 mb-2 border-b border-gray-200">
                                            <h5 class="text-sm font-semibold text-green-700 flex items-center space-x-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                                <span class="text-xs">Generator Hours</span>
                                            </h5>
                                            <span class="font-extrabold text-base text-green-700 text-xs">{{ mlimFormattedGeneratorTotals[machineName] }}</span>
                                        </div>
                                        <div class="w-full h-[120px] pt-2">
                                            <canvas :id="`generatorChart-${machineName}`"></canvas>
                                        </div>
                                    </div>
                                    
                                    <div v-if="mlimFormattedCounterTotals[machineName] !== null" class="bg-white p-3 rounded-lg shadow-sm border border-red-100">
                                        <div class="flex justify-between items-center pb-2 mb-2 border-b border-gray-200">
                                            <h5 class="text-sm font-semibold text-red-700 flex items-center space-x-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                                                <span class="text-xs">Tamping Counter</span>
                                            </h5>
                                            <span class="font-extrabold text-base text-red-700">{{ mlimFormattedCounterTotals[machineName] }}</span>
                                        </div>
                                        <div class="w-full h-[120px] pt-2"> 
                                            <canvas :id="`counterChart-${machineName}`"></canvas>
                                        </div>
                                    </div>

                                    <div v-if="mlimFormattedOddometerTotals[machineName] !== null" class="bg-white p-3 rounded-lg shadow-sm border border-indigo-100">
                                        <div class="flex justify-between items-center pb-2 mb-2 border-b border-gray-200">
                                            <h5 class="text-sm font-semibold text-indigo-700 flex items-center space-x-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                <span class="text-xs">Odometer</span>
                                            </h5>
                                            <span class="font-extrabold text-base text-indigo-700 text-xs">{{ mlimFormattedOddometerTotals[machineName] }}</span>
                                        </div>
                                        <div class="w-full h-[120px] pt-2"> 
                                            <canvas :id="`odometerChart-${machineName}`"></canvas>
                                        </div>
                                    </div>

                                    <div v-if="mlimFormattedHsdTotals[machineName] !== null" class="bg-white p-3 rounded-lg shadow-sm border border-orange-100">
                                        <div class="flex justify-between items-center pb-2 mb-2 border-b border-gray-200">
                                            <h5 class="text-sm font-semibold text-orange-700 flex items-center space-x-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                                <span class="text-xs">HSD (Liter)</span>
                                            </h5>
                                            <span class="font-extrabold text-base text-orange-700 text-xs">{{ mlimFormattedHsdTotals[machineName] }}</span>
                                        </div>
                                        <div class="w-full h-[120px] pt-2"> 
                                            <canvas :id="`hsdChart-${machineName}`"></canvas>
                                        </div>
                                    </div>

                                </div>
                                
                                <div v-else class="text-center text-xs text-red-600 font-semibold p-2 bg-red-100 rounded-lg">
                                    ⚠️ Mesin ini belum memiliki data perhitungan.
                                </div>
                            </div>
                            
                        </div>

                        <div v-if="Object.keys(mlimFormattedTotals).length === 0" class="text-center text-xs text-gray-500 p-4">
                            Tidak ada Material & Logistic Machine yang terdaftar.
                        </div>
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

    <Modal v-if="!hasRole(['admin', 'superuser', 'Kepala UPT Mekanik', 'Kepala Operator KPJR'])" :show="showAssessmentModal" max-width="full" closeable>
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
    <Modal v-if="!hasRole(['admin', 'superuser', 'Kepala UPT Mekanik', 'Kepala Operator KPJR'])" :show="showHealthCertModal" max-width="2xl" :closeable="false">
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



