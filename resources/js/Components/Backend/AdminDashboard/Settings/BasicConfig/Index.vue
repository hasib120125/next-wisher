<template>
    <h1 class="text-lg my-2 text-center mb-4">{{ Helper.translate('Basic Configuration') }}</h1>
    <div class="border border-sky-400 border-opacity-40 rounded shadow md:p-10 p-4 md:w-fit w-full md:mx-auto overflow-x-auto">
        <div class="w-full space-y-4">
            <div class="md:flex grid items-center">
                <div class="max-w-[220px] w-full font-medium text-sm md:text-right px-2">{{ Helper.translate('Company name') }} :</div>
                <div class="flex items-center gap-4 px-2 w-full">
                    <CInput 
                        class="max-w-[300px] w-full bg-transparent"
                        type="text" 
                        placeholder="Enter company name" 
                        v-model="form.companyName"
                        disabled="true"
                    />
                </div>
            </div>
            <div class="md:flex grid items-center">
                <div class="max-w-[220px] w-full font-medium text-sm md:text-right px-2">{{ Helper.translate('Commission in percentage') }} :</div>
                <div class="flex items-center gap-4 px-2 w-full">
                    <CInput 
                        class="max-w-[300px] w-full bg-transparent"
                        type="number" 
                        placeholder="ex: 5" 
                        v-model="form.commission"
                        disabled="true"
                    /> %
                </div>
            </div>
            <div class="md:flex grid items-center">
                <div class="max-w-[220px] w-full font-medium text-sm md:text-right px-2">{{ Helper.translate('Service fee in percentage') }} :</div>
                <div class="flex items-center gap-4 px-2 w-full">
                    <CInput 
                        class="max-w-[300px] w-full bg-transparent"
                        type="number" 
                        placeholder="ex: 5" 
                        v-model="form.maintenance_charge"
                        disabled="true"
                    /> %
                </div>
            </div>
            <div class="md:flex grid items-center">
                <div class="max-w-[220px] w-full font-medium text-sm md:text-right px-2">{{ Helper.translate('Request duration') }} :</div>
                <div class="flex items-center gap-4 px-2 w-full">
                    <CInput 
                        class="max-w-[300px] w-full bg-transparent"
                        type="number" 
                        placeholder="ex: 5" 
                        v-model="form.request_duration_days"
                        disabled="true"
                    /> {{ Helper.translate('Days') }}
                </div>
            </div>
            <div class="md:flex grid items-center">
                <div class="max-w-[220px] w-full font-medium text-sm md:text-right px-2">{{ Helper.translate('Security code') }} :</div>
                <div class="flex items-center gap-4 px-2 w-full">
                    <CInput 
                        class="max-w-[300px] w-full bg-transparent"
                        type="text" 
                        placeholder="Security code" 
                        v-model="form.security_code"
                    />
                </div>
            </div>
            <!-- <div class="md:flex grid items-center">
                <div class="max-w-[220px] w-full font-medium text-sm md:text-right px-2">{{ Helper.translate('Date Formate') }} :</div>
                <div class="flex items-center gap-4 px-2 w-full">
                    <CSelect
                        class="max-w-[300px] w-full bg-transparent"
                        :options="dateFormats"
                        v-model="form.dateFormate"
                    />
                </div>
            </div> -->
            <!-- <div class="md:flex grid items-center">
                <div class="max-w-[220px] w-full font-medium text-sm md:text-right px-2">{{ Helper.translate('Currency Position') }} :</div>
                <div class="flex items-center gap-4 px-2 w-full">
                    <CSelect
                        class="max-w-[300px] w-full bg-transparent"
                        :options="currencyPosition"
                        v-model="form.currencyPosition"
                    />
                </div>
            </div> -->
            <!-- <div class="md:flex grid items-center">
                <div class="max-w-[220px] w-full font-medium text-sm md:text-right px-2">{{ Helper.translate('Stripe Public Key') }} :</div>
                <div class="flex items-center gap-4 px-2 w-full">
                    <CInput 
                        class="max-w-[300px] w-full bg-transparent"
                        type="text" 
                        placeholder="Enter public key" 
                        v-model="form.stripePublicKey"
                    />
                </div>
            </div>
            <div class="md:flex grid items-center">
                <div class="max-w-[220px] w-full font-medium text-sm md:text-right px-2">{{ Helper.translate('Stripe Privet Key') }} :</div>
                <div class="flex items-center gap-4 px-2 w-full">
                    <CInput 
                        class="max-w-[300px] w-full bg-transparent"
                        type="text" 
                        placeholder="Enter private key" 
                        v-model="form.stripePrivatKey"
                    />
                </div>
            </div> -->
            <div class="md:flex grid items-center">
                <div class="max-w-[220px] w-full font-medium text-sm md:text-right px-2"></div>
                <div class="flex items-center gap-4 px-2 w-full">
                    <button @click="handleSubmit" class="border px-2 mt-3 ml-auto mr-7 py-1 rounded bg-green-500 text-white font-medium">
                        {{ Helper.translate('Save') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import CInput from '@/Components/Global/CInput.vue'
import CSelect from '@/Components/Global/CSelect.vue'
import { useForm, usePage } from '@inertiajs/inertia-vue3'
import { computed, onMounted, ref } from 'vue'
import Helper from '@/Helper'

const dateFormats = [
    {
        key: '',
        value: '-select-'
    },
    {
        key: 'MMM Do, YYYY',
        value: 'ex: Feb 5th, 2021'
    },
    {
        key: 'MMMM Do YYYY',
        value: 'ex: February 5th 2021'
    },
    {
        key: 'LL',
        value: 'ex: February 5, 2021'
    },
    {
        key: 'll',
        value: 'ex: Feb 5, 2021'
    },
    {
        key: 'MMM Do YY',
        value: 'ex: Feb 5th 21'
    },
]

const currencyPosition = [
    {
        key: '',
        value: '-select-'
    },
    {
        key: 'left',
        value: 'Left'
    },
    {
        key: 'right',
        value: 'Right'
    },
]

const page = usePage();

const form = useForm({
    logo: '',
    companyName: '',
    commission: '',
    request_duration_days: '',
    maintenance_charge: '',
    dateFormate: '',
    currencyPosition: '',
    stripePublicKey: '',
    stripePrivatKey: '',
    security_code: '',
})


const settings = computed(() => {
    return usePage().props.value.settings;
});

const assignValue = () => {
    let settings = usePage().props.value.settings;
    if (settings) {
        form.logo = settings.logo;
        form.companyName = settings.companyName;
        form.commission = settings.commission;
        form.request_duration_days = settings.request_duration_days;
        form.maintenance_charge = settings.maintenance_charge;
        form.dateFormate = settings.dateFormate;
        form.currencyPosition = settings.currencyPosition;
        form.stripePublicKey = settings.stripePublicKey;
        form.stripePrivatKey = settings.stripePrivatKey;
    }
}

onMounted(()=> {
    assignValue()
});

const handleSubmit = () => {
    form.post(route('admin.saveSettings'), {
        onSuccess(){
            assignValue()
        }
    })
}

</script>

<style lang="scss" scoped>

</style>