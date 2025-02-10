<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import Helper from '@/Helper';


const form = useForm({
    security_code: ''
});

const submit = () => {
    form.post(route('securityCodeSubmit'));
};

</script>

<template>
    <GuestLayout>
        <Head :title="Helper.translate('Admin log in')" />

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="security_code" :value="Helper.translate('Enter security code')" />

                <TextInput
                    id="security_code"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.security_code"
                    required
                    autofocus
                />

                <InputError class="mt-2" :message="form.errors.security_code" />
            </div>


            <div class="flex items-center justify-end mt-4">
                <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    {{ Helper.translate('Submit') }}
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
