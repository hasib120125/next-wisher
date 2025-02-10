<template>
    <Head title="Account" />
    <DashboardLayout>
        <template v-slot:leftSidebar>
            <LeftSide />
        </template>
        <template v-slot:content>
            <div class="grid lg:grid-cols-2 grid-cols-1 gap-6">
                <div class="border p-6">
                    <PersonalInfo :user="user"/>
                </div>
                <div class="border p-6">
                    <PasswordChange />
                </div>
            </div>
            
            <div class="bg-red-50 p-4 py-6 mt-4">
                <DeleteAccount />
            </div>
        </template>
    </DashboardLayout>
</template>

<script setup>
import DashboardLayout from '../DashboardLayout.vue'
import LeftSide from '@/Components/Backend/TalentDashboard/LeftSide.vue'
import PersonalInfo from '@/Components/Backend/TalentDashboard/Account/PersonalInfo.vue'
import PasswordChange from '@/Components/Backend/TalentDashboard/Account/PasswordChange.vue'
import DeleteAccount from '@/Components/Backend/TalentDashboard/Account/DeleteAccount.vue'
import { Head } from '@inertiajs/inertia-vue3'

const props = defineProps({
    user: Object
})
</script>


<style scoped>
    .content{
        grid-template-columns: 250px 1fr;
    }
</style>