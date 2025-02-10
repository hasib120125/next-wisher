<template>
    <div v-if="expirationDate" class="text-xs font-semibold" :class="timeout ? 'opacity-50' : ''">{{ timerData }}</div>
</template>

<script setup>
import { onMounted, onUnmounted, ref } from "vue"
import { differenceInMilliseconds, differenceInDays, addMilliseconds, addDays, addHours, addMinutes, addSeconds } from 'date-fns';

const props = defineProps({
    expirationDate: {
        type: [String, Date]
    },
    downloadStatus: {
        type: Boolean,
        default: false,
    },
})

const timerData = ref('0:0:0:0')
const timeout = ref(false)
let intervalId = null

function updateCountdown() {
    const targetDate = new Date(props.expirationDate);
    const now = new Date();
    const duration = differenceInMilliseconds(targetDate, now);
    if (duration <= 0) {
        clearInterval(intervalId)
    } else {
        const days = differenceInDays(targetDate, now);
        const hours = new Date(duration).getUTCHours();
        const minutes = new Date(duration).getUTCMinutes();
        const seconds = new Date(duration).getUTCSeconds();

        timerData.value = `${days}:${hours}:${minutes}:${seconds}`;
    }
}

onMounted(() => {
    intervalId = setInterval(updateCountdown, 1000)
})

onUnmounted(() => {
    clearInterval(intervalId)
})


</script>