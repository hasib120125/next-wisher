<template>
    <div v-if="src" @click.self="handleVideoPlayToggle" class="cursor-pointer relative h-full group bg-black">
        <span v-if="isLoading" class="w-8 h-8 absolute -ml-4 -mt-4 top-1/2 left-1/2 z-20 animate-spin">
            <svg class="w-8 h-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </span>
        <video
            ref="myVideo"
            @play="handlePlayState"
            @pause="handlePlayState"
            class="w-full absolute bottom-0 pointer-events-none"
            :muted="isMuted"
            @loadeddata="isLoading = false"
        >
            <source :src="src">
        </video>

        <button 
            v-if="!videoPlayToggle && !isLoading"
            @click="handleVideoPlayToggle"
            class="w-10 h-10 bg-white shadow z-10 flex items-center justify-center rounded-full absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2"
        >
            <svg class="w-6 h-6 ml-1" xmlns="http://www.w3.org/2000/svg" fill="#000" width="800" height="800" viewBox="0 0 100 100" xml:space="preserve">
                <path d="M76.982 50c0-.847-.474-1.575-1.167-1.957L26.541 19.595a2.23 2.23 0 0 0-1.279-.404 2.244 2.244 0 0 0-2.244 2.243c0 .087.016.169.026.253h-.026v57.131h.026a2.235 2.235 0 0 0 2.218 1.99 2.22 2.22 0 0 0 1.117-.308l.02.035L75.875 51.97l-.02-.035A2.233 2.233 0 0 0 76.982 50z" />
            </svg>
        </button>
        <img 
            class="w-full relative h-full object-center object-cover pointer-events-none" 
            v-if="thumbnail && !videoPlayToggle"
            :src="thumbnail"
        />

        <button v-if="videoPlayToggle && !isLoading" @click="toggleVolume()" class="shadow absolute bottom-4 left-4 bg-white w-8 h-8 flex items-center justify-center rounded-full z-10" >
            <svg v-if="isMuted" class="w-4 h-4" width="800" height="800" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" fill="#000">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M1.5 5h2.79l3.86-3.83.85.35v13l-.85.33L4.29 11H1.5l-.5-.5v-5l.5-.5zm3.35 5.17L8 13.31V2.73L4.85 5.85 4.5 6H2v4h2.5l.35.17zm9.381-4.108.707.707L13.207 8.5l1.731 1.732-.707.707L12.5 9.207l-1.732 1.732-.707-.707L11.793 8.5 10.06 6.77l.707-.707 1.733 1.73 1.731-1.731z" />
            </svg>
            <svg v-else class="w-4 h-4" width="800" height="800" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M13.418 2.091A1 1 0 0 1 14 3v18a1 1 0 0 1-1.65.76L5.63 16H3a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1h2.63l6.72-5.76a1 1 0 0 1 1.068-.149zM12 5.175 6.65 9.76A1 1 0 0 1 6 10H4v4h2a1 1 0 0 1 .65.24L12 18.827V5.174zm5.293.119a1 1 0 0 1 1.414 0l.002.001.001.002.004.004.01.01a2.499 2.499 0 0 1 .114.125c.07.08.165.194.274.34.22.292.503.718.782 1.278C20.456 8.175 21 9.827 21 12s-.544 3.825-1.106 4.947c-.28.56-.562.986-.781 1.278a5.847 5.847 0 0 1-.389.465l-.01.01-.004.004-.001.002h-.001v.001a1 1 0 0 1-1.42-1.41l.005-.004.04-.045c.04-.045.102-.12.18-.223a6.39 6.39 0 0 0 .593-.972c.438-.878.894-2.226.894-4.053s-.456-3.175-.894-4.053a6.393 6.393 0 0 0-.594-.972 3.888 3.888 0 0 0-.22-.268l-.004-.005a1 1 0 0 1 .005-1.41zm-2 3a1 1 0 0 1 1.414 0l.002.001.001.002.003.003.008.008.02.02.055.061a4.697 4.697 0 0 1 .599.914c.31.623.605 1.525.605 2.698s-.294 2.075-.606 2.697c-.154.31-.312.548-.438.716a3.383 3.383 0 0 1-.215.26l-.02.02-.008.008-.003.003-.002.002a1 1 0 0 1-1.418-1.411 2.691 2.691 0 0 0 .315-.492c.19-.378.395-.976.395-1.803s-.206-1.425-.394-1.803a2.693 2.693 0 0 0-.316-.492 1 1 0 0 1 .002-1.412z" fill="#000" />
            </svg>
        </button>
    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'

const props = defineProps({
    src: String,
    thumbnail: {
        type: String,
        default: null
    }
})

const isLoading = ref(true)
const isMuted = ref(false)
const emit = defineEmits();

const videoPlayToggle = ref(false)
const myVideo = ref()

const handleVideoPlayToggle = () => {
    if (videoPlayToggle.value) {
        myVideo.value.pause()
        return
    }
    myVideo.value.play()
}

const handlePlayState = (playState) => {
    videoPlayToggle.value = playState.type == "play"
    emit('pauseControl', videoPlayToggle.value)
}

const toggleVolume = () => {
    isMuted.value = !isMuted.value
}
</script>