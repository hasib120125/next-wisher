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
import BaseChart from "@/Components/Backend/Charts/BaseChart.vue";
import useAdminAnalytics from "@/Pages/Backend/AdminDashboard/useAdminAnalytics";
import Helper from "@/Helper";
import { get, map } from "lodash";
import { computed } from 'vue'
const { earnings } = useAdminAnalytics();

const series = computed(() => [
    {
        name: Helper.translate('Wish'),
        type: 'line',
        data: get(earnings.value, 'chart_data.wish')
    },
    {
        name: Helper.translate('Tips'),
        type: 'bar',
        data: get(earnings.value, 'chart_data.tips')
    },
    {
        name: Helper.translate('My Life'),
        type: 'line',
        data: get(earnings.value, 'chart_data.mylife')
    },
    {
        name: Helper.translate('Calendar'),
        type: 'line',
        data: get(earnings.value, 'chart_data.calender')
    }
])

const options = computed(() => {
    return {
        title: {
            text: Helper.translate('Analytics'),
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
            categories: map(get(earnings.value, 'chart_data.options'), item => Helper.dateFormate(item)),
        }
    }
})
</script>

<style lang="scss" scoped>

</style>