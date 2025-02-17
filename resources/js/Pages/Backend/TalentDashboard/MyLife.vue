<template>
    <Head title="My Life" />
    <DashboardLayout>
        <template v-slot:leftSidebar>
            <LeftSide />
        </template>
        <template v-slot:content>
            <div class="flex gap-2 mb-4">
                <button 
                    @click="currentComponent = 'Setup'" 
                    class="font-semibold border rounded px-4 py-1"
                    :class="currentComponent == 'Setup' && 'bg-sky-500 text-white'"
                >
                    {{ Helper.translate('Set Up') }}
                </button>
                <button 
                    @click="currentComponent = 'Manage'" 
                    class="font-semibold border rounded px-4 py-1"
                    :class="currentComponent == 'Manage' && 'bg-sky-500 text-white'"
                >
                    {{ Helper.translate('Add/Manage') }}
                </button>
                <button
                    @click="showPreview = true" 
                    class="font-semibold border rounded px-4 py-1"
                >
                    {{ Helper.translate('Preview') }}
                </button>
            </div>
            <div class="mt-10">
                <component :is="components[currentComponent]" />
            </div>
            <Modal v-model="showPreview" :isFull="true" class="w-full">
                <MyLife 
                    class="bg-white" 
                    @close="showPreview=false" 
                    :closeable="true"
                    :user="$page.props.auth.user"
                    :earning="$page.props.mylife"
                    :posts="$page.props.posts"
                />
            </Modal>
        </template>
    </DashboardLayout>
</template>

<script setup>
import { ref } from 'vue';
import DashboardLayout from '../DashboardLayout.vue'
import LeftSide from '@/Components/Backend/TalentDashboard/LeftSide.vue'
import useMyLife from '@/Pages/Backend/TalentDashboard/useMyLife.js'
import Modal from '@/Components/Library/Modal.vue'
import MyLife from '@/Components/Backend/MyLife.vue'
import { Head } from '@inertiajs/inertia-vue3'
import Helper from '@/Helper';

const currentComponent = ref('Setup')
const { components } = useMyLife()
const showPreview = ref(false)

</script>


<style scoped>
    .content{
        grid-template-columns: 250px 1fr;
    }
</style>