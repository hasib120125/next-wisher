<template>
    
    <div class="flex md:flex-nowrap flex-wrap gap-6 justify-center mt-8">
        <div class="border shadow-lg rounded overflow-hidden md:w-[300px] w-full">
            <div class="p-4 py-2 bg-red-400 font-bold text-white text-center">
                {{ Helper.translate('Wishes') }} 
                <!-- ({{ Helper.translate(get(earnings, 'wish.count'), true) }}) -->
            </div>
            <div class="p-4 items-center justify-center font-bold flex gap-1">
                {{ Helper.priceFormate(get(earnings, 'wish.amount')) }} 
                <!-- <span class="text-[15px] text-sky-500">({{ Helper.translate(get(earnings, 'wish.count'), true) }})</span> -->
            </div>
        </div>
        <div class="border shadow-lg rounded overflow-hidden md:w-[300px] w-full">
            <div class="p-4 py-2 bg-blue-400 font-bold text-white text-center">
                {{ Helper.translate('Tips') }} 
                <!-- ({{ Helper.translate(get(earnings, 'tips.count'), true) }}) -->
            </div>
            <div class="p-4 items-center justify-center font-bold flex gap-1">
                {{ Helper.priceFormate(get(earnings, 'tips.amount')) }} 
                <!-- <span class="text-[15px] text-orange-400">({{ Helper.translate(get(earnings, 'tips.count'), true) }})</span> -->
            </div>
        </div>
        <!-- <div class="border shadow-lg rounded overflow-hidden">
            <div class="p-4 py-2 bg-gray-700 font-bold text-white text-center">
                {{ Helper.translate('My Life') }} 
            </div>
            <div class="p-4 items-center justify-center font-bold flex gap-1">
                {{ Helper.priceFormate(get(earnings, 'mylife.amount'), true) }} 
            </div>
        </div> -->
        <!-- <div class="border shadow-lg rounded overflow-hidden">
            <div class="p-4 py-2 bg-orange-400 font-bold text-white text-center">
                {{ Helper.translate('Calendar') }}
            </div>
            <div class="p-4 items-center justify-center font-bold flex gap-1">
                {{ Helper.priceFormate(get(earnings, 'calender.count')) }} 
            </div>
        </div> -->
    </div>

    <div class="mt-5">
        <h2 class="text-center font-bold text-xl mb-5"># {{ Helper.translate('Received') }}</h2>
        <div class="flex flex-col gap-4 max-w-[600px] mx-auto">
            <div class="flex justify-between font-bold text-xl rounded-xl relative py-1 px-3 text-white bg-red-500">
                <div class="capitalize">{{ Helper.translate('Wishes') }}</div>
                <div>{{ Helper.translate(get(earnings, 'wish.count'), true) }}</div>
            </div>
            <div class="flex justify-between font-bold text-xl relative py-1 px-3 rounded-xl text-white bg-sky-500">
                <div class="capitalize">{{ Helper.translate('Tips') }}</div>
                <div>{{ Helper.translate(get(earnings, 'tips.count'), true) }}</div>
            </div>
            <!-- <div class="flex justify-between font-bold text-xl relative py-1 px-3 rounded-xl bg-black text-white">
                <div class="capitalize">{{ Helper.translate('My Life') }}</div>
                <div>{{ Helper.translate(get(earnings, 'mylife.count'), true) }}</div>
            </div> -->
            <!-- <div class="flex justify-between font-bold text-xl relative py-1 px-3 rounded-xl bg-orange-500 text-white">
                <div class="capitalize">{{ Helper.translate('Calendar') }}</div>
                <div>{{ Helper.translate(get(earnings, 'calender.count'), true) }}</div>
            </div> -->
        </div>
    </div>
</template>

<script setup>
import useAnalytics from '@/Pages/Backend/TalentDashboard/useAnalytics';
import Helper from '@/Helper';
import { get } from 'lodash'

const { earnings } = useAnalytics();
</script>