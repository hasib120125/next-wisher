<template>
    <div>
        <!-- <h2 class="text-lg font-semibold mt-6 mb-4 text-red-500 text-center">{{ Helper.translate(`Minimum Payout ${Helper.priceFormate(50)}`) }}</h2> -->
        <div class="relative mb-6">
            <CInput v-model="form.amount" type="number" placeholder="Enter Payout Amount" />
            <span class="absolute top-full left-0 text-xs text-red-500">{{ Helper.translate(form.errors.amount, true) }}</span>
        </div>
        <div class="relative mb-6">
            <CInput v-model="form.stripe_email" type="text" placeholder="Paypal Email" />
            <span class="absolute top-full left-0 text-xs text-red-500">{{ Helper.translate(form.errors.stripe_email, true) }}</span>
        </div>
        <div class="relative mb-6">
            <CInput v-model="confirmEmail" type="text" placeholder="Confirm Paypal Email" />
        </div>
        <button @click="handleSave" class="bg-green-500 text-white px-4 py-2 rounded block ml-auto mt-5 font-semibold">
            {{ Helper.translate('Confirm') }}
        </button>
    </div>
</template>

<script setup>
import CInput from '@/Components/Global/CInput.vue'
import { ref } from 'vue';
import { isEmpty } from 'lodash';
import { useForm } from '@inertiajs/inertia-vue3';
import Helper from '@/Helper';

const form = useForm({
    bank_type: 'Paypal',
    amount: null,
    stripe_email: null,
    account_number: null
})

const emit = defineEmits(['close'])

const confirmEmail = ref(null);
const handleSave = () => { 
    if (confirmEmail.value != form.stripe_email) {
        form.errors.stripe_email = 'Confirmation email does not match';
        return;
    }
    form.post(route('talent.payoutRequest'), {
        onSuccess(page){
            if (isEmpty(page.props.errors)) {
                form.reset(); 
                emit('close')
            }
        }
    });
}

</script>