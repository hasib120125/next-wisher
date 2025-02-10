<template>
    <div>
        <div class="flex justify-between items-center mb-3">
            <h3 class="font-bold text-xl">{{ title }}</h3>
            <button v-if="!$page.props.auth.user" @click="$emit('onClick')" class="text-white text-opacity-60 cursor-pointer hover:text-opacity-100">
                {{ Helper.translate('More') }}
            </button>
            <Link v-else :href="route('category.items', category.slug)" class="text-white text-opacity-60 cursor-pointer hover:text-opacity-100">
                {{ Helper.translate('More') }}
            </Link>
        </div>
        <div class="grid grid-cols-4 gap-4 mb-16">
            <template v-for="talent in talents" :key="talent.id">
                <button v-if="!$page.props.auth.user" @click="$emit('onClick')" class="block">
                    <img class="min-h-[200px] w-full h-full object-cover object-center" :src="`${get($page.props, 'asset')}${talent.profile_image}`" alt="">
                    <h1 class="font-bold px-2 py-2 truncate" :title="talent.name">
                        {{ talent.name }}
                    </h1>
                </button>
                <Link v-else :href="route('item.details', {
                                id: talent.id,
                                username: `${String(talent.username).replaceAll(' ', '-')}`
                            })" class="block">
                    <img class="min-h-[200px] w-full h-full object-cover object-center" :src="`${get($page.props, 'asset')}${talent.profile_image}`" alt="">
                    <h1 class="font-bold px-2 py-2 truncate" :title="talent.name">
                        {{ talent.name }}
                    </h1>
                </Link>
            </template>
        </div>
    </div>
</template>

<script setup>
    import { Link } from '@inertiajs/inertia-vue3'
    import Video from '@/Components/Global/Video.vue'
    import { get } from 'lodash'
import Helper from '@/Helper';
    defineProps({
        title: String,
        talents: Array,
        category: Object
    })
</script>

<style scoped>
</style>