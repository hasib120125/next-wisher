<template>
    <div class="wrapper">
        <Navigation />
        <div 
            class='grid items-start m-auto min-h-screen bg-slate-100'
            :class="previewMode ? '_preview' : '_leftSidebarAndMainContent'"
        >
            <aside v-if="!previewMode" class="p-4 pl-10 h-full bg-white">
                <LeftSidebar />
            </aside>
            <main class="p-4 relative select-none">
                <slot></slot>
            </main>
        </div>
    </div>
</template>

<script setup>
import Navigation from '@/Components/Backend/TalentDashboard/Calendar/Navigation.vue'
import LeftSidebar from '@/Components/Backend/TalentDashboard/Calendar/LeftSidebar/LeftSidebar.vue'
import useCalendar from '@/Components/Backend/TalentDashboard/Calendar/useCalendar.js'
import useVisitors from "@/Pages/Backend/AdminDashboard/useVisitors"
import { onMounted, onUnmounted } from "vue"
import Helper from '@/Helper'

const { previewMode } = useCalendar()

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

<style scoped>
.wrapper{
    grid-template-rows: 65px 1fr;
}
._leftSidebarAndMainContent{
    grid-template-columns: 350px calc(100vw - 370px);
}
._preview{
    grid-template-columns: 1fr;
}
</style>