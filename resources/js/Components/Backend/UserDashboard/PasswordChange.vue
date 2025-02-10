<template>
    <div>
        <h1 class="font-semibold text-xl mb-4">
            {{ Helper.translate('Change Password') }}
        </h1>
        <div class="grid usernameAndEmailWrapper items-center gap-5 flex-wrap">
            <div class="relative mb-6">
                <CInput v-model="form.current_password" type="password" :placeholder="Helper.translate('Old Password')"/>
                <span class="absolute top-full left-0 text-xs text-red-500">{{ Helper.translate(form.errors.current_password, true) }}</span>
            </div>
            <div class="relative mb-6">
                <CInput v-model="form.password" type="password" :placeholder="Helper.translate('New Password')"/>
            </div>
            <div class="relative mb-6">
                <CInput v-model="form.password_confirmation" type="password" :placeholder="Helper.translate('Confirm Password')"/>
                <span class="absolute top-full left-0 text-xs text-red-500">{{ Helper.translate(form.errors.password, true) }}</span>
            </div>
        </div>
        <button @click="handleSave" class="bg-red-500 shrink-0 text-white text-bold rounded shadow px-4 py-2 whitespace-nowrap block w-fit ml-auto mt-4">
            {{ Helper.translate('Update') }} / {{ Helper.translate('Save') }}
        </button>
    </div>
</template>

<script setup>
import CInput from '@/Components/Global/CInput.vue'
import { useForm } from '@inertiajs/inertia-vue3'
import Helper from '@/Helper';
import { ref } from 'vue'
import { isEmpty, get } from 'lodash';

const form = useForm({
    current_password: null,
    password: null,
    password_confirmation: null,
});

const handleSave = () => {
    if (form.current_password == form.password) {
        form.errors.current_password = Helper.translate('Old password cannot be new password')
        return;
    }

    form.put(route('user.account.update'), {
        onSuccess(e) {
            if (isEmpty(e.props.errors)) {
                form.reset();
            }
        }
    })
}
</script>

<style scoped>
.usernameAndEmailWrapper{
    grid-template-columns: 1fr 1fr 1fr;
}
@media all and (max-width: 1200px){
    .usernameAndEmailWrapper{
        grid-template-columns: 1fr;
    }
}
</style>