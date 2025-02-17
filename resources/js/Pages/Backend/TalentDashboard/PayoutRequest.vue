<template>
    <div class="md:p-10 p-6 bg-white shadow-lg md:min-w-[450px] relative">
        <button @click="$emit('close')" class="absolute right-4 top-4">
            <CloseIcon />
        </button>
        <h1 class="text-xl font-semibold text-center">{{ Helper.translate('Request Payout') }}</h1>
        <div class="flex gap-4 mt-5 justify-center">
            <button 
                @click="activeComponent = 'Paypal'" 
                class="px-4 py-1 border-2 w-[110px] h-[45px] flex items-center justify-center rounded font-semibold uppercase hover:shadow hover:border-sky-500"
                :class="activeComponent == 'Paypal' && 'shadow border-sky-500 text-white'"
            >
                <!-- {{ Helper.translate('Paypal') }} -->
                <img class="w-[60px]" :src="PaypalLogo" alt="">
            </button>
            <button 
                @click="activeComponent = 'Mobile'" 
                class="px-4 py-1 border-2 w-[110px] h-[45px] flex items-center justify-center rounded font-semibold uppercase hover:shadow hover:border-sky-500"
                :class="activeComponent == 'Mobile' && 'shadow border-sky-500 text-black'"
            >
                <!-- {{ Helper.translate('Paypal') }} -->
                <!-- <img class="w-[60px]" :src="PaypalLogo" alt=""> -->
                <MobileIcon />
            </button>
            <!-- <button 
                @click="activeComponent = 'Stripe'" 
                class="px-2 py-1 border-2 w-[110px] h-[45px] flex items-center justify-center rounded font-semibold uppercase hover:shadow hover:border-sky-500"
                :class="activeComponent == 'Stripe' && 'shadow border-sky-500 text-white'"
            >
                <img class="w-[90px]" :src="PayoneerLogo" alt="">
            </button> -->
            <button 
                @click="activeComponent = 'Bank'" 
                class="px-2 py-1 border-2 w-[110px] h-[45px] flex items-center justify-center rounded font-semibold uppercase hover:shadow hover:border-sky-500"
                :class="activeComponent == 'Bank' && 'shadow border-sky-500'"
            >
                <BankIcon class="w-5 h-5" />
            </button>
        </div>
        <div class="text-sm mt-3 -mb-3 text-center">
            {{ Helper.translate(`Fees may apply`) }}
        </div>
        <h2 v-if="activeComponent=='Mobile'" class="text-sm mt-6 mb-4 text-white bg-slate-700 py-1 px-2 rounded-md text-center whitespace-normal max-w-[320px] mx-auto">
            {{ Helper.translate('The minimum to withdraw is') }} {{ Helper.priceFormate(25) }} 
            {{ Helper.translate('and the maximum to withdraw per day is') }} {{ Helper.priceFormate(1000) }}
        </h2>
        <h2 v-else-if="activeComponent=='Bank'" class="text-sm mt-6 mb-4 text-white bg-slate-700 py-1 px-2 rounded-md text-center whitespace-normal max-w-[320px] mx-auto">
            {{ Helper.translate('The minimum to withdraw is €50. There is no maximum.') }}
        </h2>
        <h2 v-else class="text-sm mt-6 mb-4 text-white bg-slate-700 py-1 px-2 rounded-md text-center whitespace-normal max-w-[320px] mx-auto">
            {{ Helper.translate('The minimum to withdraw is') }} {{ Helper.priceFormate(25) }} 
            {{ Helper.translate('and the maximum to withdraw per day is') }} {{ Helper.priceFormate(2500) }}
        </h2>
        <component :is="components[activeComponent]" @close="$emit('close')" />
    </div>
</template>

<script setup>
import { ref } from 'vue'
import Stripe from './PayoutComponent/Stripe.vue'
import Bank from './PayoutComponent/Bank.vue'
import Paypal from './PayoutComponent/Paypal.vue'
import Mobile from './PayoutComponent/Mobile.vue'
import CloseIcon from '@/Icons/CloseIcon.vue';
import BankIcon from '@/Icons/BankIcon.vue'
import { useForm } from '@inertiajs/inertia-vue3';
import Helper from '@/Helper';
import PaypalLogo from '@/assets/PayPal-Logo.png'
import PayoneerLogo from '@/assets/Payoneer-logo.jpg'
import MobileIcon from '@/Components/Global/Icons/MobileIcon.vue';

const components = {
    // Stripe,
    Paypal,
    Mobile,
    Bank,
}
const activeComponent = ref('Paypal')
</script>