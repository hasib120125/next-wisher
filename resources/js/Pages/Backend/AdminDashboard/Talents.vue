<template>
    <Head title="Talents" />
    <DashboardLayout :header="false" :footer="false" title="Active Talents">
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
import useTalents from '@/Pages/Backend/AdminDashboard/useTalents.js'
import { Head } from '@inertiajs/inertia-vue3'

const { components, activeComponent } = useTalents()
</script>


<style scoped>
</style>