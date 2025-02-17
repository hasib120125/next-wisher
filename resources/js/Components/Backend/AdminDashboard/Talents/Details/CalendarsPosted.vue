<template>
    <div class="flex justify-between items-end pb-4">
        <h2 class="font-semibold -mb-1">{{ Helper.translate('Calendars Posted') }}</h2>
        <CInput type="search" placeholder="Search" class="min-w-[300px]"/>
    </div>
    <!-- <div class="flex gap-2">
        <button 
            @click="activeComponent = 'Active'" 
            class="px-4 py-1 rounded border bg-sky-500 text-white font-medium"
        >
            Active (200)
        </button>
        <button 
            @click="activeComponent = 'Deleted'" 
            class="px-4 py-1 rounded border"
            :class="activeComponent == 'Deleted' && 'bg-sky-500 text-white font-medium'"
        >
            Deleted (15)
        </button>
    </div> -->
    <div class="container mx-auto py-5">
        <div class="grid grid-cols-3 gap-4">
            <div v-for="(calender, index) in calender_posted" class="relative transition-all text-white border hover:shadow-xl" :key="'calender'+index">
                <a :href="route('calendar.download', calender.id)">
                    <DownloadIcon class="absolute right-4 top-4 w-6 h-6 cursor-pointer drop-shadow-lg" />
                </a>
                <img :src="calender.settings[0].path" class="customRatio bg-gray-200" alt="" >
                <button class="absolute left-1/2 bottom-10 transform -translate-x-1/2 cursor-pointer p-2 rounded-full transition-all bg-red-400 hover:shadow-lg hover:bg-red-500">
                    <CloseIcon class="w-4 h-4" />
                </button>
                <h3 class="text-black p-1 px-2 text-center font-semibold">{{ Helper.translate('Posted') }}: {{ Helper.dateFormate(calender.created_at) }}</h3>
            </div>
        </div>
    </div>
</template>

<script setup>
import CInput from '@/Components/Global/CInput.vue'
import CloseIcon from '@/Icons/CloseIcon.vue'
import DownloadIcon from '@/Icons/DownloadIcon.vue'
import useTalents from '@/Pages/Backend/AdminDashboard/useTalents.js'
import { usePage } from '@inertiajs/inertia-vue3'
import { onMounted, ref } from 'vue'
import { isArray, get } from 'lodash'
import Helper from '@/Helper'
const calender_posted = ref([])

onMounted(()=>{
    let calendars = get(usePage().props.value, 'talent_data.calender_posted')
    if (isArray(calendars)) {
        calender_posted.value = calendars
    }
})
</script>

<style lang="scss" scoped>

</style>