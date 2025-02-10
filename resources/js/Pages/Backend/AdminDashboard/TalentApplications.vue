<template>
    <Head :title="Helper.translate('Talent Applications')" />
    <DashboardLayout :header="false" :footer="false" :title="activeComponent == 'Applications' ? 'Talent Applications' : 'Talent\'s Declined Applications'">
        <template v-slot:leftSidebar>
            <LeftSide />
        </template>
        <template v-slot:content>
            <component :is="components[activeComponent]" />
        </template>
    </DashboardLayout>
</template>

<script setup>
import DashboardLayout from './DashboardLayout.vue'
import LeftSide from '@/Components/Backend/AdminDashboard/LeftSide.vue'
import Helper from '@/Helper'
import useTalentApplications from '@/Pages/Backend/AdminDashboard/useTalentApplications.js'
import { Head } from '@inertiajs/inertia-vue3'

const { components, activeComponent } = useTalentApplications()
</script>


<style scoped>
</style>