<template>
    <Head :title="Helper.translate('Country Management')" />
    <DashboardLayout :header="false" :footer="false" title="Country Management">
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
    import Helper from '@/Helper';
    import useCountry from '@/Pages/Backend/AdminDashboard/useCountry.js'
    import { Head } from '@inertiajs/inertia-vue3';

    const { components, activeComponent } = useCountry()
</script>


<style scoped>
</style>