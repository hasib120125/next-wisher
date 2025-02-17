<template>
    <div class="customVideoRatio bg-black relative h-full rounded-3xl overflow-hidden">
        <button 
            v-if="role && role == 'admin'"
            @click="$emit('onClick')" 
            class="absolute top-3 right-3 bg-white/50 hover:bg-white duration-300 p-2 rounded z-10"
        >
            <EditIcon class="w-4 h-4" />
        </button>

        <div class="video customVideoRatio height-[402px] overflow-hidden relative">
            <Video
                v-if="get(talent, 'talent.video_path')"
                :src="`${$page.props.asset}${get(talent, 'talent.video_path')}`" 
                class="w-full absolute bottom-0"
                @pauseControl="status => $emit('pauseControl', status)"
            />
            <img class="w-full h-full object-center object-cover" :src="`${$page.props.asset}video-not-found.webp`" />
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
    import EditIcon from '@/Icons/EditIcon.vue'
    import Video from '@/services/Video.vue'

    defineProps({
        role: String,
        talent: Object
    })

    
</script>