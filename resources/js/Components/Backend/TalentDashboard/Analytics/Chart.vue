<template>
    <div>
        <h1 class="fs-lg font-bold">{{ Helper.translate('Analytics') }}</h1>
        <BaseChart
            :height="420"
            :series="series"
            :options="options"
        />
    </div>
</template>

<script setup>
import BaseChart from "@/Components/Backend/Charts/BaseChart.vue";
import Helper from "@/Helper";
import useAnalytics from "@/Pages/Backend/TalentDashboard/useAnalytics";
import { get, map } from "lodash";
import { computed } from 'vue'
const { earnings } = useAnalytics();

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
    // {
    //     name: Helper.translate('My Life'),
    //     type: 'line',
    //     data: get(earnings.value, 'chart_data.mylife')
    // },
    // {
    //     name: Helper.translate('Calendar'),
    //     type: 'line',
    //     data: get(earnings.value, 'chart_data.calender')
    // }
])

const options = computed(() => {
    return {
        title: {
            text: '',
            align: 'left',
            offsetX: 0
        },
        legend: {
            show: true
        }
        ,legend: {
            markers: {
            fillColors: ['#ef4444', '#0ca5e9', '#000000', '#f97315']
            }
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