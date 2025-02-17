<template>
    <Head title="Settings" />
    <DashboardLayout :header="false" :footer="false" title="Settings">
        <template v-slot:leftSidebar>
            <LeftSide />
        </template>
        <template v-slot:content>
            <div class="flex flex-wrap md:gap-4 gap-2 justify-center">
                <button 
                    @click="activeComponent = 'BasicConfig'"
                    class="border md:px-4 md:py-2 px-2 py-1 rounded"
                    :class="activeComponent == 'BasicConfig' && 'bg-gray-800 text-white font-medium'"
                >
                    {{ Helper.translate('Basic Config') }}
                </button>
                <button 
                    @click="activeComponent = 'LanguageData'"
                    class="border md:px-4 md:py-2 px-2 py-1 rounded"
                    :class="activeComponent == 'LanguageData' && 'bg-gray-800 text-white font-medium'"
                >
                    {{ Helper.translate('Language Data') }}
                </button>
                <button 
                    @click="activeComponent = 'Pages'"
                    class="border md:px-4 md:py-2 px-2 py-1 rounded"
                    :class="activeComponent == 'Pages' && 'bg-gray-800 text-white font-medium'"
                >
                    {{ Helper.translate('Pages') }}
                </button>
                <button 
                    @click="activeComponent = 'Faq'"
                    class="border md:px-4 md:py-2 px-2 py-1 rounded"
                    :class="activeComponent == 'Faq' && 'bg-gray-800 text-white font-medium'"
                >
                    {{ Helper.translate('Faq') }}
                </button>
                <button 
                    @click="activeComponent = 'ChangeAdminPassword'"
                    class="border md:px-4 md:py-2 px-2 py-1 rounded"
                    :class="activeComponent == 'ChangeAdminPassword' && 'bg-gray-800 text-white font-medium'"
                >
                    {{ Helper.translate('Change password') }}
                </button>
            </div>
            <div class="">
                <component :is="components[activeComponent]" />
            </div>
        </template>
    </DashboardLayout>
</template>

<script setup>
import DashboardLayout from '../DashboardLayout.vue'
import LeftSide from '@/Components/Backend/AdminDashboard/LeftSide.vue'
import useSettings from '@/Pages/Backend/AdminDashboard/Settings/useSettings.js'
import { Head } from '@inertiajs/inertia-vue3'
import Helper from '@/Helper';

const { components, activeComponent } = useSettings()


</script>