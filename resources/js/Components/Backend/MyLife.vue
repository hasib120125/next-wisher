<template>
    <div class="bg-white">
        <div class="relative">
            <!-- <img class="h-full w-full object-cover object-center block" :src="`${$page.props.asset}${get(user, 'cover_image')}`" /> -->
            <button v-if="closeable" @click="$emit('close')" class="w-8 h-8 text-white flex items-center justify-center rounded-full bg-red-500 absolute top-4 right-4">
                <CloseIcon class="w-4 h-4" />
            </button>
        </div>
        <div class="px-4">
            <div class="container mx-auto py-5">
                <div class="flex justify-between items-center" v-if="posts.length">
                    <button v-if="!closeable" @click="back" class="">
                        <AngleLeftIcon />
                    </button>
                    <div v-else></div>
                    <h1 class="text-xl font-semibold">{{ Helper.translate(posts.length, true) }} {{ Helper.translate('Videos') }}</h1>
                </div>
                <div class=""> 
                    <h2 class="text-center font-black text-2xl">{{ get(user, 'name') }}</h2> 
                    <button
                        v-if="closeable || Helper.isExpired(user.earnings)"
                        @click="closeable ? null : handleContinue(route('payment.mylife',{talentId: user.id}), earning?.amount)"
                        class="border-2 border-black text-xl rounded px-4 py-2 mx-auto mt-4 font-black block w-fit"
                    >
                        <template v-if="size(user.earnings)">
                            {{ Helper.translate('Renew') }} 
                        </template>
                        <template v-else>
                            {{ Helper.translate('Subscribe') }} 
                        </template>
                        {{ Helper.priceFormate(earning?.amount ? earning?.amount : 0)  }}/{{ Helper.translate('Year') }}
                    </button>

                    <div v-else class="grid items-center justify-center mt-4 gap-1">
                        <div
                            class="border-2 border-green-500 text-center text-xl rounded px-4 py-2 text-green-500 font-bold inline-block"
                        >
                            {{ Helper.translate('Subscribed') }}
                        </div>
                        <div class="font-semibold text-gray-500 text-sm">
                            {{ Helper.translate('Expired') }} :
                            {{ Helper.dateFormate(get(Helper.isExpired(user.earnings, true)[0], 'expire_date')) }}
                        </div>
                    </div>
                </div>

                <div class="px-4">
                    <div class="container mx-auto py-5">
                        <div class="grid grid-cols-4 gap-4">
                            <div 
                                @click="() => {
                                    if(!closeable && !Helper.isExpired(user.earnings)){
                                        videoSrc = item.path
                                        showVideoModal=true
                                    }
                                }" 
                                v-for="(item, index) in getPosts" 
                                :key="index" 
                                class="mb-6 cursor-pointer"
                            >
                                <div class="customRatio relative">
                                    <LockIcon 
                                        v-if="closeable || Helper.isExpired(user.earnings)"
                                        class="absolute top-1 right-1 text-white bg-red-500 p-1 rounded shadow" 
                                    />
                                    <img :src="`${$page.props.asset}${item.cover_image}`" alt="" class="w-full h-full object-cover object-center">
                                </div>
                                <h1 class="text-center truncate font-bold mt-2">{{ item.title }}</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <Modal v-model="showVideoModal" @click="stopVideo">
		<div class="w-full max-w-[800px] mx-auto rounded-xl text-white relative">
            <video 
                ref="videoRef" 
                :src="`${$page.props.asset}${videoSrc}`"
                autoplay="false" 
                volume="0.1"
                controls
                style="aspect-ratio: 16/9"
                class="w-full bg-black"
            ></video>
            <button @click="showVideoModal = false" class="absolute w-6 h-6 rounded-full right-1 top-1 cursor-pointer z-5 bg-red-500 text-white p-1">
                <CloseIcon class="w-4 h-4" />
            </button>
        </div>
    </Modal>
</template>

<script setup>
import Video from '@/Components/Global/Video.vue'
import CloseIcon from '@/Icons/CloseIcon.vue'
import { get, size } from 'lodash'
import Helper from '@/Helper'
import { Link } from '@inertiajs/inertia-vue3'
import { computed, ref } from 'vue'
import Modal from '../Library/Modal.vue'
import AngleLeftIcon from '@/Icons/AngleLeftIcon.vue'
import LockIcon from '../Global/Icons/LockIcon.vue'
import { Inertia } from '@inertiajs/inertia'

const props = defineProps({
    closeable: {
        type: Boolean,
        default: false
    },
    user: {
        type: Object,
        default: {},
    },
    earning: {
        type: Object,
        default: {},
    },
    posts: {
        type: Array,
        default: [],
    },
})
const showVideoModal = ref(false)
const back = () => {
    history.back()
}
const getPosts = computed(() => props.posts.reverse())
const videoSrc = ref('')
const videoRef = ref()
const stopVideo = () => {
    videoRef.value.pause()
}

const handleContinue = (url, amount) => {
    Helper.confirm(`
        ${Helper.translate('You will be charged')} ${Helper.translate(Helper.priceFormate(Number(amount) + (+Helper.getMaintenanceCharge(amount))))} </br> 
        ${Helper.translate(`${Helper.priceFormate(amount)} + ${Helper.translate(Helper.priceFormate(Helper.getMaintenanceCharge(amount)))} ${Helper.translate('Service Fee')}`)}
    `, () => {
        Inertia.get(url)
    })
}

</script>

<style scoped>
    .banner{
        height: 400px;
    }
</style>