<template>
    <GlobalLayout class="h-full">
        <div class="h-full relative">
            <Header v-if="header" />
            <!-- <div :class="route().current('payment.tips.amount') ? 'min-h-[calc(100vh-108px)]' : 'min-h-[calc(100vh-164px)]'"> -->
                <!-- {{ route().current('payment.tips.amount') ? 'some' : 'more' }} -->
            <div class="min-h-[calc(100%-var(--footerHeight))]" :style="`--footerHeight:${footer_height}`">
                <slot></slot>
            </div>
            <Footer v-if="footer" class="" />
        </div>
    </GlobalLayout>
</template>

<script setup>
import Header from '@/Components/Backend/Global/Header.vue'
import Footer from '@/Components/Backend/Global/Footer.vue'
import GlobalLayout from '@/Layouts/GlobalLayout.vue'
import useVisitors from "@/Pages/Backend/AdminDashboard/useVisitors"
import { onMounted, onUnmounted, onBeforeMount } from "vue"
import Helper from '@/Helper'

defineProps({
    header: {
        type: Boolean,
        default: true
    },
    footer: {
        type: Boolean,
        default: true
    },
    footer_height: {
        type: String,
        default: '80px'
    }
})

const { makeVisitors, hitDelay } = useVisitors()
const { getDevice, getBrowser } = Helper

const payload = {
    device: getDevice(),
    browser: getBrowser(),
    url: location.href
}

let timeoutId = null
let intervalId = null

onBeforeMount(() => {
    if(!localStorage.language) {
        localStorage.language = "french"
    }
})

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