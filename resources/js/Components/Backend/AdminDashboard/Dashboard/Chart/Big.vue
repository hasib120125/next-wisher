<template>
    <div>
        <BaseChart
            :height="300"
            :series="series"
            :options="options"
        />
    </div>
</template>

<script setup>
import { watch, onMounted, computed } from 'vue'
import BaseChart from "@/Components/Backend/Charts/BaseChart.vue";
import useVisitors from '@/Pages/Backend/AdminDashboard/useVisitors.js'
import Helper from '@/Helper'

const { visitorFilter, visitorsAnalyticsDate, getVisitorsAnalyticsDate } = useVisitors()

watch(visitorFilter.value, getVisitorsAnalyticsDate)
onMounted(getVisitorsAnalyticsDate)

const series = computed(() => [visitorsAnalyticsDate.value.data])
const options = computed(() =>({
    title: {
        text: '',
        align: 'left',
        offsetX: 0
    },
    legend: {
        show: true
    },
    dataLabels : {
        enabled: false
    },
    xaxis: {
        categories: visitorsAnalyticsDate.value?.xaxis ? visitorsAnalyticsDate.value.xaxis.map(item => Helper.dateFormate(item)) : [],
    }
}))
</script>