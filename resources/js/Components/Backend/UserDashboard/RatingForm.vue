<template>
    <div class="mt-4 w-[300px]">
        <h3 class="font-bold mb-2">{{ Helper.translate('Your Rating') }}</h3>
        <RatingInput @onClick="(rating) => form.rating = rating" />
        <textarea
            class="mt-3 w-[300px] border border-gray-300 rounded placeholder:text-gray-400 text-sm"
            v-model="form.feedback"
            :placeholder="Helper.translate('Write Feedback')"
            rows="4"
        ></textarea>
        <button @click="handleRatings" :disabled="loadingRatings" class="px-4 py-1 bg-blue-500 mt-1 text-white rounded shadow ml-auto block font-semibold">
            {{ Helper.translate('Send') }}
        </button>
    </div>
</template>

<script setup>
    import { ref } from 'vue'
    import RatingInput from '@/Components/Global/RatingInput.vue'
    import Helper from '@/Helper'
    import { insertRatings, getRatings } from '@/Components/Backend/UserDashboard/useRatings.js'
    import { toast } from 'vue3-toastify'

    const props = defineProps({
        userId: [String, Number],
        talentId: [String, Number],
        talentEarningId: [String, Number],
        talent: [String, Number],
    })
    const form = ref({
        rating: '',
        feedback: ''
    })

    const loadingRatings = ref(false)
    
    const handleRatings = async () => {
        loadingRatings.value = true
        if (!form.value.rating || !form.value.feedback) {
            toast.error(Helper.translate('Rating and feedback are required'))
            loadingRatings.value = false
            return
        }

        await insertRatings({
            user_id: props.userId,
            talent_id: props.talentId,
            talent_earning_id: props.talentEarningId,
            ...form.value
        })
        toast.success(Helper.translate('Review submitted'))
        getRatings({
            userId: props.userId,
            talentId: props.talentId,
            talentEarningId: props.talentEarningId
        })
        loadingRatings.value = true
        let url = `${location.origin}/talents/${props.talentId}/${String(props.talent.username).replace(' ', '-')}`
        location.href = url;
    }
</script>

<style lang="scss" scoped>

</style>