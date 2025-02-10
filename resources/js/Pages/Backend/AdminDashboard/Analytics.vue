<template>
    <Head title="Analytics"/>
    <DashboardLayout :header="false" :footer="false" title="Analytics">
        <template v-slot:leftSidebar>
            <LeftSide />
        </template>
        <template v-slot:content>
            <div class="flex md:flex-nowrap flex-wrap gap-6 border rounded p-4 mb-2">
                <div class="w-full">
                    <h1 class="text-sm font-bold">{{ Helper.translate('Start Date') }}</h1>
                    <CInput type="date" v-model="filterData.start_date" />
                </div>
                <div class="w-full">
                    <h1 class="text-sm font-bold">{{ ('End Date') }}</h1>
                    <CInput type="date" v-model="filterData.end_date" />
                </div>
                <div class="w-full">
                    <h1 class="text-sm font-bold">{{ Helper.translate('Filter') }}</h1>
                    <CSelect :options="filterOptions" v-model="selectedOption"  @change="handleDateChange" />
                </div>
            </div>
            
            <Widget />
            <!-- <Chart /> -->
        </template>
    </DashboardLayout>
</template>

<script setup>
import DashboardLayout from './DashboardLayout.vue'
import LeftSide from '@/Components/Backend/AdminDashboard/LeftSide.vue'
import Widget from "@/Components/Backend/AdminDashboard/Dashboard/Widget.vue"
import Chart from "@/Components/Backend/AdminDashboard/Analytics/Chart.vue"
import CInput from '@/Components/Global/CInput.vue'
import CSelect from '@/Components/Global/CSelect.vue'
import { Head } from '@inertiajs/inertia-vue3'
import useVisitors from './useVisitors'
import { onMounted, ref, watchEffect, watch } from 'vue'
import Helper from '@/Helper'
import moment from 'moment'
import useAdminAnalytics from './useAdminAnalytics'

const {earnings, filterData, getEarnings} = useAdminAnalytics();

onMounted(()=>{
    getEarnings()
})

const selectedOption = ref(0);

const handleDateChange = () => {
    if(selectedOption.value == 'all'){
        filterData.value.start_date = null
        filterData.value.end_date = null
        return
    }
    filterData.value.end_date = moment().format('YYYY-MM-DD')
    filterData.value.start_date = moment().subtract(selectedOption.value, 'days').format('YYYY-MM-DD')
}

const filterOptions = [
    {
        key: 0,
        value: 'Today'
    },
    {
        key: 1,
        value: 'Yesterday'
    },
    {
        key: 7,
        value: 'Last 7Days'
    }, 
    {
        key: 30,
        value: 'Last 30Days'
    },
    {
        key: 60,
        value: 'Last 60Days'
    },
    {
        key: 90,
        value: 'Last 90Days'
    },
    {
        key: 365,
        value: 'Last 365Days'
    },
    {
        key: 'all',
        value: 'All time'
    }
]

onMounted(() => {
    handleDateChange()
    // getOnlineVisitors(visitorFilter.value)
    // getVisitors(visitorFilter.value)
    // getVisitorsAnalyticsDate(visitorFilter.value)
})

// watchEffect(()=>{
    // calculateDate(dateSelect.value)
// })

// watch(visitorFilter.value, ()=>{
//     getOnlineVisitors(visitorFilter.value)
//     getVisitors(visitorFilter.value)
//     getVisitorsAnalyticsDate(visitorFilter.value)
// })
</script>


<style scoped>
.customBorder{
    display: block;
    border-bottom: 1px solid #0002;
    position: relative;
}
.customBorder::before{
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0px;
    border-bottom: 1px solid red;
    transition: 0.3s ease-in-out;
}

.myInput:focus + .customBorder::before{
    width: 100%;
}
.remove-shadow{
    box-shadow: none;
}
</style>