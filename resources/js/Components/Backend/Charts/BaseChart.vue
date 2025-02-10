<template>
    <div>
        <VueApexCharts 
            type="line" 
            :height="height" 
            :options="chartOptions" 
            :series="series"
        ></VueApexCharts>

    </div>
</template>

<script setup>
import { merge, each } from "lodash"
import { computed } from "vue"
import VueApexCharts from "vue3-apexcharts"



const props = defineProps({
    chartType: {
        chart: 'String',
        default: 'bar'
    },
    options: Object,
    height: {
        type: [String, Number],
        default: 100
    },
    series: [Array, Object],
    labels: [Array, Object],
    yaxisShow: {
        type: Boolean,
        default: true
    },
    legendPosition: {
        type: String, //top, bottom
        default: 'bottom'
    },
    title: {
        type: String,
        default: 'Title Goes Here'
    }
})

const colors = computed(() => {
    let colorArray = ['hsl(0 50% 50% / 1)']
    each(props.options.xaxis.categories, (item, index) => {
        colorArray.push(`hsl(${0+(5*index)} 50% 50% / 1)`)
    })
    return colorArray;
})

const chartOptions = computed(() => {
    return merge({
        chart: {
            type: 'line',
            stacked: false,
            toolbar: {
                show: false
            }
        },
        markers: {
            size: [6, 6],
            shape: 'circle',
            strokeColors: '#fff',
            colors: colors.value,
        },
        dataLabels: {
            enabled: false
        },

        stroke: {
            width: [3, 3, 3, 3, 4],
            // colors: colors.value,
            curve: 'smooth'
        },
        fill: {
            // colors: colors.value,
        },
        title: {
            text: 'Title goes here',
            align: 'left',
            offsetX: 0
        },

        xaxis: {
            categories: [],
            labels: {
                style: {
                    colors: colors.value,
                    fontSize: 14
                }
            },
            axisBorder: {
                show: true,
                color: colors.value[0]
            },
        },
        yaxis: [
            {
                opposite: false,
                axisTicks: {
                    show: true,
                },
                axisBorder: {
                    show: true,
                    color: colors.value[0]
                },
                labels: {
                    style: {
                        colors: colors.value,
                        fontSize: 14
                    }
                },
                title: {
                    // text: "Operating Cashflow (thousand crores)",
                    style: {
                        color: colors.value[0],
                    }
                },
            }
        ],
        legend: {
            horizontalAlign: 'center',
            markers: {
                width: 15,
                height: 15,
                strokeWidth: 0,
                strokeColor: 'transparent',
                // fillColors: colors.value
            }
        },
        tooltip: {
            shared: true,
            style: {
                fontSize: '12px',
            }
        }
    }, props.options)
})
</script>