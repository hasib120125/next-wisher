<template>
    <Head title="FAQ" />
    <Master>
        <div class="px-4">
            <div class="text-center mb-16 mt-6">
                <h3 class="lg:text-4xl md:text-2xl text-xl leading-normal font-black text-gray-900">
                    {{ Helper.translate('Frequently Asked Questions') }}
                </h3>
            </div>
        </div>
        <div class="mx-auto max-w-[600px] px-6">
            <div class="lg:text-center font-bold md:text-2xl text-lg text-black border-b border-slate-300 mb-2 pb-2">
                {{ Helper.translate('Talents') }}
            </div>
            <div v-for="(item, index) in talentFaq" :key="index" class="flex items-center my-8">
                <div class="flex items-center justify-center p-3 mr-3 rounded-full bg-sky-500 text-white border-4 border-white text-xl font-semibold">
                    <svg class="lg:w-6 lg:h-6 w-4 h-4" width="24" height="24" fill="#fff" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g data-name="Layer 2"><g data-name="menu-arrow"><path d="M17 9A5 5 0 0 0 7 9a1 1 0 0 0 2 0 3 3 0 1 1 3 3 1 1 0 0 0-1 1v2a1 1 0 0 0 2 0v-1.1A5 5 0 0 0 17 9z"/><circle cx="12" cy="19" r="1"/></g></g></svg>
                </div>
                <div class="text-md">
                    <h1 class="text-gray-900 font-semibold mb-1">
                        {{ Helper.translate(item.question, true) }}
                    </h1>
                    <p class="text-gray-500 text-sm">
                        {{ Helper.translate(item.answer, true) }}
                    </p>
                </div>
            </div>
        </div>
        <div class="mx-auto max-w-[600px] px-6">
            <div class="lg:text-center font-bold md:text-2xl text-lg text-black border-b border-slate-300 mb-2 pb-2">
                {{ Helper.translate('Users') }}
            </div>
            <div v-for="(item, index) in userFaq" :key="index" class="flex items-center my-8">
                <div class="flex items-center justify-center p-3 mr-3 rounded-full bg-sky-500 text-white border-4 border-white text-xl font-semibold">
                    <svg class="lg:w-6 lg:h-6 w-4 h-4" width="24" height="24" fill="#fff" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g data-name="Layer 2"><g data-name="menu-arrow"><path d="M17 9A5 5 0 0 0 7 9a1 1 0 0 0 2 0 3 3 0 1 1 3 3 1 1 0 0 0-1 1v2a1 1 0 0 0 2 0v-1.1A5 5 0 0 0 17 9z"/><circle cx="12" cy="19" r="1"/></g></g></svg>
                </div>
                <div class="text-md">
                    <h1 class="text-gray-900 font-semibold mb-1">
                        {{ Helper.translate(item.question, true) }}
                    </h1>
                    <p class="text-gray-500 text-sm">
                        {{ Helper.translate(item.answer, true) }}
                    </p>
                </div>
            </div>
        </div>
    </Master>
</template>

<script setup>
import Master from "../Backend/Master.vue";
import { Head } from "@inertiajs/inertia-vue3";
import Helper from "@/Helper";

defineProps({
    talentFaq: {
        type: Array,
        default: []
    },
    userFaq: {
        type: Array,
        default: []
    }
})
</script>

<style scoped></style>
