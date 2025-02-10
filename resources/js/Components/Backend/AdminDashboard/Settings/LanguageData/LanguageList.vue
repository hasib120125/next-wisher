<template>
    <div class="border">
        <h2 class="border-b py-2 px-4 bg-gray-100">{{ Helper.translate(title, true) }}</h2>
        <div class="p-4 pt-0">
            <CInput v-model="searchString" type="search" placeholder="Filter" />
            <div class="overflow-y-auto h-[450px] -ml-4 -mr-4">
                <div v-for="(language, index) in filteredLanguages" :key="index" class="border-b text-xs py-2 hover:bg-gray-100 px-4 flex gap-4 justify-between">
                    {{ language[lang] }}
                    <button class="opacity-30 hover:opacity-100">
                        <EditIcon @click="()=> {
                            selectedLanguage=language;
                            showModal=true;
                        }" class="w-4 h-4"/>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <Modal v-model="showModal">
        <EditLanguage v-if="selectedLanguage" :language="selectedLanguage" @close="()=> {
            showModal=false;
            selectedLanguage=null;
        }" />
    </Modal>
</template>

<script setup>
import CInput from '@/Components/Global/CInput.vue'
import Modal from '@/Components/Library/Modal.vue' 
import EditIcon from '@/Icons/EditIcon.vue'
import { computed, ref } from 'vue'
import { isEmpty } from 'lodash'
import EditLanguage from './EditLanguage.vue'
import Helper from '@/Helper'

const props = defineProps({
    title: String,
    lang: String,
    languages: Array,
})

const showModal = ref(false)
const searchString = ref('')
const selectedLanguage = ref(null);

const filteredLanguages = computed(()=>{
    if(isEmpty(searchString.value)) {
        return props.languages;
    }
    return props.languages.filter(item => String(item[props.lang]).toLowerCase().search(searchString.value.toLowerCase()) >= 0);
});

</script>