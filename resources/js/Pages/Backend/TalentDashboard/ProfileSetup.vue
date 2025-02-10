<template>
    <Head title="Profile Setup" />
    <DashboardLayout>
        <template v-slot:leftSidebar>
            <LeftSide />
        </template>
        <template v-slot:content>
            <div class="h-full flex flex-col justify-center">
                <div class="grid lg:grid-cols-3 gap-6">

                    <!-- for mobile -->
                    <ProfileAdditionalInfo
                        class="block lg:hidden"
                    />

                    <div class="video">
                        <div class="hover:shadow-xl transition-all text-white overflow-hidden relative bg-black flex items-center justify-center" style="height: 402px;">
                            <h2 v-if="!talent.video_path" class="text-[#878787] font-bold">
                                {{ Helper.translate("Add video") }}
                            </h2>
                            <span v-if="form.processing" class="price cursor-pointer absolute top-4 z-10 right-4 shadow bg-red-500 font-bold p-2 rounded text-white">
                                <Preloader class="w-5 h-5" />
                            </span>
                            <label v-else class="price cursor-pointer absolute top-4 z-10 right-4 shadow bg-sky-500 font-bold p-2 rounded text-white">
                                <VideoIcon />
                                <input type="file" @input="handleSelectVideo($event.target.files[0])" accept="video/*" class="hidden">
                            </label>
                            <Video 
                                v-if="talent.video_path && showHandle"
                                :src="`${$page.props.asset}${talent.video_path}`" 
                                class="w-full absolute bottom-0"
                            />
                        </div>

                        <!-- for mobile -->
                        <ProfileImageUpload
                            class="block lg:hidden mt-4 border"
                            :profileForm="profileForm"
                            @oninput="(e) => handleProfile(e.target.files[0])"
                        />

                        <Bio 
                            class="mt-4"
                            v-model="form.bio"
                            :form="form"
                        />
                    </div>

                    <div class="grid items-center">
                        <div class="grid gap-4">
                            <!-- for desktop -->
                            <ProfileAdditionalInfo
                                class="hidden lg:block"
                            />

                            <Link :href="route('item.preview', $page.props.auth.user.id)" class="px-4 py-2 rounded text-xl bg-black text-white font-bold text-center">
                                {{ Helper.translate('Preview Profile') }}
                            </Link>
                            <h1 class="">
                                {{ Helper.translate('Change Category') }}
                            </h1>
                            <div class="relative mb-6">
                                <CSelect
                                    v-model="form.category_id"
                                    :options="getCategories"
                                />
                                <span class="absolute top-full left-0 text-xs text-red-500">{{ Helper.translate(form.errors.category_id, true) }}</span>
                            </div>
                            <h1 class="">
                                {{ Helper.translate('Change Subcategory') }}
                            </h1> 
                            <div class="relative mb-6">
                                <CSelect
                                    v-model="form.sub_category_id"
                                    :options="getSubCategories"
                                />
                                <span class="absolute top-full left-0 text-xs text-red-500">{{ Helper.translate(form.errors.sub_category_id, true) }}</span>
                            </div>
                            
                            <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                                {{ form.progress.percentage }}%
                            </progress>
                            <button @click="handleSave" class="px-4 py-2 rounded text-xl bg-green-500 text-white font-bold text-center">
                                {{ Helper.translate('Save') }}
                            </button>
                        </div>
                    </div>

                    <!-- for desktop -->
                    <ProfileImageUpload
                        class="hidden lg:block"
                        :profileForm="profileForm"
                        @oninput="(e) => handleProfile(e.target.files[0])"
                    />
                </div>
            </div>
        </template>
    </DashboardLayout>
</template>

<script setup>
import DashboardLayout from '../DashboardLayout.vue'
import LeftSide from '@/Components/Backend/TalentDashboard/LeftSide.vue'
import VideoIcon from '@/Icons/VideoIcon.vue'
import { Head, Link, useForm } from '@inertiajs/inertia-vue3'
import CSelect from '@/Components/Global/CSelect.vue'
import Helper from '@/Helper'
import { computed, onMounted, ref } from 'vue'
import { filter, isEmpty, map } from 'lodash' 
import { toast } from 'vue3-toastify'
import Preloader from '@/Components/Global/Preloader.vue'
import Video from '@/services/Video.vue'
import Bio from '@/Components/Backend/TalentDashboard/Bio.vue'
import ProfileAdditionalInfo from '@/Components/Backend/TalentDashboard/ProfileAdditionalInfo.vue'
import ProfileImageUpload from '@/Components/Backend/TalentDashboard/ProfileImageUpload.vue'
import { Inertia } from '@inertiajs/inertia'

const props = defineProps({
    categories: {
        type: Array,
        default: []
    },
    talent: {
        type: Object,
        default: {}
    }
})

const form = useForm({
    video_file: null,
    category_id: '',
    sub_category_id: '',
    // bio: 'Biography not added.'
    bio: null
})

const handleSelectVideo = (file) => {
    if (!file) {
        return
    }
    if (file.size > 15 * Helper.MBSIZE) {
        toast.error(Helper.translate('Maximum 15MB'))
        return
    }
    form.video_file = file
    handleSave()
    // Helper.getVideoDuration(file, (valid) => {
    //     if (valid) {
    //         form.video_file = file
    //     } else {
    //         toast.error(Helper.translate('Video duration should not be more then 10 seconds'))
    //     }
    // }, 120)
}

const profileForm = useForm({
    image: null,
})


const getCategories = computed(()=>{
    if(!props.categories) return [{key: '', value: 'Select categories'}]; 
    let filtered = props.categories.filter(item => item.parent_id == null);
    if(!filtered) return [{key: '', value: 'Select categories'}]; 
    filtered = filtered.map(item => {
        return {
            key: item.id,
            value: item.name
        }
    })
    filtered.unshift({key: '', value: 'Select categories'})

    return filtered;
});

const getSubCategories = computed(()=>{
    let subCategory = map(
                        filter(props.categories, item => item.parent_id == form.category_id), 
                        item => {
                            return {
                                key: item.id,
                                value: item.name
                            }
                        }
                    )
    subCategory.unshift({key: '', value: 'Select subcategory'});
    return subCategory;
});

onMounted(()=> {
    form.category_id = props.talent.category_id 
    form.sub_category_id = props.talent.sub_category_id 
    // form.bio = props.talent.bio || 'Biography not added.'
    form.bio = props.talent.bio
})

const showHandle = ref(true)

const handleSave = () => {
    form.post(route('talent.profile.update'), {
        onSuccess(e) {
            console.log('called after video');
            showHandle.value = false
            setTimeout(() => {showHandle.value = true}, 100)
            if(isEmpty(e.props.errors)) {
                form.video_file = null;
                // location.reload()
            }
        }
    });
}

const handleProfile = (file) => {
    if (file.size > 1 * Helper.MBSIZE) {
        toast.error(Helper.translate('Maximum 1MB'))
        return
    }
    profileForm.image = file; 
    profileForm.post(route('talent.saveProfile'))
}



</script>


<style scoped>
.content {
    grid-template-columns: 250px 1fr;
}
</style>