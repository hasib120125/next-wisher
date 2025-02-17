<template>
    <div>
        <div class="py-0 w-full">              
            <div class="flex justify-center">
                <div class="grid grid-cols-2 gap-5">
                    <label
                        v-for="(country, index) in countries"
                        class="w-14 h-14 relative"
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

            <div class="mt-2">
                <h2 class="mb-3 text-xl font-bold capitalize">{{ Helper.translate(String(current_country).replace('_', ' ')) }}</h2>
                <p>{{ Helper.translate('Select Network') }}</p>

                <div class="py-3 px-4 mt-2 flex justify-center gap-5">
                    <NetworkItem
                        v-for="(sim, index) in selected_country.sim"
                        :sim="sim"
                        :selected_sim="selected_sim"
                        :selected_country="selected_country"
                        @update="val => selected_sim=val"
                        :key="index"
                    />
                </div>

                <div v-if="selected_country && selected_sim" class="mt-2 grid gap-3">
                    <label class="space-y-1">
                        <div>{{ Helper.translate('Phone number') }}</div>
                        <div class="flex items-center gap-1 w-full relative border border-slate-400 p-1 rounded">
                            <img :src="selected_country.flag" class="w-12" alt="" />
                            <div class="relative flex-1">
                                <span class="absolute h-full flex items-center pl-1">
                                    + {{ getPrefix(selected_country.sim, selected_sim)['prefix'] }} -
                                </span>
                                <input type="text" v-model="phone_number" class="pl-14 w-full border-none outline-0 focus:outline-none focus:border-none focus:ring-0 flex-1" />
                            </div>
                        </div>
                        <span v-if="form.errors.stripe_email" class="text-sm text-red-500">
                            {{ Helper.translate(form.errors.stripe_email) }}
                        </span>
                    </label>
                    <label class="space-y-1">
                        <div>{{ Helper.translate('Confirm phone number') }}</div>
                        <div class="flex items-center gap-1 w-full relative border border-slate-400 p-1 rounded">
                            <img :src="selected_country.flag" class="w-12" alt="" />
                            <div class="relative flex-1">
                                <span class="absolute h-full flex items-center pl-1">
                                    + {{ getPrefix(selected_country.sim, selected_sim)['prefix'] }} -
                                </span>
                                <input type="text" v-model="confirmEmail" class="pl-14 w-full border-none outline-0 focus:outline-none focus:border-none focus:ring-0 flex-1" />
                            </div>
                        </div>
                    </label>
                    <label class="space-y-1">
                        <div>{{ Helper.translate('Payout Amount') }}</div>
                        <div class="flex items-center gap-1 w-full relative border border-slate-400 rounded">
                            <div class="relative flex-1">
                                <input 
                                    type="number" 
                                    v-model="form.amount"
                                    step="0.01"
                                    class="w-full border-none outline-0 focus:outline-none focus:border-none focus:ring-0 flex-1" 
                                />
                            </div>
                        </div>
                        <span v-if="form.errors.amount" class="text-sm text-red-500">
                            {{ Helper.translate(form.errors.amount) }}
                        </span>
                    </label>
                </div>
            </div>
        </div>

        <div class="mt-5 max-w-[400px] font-semibold">
            {{ Helper.translate('Please make sure you have enough room on your wallet to receive your payment.') }}
        </div>

        <button 
            @click="handleSave"
            :disabled="form.processing"
            class="bg-green-500 text-white px-4 py-2 rounded flex items-center gap-2 ml-auto mt-5 font-semibold disabled:opacity-70"
        >
            <Preloader v-if="form.processing" class="w-4 h-4" />
            {{ Helper.translate('Confirm') }}
        </button>
    </div>
</template>

<script setup>
import CInput from '@/Components/Global/CInput.vue'
import { ref, computed } from 'vue';
import { isEmpty, get } from 'lodash';
import { useForm } from '@inertiajs/inertia-vue3';
import Helper from '@/Helper';
import { countries } from "../useMobilePay";
import NetworkItem from './NetworkItem.vue';
import { toast } from "vue3-toastify";
import Preloader from '@/Components/Global/Preloader.vue';

const form = useForm({
    bank_type: 'Mobile',
    amount: null,
    stripe_email: null,
    stripe_email_confirmation: null,
    account_number: null,
    settings: {
        country: '',
        prefix: '',
        sim: ''
    }
})


const emit = defineEmits(['close'])


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

const getPrefix = (sim_list, sim) => {
    let found = sim_list.find(item => item.mno == sim)
    prefix.value = found?.prefix
    return found
}

const confirmEmail = ref(null);
const handleSave = () => {
    form.stripe_email = phone_number.value
    form.stripe_email_confirmation = confirmEmail.value
    form.settings.country = current_country.value
    form.settings.prefix = prefix.value
    form.settings.sim = selected_sim.value
    form.post(route('talent.mobilePayoutRequest'), {
        onSuccess(page){
            if (isEmpty(page.props.errors)) {
                form.reset(); 
                phone_number.value = ''
                confirmEmail.value = null
                emit('close')
            }
        }
    })
}

</script>