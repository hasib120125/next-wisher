<template>
    <Master>
        <div class="px-4">
            <div class="container mx-auto py-5">
                <div class="cardWrapper grid gap-6">
                    <div class="border p-6 rounded-lg grid gap-4">
                        <div class="border flex items-center rounded-lg bg-gray-100 px-2">
                            <div class="px-2 flex gap-1">
                                <CardIcon class="shrink-0 w-4 h-4"/>
                                <VisaIcon class="shrink-0 w-4 h-4"/>
                                <AmericanExpressIcon class="shrink-0 w-4 h-4"/>
                            </div>
                            <div id="stripeCartNumber" class="w-full border-0 focus-within:ring-0 bg-transparent py-4"></div>
                            <!-- <input type="number" v-model="form.cardNumber" class="w-full border-0 focus-within:ring-0 bg-transparent py-4" placeholder="Stripe cart number input field"> -->
                            <div :class="cartError.number ? 'bg-red-500' : 'bg-blue-500'" class="text-white w-7 h-7 flex items-center justify-center flex-shrink-0 rounded-full mr-2">
                                <CheckIcon v-if="!cartError.number" class="w-4" />
                                <CloseIcon v-if="cartError.number" class="w-4" />
                            </div>
                        </div>
                        <div class="flex justify-between mt-4">
                            <div>
                                <h3 class="font-bold text-[14px]">{{ Helper.translate('Expiry Date') }}</h3>
                                <h3 class="text-[12px] text-gray-400">{{ Helper.translate('Enter the expiration date of the card') }}</h3>
                            </div>
                            <div>
                                <div id="stripeExpiryDate" class="w-[250px] border-black border-opacity-10 border py-3 px-4 font-semibold placeholder-gray-300 rounded-lg"></div>
                                <!-- <input type="month" v-model="form.expiry_date" class="w-[250px] border-black border-opacity-10 font-semibold placeholder-gray-300 rounded-lg" /> -->
                            </div>
                        </div>
                        <div class="flex justify-between mt-4">
                            <div>
                                <h3 class="font-bold text-[14px]">{{ Helper.translate('CVC/CVV Number') }}</h3>
                                <h3 class="text-[12px] text-gray-400">{{ Helper.translate('Enter card verification code from the back of the card') }}</h3>
                            </div>
                            <div>
                                <div id="stripeCvc" class="w-[250px] border-black border py-3 px-4 border-opacity-10 font-semibold placeholder-gray-300 rounded-lg"></div>
                                <!-- <input type="password" placeholder="CVC/CVV" class="w-[250px] border-black border-opacity-10 font-semibold placeholder-gray-300 rounded-lg" /> -->
                            </div>
                        </div>
                        <div class="flex justify-between mt-4">
                            <div>
                                <h3 class="font-bold text-[14px]">{{ Helper.translate('Cardholder Name') }}</h3>
                                <h3 class="text-[12px] text-gray-400">{{ Helper.translate("Enter cardholder's name") }}</h3>
                            </div>
                            <div>
                                <input 
                                    type="text"
                                    v-model="form.cardholderName"
                                    placeholder="Cardholder Name" 
                                    class="w-[250px] border-black border-opacity-10 font-semibold placeholder-gray-300 rounded-lg" 
                                />
                            </div>
                        </div>
                        <div v-if="cartError.message" class="mt-2 text-red-500 text-center">
                            {{ cartError.message }}
                        </div>
                        <button ref="stripePay" :disabled="form.processing" class="bg-blue-500 flex items-center justify-center px-4 py-2 mt-4 rounded-lg text-white font-semibold w-full">
                            <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ Helper.translate('Pay Now') }}
                        </button>
                    </div>
                    
                    <div class="border p-6 rounded-lg bg-gray-100">
                        <div class="card w-full h-[200px] bg-blue-400 rounded-lg mb-4 shadow-lg py-6 px-5 relative overflow-hidden">
                            <img :src="masterCardImg" class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2" />
                            <div class="relative z-10">
                                <div class="flex gap-1 items-center">
                                    <CardIcon class="shrink-0 w-6 h-6"/>
                                    <VisaIcon class="shrink-0 w-6 h-6"/>
                                    <AmericanExpressIcon class="shrink-0 w-4 h-4"/>
                                </div>
                                <h1 class="uppercase font-bold text-[14px] mt-2 text-white">
                                    {{ form.cardholderName }}
                                </h1>
                                <h1 class="uppercase font-bold text-[14px] mt-2 text-white">
                                    {{ form.cardNumber }}
                                </h1>
                                <h1 class="uppercase font-bold text-[14px] mt-8 text-white">
                                    {{ Helper.translate('Expiry Date') }} : 
                                    <template v-if="form.expiry_date">
                                        {{ form.expiry_date }}
                                    </template>
                                </h1>
                            </div>
                        </div>
                        <div class="font-semibold flex justify-between gap-2 mt-8 text-sm">
                            <span class="text-gray-400">
                                {{ Helper.translate('Product') }}
                            </span>
                            <span class="capitalize">
                                {{ Helper.translate(type) }}
                            </span>
                        </div>
                        <div class="font-semibold flex justify-between mt-1 text-sm">
                            <span class="text-gray-400">
                                {{ Helper.translate('Order number') }}
                            </span>
                            <span class="">
                                {{ orderCode }}
                            </span>
                        </div>
                        <div class="font-bold grid text-gray-400 mt-8 text-sm border-t pt-4">
                            <span class="">{{ Helper.translate('You have to Pay') }}</span>
                            <span class="text-black text-lg">
                                {{ getNumber(getPrice(calender.price, type)) }}
                                <small class="text-[10px]">
                                    <template v-if="getSent(getPrice(calender.price, type))">
                                        .{{ getSent(getPrice(calender.price, type)) }}
                                    </template>
                                    <span class="text-gray-400"> USD</span>
                                </small>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Master>
</template>

<script setup>
import Master from '../Master.vue'
import CardIcon from '@/Pages/Backend/Payment/icons/CardIcon.vue'
import CheckIcon from '@/Icons/CheckIcon.vue'
import masterCardImg from './icons/masterCard.jpg'
import VisaIcon from './icons/VisaIcon.vue';
import AmericanExpressIcon from '@/Icons/AmericanExpressIcon.vue' 
import Helper from '@/Helper';
import { onMounted, ref } from 'vue';
import { useForm, usePage } from '@inertiajs/inertia-vue3';
import {loadStripe} from '@stripe/stripe-js/pure';
import moment from 'moment';
import { Inertia } from '@inertiajs/inertia';
import axios from 'axios';
import CloseIcon from '@/Icons/CloseIcon.vue';

const props = defineProps({
    orderCode: String,
    type: String,
    talent: {
        type: Object,
        default: {}
    },
    calender: {
        type: Object,
        default: {}
    },
});

const form = useForm({
    cardNumber: null,
    cardholderName: null,
    expiry_date: null,
    type: props.type,
    amount: props.calender.price,
    calender_id: props.calender.id,
    wishDetails: null,
    token: null,
});

const stripePay = ref()

const cartError = ref({
    number: false,
    expiry: false,
    cvc: false,
    message: '',
})

const getPrice = (amount, type) => {
    let siteSettings = usePage().props.value.settings;
    let charge = 0;
    if(siteSettings && siteSettings.maintenance_charge){
        charge = siteSettings.maintenance_charge
    }
    if (['wish', 'calender'].includes(type)) {
        amount = +amount + +Helper.getMaintenanceCharge(+amount, +charge)
    }
    return amount;
}

onMounted(()=> {
    let cashType = localStorage.getItem('cashType');
    if (props.type == 'wish' || props.type == 'calender') {
        try {
            cashType = JSON.parse(cashType);
            form.wishDetails = cashType;
        } catch (error) {
            window.history.back();
        }
    }
    // if (cashType.payType != props.type) {
    //     window.history.back();
    // }

    loadStripe("pk_test_51M1lgfDKrpdPsiSpc2iGtO8XgCgWkjhGvXo5JRT6jpH6NLsyPDVXTSczbUFEihz94XQBZnWvQ2hqE46mJraU238E00l1d2VpQG")
        .then(stripe => {
            let element = stripe.elements();
            var style = {
                    base: {
                        color: '#32325d',
                        fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                        fontSmoothing: 'antialiased',
                        fontSize: '16px',
                        '::placeholder': {
                            color: '#aab7c4'
                        }
                    },
                    invalid: {
                        color: '#fa755a',
                        iconColor: '#fa755a'
                    }
                };
            var card = element.create('cardNumber', {
                    hidePostalCode: true,
                    style: style
                });
                element.create('cardExpiry')
                        .on('change', function(event) {
                            if (event.error) {
                                cartError.value.expiry = true;
                            } else {
                                cartError.value.expiry = false;
                            }
                        })
                        .mount('#stripeExpiryDate')
                element.create('cardCvc')
                        .on('change', function(event) {
                            if (event.error) {
                                cartError.value.cvc = true;
                            } else {
                                cartError.value.cvc = false;
                            }
                        })
                        .mount('#stripeCvc')
            card.mount('#stripeCartNumber')
            stripePay.value.addEventListener('click', function() {
                stripe.createToken(card).then(function(result) {
                    if (result.error) {
                        cartError.value.message = result.error.message
                    } else {
                        cartError.value.message = ''
                    }
                    if (result.token) {
                        form.token = result.token;
                        form.post(route('payment.createPayment', {
                            id: props.talent.id,
                            type: props.type
                        }))
                    }
                })
            })
        })
});

const getNumber = (amount) => {
    if (String(amount).indexOf('.') == -1) return amount;
    return String(amount).split('.')[0];
}
const getSent = (amount) => {
    if (String(amount).indexOf('.') == -1) return null;
    return String(amount).split('.')[1];
}

// const handlePay = () => {
//     form.post(route('payment.createPayment', {
//         id: props.talent,
//         type: props.type,
//     }));
// }

</script>

<style scope>
    .cardWrapper{
        grid-template-columns: calc(100% - 450px) 450px;
    } 
</style>