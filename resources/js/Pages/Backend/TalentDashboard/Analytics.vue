<template>
    <div>
        <div class="flex gap-6 md:flex-nowrap flex-wrap border rounded p-4 mb-2">
            <div class="w-full">
                <h1 class="text-sm font-bold">{{ Helper.translate('Start Date') }}</h1>
                <CInput type="date" v-model="filterData.start_date" />
            </div>
            <div class="w-full">
                <h1 class="text-sm font-bold">{{ Helper.translate('End Date') }}</h1>
                <CInput type="date" v-model="filterData.end_date" />
            </div>
            <div class="w-full">
                <h1 class="text-sm font-bold">{{ Helper.translate('Filter') }}</h1>
                <CSelect :options="filterOptions" v-model="selectedOption" @change="handleDateChange" />
            </div>
        </div>
        <Widget />
    </div>
</template>

<script setup>
import DashboardLayout from '../DashboardLayout.vue'
import LeftSide from '@/Components/Backend/TalentDashboard/LeftSide.vue'
import Widget from '@/Components/Backend/TalentDashboard/Analytics/Widget.vue'
import CInput from '@/Components/Global/CInput.vue'
import CSelect from '@/Components/Global/CSelect.vue'
import Chart from '@/Components/Backend/TalentDashboard/Analytics/Chart.vue'
import useAnalytics from './useAnalytics'
import { onMounted, ref } from 'vue'
import Helper from '@/Helper'
import moment from 'moment'
import { Head } from '@inertiajs/inertia-vue3'
import { get } from 'lodash'


const { filterData, getEarnings } = useAnalytics();

onMounted(()=> {
    getEarnings()
})

const selectedOption = ref('all');

const handleDateChange = () => {
    if(selectedOption.value == 'all') {
        filterData.value.end_date = null
        filterData.value.start_date = null
        getEarnings()
    }
    if(selectedOption.value == 'today') {
        filterData.value.end_date = moment().format('YYYY-MM-DD')
        filterData.value.start_date = moment().subtract(0, 'days').format('YYYY-MM-DD')
    }
    if(selectedOption.value == 'yesterday') {
        filterData.value.end_date = moment().subtract(1, 'days').format('YYYY-MM-DD')
        filterData.value.start_date = moment().subtract(1, 'days').format('YYYY-MM-DD')
    }
    if(selectedOption.value == 'last 7days') {
        filterData.value.end_date = moment().format('YYYY-MM-DD')
        filterData.value.start_date = moment().subtract(7, 'days').format('YYYY-MM-DD')
    }
    if(selectedOption.value == 'last 30days') {
        filterData.value.end_date = moment().format('YYYY-MM-DD')
        filterData.value.start_date = moment().subtract(30, 'days').format('YYYY-MM-DD')
    }
    if(selectedOption.value == 'last 60days') {
        filterData.value.end_date = moment().format('YYYY-MM-DD')
        filterData.value.start_date = moment().subtract(60, 'days').format('YYYY-MM-DD')
    }
    if(selectedOption.value == 'last 90days') {
        filterData.value.end_date = moment().format('YYYY-MM-DD')
        filterData.value.start_date = moment().subtract(90, 'days').format('YYYY-MM-DD')
    }
    if(selectedOption.value == 'last 365days') {
        filterData.value.end_date = moment().format('YYYY-MM-DD')
        filterData.value.start_date = moment().subtract(365, 'days').format('YYYY-MM-DD')
    }
}

const filterOptions = [
    {
        key: 'all',
        value: 'All time'
    },
    {
        key: 'today',
        value: 'Today'
    },
    {
        key: 'yesterday',
        value: 'Yesterday'
    },
    {
        key: 'last 7days',
        value: 'Last 7Days'
    },
    {
        key: 'last 30days',
        value: 'Last 30Days'
    },
    {
        key: 'last 60days',
        value: 'Last 60Days'
    },
    {
        key: 'last 90days',
        value: 'Last 90Days'
    },
    {
        key: 'last 365days',
        value: 'Last 365Days'
    },
]
</script>


<style scoped>
    .content{
        grid-template-columns: 250px 1fr;
    }
</style>