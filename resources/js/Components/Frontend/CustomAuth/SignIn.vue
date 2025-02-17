<template>
    <div class="py-6">
        <form @submit.prevent="submit">
            <div class="relative">
                <CInput type="email" placeholder="Email" v-model="form.email" class="mb-6" />
                <span class="absolute top-full left-0 text-xs text-red-500 mt-[2px]">
                    {{ Helper.translate(form.errors.email, true) }}
                </span>
            </div>
            <div class="relative">
                <CInput type="password" placeholder="Password" v-model="form.password" class="mb-6" />
                <span class="absolute top-full left-0 text-xs text-red-500 mt-[2px]">
                    {{ Helper.translate(form.errors.password, true) }}
                </span>
            </div>
            <!-- <label class="relative block mb-4">
                <Checkbox name="remember" v-model:checked="form.remember" />
                <span class="ml-2 text-sm text-gray-600">
                    {{ Helper.translate('Remember me') }}
                </span>
            </label> -->

            <button type="button" @click="$emit('forgotPassword')" class="block opacity-70 hover:opacity-100 hover:text-[var(--link-color)]">
                {{ Helper.translate('Forgot password?') }}
            </button>
            <button
                type="submit"
                class=" bg-[var(--btn-bg-color)] text-[var(--btn-text-color)] rounded-full px-5 md:py-2 py-1 shadow ml-auto flex items-center gap-1"
                :class="!!form.processing && 'pointer-events-none opacity-70'"
            >
                <Preloader class="w-4 h-4" v-if="!!form.processing" />
                {{ Helper.translate('Sign in') }}
            </button>
        </form>
        <!-- <div>
            {{ Helper.translate('Don\'t have account ?') }}
            <button @click="$emit('register')" class="text-[var(--link-color)]">
                {{ Helper.translate('Sign up') }}
            </button>
        </div> -->
    </div>
</template>


<script setup>
import { isEmpty } from 'lodash'
import CInput from '@/Components/Global/CInput.vue'
import { useForm } from '@inertiajs/inertia-vue3'
import Checkbox from '@/Components/Global/Checkbox.vue'
import Helper, { showAuthModal } from '@/Helper'
import Preloader from '@/Components/Global/Preloader.vue'

defineProps({
    canResetPassword: Boolean,
    status: String,
})

const form = useForm({
    email: '',
    password: '',
    remember: false,
})

const emit = defineEmits('close')
const submit = () => {
    form.post(route('login'), {
        onFinish: () => {
            form.reset('password')
            if(isEmpty(form.errors)){
                showAuthModal.value = false;
                emit('close')
            }
        }
    })
}
</script>