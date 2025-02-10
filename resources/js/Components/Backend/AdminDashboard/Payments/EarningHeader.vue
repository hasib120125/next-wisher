<template>
    <div class="grid sm:grid-cols-2 uppercase gap-6 font-semibold lg:text-lg text-sm">
        <div 
            @click="activeComponent = 'Earnings'" 
            class="relative p-4 rounded-lg border text-center cursor-pointer hover:bg-sky-500 hover:text-white hover:shadow-lg transition-all"
            :class="activeComponent == 'Earnings' && 'bg-sky-500 text-white'"
        >
            <!-- <span 
                v-if="size(get($page.props, 'payout_requests'))>0" 
                class="absolute right-0 top-0 transform translate-x-2 -translate-y-1/2 min-w-6 h-6 px-3 rounded-full bg-red-400 text-white text-[10px] font-semibold flex items-center justify-center"
            >
                {{ size(get($page.props, 'payout_requests')) }}
            </span> -->
            {{ Helper.translate('Earnings') }}
        </div>
        <div
            @click="activeComponent = 'Details'" 
            class="relative p-4 rounded-lg border text-center cursor-pointer hover:bg-sky-500 hover:text-white hover:shadow-lg transition-all"
            :class="activeComponent == 'Details' && 'bg-sky-500 text-white'"
        >
            {{ Helper.translate('Details') }}
        </div>
    </div>
</template>

<script setup>
import Helper from '@/Helper';
import usePayments from '@/Pages/Backend/AdminDashboard/usePayments.js'
import { get, size } from 'lodash';

const { activeComponent } = usePayments()
</script>

<style lang="scss" scoped>

</style>