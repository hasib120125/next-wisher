<template>
    <div class="flex justify-between items-end pb-4">
        <h2 class="font-semibold -mb-1">{{ Helper.translate('Wish List') }}</h2>
        <!-- <CInput type="search" placeholder="Search" class="min-w-[300px]" /> -->
    </div>
    <div class="container mx-auto py-5">
        <div v-for="(item, index) in talentData.wish_sent" :key="index">
            <div class="grid grid-cols-4 gap-4">
                <div v-for="(item, index) in item.mail" :key="index" class="video customRatio height-[402px] overflow-hidden relative">
                    
                    <Video v-if="get(item, 'attachment')" :src="`${$page.props.asset}${get(item, 'attachment')}`" class="w-full absolute bottom-0" />
                    <a class="absolute top-4 right-4 z-10 bg-sky-500 text-white p-2 rounded" :href="`${$page.props.asset}${get(item, 'attachment')}`" download>
                        <DownloadIcon class="w-4 h-4" />
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import CInput from '@/Components/Global/CInput.vue'
import Video from '@/services/Video.vue'
import DownloadIcon from '@/Icons/DownloadIcon.vue'
import useTalents from '@/Pages/Backend/AdminDashboard/useTalents.js'
import { onMounted } from 'vue'
import { get } from 'lodash'
import Helper from '@/Helper'
const { talentData } = useTalents()

</script>

<style lang="scss" scoped></style>