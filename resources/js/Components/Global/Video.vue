<template>
    <div class="relative hover:shadow-xl transition-all text-white h-full select-none">
        <!-- <IconPlay @click="handlePlay" v-if="!videoSrc && playIcon" class="absolute z-20 left-1/2 top-1/2 w-12 h-12 transform -translate-x-1/2 -translate-y-1/2 cursor-pointer drop-shadow-lg" /> -->
        <IconPlay @click="handlePlay" class="absolute z-20 left-1/2 top-1/2 w-12 h-12 transform -translate-x-1/2 -translate-y-1/2 cursor-pointer drop-shadow-lg" />
        <LockIcon v-if="lock" class="absolute right-4 top-4 w-6 h-6 cursor-pointer drop-shadow-lg" />
        
        <a 
            v-if="download"
            :href="$page.props.asset+poster" 
            download
            class="absolute right-4 top-4 cursor-pointer drop-shadow-lg bg-red-500"
        >
            <DownloadIcon class="absolute right-4 top-4 w-6 h-6 cursor-pointer drop-shadow-lg" />
        </a>
        <img v-if="hasCover" 
            class="customRatio object-cover object-center w-full h-full cursor-pointer" 
            :src="getImage(cover, $page.props.asset)" alt=""
        >

        <!-- <video v-if="playIcon && videoSrc" ref="videoRef" class="w-full customRatio bg-black" :src="videoSrc" controls></video> -->
        <button v-if="deletable" class="absolute left-1/2 bottom-4 transform -translate-x-1/2 cursor-pointer p-2 rounded-full transition-all bg-red-400 hover:shadow-lg hover:bg-red-500 hover:bottom-5">
            <CloseIcon class="w-4 h-4" />
        </button>
    </div>
    <Modal v-model="showVideoModal">
        <div class="relative">
            <video 
                v-if="playIcon && videoSrc" 
                ref="videoRef" 
                class="h-[70vh] bg-black" 
                :src="videoSrc" 
                autoplay="false" 
                volume="0.1"
                controls
            ></video>
            <button @click="showVideoModal = false" class="absolute w-6 h-6 rounded-full right-1 top-1 cursor-pointer z-5 bg-red-500 text-white p-1">
                <CloseIcon class="w-4 h-4" />
            </button>
        </div>
    </Modal>
</template>

<script setup>
import IconPlay from '@/Components/Global/Icons/IconPlay.vue'
import LockIcon from '@/Components/Global/Icons/LockIcon.vue'
import CloseIcon from '@/Icons/CloseIcon.vue'
import DownloadIcon from '@/Icons/DownloadIcon.vue'
import { usePage } from '@inertiajs/inertia-vue3'
import { onMounted, ref, watch } from 'vue'
import NoImageFound from '@/assets/no-image-found.webp'
import Modal from '../Library/Modal.vue'


const props = defineProps({
    poster: String,
    cover: String,
    load: {
        type: Boolean,
        default: false,
    },
    hasCover: {
        type: Boolean,
        default: true,
    },
    playIcon: {
        type: Boolean,
        default: true,
    },
    lock: {
        type: Boolean,
        default: false
    },
    control: {
        type: Boolean,
        default: false,
    },
    download: {
        type: Boolean,
        default: false
    },
    isDisabled: {
        type: Boolean,
        default: false
    },
    deletable: {
        type: Boolean,
        default: false
    }
})

const getImage = (cover, asset) => {
    return cover ? `${asset}${cover}` : NoImageFound
}

const showVideoModal = ref(false)
const videoRef = ref()
const videoSrc = ref('')
const playStatus = ref(false)

onMounted(()=>{
    if (props.load && videoSrc.value) {
        videoSrc.value = `${usePage().props.value.asset}${props.poster}`
        videoSrc.value.pause()
    }
})

const handlePlay = () => {
    showVideoModal.value = true
    if (props.lock || props.isDisabled) {
        return
    }
    videoSrc.value = `${usePage().props.value.asset}${props.poster}`
    if (videoRef.value) {
        if (videoRef.value.paused) {
            playStatus.value = true;
            videoRef.value.play()
        } else {
            playStatus.value = false;
            videoRef.value.pause()
        }
    }
}

watch(showVideoModal, () => !showVideoModal.value && videoRef.value.pause())
</script>