<template>
    <div class="h-full">
        <slot></slot>
    </div> 
</template>

<script setup>
import Helper from "@/Helper"
import useVisitors from "@/Pages/Backend/AdminDashboard/useVisitors"
import { onMounted, onUnmounted } from "vue"

const { makeVisitors, hitDelay } = useVisitors()
const { getDevice, getBrowser } = Helper

const payload = {
    device: getDevice(),
    browser: getBrowser(),
    url: location.href
}
let timeoutId = null
let intervalId = null
onMounted(() => {
    timeoutId = setTimeout(() =>{
        makeVisitors(payload)
        intervalId = setInterval(() => {
            makeVisitors(payload)
        }, hitDelay)
    }, hitDelay)
    makeVisitors(payload)
})

onUnmounted(() => {
    clearTimeout(timeoutId)
    clearInterval(intervalId)
})
</script>