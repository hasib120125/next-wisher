<template>
    <label class="relative block w-full text-sm text-gray-900 border border-gray-400 p-2 rounded cursor-pointer bg-white focus:outline-none" >
        <button class="border border-gray-500 rounded-[2px] px-[6px] py-[2px] text-sm bg-black/5">
            {{ Helper.translate('Choose file') }}
        </button>
        {{ Helper.translate(fileName) }}
        <input 
            type="file" 
            @input="handleFile" 
            class="absolute top-0 left-0 w-full h-full opacity-0"
            :accept="accept"
        />
    </label>
</template>

<script setup>
    import Helper from '@/Helper'
    import { ref } from 'vue'

    const props = defineProps({
        accept: {
            type: String,
            default: ''
        }
    })

    const fileName = ref('No file chosen')
    const emit = defineEmits()
    const handleFile = (e) => {
        fileName.value = e.target.files[0].name
        if(fileName.value.length > 25){
            fileName.value = fileName.value.substring(0, 13) + "..." + fileName.value.substring(fileName.value.length - 13);
        }

        emit('input', e)
    }
</script>