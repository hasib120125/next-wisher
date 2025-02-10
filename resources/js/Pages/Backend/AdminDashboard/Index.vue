<template>
    <Head :title="Helper.translate('Dashboard')" />
    <DashboardLayout :header="false" :footer="false" title="Dashboard">
        <template v-slot:leftSidebar>
            <LeftSide />
        </template>
        <template v-slot:content>
            <Header :onlineVisitors="onlineVisitors" class="border-b pb-4" />
            <div class="graph-wrapper grid gap-6 mt-5">
                <div class="grid gap-6">
                    <div class="p-4 min-h-[200px]">
                        <VisitorWidget :visitors="visitors" :userSummery="userSummery" class="mt-5" />
                        <!-- <Big /> -->
                    </div>

                    <!-- <UserSummery :userSummery="userSummery" /> -->
                </div>
                <div class="p-4 flex flex-col items-center">
                    <CSelect 
                        class="sm:w-[200px] w-full block ml-auto"
                        v-model="selected"
                        :options="options"
                    />
                    <h1 class="font-semibold text-center mb-10 mt-5 capitalize">{{ selected }} wise visitors</h1>
                    <Small :dataType="selected" />
                </div>
            </div>
        </template>
    </DashboardLayout>
</template>

<script setup>
import { ref } from 'vue'
import DashboardLayout from './DashboardLayout.vue'
import LeftSide from '@/Components/Backend/AdminDashboard/LeftSide.vue'
import Header from '@/Components/Backend/AdminDashboard/Dashboard/Header.vue'
import Widget from '@/Components/Backend/AdminDashboard/Dashboard/Widget.vue'
import VisitorWidget from '@/Components/Backend/AdminDashboard/Dashboard/VisitorWidget.vue'
import Big from '@/Components/Backend/AdminDashboard/Dashboard/Chart/Big.vue'
import Small from '@/Components/Backend/AdminDashboard/Dashboard/Chart/Small.vue'
import { Head } from '@inertiajs/inertia-vue3'
import useVisitors from './useVisitors'
import UserSummery from '@/Components/Backend/AdminDashboard/Dashboard/UserSummery.vue'
import CSelect from '@/Components/Global/CSelect.vue'
import Helper from '@/Helper'

const { onlineVisitors, visitors, userSummery, visitorsAnalyticsDate } = useVisitors()

const selected = ref('country')
const options = [
    {
        key: 'country',
        value: 'Country wise visitors'
    },
    {
        key: 'device',
        value: 'Device wise visitors'
    },
    {
        key: 'browser',
        value: 'Browser wise visitors'
    }
]
</script>


<style scoped>
    .content{
        grid-template-columns: 250px 1fr;
    }

    @media all and (min-width: 768px){
        .graph-wrapper{
            grid-template-columns: 2fr 1fr;
        }
    }
</style>