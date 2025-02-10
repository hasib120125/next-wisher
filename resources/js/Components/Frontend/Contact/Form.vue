<template>
<div>
    <div class="relative">
        <CSelect type="text" :options="reason" v-model="form.reason" placeholder="Reason" class="mb-6 border-0 p-0" />
        <span class="absolute top-full left-0 text-xs text-red-500 mt-[2px]" v-if="form.errors.reason">
            {{ Helper.translate(form.errors.reason, true) }}
        </span>
    </div>
    <div class="relative">
        <CInput type="text" v-model="form.name" placeholder="Name" class="mb-6" />
        <span class="absolute top-full left-0 text-xs text-red-500 mt-[2px]" v-if="form.errors.name">
            {{ Helper.translate(form.errors.name, true) }}
        </span>
    </div>
    <div class="relative">
        <CInput type="text" v-model="form.subject" placeholder="Subject" class="mb-6" />
        <span v-if="form.errors.subject" class="absolute top-full left-0 text-xs text-red-500 mt-[2px]">
            {{ Helper.translate(form.errors.subject, true) }}
        </span>
    </div>
    <div class="relative">
        <CInput type="email" v-model="form.email" placeholder="Email" class="mb-6" />
        <span v-if="form.errors.email" class="absolute top-full left-0 text-xs text-red-500 mt-[2px]">
            {{ Helper.translate(form.errors.email, true) }}
        </span>
    </div>
    <div class="relative">
        <CTextarea v-model="form.message" placeholder="Message" class="mb-6" />
        <span v-if="form.errors.message" class="absolute top-full left-0 text-xs text-red-500 mt-[2px]">
            {{ Helper.translate(form.errors.message, true) }}
        </span>
    </div>

    <div class="mt-8">
        <button @click="handleSave" :disabled="form.processing" class="uppercase flex items-center justify-center gap-2 disabled:opacity-50 text-sm font-bold tracking-wide bg-green-500 text-gray-100 p-3 rounded w-full hover:shadow">
            <Preloader v-if="form.processing" class="w-5 h-5" />
            {{ Helper.translate('Send Message') }}
        </button>
    </div>
</div>
</template>

<script setup>
import CInput from '@/Components/Global/CInput.vue'
import CTextarea from '@/Components/Global/CTextarea.vue'
import { useForm } from '@inertiajs/inertia-vue3'
import Helper from '@/Helper';
import CSelect from '@/Components/Global/CSelect.vue';
import Preloader from '@/Components/Global/Preloader.vue';

const form = useForm({
    name: null,
    subject: null,
    email: null,
    message: null,
    reason: '',
})
const reason = [
    {
        key: '',
        value: Helper.translate('-Select reason for contact-')
    },
    {
        key: 'General Inquiry',
        value: Helper.translate('General Inquiry')
    },
    {
        key: 'Refund status (users-only for wish requests not fulfilled on time)',
        value: Helper.translate('Refund status (users-only for wish requests not fulfilled on time)')
    },
    {
        key: 'Payout (talents only)',
        value: Helper.translate('Payout (talents only)')
    },
    {
        key: 'Talents related inquiry',
        value: Helper.translate('Talents related inquiry')
    },
    {
        key: 'Users related inquiry',
        value: Helper.translate('Users related inquiry')
    },
]

const handleSave = () => {
    form.post(route('contact.store'), {
        onSuccess() {
            form.reset()
        }
    })
}

</script>

<style lang="scss" scoped>

</style>