<template>
    <div class="flex gap-2 items-center">
        <RatingWithStar :rating="getAverageRatings(ratings)" class="font-bold" />
        <button v-if="ratings && ratings.length" @click="toggleModal=true" class="font-semibold text-sm text-blue-500">
            ({{ ratings.length }})
        </button>
    </div>

    <Modal v-model="toggleModal">
        <button @click="toggleModal = false" class="absolute top-4 right-4 bg-white hover:bg-red-500 hover:text-white rounded-full p-2 shadow">
            <CloseIcon />
        </button>
        <div class="xl:w-[500px] w-full bg-[#f9f9f9] rounded shadow-lg py-10 p-6">
            <RatingBox 
                v-if="ratings && ratings.length"
                class="rounded"
                :ratings="ratings"
            />
            <div class="mt-6 space-y-3 rounded">
                <RatingContent
                    v-for="item in orderRating(ratings)"
                    :key="item.id"
                    :item="item"
                />
            </div>
        </div>
    </Modal>

</template>

<script setup>
    import { onMounted, ref } from 'vue'
    import Modal from '@/Components/Library/Modal.vue'
    import RatingBox from '@/Components/Global/RatingBox.vue'
    import RatingContent from '@/Components/Global/RatingContent.vue'
    import RatingWithStar from '@/Components/Global/RatingWithStar.vue'
    import CloseIcon from '@/Icons/CloseIcon.vue'
    import { orderBy } from 'lodash'
    import { ratings, getRatings, getAverageRatings } from '@/Components/Backend/UserDashboard/useRatings.js'

    const props = defineProps({
        talentId: [Number, String]
    })
    const toggleModal = ref(false)

    const orderRating = (r) => {
        let orderedRating = orderBy(r, ['id'], ['desc'])
        return orderedRating ? orderedRating : []
    }

    onMounted(async() => {
        getRatings({talentId: props.talentId})
    })
</script>