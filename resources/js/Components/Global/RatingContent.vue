<template>
    <div class="bg-[#fff] p-4 rounded shadow">
        <h3 class="text-gray-400 text-[12px]">
            {{ makeTranslatable(dayjs(item.created_at).fromNow()) }}
        </h3>
        <div class="flex items-center text-sm font-semibold">
            <RatingWithStar
                bg="#fff"
                :rating="item.rating"
            />/5
        </div>
        <p class="text-sm mt-4 text-gray-500">
            {{ item.feedback }}
        </p>
    </div>
</template>

<script setup>
    import RatingWithStar from '@/Components/Global/RatingWithStar.vue'
    import Helper from '@/Helper'
    import dayjs from 'dayjs'
    import relativeTime from 'dayjs/plugin/relativeTime'
    
    dayjs.extend(relativeTime);
    const props = defineProps({
        item: Object
    })

    const makeTranslatable = (dateInWord) => {
        let wordsArray = dateInWord.split(' ')
        let myDate = `${wordsArray[0]} ${Helper.translate(getText())}`
        
        function getText() {
            let newText = dateInWord.replace(wordsArray[0], '')
            return newText.trim()
        }

        return myDate
    }
</script>

<style lang="scss" scoped>

</style>