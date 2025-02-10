<template>
    <div class="bg-white sm:w-[600px] p-5 md:p-10 shadow text-gray-800 md:text-[16px] text-[14px]">
        <div class="font-semibold flex md:gap-10 gap-4 items-center pb-3 mb-3 border-b">
            <button 
                @click="templateName='SignIn'"
                :class="templateName == 'SignIn' ? 'opacity-100' : 'opacity-50'"
            >
                {{ Helper.translate('Login') }}
            </button>
            <button 
                @click="templateName='SignUp'"
                :class="templateName == 'SignUp' ? 'opacity-100' : 'opacity-50'"
            >
                {{ Helper.translate('Sign up') }}
            </button>
            <button 
                @click="templateName='TalentSignup'"
                :class="templateName == 'TalentSignup' ? 'opacity-100' : 'opacity-50'"
            >
                {{ Helper.translate('Become a Talent') }}
            </button>
        </div>

        <h1 class="font-bold mt-5">{{ Helper.translate(componentList[templateName].title, true) }}</h1>

        <component 
            @login="templateName='SignIn'" 
            @register="templateName='SignUp'" 
            @forgotPassword="templateName='ForgotPassword'"
            @close="()=> {
                $emit('close');
                showAuthModal=false
            }"
            :is="componentList[templateName].component" 
        />

        <div class="flex justify-between gap-3 items-center">
            <button 
                class="bg-black text-white px-4 sm:py-2 py-1" 
                @click="() => {
                    $emit('close');
                    showAuthModal=false
                }
            ">
                {{ Helper.translate('Close') }}
            </button>
        </div>
    </div>
</template>

<script setup>
    import Helper, { showAuthModal } from '@/Helper'
    import { onMounted } from 'vue'
    import { componentList, templateName } from '@/Components/Frontend/CustomAuth/useCustomAuth.js'
   
    onMounted(() => {
        let params = new URLSearchParams(location.search);
        let modalParam = params.get('modal');

        if(modalParam == 'login') {
            showAuthModal.value = true;
            templateName.value = 'SignIn'
        }
    })
</script>