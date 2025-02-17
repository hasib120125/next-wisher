<template>
    <slot></slot>

    <div @click.self="showTalentRegisteredPopup=false" v-if="showTalentRegisteredPopup" class="fixed bg-white/80 backdrop-blur w-full h-full top-0 left-0 z-[99990] flex items-center justify-center p-10">
        <div class="bg-[#000026] text-white p-5 rounded w-full max-w-[500px] relative">
            <button @click="showTalentRegisteredPopup=false" class="w-[30px] h-[30px] rounded-full bg-red-100 border border-red-400 text-red-600 flex items-center justify-center absolute -top-4 -right-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <div class="text-[#00BF15] mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-14 h-14" width="28" height="28" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M9 11l3 3l8 -8"></path>
                    <path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9"></path>
                </svg>
            </div>
            <div class="text-lg">
                {{ Helper.translate('Your request to become a talent has been submitted. We will be in touch within 72 hours.') }}
            </div>
            <div class="text-center mt-4">
                {{ Helper.translate('Thank you!') }}
            </div>
        </div>
    </div>

    <div @click.self="showPaymentFailedPopup=false" v-if="showPaymentFailedPopup" class="fixed bg-black/40 backdrop-blur w-full h-full top-0 left-0 z-[99990] flex items-center justify-center p-10">
        <div class="bg-white p-5 rounded w-full max-w-[500px] relative">
            <button @click="showPaymentFailedPopup=false" class="w-[30px] h-[30px] rounded-full bg-red-100 border border-red-400 text-red-600 flex items-center justify-center absolute -top-4 -right-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <div>
                <div class="mb-3 w-10 h-10 rounded-full bg-red-500 text-white flex items-center justify-center">
                    <CloseIcon />
                </div>
            </div>
            <div class="text-lg font-semibold">
                {{ Helper.translate('Payment failed') }}
            </div>
            <div class="mt-4">
                {{ Helper.translate('We are sorry, there was an error processing your payment. Please try again with a different payment method.') }}
            </div>
        </div>
    </div>
    <div @click.self="() => {
            showPaymentSuccessPopup=false
            is_pawapay = false
        }" v-if="showPaymentSuccessPopup" class="fixed bg-black/40 backdrop-blur w-full h-full top-0 left-0 z-[99990] flex items-center justify-center p-10">
        <div class="bg-white p-5 rounded w-full max-w-[500px] relative">
            <button @click="showPaymentSuccessPopup=false" class="w-[30px] h-[30px] rounded-full bg-red-100 border border-red-400 text-red-600 flex items-center justify-center absolute -top-4 -right-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <div>
                <div class="mb-3 w-10 h-10 rounded-full bg-green-500 text-white flex items-center justify-center">
                    <CheckIcon />
                </div>
            </div>
            <div class="text-lg font-semibold">
                {{ Helper.translate('Payment successful') }}
            </div>
            <div class="mt-4">
                {{ Helper.translate(`Your order has been placed.`) }} 
                <template v-if="!is_pawapay">
                    {{ Helper.translate(`We'll send you an email with your order details.`) }}
                </template>
            </div>
        </div>
    </div>
</template>

<script setup>
import { usePage } from '@inertiajs/inertia-vue3'
import { toast } from 'vue3-toastify'
import { watchEffect, ref } from 'vue'
import { get } from 'lodash';
import Helper from '@/Helper';
import CloseIcon from '@/Icons/CloseIcon.vue';
import CheckIcon from '@/Icons/CheckIcon.vue';

const showPaymentFailedPopup = ref(false)

const showPaymentSuccessPopup = ref(false)
const is_pawapay = ref(false)

const showTalentRegisteredPopup = ref(false)

let timeout;

const replaceParams = () => {
    setTimeout(()=>{
        const url = new URL(location.href);
        url.searchParams.delete('payment_status');
        const newUrlString = url.toString();
        window.history.replaceState(null, null, newUrlString);
    }, 1000)
}

watchEffect(() => {
    const props = usePage().props.value;

    clearTimeout(timeout)
    timeout = setTimeout(() => {
        if(props.flash.become_talent_msg == 'become_talent_msg'){
            showTalentRegisteredPopup.value = true
        }
        if(props.flash.payment_status == 'failed'){
            showPaymentFailedPopup.value = true
            replaceParams()
        }

        if(get(route().params, 'payment_status') == 'success'){
            showPaymentSuccessPopup.value = true
            replaceParams()
        }
        if(props.flash.payment_status == 'success'){
            showPaymentSuccessPopup.value = true
        }
        if(get(props, 'is_pawapay') == 'success'){
            showPaymentSuccessPopup.value = true
            is_pawapay.value = true
        }
        if(props.flash.message){
            toast.success(Helper.translate(props.flash.message), {
                autoClose: 2000,
            })
        }
        if(props.flash.error){
            toast.error(Helper.translate(props.flash.error), {
                autoClose: 2000,
            })
        }
        props.flash = {}
    }, 100)
})
</script>