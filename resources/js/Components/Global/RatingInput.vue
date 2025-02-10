<template>
    <div class="flex items-center gap-2">
        <svg 
            v-for="index in 5" 
            @click="() => {
                rating = index
                $emit('onClick', rating)
            }" 
            :key="index"
            class="w-4 h-4 cursor-pointer flex-shrink-0 flex-grow-0 text-gray-200 drop-shadow-sm" 
            :style="{color: getColor(rating, index)}"
            width="50" 
            height="50" 
            viewBox="0 0 50 50" 
            fill="none" xmlns="http://www.w3.org/2000/svg"
        >
            <path d="m25 0 8.333 18.75L50 20.833 37.5 31.25 41.667 50 25 39.583 8.333 50 12.5 31.25 0 20.833l16.667-2.083L25 0Z" fill="currentColor"></path>
        </svg>
    </div>
</template>

<script setup>
    import { ref } from 'vue'

    const rating = ref(0)
    const getColor = (rating, index) => {
        if(index <= rating){
            return '#facc14'
        }
        return '#d1d5db'
    }
</script>

<style lang="scss" scoped>

</style>