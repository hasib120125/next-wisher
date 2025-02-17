<template>
    <Head :title="Helper.translate('Talent Earnings')"/>
    <DashboardLayout :header="false" :footer="false" title="Talent Earnings">
        <template v-slot:leftSidebar>
            <LeftSide />
        </template>
        <template v-slot:content>
            <EarningHeader />
            <component :is="components[activeComponent]" />
        </template>
    </DashboardLayout>
</template>

<script setup>
import DashboardLayout from './DashboardLayout.vue'
import LeftSide from '@/Components/Backend/AdminDashboard/LeftSide.vue'
import EarningHeader from '@/Components/Backend/AdminDashboard/Payments/EarningHeader.vue'
import Helper from '@/Helper'
import usePayments from '@/Pages/Backend/AdminDashboard/usePayments.js'
import { Head } from '@inertiajs/inertia-vue3'
import { onMounted } from 'vue'

const { components, activeComponent } = usePayments()

onMounted(()=>{
    activeComponent.value = 'Earnings'
})


</script>


<style scoped>
</style>