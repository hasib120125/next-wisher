<template>
    <div class="grid xl:grid-cols-4 lg:grid-cols-3 md:grid-cols-2 gap-6 w-full">
        <div class="border shadow-lg rounded overflow-hidden">
            <div class="p-2 bg-green-400 font-bold text-white text-center">{{ Helper.translate('Revenue') }}</div>
            <div class="p-4 py-4 grid items-center justify-center font-bold">
                {{ Helper.priceFormate(get($page.props, 'revenue')) }}
            </div>
        </div>
        <div class="border shadow-lg rounded overflow-hidden">
            <div class="p-2 bg-orange-300 font-bold text-white text-center">{{ Helper.translate('Pending') }}</div>
            <div class="p-4 py-4 grid items-center justify-center font-bold">
                {{ Helper.priceFormate(get($page.props, 'pending')) }}
            </div>
        </div>
        <div class="border shadow-lg rounded overflow-hidden">
            <div class="p-2 bg-purple-400 font-bold text-white text-center">{{ Helper.translate('Paid') }}</div>
            <div class="p-4 py-4 grid items-center justify-center font-bold">
                {{ Helper.priceFormate(get($page.props, 'paid')) }}
            </div>
        </div>
        <div class="border shadow-lg rounded overflow-hidden">
            <div class="p-2 bg-black font-bold text-white text-center">{{ Helper.translate('Balance') }}</div>
            <div class="p-4 py-4 grid items-center justify-center font-bold">
                {{ Helper.priceFormate(get($page.props, 'auth.user.balance')) }}
                <!-- *** BALANCE = NET REVENUE – PAID *** -->
            </div>
        </div>
    </div>
</template>

<script setup>
import Helper from '@/Helper';
import { get } from 'lodash';
</script>