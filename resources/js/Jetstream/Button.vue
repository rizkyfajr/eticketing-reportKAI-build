<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});
</script>

<template>
    <button :type="type" class="w-full mt-6 text-indigo-50 font-bold bg-black py-3 rounded-lg hover:bg-slate-800 transition duration-300">
        <slot />
    </button>
</template>
