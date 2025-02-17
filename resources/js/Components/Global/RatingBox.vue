<template>
    <div class="flex gap-4 justify-center">
        <div class="md:w-[90px] md:h-[90px] w-[80px] h-[80px] flex justify-center items-center rounded-md bg-yellow-500 flex-shrink-0 flex-grow-0 shadow-lg">
            <div class="text-white text-center drop-shadow">
                <h2 class="font-black text-2xl">
                    {{ getAverageRatings(ratings) }}
                </h2>
                <h2 class="text-sm font-bold">
                    {{ Helper.translate('Out of') }} 5
                </h2>
            </div>
        </div>
        <div class="flex flex-col-reverse w-full">
            <RatingWithBar 
                v-for="index in 5"
                :key="index"
                :rating="index" 
                :total="ratingCountGroupBy[index]"
            />
        </div>
    </div>
</template>
<script setup>
    import RatingWithBar from '@/Components/Global/RatingWithBar.vue'
    import { getRatingCountGroupBy, getAverageRatings } from '@/Components/Backend/UserDashboard/useRatings.js'
    import { onMounted, ref } from 'vue'
    import Helper from '@/Helper'

    const props = defineProps({
        ratings: Array
    })
    const ratingCountGroupBy = ref({})
    onMounted(() => {
        ratingCountGroupBy.value = getRatingCountGroupBy(props.ratings)
    })
</script>