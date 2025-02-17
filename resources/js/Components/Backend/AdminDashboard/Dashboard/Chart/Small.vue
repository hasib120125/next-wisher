<template>
    <div>
        <PolarBaseChart
            v-if="series && labels"
            height="300"
            :series="series"
            :labels="labels"
            :options="options"
        />
    </div>
</template>

<script setup>
import PolarBaseChart from "@/Components/Backend/Charts/PolarBaseChart.vue"
import { watch, onMounted, computed } from 'vue'
import { get } from 'lodash'
import useVisitors from '@/Pages/Backend/AdminDashboard/useVisitors.js'

const props = defineProps({
    dataType: String
})


const { visitorFilter, getVisitorsDemographyDate, visitorsDemographyDate } = useVisitors()
const series = computed(() => {
    let series = visitorsDemographyDate.value ? visitorsDemographyDate.value[props.dataType]?.series : []
    if(!series) return []
    return series
})
const labels = computed(() => {
    let labels = visitorsDemographyDate.value ? visitorsDemographyDate.value[props.dataType]?.labels : []
    if(!labels) return []
    labels = labels.map(item => {
        return !item ? 'n/a' : item
    })
    return labels
})
const options = {
    title: {
        text: '',
        align: 'left'
    }
}


watch(visitorFilter.value, () => {
    getVisitorsDemographyDate(visitorFilter.value)
})

watch(props, () => {
    getVisitorsDemographyDate(visitorFilter.value)
})
onMounted(() => {
    getVisitorsDemographyDate(visitorFilter.value)
})
</script>