<template>
    <Head :title="Helper.translate('Account')" />
    <DashboardLayout>
        <template v-slot:leftSidebar>
            <LeftSide />
        </template>
        <template v-slot:content>
            <PersonalInformation :user="user" />
            <PasswordChange />
            <DeleteAccount />
        </template>
    </DashboardLayout>
</template>

<script setup>
import DashboardLayout from '../DashboardLayout.vue'
import LeftSide from '@/Components/Backend/UserDashboard/LeftSide.vue'
import PersonalInformation from '@/Components/Backend/UserDashboard/PersonalInformation.vue'
import PasswordChange from '@/Components/Backend/UserDashboard/PasswordChange.vue'
import DeleteAccount from '@/Components/Backend/UserDashboard/DeleteAccount.vue'
import { Head } from '@inertiajs/inertia-vue3'
import Helper from '@/Helper'

defineProps({
    user: Object
})
</script>


<style scoped>
    .content{
        grid-template-columns: 250px 1fr;
    }
</style>