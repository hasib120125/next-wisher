<template>
    <Master>
        <div class="bg-gray-100 border-b px-4">
            <div class="max-w-[1200px] mx-auto flex justify-between py-2">
                <h1 class="font-semibold text-xl">
                    <template v-if="category.parent">
                        {{ Helper.translate(category.parent.name, true) }} / 
                    </template>
                    {{ Helper.translate(category.name, true) }}
                </h1>
                <button @click="order=!order">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-all duration-300" :class="order ? 'text-black rotate-180' : ''" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M3 9l4 -4l4 4m-4 -4v14"></path>
                        <path d="M21 15l-4 4l-4 -4m4 4v-14"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- <div class="px-4 md:h-[80vh] overflow-y-auto"> -->
        <div class="px-4">
            <div class="max-w-[1200px] mx-auto py-5">
                <div v-if="computedValue && !isEmpty(computedValue)" class="grid xl:grid-cols-5 md:grid-cols-4 sm:grid-cols-3 grid-cols-2 items-end gap-6">
                    <template v-for="(talent, index) in computedValue" :key="index">
                        <Home2TalentItem
                            :blueName="true"
                            :talent="talent" class="w-full" 
                        >
                            <template #rating>
                                <div class="flex items-center gap-1 pr-1">
                                    <svg 
                                        class="w-4 h-4 drop-shadow" 
                                        :class="talent.rating_avg_rating ? 'text-yellow-500' : 'text-gray-300'"
                                        width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="m25 0 8.333 18.75L50 20.833 37.5 31.25 41.667 50 25 39.583 8.333 50 12.5 31.25 0 20.833l16.667-2.083L25 0Z" fill="currentColor"></path>
                                    </svg>
                                    <span>
                                        {{ isNaN(talent.rating_avg_rating) ? '0.0' : Number(talent.rating_avg_rating).toFixed(1) }}
                                    </span>
                                </div>
                            </template>
                        </Home2TalentItem>
                        <!-- <Link 
                            :href="route('item.details', {
                                id: talent.id,
                                username: `${String(talent.username).replaceAll(' ', '-')}`
                            })" 
                            class="relative border hover:shadow-xl transition-all"
                        >
                            <span 
                                v-if="!isEmpty(talent.talent_earning_type)"
                                class="absolute left-1 bottom-16 rounded-[30px] shadow bg-[#3e1e9a] bg-opacity-60 text-white rounded-br font-semibold text-lg px-4 py-1"
                            >
                                {{ Helper.priceFormate(talent.talent_earning_type[0].amount) }}
                            </span>
                            <span v-if="isLoadingImage" class="w-8 h-8 absolute -ml-4 -mt-4 top-1/2 left-1/2 z-20 animate-spin">
                                <svg class="w-8 h-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>

                            <img 
                                @load="isLoadingImage=false"
                                class="customRatio w-full h-full object-cover object-center" 
                                :src="`${$page.props.asset}${talent.profile_image}`" 
                                onerror="this.src='/no-image-found.webp'"
                                alt=""
                            >
                            <h2 class="absolute bottom-0 p-4 bg-white w-full bg-opacity-60 backdrop-blur-md border-t font-semibold truncate flex items-center justify-between">
                                <div class="truncate">
                                    {{ talent.name }}
                                </div>
                                <div class="flex text-black items-center gap-1">
                                    <svg 
                                        class="w-4 h-4 drop-shadow" 
                                        :class="talent.rating_avg_rating ? 'text-yellow-500' : 'text-gray-300'"
                                        width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="m25 0 8.333 18.75L50 20.833 37.5 31.25 41.667 50 25 39.583 8.333 50 12.5 31.25 0 20.833l16.667-2.083L25 0Z" fill="currentColor"></path></svg>
                                    {{ isNaN(talent.rating_avg_rating) ? '0.0' : Number(talent.rating_avg_rating).toFixed(1) }}
                                </div>
                            </h2>
                        </Link> -->
                    </template>
                </div>
                <div v-else class="text-center py-4">
                    {{ Helper.translate('No talent found') }}
                </div>
            </div>
        </div>
    </Master>
</template>

<script setup>
    import Helper from '@/Helper'
    import Master from '@/Pages/Backend/Master.vue'
    import { Link } from '@inertiajs/inertia-vue3'
    import { isEmpty, orderBy } from 'lodash'
    import Video from '@/Components/Global/Video.vue'
    import Home2TalentItem from '@/Components/Frontend/Home2TalentItem.vue'
    import { computed, ref } from 'vue'

    const isLoadingImage = ref(true)
    const props = defineProps({
        talents: {
            type: Array,
            default: []
        },
        category: {
            type: Object,
            default: {}
        }
    });

    const order = ref(false)
    const computedValue = computed(()=> orderBy(props.talents, ['created_at'], [order.value?'asc':'desc']))

</script>

<style scoped>
    .customRatio{
        aspect-ratio: 3/4;
        object-fit: cover;
        object-position: center;
    }
</style>