<template>
    <div class="calendar flex flex-col relative h-[402px]">
        <span v-if="profileForm.processing" class="price cursor-pointer absolute top-4 z-10 right-4 shadow bg-sky-500 font-bold p-2 rounded text-white">
            <Preloader class="w-5 h-5" />
        </span>
        <label class="price absolute top-4 right-4 shadow bg-sky-500 font-bold p-2 rounded z-20 text-white cursor-pointer">
            <CameraIcon />
            <input type="file" @input="e => $emit('oninput', e)" hidden accept="image/*" />
        </label>
        <img
            v-if="get($page.props.auth, 'user.profile_image')"
            class="block w-full h-full object-cover object-center customRatio"
            :src="`${$page.props.asset}${get($page.props.auth, 'user.profile_image')}`"
        />
        <div v-if="!get($page.props.auth, 'user.profile_image')" class="z-10 w-full h-full top-0 left-0 text-[#878787] cursor-pointe flex items-center justify-center select-none pointer-events-none bg-black font-bold">
            {{ Helper.translate('Add picture') }}
        </div>
    </div>
</template>

<script setup>
import Helper from "@/Helper"
import CameraIcon from '@/Icons/CameraIcon.vue'
import Preloader from '@/Components/Global/Preloader.vue'

import { get } from 'lodash' 

defineProps({
    profileForm: Object
})
</script>
