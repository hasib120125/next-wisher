<template>
    <div class="customVideoRatio bg-black relative overflow-hidden">
        <div class="video customVideoRatio height-[402px] overflow-hidden relative">
            <Video
                v-if="path"
                :src="`${$page.props.asset}${path}`"
                :poster="showCover ? `${$page.props.asset}${cover ? cover : 'video-not-found.webp'}` : null"
                @pauseControl="status => $emit('pauseControl', status)"
                class="w-full absolute bottom-0"
            />
            <!-- <img 
                class="w-full z-10 relative h-full object-center object-cover pointer-events-none" 
                :src="`${$page.props.asset}${cover ? cover : 'video-not-found.webp'}`"
            /> -->
        </div>
        <div 
            v-if="!isEmpty(talent)"
            class="block text-lg font-bold absolute bottom-2 right-4 z-20 text-white" 
        >
            {{ get(talent, 'talent.username') }}
        </div>
    </div>
</template>

<script setup>
    import { get, isEmpty } from 'lodash'
    import Video from '@/services/Video.vue'

    defineProps({
        path: String,
        cover: String,
        showCover: {
            type: Boolean,
            default: true
        }
    })


    
</script>