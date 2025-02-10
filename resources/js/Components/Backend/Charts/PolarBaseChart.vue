<template>
    <div>
        <VueApexCharts
            :height="height"
            :options="options"
            :series="series"
        />
    </div>
</template>

<script setup>
import { merge } from "lodash"
import { computed } from "vue"
import VueApexCharts from "vue3-apexcharts"

const props = defineProps({
    height: {
        type: [String, Number],
        default: 300
    },
    series: {
        type: Array
    },
    labels: {
        type: Array
    },
    options: {
        type: Object
    }
})


const options = computed(() => {
    return merge({
        labels: props.labels,
        title: {
            text: 'Title Goes Here',
            align: 'left'
        },
        chart: {
            type: 'polarArea',
        },
        stroke: {
            colors: ['#fff']
        },
        fill: {
            opacity: 0.8
        },
        legend: {
            position: 'bottom'
        },
        colors: ['#F44336', '#E91E63', '#9C27B0', '#673AB7', '#3F51B5', '#2196F3', '#03A9F4', '#00BCD4', '#009688', '#43A047', '#7CB342', '#AFB42B', '#AFB42B', '#4E342E'],
        yaxis: {
            show: false
        },
    }, props.options)
})
</script>

<style lang="scss" scoped>

</style>