<template>
    <GlobalLayout class="h-full">
        <div class="h-full relative">
            <Header v-if="header" />
            <!-- <div :class="route().current('payment.tips.amount') ? 'min-h-[calc(100vh-108px)]' : 'min-h-[calc(100vh-164px)]'"> -->
                <!-- {{ route().current('payment.tips.amount') ? 'some' : 'more' }} -->
            <div class="min-h-[calc(100%-80px)]">
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
import { onMounted, onUnmounted } from "vue"
import Helper from '@/Helper'

defineProps({
    header: {
        type: Boolean,
        default: true
    },
    footer: {
        type: Boolean,
        default: true
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
onMounted(() => {
    timeoutId = setTimeout(() =>{
        makeVisitors(payload)
        intervalId = setInterval(() => {
            makeVisitors(payload)
        }, hitDelay)
    }, hitDelay)
})

onUnmounted(() => {
    clearTimeout(timeoutId)
    clearInterval(intervalId)
})
</script>