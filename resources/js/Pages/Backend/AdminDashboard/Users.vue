<template>
    <Head :title="Helper.translate('Users')" />
    <DashboardLayout :header="false" :footer="false" title="Users">
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
import useUser from '@/Pages/Backend/AdminDashboard/useUser.js'
import { Head } from '@inertiajs/inertia-vue3'

const { components, activeComponent } = useUser()
</script>


<style scoped>
</style>