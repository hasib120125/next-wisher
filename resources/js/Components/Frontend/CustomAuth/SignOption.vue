<template>
    <div class="py-4">
        <div class="font-semibold text-center text-lg">
            {{ Helper.translate('Create your account as a') }}:
        </div>
        <div class="text-center grid gap-4 mt-3">
            <div>
                {{ Helper.translate('A user can place order or request from celebrities/talents') }}:
            </div>
            <div>
                {{ Helper.translate('A celebrity/talent can earn by fulfilling requests from users/fans') }}:
            </div>
        </div>
        <div
            class="flex justify-between max-w-[300px] mx-auto gap-4 mt-4 mb-5"
        >
            <button 
                @click="$emit('setComponent', 'SignUp')"
                class="py-2 px-8 bg-[#00a1ff] text-white rounded-full"
            >
                {{ Helper.translate('User') }}
            </button>
            <button 
                @click="$emit('setComponent', 'TalentSignup')"
                class="py-2 px-8 bg-[#ee0b62] text-white rounded-full"
            >
                {{ Helper.translate('Talent') }}
            </button>
        </div>
    </div>
</template>

<script setup>
import Helper from '@/Helper'
</script>