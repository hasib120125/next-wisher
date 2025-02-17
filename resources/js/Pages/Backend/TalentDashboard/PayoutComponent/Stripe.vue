<template>
    <div>
        <!-- <h2 class="text-lg font-semibold mt-6 mb-4 text-red-500 text-center">{{ Helper.translate('Minimum Payout') }} {{ Helper.priceFormate(50) }}</h2> -->
        <div class="relative mb-6">
            <CInput 
                type="number" 
                v-model="form.amount"
                step="0.01"
                placeholder="Enter Payout Amount" 
            />
            <span class="absolute top-full left-0 text-xs text-red-500">{{ Helper.translate(form.errors.amount, true) }}</span>
        </div>
        <div class="relative mb-6">
            <CInput type="email" v-model="form.stripe_email" placeholder="Payoneer Email" />
            <span class="absolute top-full left-0 text-xs text-red-500">{{ Helper.translate(form.errors.stripe_email, true) }}</span>
        </div>
        <div class="relative mb-6">
            <CInput type="email" v-model="confirmEmail" placeholder="Confirm Payoneer Email" />
        </div>
        <button @click="handleSave" class="bg-green-500 text-white px-4 py-2 rounded block ml-auto mt-5 font-semibold">
            {{ Helper.translate('Confirm') }}
        </button>
    </div>
</template>

<script setup>
import CInput from '@/Components/Global/CInput.vue'
import { useForm } from '@inertiajs/inertia-vue3';
import Helper from '@/Helper';
import { ref } from 'vue';
import { isEmpty } from 'lodash';

const form = useForm({
    bank_type: 'Payoneer',
    amount: null,
    stripe_email: null
});

const confirmEmail = ref();
const emit = defineEmits(['close'])
const handleSave = () => {
    if (confirmEmail.value != form.stripe_email) {
        form.errors.stripe_email = 'Confirmation email does not match';
        return;
    }

    form.post(route('talent.payoutRequest'), {
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