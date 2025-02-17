<template>
    <div>
        <div class="mb-2">
            <h1 class="text-xl font-bold">{{ Helper.translate(talent.name, true) }}</h1>
            <h2 class="text-gray-500">
                {{ Helper.translate(get(talent, 'category.name')) }} / 
                {{ Helper.translate(get(talent, 'subcategory.name')) }}
            </h2>
        </div>

        <ShowRating
            :talentId="get(talent, 'id')"
        />
        
        <div v-if="talent.bio">
            <h2 class="font-black">
                {{ Helper.translate('Biography') }}
            </h2>
            <p class="font-semibold text-sm">
                {{ talent.bio || Helper.translate('Biography not available') }}
            </p>
        </div>
    </div>
</template>

<script setup>
    import Helper from '@/Helper'
    import { get } from 'lodash'
    import ShowRating from '@/Components/Global/ShowRating.vue'

    defineProps({
        talent: Object
    })
</script>