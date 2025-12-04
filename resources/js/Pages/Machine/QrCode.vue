<script setup>
import { Link } from '@inertiajs/inertia-vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Card from '@/Components/Card.vue'
import Button from '@/Components/Button.vue'
import ButtonBlue from '@/Components/Button/Blue.vue'
import Icon from '@/Components/Icon.vue'

const { machine, qrCodeUrl } = defineProps({
    machine: Object,
    qrCodeUrl: String,
})

const downloadQr = () => {
    window.location.href = route('master-machines.download-qr', machine.id)
}

const printQr = () => {
    window.print()
}
</script>

<style>
@media print {
    .no-print {
        display: none !important;
    }
    .print-only {
        display: block !important;
    }
}

.print-only {
    display: none;
}
</style>

<template>
    <DashboardLayout :title="__('QR Code Mesin')">
        <Card class="bg-white shadow-lg border border-solid border-slate-200" style="border-radius: 0.625rem;">
            <template #header>
                <div class="flex items-center justify-between px-4 py-3 no-print">
                    <h2 class="text-xl font-bold">QR Code - {{ machine.name }}</h2>
                    <div class="flex gap-2">
                        <ButtonBlue @click.prevent="printQr">
                            <Icon name="printer" />
                            <p class="font-bold text-xs">{{ __('Print') }}</p>
                        </ButtonBlue>

                        <Button
                            @click.prevent="downloadQr"
                            class="bg-green-600 hover:bg-green-800"
                        >
                            <Icon name="download" />
                            <p class="font-bold text-xs">{{ __('Download') }}</p>
                        </Button>

                        <Link :href="route('master-machines.index')">
                            <Button class="bg-gray-600 hover:bg-gray-800">
                                <Icon name="arrow-left" />
                                <p class="font-bold text-xs">{{ __('Kembali') }}</p>
                            </Button>
                        </Link>
                    </div>
                </div>
            </template>

            <template #body>
                <div class="flex flex-col items-center justify-center p-8 space-y-6">
                    <!-- Info Mesin -->
                    <div class="w-full max-w-2xl bg-gray-50 p-6 rounded-lg border border-gray-200">
                        <h3 class="text-lg font-bold mb-4 text-center">Informasi Mesin</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-600">Daop:</p>
                                <p class="font-semibold">{{ machine.region?.name }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Klasifikasi:</p>
                                <p class="font-semibold">{{ machine.classification?.name || '-' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Jenis:</p>
                                <p class="font-semibold">{{ machine.name }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Type:</p>
                                <p class="font-semibold">{{ machine.type || '-' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Nomor:</p>
                                <p class="font-semibold">{{ machine.nomor || '-' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">No. Sarana:</p>
                                <p class="font-semibold">{{ machine.no_sarana || '-' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Tahun Mulai Dinas:</p>
                                <p class="font-semibold">{{ machine.tahun_md || '-' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Umur:</p>
                                <p class="font-semibold">{{ machine.umur || '-' }} tahun</p>
                            </div>
                        </div>
                    </div>

                    <!-- QR Code -->
                    <div class="flex flex-col items-center space-y-4">
                        <h3 class="text-lg font-bold">QR Code</h3>
                        <div class="bg-white p-8 rounded-lg border-4 border-gray-800 shadow-lg">
                            <img
                                :src="qrCodeUrl"
                                :alt="'QR Code ' + machine.no_sarana"
                                class="w-64 h-64"
                            />
                        </div>
                        <p class="text-sm text-gray-600 text-center max-w-md">
                            Scan QR Code ini untuk mengisi data mesin otomatis di Working Report
                        </p>
                        <p class="text-xs font-mono bg-gray-100 px-4 py-2 rounded">
                            {{ machine.no_sarana }}
                        </p>
                    </div>

                    <!-- Print Version -->
                    <div class="print-only w-full max-w-4xl mx-auto text-center">
                        <h1 class="text-2xl font-bold mb-4">QR Code Mesin</h1>
                        <div class="border-4 border-black p-8 inline-block">
                            <img
                                :src="qrCodeUrl"
                                :alt="'QR Code ' + machine.no_sarana"
                                class="w-96 h-96 mx-auto"
                            />
                        </div>
                        <div class="mt-6 space-y-2">
                            <p class="text-xl font-bold">{{ machine.name }}</p>
                            <p class="text-lg">{{ machine.no_sarana }}</p>
                            <p>{{ machine.region?.name }}</p>
                            <p class="text-sm text-gray-600 mt-4">
                                Scan untuk input otomatis di Working Report
                            </p>
                        </div>
                    </div>
                </div>
            </template>
        </Card>
    </DashboardLayout>
</template>
