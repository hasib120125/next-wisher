<template>
    <div class="px-10 py-10 bg-white text-black text-opacity-80 rounded-lg backdrop-blur-md relative">
        <button class="absolute right-4 top-4" @click="$emit('close')">
            <CloseIcon />
        </button>
        
        <div class="flex md:flex-nowrap flex-wrap justify-center items-center gap-4 mt-3">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" name="language" @click="changeLanguage('english')" value="english" />
                English
            </label>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" v-model="language" @click="changeLanguage('french')" value="french" />
                Français
            </label>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" v-model="language" @click="changeLanguage('portugues')" value="portugues" />
                Portuguese
            </label>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" v-model="language" @click="changeLanguage('spanish')" value="spanish" />
                Español
            </label>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'
import CloseIcon from '@/Icons/CloseIcon.vue'
import GlobIcon from '@/Icons/GlobIcon.vue'
import { languagePopup } from '@/Helper'

const language = ref('english')

const changeLanguage = (lang) => {
    language.value = lang
    localStorage.language = lang
    window.location.reload()
}

watch(languagePopup, () => {
    if(localStorage.language){
        language.value = localStorage.language
    }else{
        localStorage.language = 'english'
    }
}, {deep: true})
</script>