<template>
    <h1 class="text-lg my-2 mb-4 flex items-center gap-6">
        {{ Helper.translate('Language Data') }}
        <button @click="showModal=true" class="bg-sky-500 font-bold text-sm px-4 py-1 rounded text-white">
            + {{ Helper.translate('Add') }}
        </button>
    </h1>
    <div class="grid lg:grid-cols-4 md:grid-cols-2 mt-4 gap-6">
        <LanguageList title="English" lang="english" :languages="$page.props.languages" />
        <LanguageList title="Français" lang="french" :languages="$page.props.languages" />
        <LanguageList title="Portugues" lang="portugues" :languages="$page.props.languages" />
        <LanguageList title="Español" lang="spanish" :languages="$page.props.languages" />
    </div>

    <Modal v-model="showModal">
        <AddLanguage @close="showModal=false" />
    </Modal>
</template>

<script setup>
import { ref } from 'vue' 
import Modal from '@/Components/Library/Modal.vue' 
import LanguageList from '@/Components/Backend/AdminDashboard/Settings/LanguageData/LanguageList.vue'
import AddLanguage from '@/Components/Backend/AdminDashboard/Settings/LanguageData/AddLanguage.vue'
import Helper from '@/Helper'

const showModal = ref(false)
</script>