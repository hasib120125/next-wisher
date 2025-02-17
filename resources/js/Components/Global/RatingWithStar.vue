<template>
    <div class="flex gap-2 items-center">
        <div class="relative bg-gray-300 h-[23px] overflow-hidden w-[112px]">
            <span 
                class="absolute top-0 left-0 bg-yellow-400 h-full"
                :style="{width: `${Number(rating) * 20}%`}"
            ></span>
            <svg class="absolute top-0 left-0 w-full h-full" width="290" height="58" viewBox="0 1 290 58" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M290 0H0V25.8333V60H290V25.8333V0ZM290 25.8333L277.5 36.25L281.667 55L265 44.5833L248.333 55L252.5 36.25L240 25.8333L256.667 23.75L265 5L273.333 23.75L290 25.8333ZM0 25.8333L16.6667 23.75L25 5L33.3333 23.75L50 25.8333L37.5 36.25L41.6667 55L25 44.5833L8.33331 55L12.5 36.25L0 25.8333ZM85 5L93.3334 23.75L110 25.8333L97.5 36.25L101.667 55L85 44.5833L68.3334 55L72.5 36.25L60 25.8333L76.6666 23.75L85 5ZM153.333 23.75L145 5L136.667 23.75L120 25.8333L132.5 36.25L128.333 55L145 44.5833L161.667 55L157.5 36.25L170 25.8333L153.333 23.75ZM213.333 23.75L205 5L196.667 23.75L180 25.8333L192.5 36.25L188.333 55L205 44.5833L221.667 55L217.5 36.25L230 25.8333L213.333 23.75Z" :fill="bg"/>
            </svg>
        </div>
        <span>{{ rating }}</span>
    </div>
</template>

<script setup>
    defineProps({
        rating: {
            type: [Number, String],
            default: 0
        },
        bg: {
            type: String,
            default: "white"
        },
    })
</script>