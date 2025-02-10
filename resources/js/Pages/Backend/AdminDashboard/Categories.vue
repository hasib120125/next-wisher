<template>
    <Head :title="Helper.translate('Category Management')" />
    <DashboardLayout :header="false" :footer="false" title="Category Management">
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
    import useCategory from '@/Pages/Backend/AdminDashboard/useCategory.js'
    import { Head } from '@inertiajs/inertia-vue3';

    const { components, activeComponent } = useCategory()
</script>


<style scoped>
</style>