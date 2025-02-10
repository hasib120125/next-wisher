<template>
    <Head :title="Helper.translate('Occasion Management')" />
    <DashboardLayout :header="false" :footer="false" :title="Helper.translate('Occasion Management')">
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
    import useOccasion from '@/Pages/Backend/AdminDashboard/useOccasion.js'
    import { Head } from '@inertiajs/inertia-vue3';
    import Helper from '@/Helper';

    const { components, activeComponent } = useOccasion()
</script>


<style scoped>
</style>