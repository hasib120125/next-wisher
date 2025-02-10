<template>
    <div class="flex md:justify-start justify-center gap-2">
        <button 
            @click="activeComponent = 'Active'" 
            class="px-4 py-1 rounded border"
            :class="activeComponent == 'Active' && 'bg-sky-500 text-white font-medium'"
        >
            {{ Helper.translate('Active') }} ({{ activeItems }})
        </button>
        <button 
            @click="activeComponent = 'Deleted'" 
            class="px-4 py-1 rounded border"
            :class="activeComponent == 'Deleted' && 'bg-sky-500 text-white font-medium'"
        >
            {{ Helper.translate('Blocked') }} ({{ deleteItems }})
        </button>
    </div>
</template>

<script setup>
import Helper from '@/Helper';
import useUser from '@/Pages/Backend/AdminDashboard/useUser.js'

const { activeComponent } = useUser()
defineProps({
    activeItems: {
        type: [String, Number],
        default: 0
    },
    deleteItems: {
        type: [String, Number],
        default: 0
    },
})
</script>

<style lang="scss" scoped>

</style>