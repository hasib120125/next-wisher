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
                type="email"
                v-model="form.email"
                placeholder="Interac registered email"
            />
            <span class="absolute top-full left-0 text-xs text-red-500">
                {{ Helper.translate(form.errors.email, true) }}
            </span>
        </div>
        <div class="relative mb-6">
            <CInput 
                type="email"
                v-model="confirmEmail"
                placeholder="Confirm interac registered email"
            />
        </div>
        <button @click="handleSave" class="bg-green-500 text-white px-4 py-2 rounded block ml-auto mt-5 font-semibold">
            {{ Helper.translate('Confirm') }}
        </button>
    </div>
</template>
<script setup>
import { ref } from 'vue'
import Helper from '@/Helper';
import { useForm } from '@inertiajs/inertia-vue3';
import CInput from '@/Components/Global/CInput.vue';
import { isEmpty } from 'lodash'

const form = useForm({
    bank_type: 'bank',
    full_name: null,
    email: null,
    amount: null,
})

const confirmEmail = ref('');

const emit = defineEmits(['close'])
const handleSave = () => {
    if (!confirmEmail.value || confirmEmail.value != form.email) {
        form.errors.email = 'Confirmation email does not match';
        return;
    }
    form.post(route('talent.bankPayoutCanada'), {
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