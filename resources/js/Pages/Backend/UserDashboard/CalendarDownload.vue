<template>
    <div class="grid gap-5 !pointer-events-none">
        <template v-for="index in 14" :key="index">
            <CalendarPage :selectedPageIndex="index-1" />
        </template>
    </div>
</template>

<script setup>
    import CalendarPage from '@/Components/Backend/TalentDashboard/Calendar/CalendarPage/index.vue'
    import useCalendar from '@/Components/Backend/TalentDashboard/Calendar/useCalendar.js'
    import { computed, onMounted } from 'vue'
    import { usePage } from '@inertiajs/inertia-vue3';

    const { calendarPayload } = useCalendar()
    const getCalendar = computed(() => usePage().props.value.calendar)
    
    onMounted(() => {
        calendarPayload.value = getCalendar.value
    })
</script>