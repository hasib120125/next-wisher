<template>
    <div class="flex gap-4 justify-between">
        <slot name="pageSize" />
        <div class="flex gap-4 items-center">
            <button
                v-for="(item, index) in ['Tips', 'Wishes', 'My Life', 'Calendar']"
                :key="index"
                @click="$emit('updateTab', item)"
                class="font-semibold border rounded px-4 py-1"
                :class="activeTab==item ? 'bg-blue-500 text-white' : ''"
            >
                {{ Helper.translate(item) }}
            </button>
        </div>
        <div class="">
            <slot name="search" />
        </div>
    </div>
</template>

<script setup>
import CInput from '@/Components/Global/CInput.vue'
import Helper from '@/Helper';

const props = defineProps({
    activeTab: String,
})

</script>