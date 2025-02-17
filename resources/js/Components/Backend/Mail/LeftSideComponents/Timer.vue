<template>
    <div v-if="expirationDate" class="text-xs font-semibold" :class="timeout ? 'opacity-50' : ''">{{ timerData }}</div>
</template>

<script setup>
import { onMounted, onUnmounted, ref } from "vue"
import moment from "moment";

const props = defineProps({
    expirationDate: {
        type: [String, Date]
    },
    downloadStatus: {
        type: Boolean,
        default: false,
    },
    dateDifferenceInMillisecond: {
        type: Number,
        default: 0,
    },
})

const timerData = ref('0:0:0:0')
const timeout = ref(false)
let intervalId = null

function countdown(milliseconds) {
    const secondsInMillisecond = 1000;
    const minutesInMillisecond = secondsInMillisecond * 60;
    const hoursInMillisecond = minutesInMillisecond * 60;
    const daysInMillisecond = hoursInMillisecond * 24;

    const days = Math.floor(milliseconds / daysInMillisecond);
    milliseconds -= days * daysInMillisecond;

    const hours = Math.floor(milliseconds / hoursInMillisecond);
    milliseconds -= hours * hoursInMillisecond;

    const minutes = Math.floor(milliseconds / minutesInMillisecond);
    milliseconds -= minutes * minutesInMillisecond;

    const seconds = Math.floor(milliseconds / secondsInMillisecond);

    return {
        days: days,
        hours: hours,
        minutes: minutes,
        seconds: seconds
    };
}

const milliseconds = props.dateDifferenceInMillisecond;

const subtract = ref(0);
const second = 1000;
function updateCountdown() {
    if (milliseconds <= 0) {
        clearInterval(intervalId)
        return
    }
    const countdownValues = countdown(milliseconds - subtract.value);
    subtract.value += second
    timerData.value = `${countdownValues.days}:${countdownValues.hours}:${countdownValues.minutes}:${countdownValues.seconds}`
}

onMounted(() => {
    intervalId = setInterval(updateCountdown, 1000)
})

// onUnmounted(() => {
//     clearInterval(intervalId)
// })

// const timeout = ref(false)
// const timerData = ref('0:0:0:0')
// const tick = () => {
//     if(!props.expirationDate) return false
//     const currentDate = new Date()
//     const expirationDate = new Date(props.expirationDate)
//     const oneDay   = 86400000
//     // 2023-07-30 23:08:20
//     // Calculate the time difference between the expiration date and current date
//     const timeDiff = (expirationDate - currentDate);

//     // Convert the time difference to days, hours, minutes, and seconds
//     let days = Math.round(timeDiff / oneDay) - 1;
//     let hours = Math.round((timeDiff % oneDay / (1000 * 60 * 60))) - 1
//     let minutes = Math.floor((timeDiff % (1000 * 60 * 60)) / (1000 * 60)) - 1
//     let seconds = Math.floor((timeDiff % (1000 * 60)) / 1000)

//     days = days < 0 ? 0 : days
//     hours = hours < 0 ? 0 : hours
//     minutes = minutes < 0 ? 0 : minutes
//     seconds = seconds < 0 ? 0 : seconds

//     timerData.value = `${days}:${hours}:${minutes}:${seconds}`
//     if(!days && !hours && !minutes && !seconds){
//         timeout.value = true
//         clearInterval(intervalId)
//     }
// }


</script>