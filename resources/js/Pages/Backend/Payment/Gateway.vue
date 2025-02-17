<template>
    <Master>
        <div class="px-4">
            <div class="container mx-auto py-5">
                <div class="cardWrapper flex flex-col-reverse lg:grid gap-6 md:items-stretch items-start">
                    <div class="border w-full p-6 rounded-lg grid gap-4">
                        <div>
                            <h3 class="mb-5 font-semibold w-full max-w-[300px] mx-auto">
                                {{ Helper.translate('Select payment method') }}
                            </h3>
                            <div class="grid gap-5">
                                <label class="flex gap-3 w-full max-w-[300px] mx-auto">
                                    <input type="radio" v-model="payment_method" value="card" name="pay_method" />
                                    {{ Helper.translate('Credit card') }}
                                </label>
                                <div v-show="payment_method=='card'" class="grid gap-4">
                                    <div class="border flex items-center rounded-lg bg-gray-100 px-2">
                                        <div class="px-2 flex gap-1">
                                            <CardIcon class="shrink-0 w-4 h-4"/>
                                            <VisaIcon class="shrink-0 w-4 h-4"/>
                                            <AmericanExpressIcon class="shrink-0 w-4 h-4"/>
                                        </div>
                                        <div id="stripeCartNumber" class="w-full border-0 focus-within:ring-0 bg-transparent py-4"></div>
                                    </div>
                                    <div class="flex lg:flex-nowrap flex-wrap justify-between mt-4">
                                        <div>
                                            <h3 class="font-bold text-[14px]">{{ Helper.translate('Expiry Date') }}</h3>
                                            <h3 class="text-[12px] text-gray-400">{{ Helper.translate('Enter the expiration date of the card') }}</h3>
                                        </div>
                                        <div class="lg:w-[250px] w-full">
                                            <div id="stripeExpiryDate" class="w-full border-black border-opacity-10 border py-3 px-4 font-semibold placeholder-gray-300 rounded-lg"></div>
                                        </div>
                                    </div>
            
                                    <div class="flex lg:flex-nowrap flex-wrap justify-between mt-4">
                                        <div>
                                            <h3 class="font-bold text-[14px]">{{ Helper.translate('CVC/CVV Number') }}</h3>
                                            <h3 class="text-[12px] text-gray-400">{{ Helper.translate('Enter card verification code from the back of the card') }}</h3>
                                        </div>
                                        <div class="lg:w-[250px] w-full">
                                            <div id="stripeCvc" class="w-full border-black border py-3 px-4 border-opacity-10 font-semibold placeholder-gray-300 rounded-lg"></div>
                                        </div>
                                    </div>
                                    <div class="flex lg:flex-nowrap flex-wrap justify-between mt-4">
                                        <div class="lg:w-[250px] w-full">
                                            <h3 class="font-bold text-[14px]">{{ Helper.translate('Cardholder Name') }}</h3>
                                            <h3 class="text-[12px] text-gray-400">{{ Helper.translate("Enter cardholder's name") }}</h3>
                                        </div>
                                        <div class="lg:w-[250px] w-full">
                                            <input 
                                                type="text"
                                                v-model="form.cardholderName"
                                                class="w-full block border-black border-opacity-10 font-semibold placeholder-gray-300 rounded-lg" 
                                            />
                                        </div>
                                    </div>
                                    <div v-if="cartError.message" class="mt-2 text-red-500 text-center">
                                        {{ Helper.translate(cartError.message) }}
                                    </div>
                                    <button ref="stripePay" :disabled="form.processing || stripeLoading" class="bg-blue-500 flex items-center justify-center px-4 py-2 mt-4 rounded-lg text-white font-semibold w-full">
                                        <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        {{ Helper.translate('Pay Now') }}
                                    </button>
                                </div>
                                <label class="flex gap-3 w-full max-w-[300px] mx-auto">
                                    <input type="radio" v-model="payment_method" value="mobile" name="pay_method" />
                                    {{ Helper.translate('Mobile payment') }}
                                </label>
                                <div v-show="payment_method == 'mobile'" class="py-5 px-4 rounded-xl border w-full max-w-[400px] border-slate-400 mx-auto">
                                    
                                    <div class="flex justify-center">
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
                                            <label 
                                                v-for="(country, index) in countries"
                                                class="w-14 h-14 relative" :key="'country'+index"
                                            >
                                                <input type="radio" @input="selected_sim=null" v-model="current_country" :value="country.name" name="country_name" class="hidden peer" />
                                                <div 
                                                    class="w-14 h-14 rounded-full overflow-hidden"
                                                    :class="current_country==country.name ? 'ring-4 ring-blue-500/50' : ''"
                                                >
                                                    <img :src="country.flag" alt="" class="block w-full h-full object-cover" />
                                                </div>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="mt-5">
                                        <h2 class="mb-3 text-xl font-bold capitalize">{{ Helper.translate(String(current_country).replace('_', ' ')) }}</h2>
                                        <!-- <p>{{ Helper.translate('Select Network') }}</p>

                                        <div class="py-3 px-4 mt-4 border border-slate-400 rounded-xl flex justify-center gap-5">
                                            <NetworkItem
                                                v-for="(sim, index) in selected_country.sim"
                                                :sim="sim"
                                                :selected_sim="selected_sim"
                                                :selected_country="selected_country"
                                                @update="val => selected_sim=val"
                                                :key="index"
                                            />
                                        </div>

                                        <div v-if="selected_country && selected_sim" class="mt-5">
                                            <h2 class="font-semibold">{{ Helper.translate(selected_sim) }}</h2>

                                            <label class="mt-5 flex items-center gap-1 w-full relative">
                                                <img :src="selected_country.flag" class="w-12" alt="" />
                                                <div class="relative flex-1">
                                                    <span class="absolute h-full flex items-center pl-1">
                                                        + {{ getPrefix(selected_country.sim, selected_sim)['prefix'] }} -
                                                    </span>
                                                    <input type="text" v-model="phone_number" class="pl-14 w-full border outline-0 focus:outline-none flex-1" />
                                                </div>
                                            </label>

                                            <div class="flex justify-center mt-5">
                                                <button 
                                                    @click="handleNext"
                                                    :disabled="String(phone_number).length==0"
                                                    class="py-1.5 px-10 flex items-center gap-2 rounded bg-orange-600 hover:bg-opacity-80 text-white disabled:opacity-70 disabled:cursor-not-allowed"
                                                >
                                                    <Preloader v-if="form.processing" class="w-5 h-5" />
                                                    {{ Helper.translate('Next') }}
                                                </button>
                                            </div>
                                        </div> -->
                                    </div>

                                    <div class="flex justify-center mt-5">
                                        <button 
                                            @click="handleNext"
                                            class="py-1.5 px-10 flex items-center gap-2 rounded bg-orange-600 hover:bg-opacity-80 text-white disabled:opacity-70 disabled:cursor-not-allowed"
                                        >
                                            <Preloader v-if="form.processing" class="w-5 h-5" />
                                            {{ Helper.translate('Next') }}
                                        </button>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="border w-full px-6 py-4 rounded-lg xl:w-full">
                        <div class="font-semibold flex justify-between gap-2 mt-0 lg:mt-8 text-sm">
                            <span class="text-gray-400">
                                {{ Helper.translate('Product') }}
                            </span>
                            <span class="">
                                <template v-if="type == 'wish'">
                                    {{ Helper.translate('Personalized video') }}
                                </template>
                                <template v-else>
                                    {{ Helper.translate(type) }}
                                </template>
                            </span>
                        </div>
                        <div class="font-semibold flex justify-between gap-2 mt-1 text-sm">
                            <span class="text-gray-400">
                                {{ Helper.translate('Talent') }}
                            </span>
                            <span class="capitalize">
                                {{ talent.name }}
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
                        <div class="font-bold text-gray-400 grid mt-3 lg:mt-8 text-sm border-t pt-0 lg:pt-4">
                            <span class="text-black text-2xl mt-2">
                                <!-- <template v-if="payment_method == 'mobile'">
                                    {{ getConvertedCurrency(getPrice(Number(earning.amount), type), get(getPrefix(selected_country.sim, selected_sim), 'rate')) }}
                                </template>
                                <template v-else>
                                </template> -->
                                {{ Helper.priceFormate(getPrice(Number(earning.amount), type)) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <ConfirmNoticePopup
            v-if="showConfirmPopup"
            @close="showConfirmPopup=false"
            :rate="get(getPrefix(selected_country.sim, selected_sim), 'rate')"
            :currency="get(getPrefix(selected_country.sim, selected_sim), 'currency')"
            :amount="getPrice(Number(earning.amount), type)"
            @submitToPay="charge => handleMobilePay(charge)"
            :processing="form.processing"
        />
        
    </Master>
</template>

<script setup>
import Master from '../Master.vue'
import CardIcon from '@/Pages/Backend/Payment/icons/CardIcon.vue'
import CheckIcon from '@/Icons/CheckIcon.vue'
import masterCardImg from './icons/masterCard.jpg'
import VisaIcon from './icons/VisaIcon.vue';
import ConfirmNoticePopup from './fragments/ConfirmNoticePopup.vue'
import AmericanExpressIcon from '@/Icons/AmericanExpressIcon.vue' 
import Helper from '@/Helper';
import cameroon from '@/Icons/cameroon.png'
import Preloader from '@/Components/Global/Preloader.vue';

import NetworkItem from './fragments/NetworkItem.vue'
import { get } from 'lodash'
// import ghana_mtn from '@/Icons/ghana-mtn.png'

import { onMounted, ref, computed } from 'vue';
import { useForm } from '@inertiajs/inertia-vue3';
import { loadStripe } from '@stripe/stripe-js/pure';
import CloseIcon from '@/Icons/CloseIcon.vue';
import CInput from '@/Components/Global/CInput.vue';
import { countries } from "./data.js";
import { getCorrespondent } from './usePayment'

const props = defineProps({
    orderCode: String,
    type: String,
    talent: {
        type: Object,
        default: {}
    },
    earning: {
        type: Object,
        default: {}
    },
});

const form = useForm({
    cardNumber: null,
    cardholderName: null,
    expiry_date: null,
    type: props.type,
    amount: props.earning.amount,
    wishDetails: null,
    token: null,
    mobile_payload: {}
});

const showConfirmPopup = ref(false)

const payment_method = ref(null)
const current_country = ref('ivory_coast')
const selected_sim = ref('MTN')
const phone_number = ref('')
const prefix = ref('')
// countries
const selected_country = computed(() => {
    phone_number.value=''
    let country = countries.find(item => item.name == current_country.value)
    let mno = get(country, 'sim[0].mno')
    if(mno) {
        selected_sim.value = mno
    }
    return country
})

const getConvertedCurrency = (amount, rate)  => {
    let _total = (amount * rate) || 0
    return parseFloat(Number(0.035 * _total).toFixed(4))
}

/**
Cameroon -- 
Ivory Coast --
Ghana
Senegal
*/

/**
 XOF = Ivory Coast
 XAF = Cameroon
 GH₵ = Ghana
*/

const stripePay = ref()

const stripeLoading = ref(false)

const cartError = ref({
    number: false,
    expiry: false,
    cvc: false,
    message: '',
})

const validatePhoneNumber = (num) => {
    let valid = true
    
    if (String(num).length == 0) {
        valid = false
    }
    return {
        valid,
        num
    }
}

const getPrefix = (sim_list, sim) => {
    let found = sim_list.find(item => item.mno == sim)
    prefix.value = found?.prefix
    return found
}

const handleNext = () => {
    const validPhone = validatePhoneNumber(phone_number.value)
    let sim_info = getCorrespondent(current_country.value, selected_sim.value)
    
    // if (!current_country.value || !selected_sim.value || !validPhone.valid || !sim_info) {
    //     return;
    // }
    showConfirmPopup.value = true;
}

const handleMobilePay = (charge) => {
    const validPhone = validatePhoneNumber(phone_number.value)
    let sim_info = getCorrespondent(current_country.value, selected_sim.value)
    form.mobile_payload.sim = selected_sim.value
    form.mobile_payload.phone_number = `${prefix.value}${validPhone.num}`
    form.mobile_payload.country_name = current_country.value
    form.mobile_payload.country = sim_info.country
    form.mobile_payload.correspondent = sim_info.correspondent
    form.mobile_payload.currency = sim_info.currency
    form.mobile_payload.rate = sim_info.rate
    form.mobile_payload.charge = charge
    form.mobile_payload.mno = sim_info.mno
    form.mobile_payload.decimal = sim_info.decimal
    // console.log(form);
    // return
    form.post(route('payment.mobile_pay_test', {
        id: props.talent.id,
        type: props.type
    }))
}

const getPrice = (amount, type) => {
    if (['wish', 'calender', 'mylife', 'tips'].includes(type)) {
        amount += Helper.getMaintenanceCharge(amount)
    }
    return amount;
}

onMounted(async ()=> {
    let cashType = localStorage.getItem('cashType');
    if (props.type == 'wish') {
        try {
            cashType = JSON.parse(cashType);
            form.wishDetails = cashType;
        } catch (error) {
            window.history.back();
        }
    }
    if (props.type == 'tips' && form.amount > Helper.MAXTIPS) {
        console.log('max');
        window.history.back();
    }

    const settings = await axios.get('settings').then(res => res.data);
    loadStripe(settings.stripePublicKey)
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
                stripeLoading.value = true
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
                    stripeLoading.value = false
                }).catch(()=>{
                    stripeLoading.value = false
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
        grid-template-columns: 1fr;
    }
    
    @media all and (min-width: 1000px){
        .cardWrapper{
            grid-template-columns: calc(100% - 325px) 300px;
        }
    }

    @media all and (min-width: 1280px){
        .cardWrapper{
            grid-template-columns: calc(100% - 475px) 450px;
        }
    }
</style>