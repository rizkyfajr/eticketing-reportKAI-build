<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import JetAuthenticationCard from '@/Jetstream/AuthenticationCard.vue';
import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.vue';
import JetButton from '@/Jetstream/Button.vue';
import JetInput from '@/Jetstream/Input.vue';
import JetCheckbox from '@/Jetstream/Checkbox.vue';
import JetLabel from '@/Jetstream/Label.vue';
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    username: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Log in" />
            <!-- <img src="/assets/img/bg.jpeg" class="w-full h-full" /> -->

    <JetAuthenticationCard>
        <!-- Logo -->
        <template #logo>
            <!-- <JetAuthenticationCardLogo /> -->
        </template>
            <!-- <img src="/assets/img/55.png" class="w-full h-full" /> -->

        <JetValidationErrors class="mb-4" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div class="flex flex-col items-center justify-center rounded-lg">
                <!-- Npp -->
                <!-- <JetLabel for="username" value="NPP" class="text-center" /> -->
                <JetInput
                    id="username"
                    v-model="form.username"
                    placeholder="masukan nip"
                    type="text"
                    class="mt-2 block w-full text-center rounded-lg"
                    required
                    autofocus
                />
            </div>

            <div class="mt-4">
                <JetInput
                    id="password"
                    v-model="form.password"
                    placeholder="masukan password"
                    type="password"
                    class="mt-2 block w-full text-center rounded-lg"
                    required
                    autocomplete="current-password"
                />
            </div>


            <div class="flex items-center justify-center mt-4 ">
                <Link v-if="canResetPassword" :href="route('password.request')" class="underline text-sm text-gray-600 hover:text-gray-900">
                    Forgot your password?
                </Link>

                <JetButton class="" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Masuk
                </JetButton>
            </div>
        </form>
    </JetAuthenticationCard>
</template>
