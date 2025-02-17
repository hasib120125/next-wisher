<template>
    <div>
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
                v-model="form.iban"
                placeholder="IBAN"
            />
            <span class="absolute top-full left-0 text-xs text-red-500">
                {{ Helper.translate(form.errors.iban, true) }}
            </span>
        </div>
        <div class="relative mb-6">
            <CInput 
                type="text"
                v-model="confirmEmail"
                placeholder="Confirm IBAN"
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
    iban: null,
    amount: null,
})

const confirmEmail = ref('');

const emit = defineEmits(['close'])
const handleSave = () => {
    if (!confirmEmail.value || confirmEmail.value != form.iban) {
        form.errors.iban = 'Confirmation IBAN does not match';
        return;
    }
    form.post(route('talent.bankPayoutEurope'), {
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