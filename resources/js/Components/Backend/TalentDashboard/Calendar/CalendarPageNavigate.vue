<template>
    <div>
        <button @click="change(-1)" class="cursor-pointer">
            <AngleLeftIcon />
        </button>
        <button  @click="change(+1)" class="cursor-pointer">
            <AngleRightIcon />
        </button>
    </div>
</template>

<script setup>
import useCalendar from '@/Components/Backend/TalentDashboard/Calendar/useCalendar.js'
import AngleLeftIcon from '@/Icons/AngleLeftIcon.vue'
import AngleRightIcon from '@/Icons/AngleRightIcon.vue'
import { onMounted, ref } from 'vue'

const index = ref(0)
const { calendarPayload } = useCalendar()
const change = (direction) => {
    index.value += direction
    if(index.value > 13) index.value = 0
    if(index.value < 0) index.value = 13

    calendarPayload.value.selectedPageIndex = index.value
}
</script>