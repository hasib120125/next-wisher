<template>
    <div class="flex justify-between items-end pb-4">
        <h2 class="font-semibold -mb-1">{{ Helper.translate('My Life Videos') }}</h2>
        <!-- <CInput type="search" placeholder="Search" class="min-w-[300px]"/> -->
    </div>
    <!-- <div class="flex gap-2">
        <button 
            @click="activeComponent = 'Active'" 
            class="px-4 py-1 rounded border bg-sky-500 text-white font-medium"
        >
            {{ Helper.translate('Active') }} (200)
        </button>
        <button 
            @click="activeComponent = 'Deleted'" 
            class="px-4 py-1 rounded border"
            :class="activeComponent == 'Deleted' && 'bg-sky-500 text-white font-medium'"
        >
            {{ Helper.translate('Deleted') }} (15)
        </button>
    </div> -->
    <div class="container mx-auto py-5">
        <div class="grid grid-cols-4 gap-4">
            <template v-for="(item, index) in talentData.mylife_videos" :key="index">
                <Video
                    :download="true"
                    deletable="true" 
                    :poster="item.path"
                    :cover="item.cover_image"
                />
            </template>
            <!-- <Video :download="true" deletable="true" poster="https://images.unsplash.com/photo-1673605124954-132c332de83f?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHw0fHx8ZW58MHx8fHw%3D&auto=format&fit=crop&w=800&q=60" />
            <Video :download="true" deletable="true" poster="https://images.unsplash.com/photo-1673868077539-9c3120f78420?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHw4fHx8ZW58MHx8fHw%3D&auto=format&fit=crop&w=800&q=60" />
            <Video :download="true" deletable="true" poster="https://images.unsplash.com/photo-1673845861099-2785734e247c?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHwxMHx8fGVufDB8fHx8&auto=format&fit=crop&w=800&q=60" /> -->
        </div>
    </div>
</template>

<script setup>
import CInput from '@/Components/Global/CInput.vue'
import Video from '@/Components/Global/Video.vue'
import Helper from '@/Helper';
import useTalents from '@/Pages/Backend/AdminDashboard/useTalents.js'

const { talentData } = useTalents()

</script>

<style lang="scss" scoped>

</style>