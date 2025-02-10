<template>
    <div class="md:p-10 p-6 bg-white shadow-lg md:min-w-[450px] mx-2 relative">
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
                @click="activeComponent = 'Stripe'" 
                class="px-2 py-1 border-2 w-[110px] h-[45px] flex items-center justify-center rounded font-semibold uppercase hover:shadow hover:border-sky-500"
                :class="activeComponent == 'Stripe' && 'shadow border-sky-500 text-white'"
            >
                <!-- {{ Helper.translate('Stripe') }} -->
                <img class="w-[90px]" :src="PayoneerLogo" alt="">
            </button>
        </div>
        <div class="text-sm mt-3 -mb-3 text-center">
            {{ Helper.translate(`Fees may apply`) }}
        </div>
        <h2 class="text-sm mt-6 mb-4 text-white bg-slate-700 py-1 px-2 rounded-md text-center whitespace-normal max-w-[320px] mx-auto">
            {{ Helper.translate('The minimum to withdraw is') }} {{ Helper.priceFormate(50) }} 
            {{ Helper.translate('and the maximum to withdraw per day is') }} {{ Helper.priceFormate(2500) }}
        </h2>
        <component :is="components[activeComponent]" @close="$emit('close')" />
    </div>
</template>

<script setup>
import { ref } from 'vue'
import Stripe from './PayoutComponent/Stripe.vue'
import Paypal from './PayoutComponent/Paypal.vue'
import CloseIcon from '@/Icons/CloseIcon.vue';
import { useForm } from '@inertiajs/inertia-vue3';
import Helper from '@/Helper';
import PaypalLogo from '@/assets/PayPal-Logo.png'
import PayoneerLogo from '@/assets/Payoneer-logo.jpg'

const components = {
    Stripe,
    Paypal,
}
const activeComponent = ref('Paypal')
</script>