<template>
    <Head title="Featured Video" />
    <DashboardLayout :header="false" :footer="false" title="Featured Video">
        <template v-slot:leftSidebar>
            <LeftSide />
        </template>
        <template v-slot:content>
            <div class="grid gap-5">

                <form @submit.prevent="handleSubmit" class="grid grid-cols-1 gap-5 border-b border-gray-400 max-w-[400px] relative">
                    <div class="relative mb-2">
                        <h2>{{ Helper.translate('Thumbnail') }}</h2>
                        <CInput type="file" @input="(e)=> form.thumbnail = e.target.files[0]" accept="image/*" class="max-w-fit" />
                        <span class="absolute top-full left-0 text-xs text-red-500">{{ Helper.translate(form.errors.thumbnail, true) }}</span>
                    </div>

                    <div class="relative mb-2">
                        <h2>{{ Helper.translate('Video') }}</h2>
                        <CInput type="file" @input="(e)=> form.video = e.target.files[0]" accept="video/*" class="max-w-fit" />
                        <span class="absolute top-full left-0 text-xs text-red-500">{{ Helper.translate(form.errors.video, true) }}</span>
                    </div>

                    <div class="relative mb-2">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" v-model="form.status">
                            {{ Helper.translate('Publish status') }}
                        </label>
                        <span class="absolute top-full left-0 text-xs text-red-500">{{ Helper.translate(form.errors.status, true) }}</span>
                    </div>

                    <div v-if="featured_videos.length < 4" class="relative mb-6 flex flex-col">
                        <div class="mb-2">
                            <template v-if="form.progress">
                                {{ Helper.translate(get(form.progress, 'percentage'), true) }}%
                                <progress :value="form.progress.percentage" max="100"></progress>
                            </template>
                        </div>
                        <button  class="bg-red-500 text-white px-4 py-1 rounded-md ml-auto flex items-center gap-2">
                            <Preloader v-if="form.processing" class="w-4 h-4" />
                            {{ Helper.translate('Save') }}
                        </button>
                    </div>
                    <p v-else class="text-red-500 absolute top-0 left-0 right-0 bottom-0 flex items-center justify-center bg-white/70 font-bold text-lg">
                        {{ Helper.translate('You already added 4 videos') }}
                    </p>

                </form>

                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4 md:gap-7">
                    <div
                        v-for="(item, index) in featured_videos"
                        :key="item.id"
                        class="relative group"
                    >
                        <FeatureVideoItem
                            :path="item.path"
                            :cover="item.thumbnail"
                        />
                        <div class="absolute -bottom-12 left-0">
                            <label class="relative inline-flex items-center mb-4 cursor-pointer">
                                <input @change="handleStatus(item.id)" type="checkbox" class="sr-only peer" :checked="+item.status==1">
                                <div class="w-11 h-6 bg-red-100 border-red-500 border rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-red-500 peer-checked:after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600"></div>
                                <Preloader v-if="statusForm.processing" class="w-4 h-4 ml-2 text-red-500" />
                                <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300 select-none">
                                    <template v-if="+item.status==1">
                                        {{ Helper.translate('Active') }}
                                    </template>
                                    <template v-else>
                                        {{ Helper.translate('Pending') }}
                                    </template>
                                </span>
                            </label>
                        </div>
                        <button @click="handleDelete(item.id)" class="bg-red-500 text-white w-10 h-10 flex justify-center items-center rounded-full absolute top-4 right-4 group-hover:scale-110">
                            <DeleteIcon class="w-6 h-6" />
                        </button>
                    </div>
                </div>
                
            </div>
        </template>
    </DashboardLayout>
</template>

<script setup>
import DashboardLayout from './AdminDashboard/DashboardLayout.vue'
import LeftSide from '@/Components/Backend/AdminDashboard/LeftSide.vue'
import { Head, useForm } from '@inertiajs/inertia-vue3'
import Helper from '@/Helper.js'
import CInput from '@/Components/Global/CInput.vue'
import FeatureVideoItem from '@/Components/Frontend/FeatureVideoItem.vue'
import DeleteIcon from '@/Icons/DeleteIcon.vue'
import Preloader from '@/Components/Global/Preloader.vue'
import { get } from 'lodash'
import { Inertia } from '@inertiajs/inertia'


const props = defineProps({
    featured_videos: {
        type: Array,
        default: []
    }
})


const form = useForm({
    video: null,
    thumbnail: null,
    status: false,
})

const handleSubmit = () => {
    form.post(route('admin.saveFeaturedVideo'), {
        onSuccess(){
            form.reset()
        }
    })
}

const handleDelete = (id) => {
    Helper.confirm('Are you sure?', ()=> {
        Inertia.delete(route('admin.deleteFeaturedVideo', id))
    })
}

const statusForm = useForm({});

const handleStatus = (id) => {
    // Inertia.post(route('admin.statusFeaturedVideo', id))
    statusForm.post(route('admin.statusFeaturedVideo', id))
}



</script>


<style scoped>
</style>