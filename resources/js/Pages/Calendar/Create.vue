<template>
    <Head :title="Helper.translate('Create Calendar')" />
    <Master class="relative">
        <CalendarPageNavigate class="absolute left-0 w-full flex justify-between top-[40%] px-10" />
        <CalendarPage :selectedPageIndex="calendarPayload.selectedPageIndex" />
        <PreviewChanger />
    </Master>
</template>

<script setup>
import Master from './Master.vue';
import useCalendar from '@/Components/Backend/TalentDashboard/Calendar/useCalendar.js'
import PreviewChanger from '@/Components/Backend/TalentDashboard/Calendar/PreviewChanger/index.vue'
import CalendarPage from '@/Components/Backend/TalentDashboard/Calendar/CalendarPage/index.vue'
import CalendarPageNavigate from '@/Components/Backend/TalentDashboard/Calendar/CalendarPageNavigate.vue'
import { onMounted } from 'vue'
import { cloneDeep } from 'lodash';
import { Head } from '@inertiajs/inertia-vue3';
import Helper from '@/Helper';

const { calendarPayload, defaultPayload } = useCalendar()

onMounted(() => {
    calendarPayload.value = cloneDeep(defaultPayload)
})
</script>
