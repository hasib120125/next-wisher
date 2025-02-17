<template>
    <div class="grid lg:grid-cols-4 md:grid-cols-2 gap-6">
        <div class="border-2 border-[#5C2586] shadow-lg rounded overflow-hidden">
            <div class="p-4 py-2 bg-[#5C2586] font-bold text-white text-center">
                {{ Helper.translate('REVENUE') }} 
            </div>
            <div class="p-4 items-center justify-center font-bold flex gap-1">
                {{ Helper.priceFormate(price_floor(get(earnings, 'revenue'))) }}  
            </div>
        </div>

        <div class="border-2 border-[#E8E600] shadow-lg rounded overflow-hidden">
            <div class="p-4 py-2 bg-[#E8E600] font-bold text-white text-center">
                {{ Helper.translate('PENDING') }}  
            </div>
            <div class="p-4 items-center justify-center font-bold flex gap-1">
                {{ Helper.priceFormate(price_floor(get(earnings, 'pending'))) }} 
            </div>
        </div>
        <div class="border-2 border-[#545454] shadow-lg rounded overflow-hidden">
            <div class="p-4 py-2 bg-[#545454] font-bold text-white text-center">
                {{ Helper.translate('SERVICE FEE') }} 
            </div>
            <div class="p-4 items-center justify-center font-bold flex gap-1">
                {{ Helper.priceFormate(price_floor(get(earnings, 'service_fee')), true) }} 
            </div>
        </div>
        <div class="border-2 border-[#299740] shadow-lg rounded overflow-hidden">
            <div class="p-4 py-2 bg-[#299740] font-bold text-white text-center">
                {{ Helper.translate('PROFITS') }} 
            </div>
            <div class="p-4 items-center justify-center font-bold flex gap-1">
                {{ Helper.priceFormate(price_floor(get(earnings, 'net_revenue'))) }} 
            </div>
        </div>
    </div>


    <div class="mt-5">
        <h2 class="text-center font-bold md:text-2xl text-base mb-5">
            {{ Helper.translate('ANALYTICS (NUMBERS)') }}
        </h2>
        <table class="max-w-[600px] w-full mx-auto border-spacing-y-2 border-separate border-collapse">
            <tr class="font-bold md:text-lg text-base rounded-xl relative py-1 px-3">
                <th colspan="2" class="sm:table-cell hidden text-right pb-4 pr-4">{{ Helper.translate('Number (Received)') }}</th>
                <th colspan="2" class="sm:hidden table-cell text-right pb-4 pr-4">{{ Helper.translate('Number') }}</th>
                <th class="text-right pb-4 w-[70px]">{{ Helper.translate('Revenue') }}</th>
            </tr>
            <tr class="rounded-xl relative text-white font-bold">
                <td class="w-[120px] pl-8 py-3 border-0  bg-red-500 rounded-l-xl">{{ Helper.translate('WISH') }}</td>
                <td class="text-center  bg-red-500 border-0">{{ Helper.translate(get(earnings, 'wish.count'), true) }}</td>
                <td class="text-right pr-8  bg-red-500 border-0 rounded-r-xl">{{ Helper.priceFormate(price_floor(get(earnings, 'wish.amount')), true) }}</td>
            </tr>
            <tr class="relative py-1 text-white font-bold">
                <td class="w-[120px] pl-8 py-3 bg-sky-500 rounded-l-xl">{{ Helper.translate('TIPS') }}</td>
                <td class="text-center bg-sky-500">{{ Helper.translate(get(earnings, 'tips.count'), true) }}</td>
                <td class="text-right pr-8 bg-sky-500 rounded-r-xl">{{ Helper.priceFormate(price_floor(get(earnings, 'tips.amount')), true) }}</td>
            </tr>
            <!-- <div class="flex justify-between  relative py-1 px-3 rounded-xl bg-black text-white">
                <div class="md:w-[120px] flex-1 flex-shrink-0">{{ Helper.translate('MY LIFE') }}</div>
                <div class="md:w-[120px] flex-1 flex-shrink-0 text-right">{{ Helper.translate(get(earnings, 'mylife.count'), true) }}</div>
                <div class="w-[150px] flex-shrink-0 text-right">{{ Helper.priceFormate(get(earnings, 'mylife.amount'), true) }}</div>
            </div> -->
            <!-- <div class="flex justify-between  relative py-1 px-3 rounded-xl bg-orange-500 text-white">
                <div class="md:w-[120px] flex-1 flex-shrink-0">{{ Helper.translate('CALENDAR') }}</div>
                <div class="md:w-[120px] flex-1 flex-shrink-0 text-right">{{ Helper.translate(get(earnings, 'calender.count'), true) }}</div>
                <div class="w-[150px] flex-shrink-0 text-right">{{ Helper.priceFormate(get(earnings, 'calender.amount'), true) }}</div>
            </div> -->
        </table>
    </div>
</template>

<script setup>
import AngleUpIcon from '@/Icons/AngleUpIcon.vue'
import Helper from '@/Helper';
import { get } from 'lodash';
import useAdminAnalytics from '@/Pages/Backend/AdminDashboard/useAdminAnalytics';

const { earnings } = useAdminAnalytics();

const price_floor = (amount) => {
    let floored = 0;
    try {
        floored = parseFloat(Number(amount).toFixed(2))
    } catch (error) {}
    return floored;
}

</script>