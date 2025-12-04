<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { Html5Qrcode } from 'html5-qrcode'
import Button from '@/Components/Button.vue'
import Icon from '@/Components/Icon.vue'

const emit = defineEmits(['scanned', 'error'])

const isScanning = ref(false)
const scannerRef = ref(null)
let html5QrCode = null

const startScanner = async () => {
  try {
    isScanning.value = true

    html5QrCode = new Html5Qrcode("qr-reader")

    const config = {
      fps: 10,
      qrbox: { width: 250, height: 250 },
      aspectRatio: 1.0
    }

    await html5QrCode.start(
      { facingMode: "environment" },
      config,
      (decodedText, decodedResult) => {
        try {
          const data = JSON.parse(decodedText)
          emit('scanned', data)
          stopScanner()
        } catch (e) {
          emit('error', 'Format QR Code tidak valid')
        }
      },
      (errorMessage) => {
        // Ignore scanning errors
      }
    )
  } catch (err) {
    console.error('Error starting scanner:', err)
    emit('error', 'Gagal mengakses kamera. Pastikan izin kamera sudah diaktifkan.')
    isScanning.value = false
  }
}

const stopScanner = async () => {
  if (html5QrCode && html5QrCode.isScanning) {
    try {
      await html5QrCode.stop()
      html5QrCode.clear()
    } catch (err) {
      console.error('Error stopping scanner:', err)
    }
  }
  isScanning.value = false
}

onUnmounted(() => {
  stopScanner()
})

defineExpose({
  startScanner,
  stopScanner
})
</script>

<template>
  <div class="flex flex-col items-center space-y-4">
    <div v-show="isScanning" id="qr-reader" class="w-full max-w-md"></div>

    <div class="flex gap-2">
      <Button
        v-if="!isScanning"
        @click.prevent="startScanner"
        class="bg-purple-600 hover:bg-purple-800"
      >
        <Icon name="qrcode" />
        <p class="font-bold text-xs">Scan QR Code</p>
      </Button>

      <Button
        v-if="isScanning"
        @click.prevent="stopScanner"
        class="bg-red-600 hover:bg-red-800"
      >
        <Icon name="close" />
        <p class="font-bold text-xs">Tutup Scanner</p>
      </Button>
    </div>
  </div>
</template>

<style scoped>
#qr-reader {
  border: 2px solid #7c3aed;
  border-radius: 0.5rem;
  overflow: hidden;
}
</style>
