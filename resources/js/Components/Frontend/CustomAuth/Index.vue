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
                @click="templateName='SignOption'"
                :class="templateName == 'SignOption' ? 'opacity-100' : 'opacity-50'"
            >
                {{ Helper.translate('Sign up') }}
            </button>
            <!-- <button 
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
            </button> -->
        </div>

        <div class="flex justify-between flex-wrap items-center mt-5">
            <h1 class="font-bold">{{ Helper.translate(componentList[templateName].title, true) }}</h1>
            <!-- <button
                v-if="templateName == 'TalentSignup'"
                @click="() => {
                    instructionOpen=true
                }"
                class="text-[#137aff]"
            >
                {{ Helper.translate('Instructions') }}
            </button> -->
        </div>

        <component 
            @login="templateName='SignIn'" 
            @register="templateName='SignUp'" 
            @forgotPassword="templateName='ForgotPassword'"
            @setComponent="name => templateName=name"
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
    <div 
        v-if="instructionOpen"
        class="fixed inset-0 flex justify-center items-center bg-black/20 backdrop-blur z-[9000]"
        @click.self="instructionOpen=false"
    >
        <div class="bg-white h-[calc(100%-40px)] max-w-[calc(100vw-20px)] md:max-w-[600px] lg:max-w-[800px] flex flex-col rounded text-black">
            <div class="py-2 px-4 border-b flex justify-between items-center gap-4 text-2xl">
                {{ Helper.translate('Instructions') }}
                <button 
                    @click="instructionOpen=false"
                    class="w-10 h-10 grid place-content-center rounded-full bg-red-200 text-red-600"
                >
                    &times;
                </button>
            </div>
            <div class="py-2 px-4 flex-1 overflow-y-auto">
                <template
                    v-for="i in 6"
                    :key="i"
                >
                    <img
                        :src="`/${i}.jpg`"
                        class="block w-full"
                    />
                </template>
            </div>
        </div>
    </div>
</template>

<script setup>
    import Helper, { showAuthModal } from '@/Helper'
    import { onMounted, ref } from 'vue'
    import { componentList, templateName } from '@/Components/Frontend/CustomAuth/useCustomAuth.js'

    const instructionOpen = ref(false)

    onMounted(async () => {
        let params = new URLSearchParams(location.search);
        let modalParam = params.get('modal');

        if(modalParam == 'login') {
            showAuthModal.value = true;
            templateName.value = 'SignIn'
        }
    })
</script>