<template>
    <div class="flex gap-2">
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
            {{ Helper.translate('Blocked') }} ({{ deleteItem }})
        </button>
    </div>
</template>

<script setup>
import Helper from '@/Helper';
import useTalents from '@/Pages/Backend/AdminDashboard/useTalents.js'
const { activeComponent } = useTalents()

defineProps({
    deleteItem: {
        type: Number,
        default: 0
    },
    activeItems: {
        type: Number,
        default: 0
    },
});


</script>