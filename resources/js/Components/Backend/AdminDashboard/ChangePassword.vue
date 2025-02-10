<template>
    <h2 class="font-semibold mb-2">{{ Helper.translate('Change Password') }}</h2>
    <form class="mt-5" @submit.prevent="handleSubmit">
        <div class="relative mb-6">
            <CInput v-model="form.current_password" :placeholder="Helper.translate('Current Password')" />
            <span class="absolute top-full left-0 text-xs text-red-500">{{ Helper.translate(form.errors.current_password, true) }}</span>
        </div>
        <div class="relative mb-6">
            <CInput type="password" v-model="form.password" :placeholder="Helper.translate('New Password')" />
            
        </div>
        <div class="relative mb-6">
            <CInput type="password" v-model="form.password_confirmation" :placeholder="Helper.translate('Confirm Password')" />
            <span class="absolute top-full left-0 text-xs text-red-500">{{ Helper.translate(form.errors.password, true) }}</span>
        </div>
        <button type="submit" :disabled="form.processing" class="px-4 py-1 rounded bg-green-500 text-white font-semibold mt-5 ml-auto block">
            {{ Helper.translate('Make Change') }}
        </button>
    </form>
</template>

<script setup>
import CInput from '@/Components/Global/CInput.vue';
import { useForm } from '@inertiajs/inertia-vue3';
import Helper from '@/Helper';
import { isEmpty } from 'lodash';

const form = useForm({
    current_password: null,
    password: null,
    password_confirmation: null,
});

const handleSubmit = () => {
    form.put(route('password.update'), {
        onSuccess(e) {
            if(isEmpty(e.props.errors)) {
                form.reset();
            }
        }
    });
}

</script>

<style lang="scss" scoped>

</style>