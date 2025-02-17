<template>
    <h1 class="font-semibold text-xl mb-4">
        {{ Helper.translate('Change Password') }}
    </h1>
    <div class="relative mb-6">
        <CInput type="password" v-model="form.old_password" placeholder="Old Password" />
        <span class="absolute top-full left-0 text-xs text-red-500">{{ Helper.translate(form.errors.old_password, true) }}</span>
    </div>
    <div class="relative mb-6">
        <CInput type="password" v-model="form.password" placeholder="New Password" />
    </div>
    <div class="relative mb-6">
        <CInput type="password" v-model="form.password_confirmation" placeholder="Confirm Password" />
        <span class="absolute top-full left-0 text-xs text-red-500">{{ Helper.translate(form.errors.password, true) }}</span>
    </div>
    <button @click="handleChange" class="bg-red-500 px-4 py-1 rounded text-white font-bold mt-4 ml-auto block">
        {{ Helper.translate('Change') }}
    </button>
</template>

<script setup>
import CInput from '@/Components/Global/CInput.vue'
import { useForm } from '@inertiajs/inertia-vue3'
import { isEmpty } from 'lodash'
import Helper from '@/Helper'

const form = useForm({
    old_password: null,
    password: null,
    password_confirmation: null,
})

const handleChange = () => {
    if (form.old_password == form.password) {
        form.errors.old_password = Helper.translate('Old password cannot be new password')
        return;
    }
    form.put(route('talent.account.update'), {
        onSuccess(e) {
            if (isEmpty(e.props.errors)) {
                form.reset();
            }
        }
    })
}
</script>

<style lang="scss" scoped>

</style>