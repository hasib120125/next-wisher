<script setup>
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import Helper from '@/Helper';

const props = defineProps({
    status: String,
});

const form = useForm();

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>

<template>
    <GuestLayout class="bg-[#2b2c3b] text-white">
        <Head title="Email Verification" />

        <div class="mb-4 text-xl font-bold text-black text-center">
            {{ Helper.translate("Please verify your email") }}
        </div>
        <div class="mb-4 text-sm text-gray-600">
            {{ Helper.translate("Just click on the verification button in that email to complete your signup. If you do not see it, you should check your spam folder.") }}
        </div>

        <div class="mb-4 font-medium text-sm text-green-600" v-if="verificationLinkSent">
            {{ Helper.translate(`Email resent`) }}
        </div>

        <form @submit.prevent="submit">
            <div class="mt-10 flex flex-wrap flex-col md:flex-row gap-5 items-center justify-center relative">
                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    {{ Helper.translate('Resend Email') }}
                </PrimaryButton>

                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="font-bold right-1 -bottom-5 text-red-600 hover:text-red-900 bg-white rounded-md sm:absolute"
                >
                    {{ Helper.translate('Logout') }}
                </Link>
            </div>
        </form>
    </GuestLayout>
</template>
