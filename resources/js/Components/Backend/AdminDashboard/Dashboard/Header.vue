<template>
    <div class="items-center uppercase md:font-bold font-semibold md:text-base text-sm">
        <div class="flex flex-wrap items-center justify-center gap-4">
            <button class="border" @click="refreshVisitors">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M10 3v2a5 5 0 0 0-3.54 8.54l-1.41 1.41A7 7 0 0 1 10 3zm4.95 2.05A7 7 0 0 1 10 17v-2a5 5 0 0 0 3.54-8.54l1.41-1.41zM10 20l-4-4 4-4v8zm0-12V0l4 4-4 4z"/></svg>
            </button>

            <span class="flex items-center gap-2">
                <span class="w-4 h-4 rounded-full bg-green-500 inline-block"></span>
                {{ Helper.translate('Online') }}:
            </span>

            <span class="text-md text-amber-400">
                {{ Helper.translate('Others') }}: {{ onlineVisitors.visitors }}
            </span>

            <span class="text-md text-red-400">
                {{ Helper.translate('Users') }}: {{ onlineVisitors.users }}
            </span>

            <span class="text-md text-sky-500">
                {{ Helper.translate('Talents') }}: {{ onlineVisitors.talents }}
            </span>
        </div>
    </div>
    
    <div class="flex sm:flex-nowrap flex-wrap items-center justify-between gap-6 py-5">
        <div class="w-full">
            <h1 class="text-sm font-bold">{{ Helper.translate('Start Date') }}</h1>
            <CInput type="date" v-model="visitorFilter.from" />
        </div>
        <div class="w-full">
            <h1 class="text-sm font-bold">{{ ('End Date') }}</h1>
            <CInput type="date" v-model="visitorFilter.to" />
        </div>
        <div class="w-full"> 
            <h1 class="text-sm font-bold">{{ Helper.translate('Filter') }}</h1>
            <CSelect @change="getData" :options="filterOptions" v-model="dateSelect" />
        </div>
    </div>
</template>

<script setup>
import Helper from '@/Helper';
import useVisitors from '@/Pages/Backend/AdminDashboard/useVisitors';
import { watchEffect, ref, onMounted, watch } from 'vue'
import moment from 'moment';
import CSelect from '@/Components/Global/CSelect.vue';
import CInput from '@/Components/Global/CInput.vue';

const { getOnlineVisitors, getVisitors, getVisitorsAnalyticsDate, onlineVisitors, visitors, visitorFilter, refreshVisitors, getUserSummery } = useVisitors()

defineProps({
    onlineVisitors: {}
})

const filterOptions = [
    {
        key: 0,
        value: 'Today'
    },
    {
        key: 7,
        value: 'Last 7Days'
    },
    {
        key: 15,
        value: 'Last 15Days'
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


const dateSelect = ref(0)

const getData = ()=>{
    calculateDate(dateSelect.value)
    getUserSummery(visitorFilter.value)
}
const calculateDate = (days) => {
    console.log(days, 'days')
    if(days == 'all'){
        visitorFilter.value.from = null
        visitorFilter.value.to   =  null
        return
    }
    visitorFilter.value.from = moment().subtract(days, 'd').format('YYYY-MM-DD');
    visitorFilter.value.to   =  moment().format('YYYY-MM-DD');
}

onMounted(()=> {
    calculateDate(dateSelect.value)
    getOnlineVisitors()
    getVisitors(visitorFilter.value)
    getVisitorsAnalyticsDate(visitorFilter.value)
    getUserSummery(visitorFilter.value)
})

watch(visitorFilter.value, ()=>{
    getOnlineVisitors(visitorFilter.value)
    getVisitors(visitorFilter.value)
    getUserSummery(visitorFilter.value)
    getVisitorsAnalyticsDate(visitorFilter.value)
})

</script>

<style scoped>
    .wrapper{
        display: grid;
        grid-template-columns: 200px 1fr 200px;
    }
</style>