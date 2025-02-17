<template>
    <FileUpload />
    <div v-if="!isEmpty(allMedia)" class="gap-3 grid grid-cols-3 content-start overflow-y-auto" style="height: 930px;">
        <div 
            v-for="item in allMedia" 
            :key="item.id" 
            class="relative cursor-pointer h-20 bg-gray-200 overflow-hidden"
            @click="handleImage(item.path)"
        >
            <button 
                @click="deleteMedia(item.id)"
                class="absolute z-10 bg-[#ff3b04] text-white w-6 h-6 block scale-90 right-0 opacity-90 hover:opacity-100 hover:shadow-lg"
            >
                <svg class="w-6 h-6"><path fill="none" stroke="currentColor" stroke-width="2" d="M7,7 L17,17 M7,17 L17,7"></path></svg>
            </button>
            <img 
                class="block w-full h-full object-cover object-center hover:scale-110 hover:rotate-3 transition" 
                :src="item.path"
            >
        </div>
    </div>
    <Alert v-else title="No media found" />
</template>

<script setup>
import useMedia from '../../useMedia'
import { onMounted } from 'vue'
import Alert from '@/Components/Global/Alert.vue'
import { isEmpty } from 'lodash'
import FileUpload from '../../FileUpload.vue';

const { getMedia, allMedia, deleteMedia, handleImage } = useMedia()


onMounted(() => {
    getMedia()
})
</script>