<template>
    <div class="flex gap-2 items-center">
        <span class="text-black font-bold text-sm flex-shrink-0 flex-grow-0 w-4 text-right">
            {{ rating }}
        </span>
        <svg class="w-4 h-4 flex-shrink-0 flex-grow-0 text-yellow-500 drop-shadow-sm" width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="m25 0 8.333 18.75L50 20.833 37.5 31.25 41.667 50 25 39.583 8.333 50 12.5 31.25 0 20.833l16.667-2.083L25 0Z" fill="currentColor"/></svg>
        <span class="block h-2 bg-gray-200 sm:w-[200px] w-full rounded">
            <span 
                class="block h-2 bg-yellow-500 rounded"
                :class="{'shadow': rating}"
                :style="{width: `${(rating*20)}%`}"
            ></span>
        </span>
        <span class="font-semibold text-[12px] text-left">{{ total }}</span>
    </div>
</template>

<script setup>
    const props = defineProps({
        rating: [Number, String],
        total: [Number, String]
    })
</script>