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

const { user } = usePage().props.value

const { users, hasCompletedAssessment, assessmentData, data_laporin_full, mesin_totals, formatted_mesin_total, formatted_generator_total, formatted_counter_total, formatted_oddometer_total, formatted_hsd_total } = defineProps({
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
  count : Number
}) 

const showAssessmentModal = ref(false);

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
        }
    });
};

let currentCount = 0;
const getQuestionNumber = (reset = false) => {
    if (reset) currentCount = 0;
    return ++currentCount;
};

onMounted(() => {
    if (!hasCompletedAssessment && assessmentData) {
        showAssessmentModal.value = true;
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
            </div> 
        </div>
        </div>
        </div>
    </DashboardLayout>

    <Modal v-if="!hasRole(['admin', 'superuser', 'Kepala UPT Mekanik'])" :show="showAssessmentModal" max-width="full" closeable>
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
    
</template>

 

