<template>
    <div>
        <div class="flex gap-3 flex-wrap">
            <div class="relative">
                {{ Helper.translate('Upload video') }}
                <label class="cursor-pointer gap-2 h-[40px] py-2 rounded text-center flex justify-center items-center">
                    <FileInput @input="(e) => {
                        form.video = e.target.files[0]
                    }" accept="video/*" />
                </label>
                <span class="absolute top-full left-0 text-xs text-red-500">{{ Helper.translate(form.errors.video, true) }}</span>
            </div>
        </div>
        <div class="flex gap-3 flex-wrap mt-5">
           <div class="relative">
                {{ Helper.translate('Upload thumbnail') }}
                <label class="cursor-pointer gap-2 h-[40px] py-2 rounded text-center flex justify-center items-center">
                    <FileInput @input="(e) =>  {
                        form.cover_image = e.target.files[0]
                    }" accept="image/*" />
                </label>
                <span class="absolute top-full left-0 text-xs text-red-500">{{ Helper.translate(form.errors.cover_image, true) }}</span>
            </div>
        </div>
        <div class="font-bold mt-5">
            {{ Helper.translate('Write title') }}
            <div class="flex items-center gap-6">
                <div class="relative w-full">
                    <CInput v-model="form.title" type="text" placeholder="Title" class="w-full font-normal" />
                    <span class="absolute top-full left-0 text-xs text-red-500">{{ Helper.translate(form.errors.title, true) }}</span>
                </div>
                <button @click="handleSave" :disabled="form.processing" class="px-4 py-2 bg-red-500 text-white rounded">{{ Helper.translate('Post') }}</button>
            </div>
            <template v-if="form.progress">
                {{ Helper.translate(get(form.progress, 'percentage'), true) }}%
                <progress :value="form.progress.percentage" max="100"></progress>
            </template>
        </div>
        <hr class="mt-10" />
        <div class="rounded mt-10 grid grid-cols-4 gap-6">
            <div v-for="(post, index) in posts" :key="index" class="relative border">
                <Video 
                    :poster="post.path"
                    :cover="post.cover_image"
                />
                <div class="absolute bottom-0 p-4 bg-white w-full bg-opacity-50 backdrop-blur-md">
                    <h3>{{ Helper.translate(post.title, true) }}</h3>
                    
                    <button @click="handleDelete(post.id)" class="bg-red-500 text-white px-6 py-1 ml-auto block rounded mt-4 font-semibold">
                        {{ Helper.translate('Delete') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import CInput from '@/Components/Global/CInput.vue';
import Video from '@/Components/Global/Video.vue'
import Helper from '@/Helper';
import { useForm, usePage } from '@inertiajs/inertia-vue3';
import { computed, ref } from 'vue';
import { get } from 'lodash'
import { toast } from 'vue3-toastify';
import FileInput from '@/Components/Global/FileInput.vue'

const posts = computed(() => {
    return usePage().props.value.posts
})

const form = useForm({
    title: null,
    video: null,
    cover_image: null,
});

const handleSave = () => {
    if (!form.video) {
        form.errors.video = "Video is required"
    }
    if (!form.cover_image) {
        form.errors.cover_image = "Thumbnail is required"
    }
    if (!form.title) {
        form.errors.title = "Title is required"
    }

    if(!form.video || !form.cover_image || !form.title){
        return
    }

    form.errors.video = form.errors.cover_image = form.errors.title = ""

    Helper.getVideoDuration(form.video, (valid) => {
        if (valid) {
            form.post(route('talent.myLife.post'), {
                onSuccess() {
                    form.reset()
                    form.video = null
                    form.cover_image = null
                }
            });
        } else {
            toast.error(Helper.translate('Video duration should not be more then 30 seconds'))
        }
    }, 30)
}

const handleDelete = (id) => {
    Helper.confirm(Helper.translate('Do you really want to delete this post?'), () => {
        form.post(route('talent.myLife.postDelete', id));
    })
}
</script>