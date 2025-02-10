<template>
    <Head :title="Helper.translate('Home')" />
    <Master>

        <div class="flex relative items-center justify-center w-full h-full pt-12">

            <img :src="ComingSoon" alt="" class="w-full h-full object-cover opacity-50 absolute -z-10" />
            
            <div class="w-full max-w-[700px] z-10">
                <div class="grid text-[8rem] text-slate-700 text-center tracking-widest leading-[8rem] mb-10 font-extrabold">
                    <span>{{ Helper.translate('COMING SOON') }}</span>
                </div>
                <div class="text-center font-semibold text-4xl">
                    {{ Helper.translate('We will be celebrating the launch of our new site very soon!') }}
                </div>
                <div class="flex flex-col items-center gap-4 justify-center mt-10">
                    <button @click="showAuthModal=true" class="py-4 px-10 bg-emerald-500 text-white">{{ Helper.translate('Notify Me') }}!</button>
                    <button @click="languagePopup=true" class="py-2 px-5 border border-emerald-500 text-emerald-500">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 256 256"><path d="M128,20A108,108,0,1,0,236,128,108.12,108.12,0,0,0,128,20ZM107.07,172h41.86A115.75,115.75,0,0,1,128,209.85,115.75,115.75,0,0,1,107.07,172Zm-5.66-24a142.55,142.55,0,0,1,0-40h53.18a142.55,142.55,0,0,1,0,40ZM44,128a83.49,83.49,0,0,1,2.43-20H77.22a164.54,164.54,0,0,0,0,40H46.43A83.49,83.49,0,0,1,44,128ZM148.93,84H107.07A115.75,115.75,0,0,1,128,46.15,115.75,115.75,0,0,1,148.93,84Zm29.85,24h30.79a83.52,83.52,0,0,1,0,40H178.78a164.54,164.54,0,0,0,0-40Zm20.74-24H174a148.59,148.59,0,0,0-13.95-33.63A84.5,84.5,0,0,1,199.52,84ZM96,50.37A148.59,148.59,0,0,0,82,84H56.48A84.5,84.5,0,0,1,96,50.37ZM56.48,172H82a148.59,148.59,0,0,0,14,33.63A84.5,84.5,0,0,1,56.48,172Zm103.57,33.63A148.59,148.59,0,0,0,174,172h25.52A84.5,84.5,0,0,1,160.05,205.63Z"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        <Modal v-model="languagePopup" class="flex items-center justify-center p-4">
            <LanguageChange @close="languagePopup = false" />
        </Modal>

        <Modal v-model="showAuthModal">
            <CustomAuth class="z-[100]" />
        </Modal>

    </Master>
</template>

<script setup>
import Master from '../Backend/Master.vue'
import CustomAuth from '@/Components/Frontend/CustomAuth/Index.vue'
import { Head } from '@inertiajs/inertia-vue3'
import Modal from '@/Components/Library/Modal.vue'
import LanguageChange from '@/Components/Global/LanguageChange.vue'
import ComingSoon from '@/assets/coming_soon_pattern.svg'
import Helper, { languagePopup, showAuthModal } from '@/Helper'

</script>