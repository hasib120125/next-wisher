<template>
    <div>
        <Create />
    </div>
</template>

<script setup>
import Create from '@/Pages/Calendar/Create.vue'
import useCalendar from '@/Components/Backend/TalentDashboard/Calendar/useCalendar.js'
import { onMounted, onUnmounted } from 'vue'

const { calendarPayload, previewMode } = useCalendar()
const props = defineProps({
    calendar: {
        type: Object
    }
})

onMounted(() => {
    previewMode.value = true
    calendarPayload.value = {
        user_id: props.calendar.user_id,
        year: props.calendar.year,
        selectedPageIndex: props.calendar.selectedPageIndex,
        language: props.calendar.language,
        weekStartDay: props.calendar.weekStartDay,
        theme: props.calendar.theme,
        settings: props.calendar.settings
    }
})

onUnmounted(() => {
    previewMode.value = false
})
</script>