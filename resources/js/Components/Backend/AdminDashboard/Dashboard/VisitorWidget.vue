<template>
    <div class="mt-5">
        <h2 class="text-center font-bold lg:text-2xl md:text-lg text-sm mb-5">{{ Helper.translate('ANALYTICS (NUMBER)') }}</h2>
        <div class="flex flex-col gap-5 max-w-[600px] mx-auto">
            <div class="flex justify-between font-bold lg:text-2xl md:text-lg text-sm relative pb-4">
                <div class="text-red-500">{{ Helper.translate('VISITORS') }}</div>
                <div>{{ Helper.translate(get(visitors, 'visitors'), true) }}</div>
                <div class="w-full h-4 bg-red-500 rounded-full absolute bottom-0"></div>
            </div>
            <div class="flex justify-between font-bold lg:text-2xl md:text-lg text-sm relative pb-4">
                <div class="text-red-500">{{ Helper.translate('PREVIEWS') }}</div>
                <div>{{ Helper.translate(get(visitors, 'total_hits'), true) }}</div>
                <div class="w-full h-4 bg-red-500 rounded-full absolute bottom-0"></div>
            </div>
            <div class="flex justify-between font-bold lg:text-2xl md:text-lg text-sm relative pb-4">
                <div class="text-[#004AAD]">{{ Helper.translate('NEW USER') }}</div>
                <div>{{ Helper.translate(get(userSummery, 'new_users'), true) }}</div>
                <div class="w-full h-4 bg-[#004AAD] rounded-full absolute bottom-0"></div>
            </div>
            <div class="flex justify-between font-bold lg:text-2xl md:text-lg text-sm relative pb-4">
                <div class="text-[#FD7900]">{{ Helper.translate('TALENTS APPLICATIONS') }}</div>
                <div>{{ Helper.translate(get(userSummery, 'talent_applications'), true) }}</div>
                <div class="w-full h-4 bg-[#FD7900] rounded-full absolute bottom-0"></div>
            </div>
            <div class="flex justify-between font-bold lg:text-2xl md:text-lg text-sm relative pb-4">
                <div class="text-[#299740]">{{ Helper.translate('TALENTS APPLICATIONS APPROVED') }}</div>
                <div>{{ Helper.translate(userSummery.talent_applications_approved, true) }}</div>
                <div class="w-full h-4 bg-[#299740] rounded-full absolute bottom-0"></div>
            </div>
        </div>
    </div>
</template>

<script setup>
import Helper from '@/Helper';
import useAdminAnalytics from '@/Pages/Backend/AdminDashboard/useAdminAnalytics';
import { get } from 'lodash';

const props = defineProps({
    visitors: Object,
    userSummery: Object
})

console.log(props.userSummery);

const { earnings } = useAdminAnalytics()

</script>