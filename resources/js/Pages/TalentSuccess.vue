<template>
    <Head title="Registered successfully" />
    <Master>
        <div class="flex flex-col gap-8 items-center justify-center h-[60vh]">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-20 h-20 text-[#299740]" width="28" height="28" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path d="M9 11l3 3l8 -8"></path>
                <path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9"></path>
            </svg>
            <div class="text-xl">
                {{ Helper.translate('Your request to become a talent has been submitted. We will be in touch within 72 hours. Thank you!') }}
                <div class="text-center pt-3">
                    {{ Helper.translate('Do not forget to verify your spam folder.') }}
                </div>
            </div>
        </div>
    </Master>
</template>

<script setup>
import { Head } from "@inertiajs/inertia-vue3";
import Master from "./Backend/Master.vue";
import Helper from "@/Helper";

</script>

<style scoped></style>
