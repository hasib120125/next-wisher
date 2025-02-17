<template>
    <div class="flex items-center gap-4 pb-4">
        <h2 class="font-semibold">{{ Helper.translate('Profile Video') }}</h2>
    </div>
    <div class="container py-1 relative w-fit">
        <a
            :href="$page.props.asset+get($page.props, 'user.video_path')"
            class="py-0.5 px-4 bg-blue-500 text-white rounded flex items-center gap-2 absolute z-10 top-2 right-2"
            :download="get($page.props, 'user.username')"
        >
            <DownloadIcon class="w-4 h-4" />
            {{ Helper.translate('Download video') }}
        </a>
        <div class="h-[402px] customRatio">
            <Video 
                :download="true"
                :src="$page.props.asset+get($page.props, 'user.video_path')"
            />
        </div>
    </div>
</template>

<script setup>
import Video from '@/services/Video.vue'
import Helper from '@/Helper';
import { usePage } from '@inertiajs/inertia-vue3';
import { onMounted } from 'vue';
import DownloadIcon from '@/Icons/DownloadIcon.vue';
import { get } from 'lodash'

onMounted(() => {
    console.log(usePage().props.value.asset+get(usePage().props.value, 'user.video_path'));
})
</script>

<style lang="scss" scoped>

</style>