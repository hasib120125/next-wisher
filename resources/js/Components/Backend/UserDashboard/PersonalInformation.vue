<template>
    <div class="md:mb-0 mb-6">
        <h1 class="font-semibold text-xl mb-4">{{ Helper.translate('Personal Information') }}</h1>
        <div class="flex md:flex-nowrap flex-wrap gap-5 items-end">
            <div class="relative w-full">
                <CInput type="text" v-model="form.name" :placeholder="Helper.translate('Name')"/>
                <span class="absolute top-full left-0 text-xs text-red-500">{{ Helper.translate(form.errors.name, true) }}</span>
            </div>
            <CInput disabled="false" type="email" v-model="$page.props.auth.user.email" class="w-full" />
            <button @click="handleSave" class="bg-red-500 shrink-0 text-white text-bold rounded shadow px-4 py-2 whitespace-nowrap md:ml-0 ml-auto">
                {{ Helper.translate('Update') }} / {{ Helper.translate('Save') }}
            </button>
        </div>
    </div>
</template>

<script setup>
import CInput from '@/Components/Global/CInput.vue'
import { useForm } from '@inertiajs/inertia-vue3'
import Helper from '@/Helper'

const props = defineProps({
    user: Object
})
const form = useForm({
    name: props.user.name
})
const handleSave = () => {
    form.put(route('user.account.update'))
}
</script>

<style scoped>
.usernameAndEmailWrapper{
    grid-template-columns: 1fr 1fr 1fr;
}
</style>