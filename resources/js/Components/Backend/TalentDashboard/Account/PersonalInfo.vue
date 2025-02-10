<template>
    <h1 class="font-semibold text-xl mb-4">{{ Helper.translate('Personal Information') }}</h1>
    <div class="relative mb-6">
        <CInput type="text" v-model="form.username" placeholder="Username" />
        <span class="absolute top-full left-0 text-xs text-red-500">{{ Helper.translate(form.errors.username, true) }}</span>
    </div>
    <div class="relative mb-6">
        <CInput type="email" v-model="form.email" disabled="true" placeholder="Email" />
        <span class="absolute top-full left-0 text-xs text-red-500">{{ Helper.translate(form.errors.last_name, true) }}</span>
    </div>
    <button @click="handleSave" class="bg-red-500 px-4 py-1 rounded text-white font-bold mt-4 ml-auto block">
        {{ Helper.translate('Update') }}
    </button>
</template>

<script setup>
import { useForm } from '@inertiajs/inertia-vue3'
import Helper from '@/Helper'
import CInput from '@/Components/Global/CInput.vue'

const props = defineProps({
    user: Object
})

const form = useForm({
    first_name: props.user.first_name,
    last_name: props.user.last_name,
    username: props.user.username,
    name: `${props.user.first_name} ${props.user.last_name}`,
    email: props.user.email
})

const handleSave = () => {
    form.put(route('talent.account.update'))
}
</script>