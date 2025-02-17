<template>
    <div>
        <div class="relative mb-1 text-slate-600">
            {{ Helper.translate('View the list to see if your country is listed') }}
        </div>
        <div class="relative mb-6">
            <CInput
                type="number"
                v-model="form.amount"
                step="0.01"
                placeholder="Enter Payout Amount"
            />
            <span class="absolute top-full left-0 text-xs text-red-500">
                {{ Helper.translate(form.errors.amount, true) }}
            </span>
        </div>
        <div class="relative mb-6">
            <CInput
                type="text"
                v-model="form.full_name"
                placeholder="Full name of the account holder"
            />
            <span class="absolute top-full left-0 text-xs text-red-500">
                {{ Helper.translate(form.errors.full_name, true) }}
            </span>
        </div>
    
        <div class="relative mb-6">
            <CInput
                type="text"
                v-model="form.swift"
                placeholder="SWIFT / BIC code"
            />
            <span class="absolute top-full left-0 text-xs text-red-500">
                {{ Helper.translate(form.errors.swift, true) }}
            </span>
        </div>
        <div class="relative mb-6">
            <CInput
                type="text"
                v-model="form.account_number"
                placeholder="Account number"
            />
            <span class="absolute top-full left-0 text-xs text-red-500">
                {{ Helper.translate(form.errors.account_number, true) }}
            </span>
        </div>
        <div class="relative mb-6">
            <CInput 
                type="text"
                v-model="confirmEmail"
                placeholder="Confirm account number"
            />
        </div>
        <button @click="handleSave" class=" bg-green-500 text-white px-4 py-2 rounded flex items-center gap-2 ml-auto mt-5 font-semibold">
            <Preloader v-if="form.processing" class="w-4 h-4" />
            {{ Helper.translate('Confirm') }}
        </button>
    </div>
</template>
<script setup>
import { ref } from 'vue'
import Helper from '@/Helper';
import { useForm } from '@inertiajs/inertia-vue3';
import CInput from '@/Components/Global/CInput.vue';
import Preloader from '@/Components/Global/Preloader.vue';
import { isEmpty } from 'lodash'

const form = useForm({
    bank_type: 'bank',
    full_name: null,
    account_number: null,
    swift: null,
    amount: null,
})

const confirmEmail = ref('');

const emit = defineEmits(['close'])

const handleSave = () => {
    if (!confirmEmail.value || confirmEmail.value != form.account_number) {
        form.errors.account_number = 'Confirmation account number does not match';
        return;
    }

    form.post(route('talent.bankPayoutOutside'), {
        onSuccess(page){
            if (isEmpty(page.props.errors)) {
                form.reset();
                confirmEmail.value = null;
                emit('close')
            }
        }
    });
}

</script>