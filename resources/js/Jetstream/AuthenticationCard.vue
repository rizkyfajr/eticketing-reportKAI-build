<template>
  <div class="min-h-screen flex flex-col md:flex-row bg-gradient-to-br from-blue-50 to-orange-50">
    <!-- Left: Login -->
    <div class="w-full md:w-1/2 flex flex-col justify-center items-center p-8 bg-white shadow-lg md:rounded-r-3xl">
      <div class="flex flex-col items-center mb-8">
        <img src="/assets/TJFE (1).png" alt="Logo KAI" class="w-20 mb-2" />
        <h1 class="text-3xl font-bold text-gray-800">
          <span class="text-orange-500 font-serif">E-</span>Ticketing Report KAI
        </h1>
        <p class="text-gray-500 mt-3 text-center">
          Sistem Pelaporan Aplikasi KAI
        </p>
      </div>

      <div class="w-full max-w-sm">
        <h2 class="text-center text-2xl font-semibold text-blue-700 mb-6">Sign In</h2>

        <!-- Slot form/auth dari luar -->
        <slot name="logo" />
        <slot />
<!-- <button
          class="mt-6 w-full bg-gradient-to-r from-orange-500 to-blue-600 text-white py-2 rounded-xl hover:opacity-90 transition"
        >
          Masuk
        </button> -->
      </div>

      <p class="mt-10 text-gray-400 text-sm">© TIDeveloper 2025</p>
    </div>

    <!-- Right: Side info -->
    <div
      class="hidden md:flex w-1/2 bg-gradient-to-tr from-blue-600 to-orange-500 justify-center items-center text-white text-center p-10"
    >
      <div class="max-w-md">
        <img src="/assets/TJFE (2).png" alt="Ilustrasi" class="w-32 mx-auto mb-6 drop-shadow-lg" />
        <h2 class="text-3xl font-semibold mb-4">
          Laporkan Kendala Operasional & Aplikasi
        </h2>
        <p class="opacity-90">
          Percepat penanganan tiket—terstruktur, terukur, dan terintegrasi dengan alur kerja KAI.
        </p>
      </div>
    </div>
  </div>
</template>
