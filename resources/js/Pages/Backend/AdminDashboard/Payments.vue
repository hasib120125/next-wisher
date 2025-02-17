<template>
    <Head :title="Helper.translate('Payments')"/>
    <DashboardLayout :header="false" :footer="false" title="Payments">
        <template v-slot:leftSidebar>
            <LeftSide />
        </template>
        <template v-slot:content>
            <Header />
            <component :is="components[activeComponent]" />
        </template>
    </DashboardLayout>
</template>

<script setup>
import DashboardLayout from './DashboardLayout.vue'
import LeftSide from '@/Components/Backend/AdminDashboard/LeftSide.vue'
import Header from '@/Components/Backend/AdminDashboard/Payments/Header.vue'
import Helper from '@/Helper'
import usePayments from '@/Pages/Backend/AdminDashboard/usePayments.js'
import { Head } from '@inertiajs/inertia-vue3'

const { components, activeComponent } = usePayments()



</script>


<style scoped>
</style>