<template>
    <div class="relative max-w-[1200px] sm:px-10 px-5 w-full mx-auto">
        <div v-if="title" class="font-semibold text-xl mb-3 mt-4">{{ Helper.translate(title) }}</div>
        <div class="flex overflow-x-auto md:grid grid-cols-2 xl:grid-cols-5 lg:grid-cols-4 md:grid-cols-3 gap-4">
            <Home2TalentItem 
                v-for="index in 5"
                :key="index"
                @onClick="$emit('onClick', (indexStart * 5) + index)" 
                :role="user?.role"
                :blueName="blueName"
                :talent="getTalent((indexStart * 5) + index)?.talent" class="md:w-full w-[calc(50%-16px)] flex-shrink-0" 
            />
        </div>
    </div>
</template>

<script setup>
import { get, find } from 'lodash'
import Home2TalentItem from '@/Components/Frontend/Home2TalentItem.vue'
import { usePage } from '@inertiajs/inertia-vue3'
import { computed, ref } from 'vue'
import 'vue3-carousel/dist/carousel.css'
import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel'
import AngleLeftIcon from '@/Icons/AngleLeftIcon.vue'
import AngleRightIcon from '@/Icons/AngleRightIcon.vue'
import Helper from '@/Helper'

const props = defineProps({
    homeTalents: Array,
    indexStart: {
        type: Number,
        default: 0,
    },
    blueName: {
        type: Boolean,
        default: false,
    },
    title: {
        type: String,
        default: ''
    },
})
const myCarousel = ref(null)
const user = computed(() => {
    if (usePage().props.value.auth.user) {
        const { role, name } = get(usePage().props.value, 'auth.user')
        return { role, name }
    }

    return null
})

const getTalent = (index) => {
    const talents = props.homeTalents
    if (talents.length) {
        return find(talents, item => item.box_index == index)
    }
    return null
}

const breakpoints = {
    640: {
        itemsToShow: 2,
        itemsToScroll: 2,
        snapAlign: 'start',
    },
    768: {
        itemsToShow: 3,
        itemsToScroll: 3,
        snapAlign: 'start',
    },

    1024: {
        itemsToShow: 4,
        itemsToScroll: 4,
        snapAlign: 'start',
    }
}
</script>