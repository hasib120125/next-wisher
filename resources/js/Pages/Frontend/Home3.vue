<template>
    <Head :title="Helper.translate('Home')" />
    <Master>

        <DiscoverCategory />

        <div class="bg-white pb-6">
            <div id="exploreSection" class="mb-10">
                <p class="text-center text-2xl font-semibold px-2.5">
                    {{ Helper.translate('Special videos for special occasions from your favorite celebrities') }}
                </p>
            </div>
            <TalentCarousel
                v-if="homeTalents"
                :homeTalents="homeTalents"
                title="Music"
                :indexStart="0"
                :blueName="true"
                @onClick="(index) => toggleModalOfProfileVideoConfig = index"
            />
            <TalentCarousel
                v-if="homeTalents"
                :homeTalents="homeTalents"
                title="Sports"
                :indexStart="1"
                :blueName="true"
                @onClick="(index) => toggleModalOfProfileVideoConfig = index"
            />
        </div>

        <div class="bg-white p-5">
            <div class="max-w-[1000px] mx-auto">
                <h3 class="text-center font-bold text-3xl text-[#268AFF] mb-4">{{ Helper.translate('Trending celebrities') }}</h3>
                <div class="flex overflow-x-auto noScrollbarHeight md:grid xl:grid-cols-4 lg:grid-cols-3 md:grid-cols-2 gap-5 mt-8">
                    <div 
                        v-for="index in 4"
                        :key="index" 
                        ref="videosWrapper"
                        class="flex-shrink-0 md:w-full w-[200px]"
                    >
                        <ExploreSectionItem 
                            @onClick="toggleModalOfProfileVideoConfig = (index+29)"
                            :role="user?.role"
                            :talent="getTalent((index+29))"
                            @pauseControl="(status) => Helper.pauseControl(status, (index-1), videosWrapper)"
                        />
                    </div>
                </div>
            </div>
        </div>

        <TalentCarousel
            v-if="homeTalents"
            :homeTalents="homeTalents"
            title="People"
            :indexStart="2"
            :blueName="true"
            @onClick="(index) => toggleModalOfProfileVideoConfig = index"
        />
        <TalentCarousel
            v-if="homeTalents"
            :homeTalents="homeTalents"
            title="Models"
            :indexStart="3"
            :blueName="true"
            @onClick="(index) => toggleModalOfProfileVideoConfig = index"
        />

        <div class="bg-white p-5" v-if="$page.props.featured_videos">
            <div class="max-w-[1000px] mx-auto">
                <h3 class="text-center font-bold text-3xl text-[#268AFF] mb-4">{{ Helper.translate('Featured videos') }}</h3>
                <div class="flex overflow-x-auto noScrollbarHeight md:grid xl:grid-cols-4 lg:grid-cols-3 md:grid-cols-2 gap-5 mt-8">
                    <div 
                        v-for="(video, index) in $page.props.featured_videos"
                        :key="index" 
                        ref="videosWrapper"
                        class="flex-shrink-0 md:w-full w-[200px]"
                    >
                        <FeatureVideoItem
                            :path="video.path"
                            :cover="video.thumbnail"
                            @pauseControl="(status) => Helper.pauseControl(status, index+4, videosWrapper)"
                        />
                    </div>
                </div>
            </div>
        </div>


        <TalentCarousel
            v-if="homeTalents"
            :homeTalents="homeTalents"
            title="Creators"
            :indexStart="4"
            :blueName="true"
            @onClick="(index) => toggleModalOfProfileVideoConfig = index"
        />
        <TalentCarousel
            v-if="homeTalents"
            :homeTalents="homeTalents"
            title="TV/Radio"
            :indexStart="5"
            :blueName="true"
            @onClick="(index) => toggleModalOfProfileVideoConfig = index"
        />

        <div class="mb-10"></div>

    </Master>

    <Modal v-model="toggleModalOfProfileVideoConfig">
        <TalentsList 
            v-if="toggleModalOfProfileVideoConfig"
            :selectedIndex="toggleModalOfProfileVideoConfig"
            :homeTalents="homeTalents"
            @close="toggleModalOfProfileVideoConfig=null"
        />
    </Modal>
</template>

<script setup>
import Master from '../Backend/Master.vue'
import { Head, Link } from '@inertiajs/inertia-vue3'
import Modal from '@/Components/Library/Modal.vue'
import EditIcon from '@/Icons/EditIcon.vue'
import { ref, computed } from 'vue'
import { find, get, indexOf, isEmpty } from 'lodash'
import { usePage } from '@inertiajs/inertia-vue3'
import TalentsList from '@/Pages/Frontend/TalentsList.vue'
import Helper from '@/Helper'
import { Inertia } from '@inertiajs/inertia'
import ExploreSectionItem from '@/Components/Frontend/ExploreSectionItem.vue'
import Home2TalentItem from '@/Components/Frontend/Home2TalentItem.vue'
import Swal from 'sweetalert2'
import Slider from '@/Components/Frontend/Home/Slider.vue'
import TalentCarousel from '@/Components/Frontend/Home/TalentCarousel.vue'
import HowWorks from '@/Components/Frontend/HowWorks.vue'
import DiscoverCategory from '@/Components/Frontend/DiscoverCategory.vue'
import FeatureVideoItem from '@/Components/Frontend/FeatureVideoItem.vue'


const props = defineProps({
    editable: {
        type: Boolean,
        default: false
    },
    homeTalents: {
        type: Array,
        default: []
    },
    parentCategories: {
        type: Array,
        default: []
    }
})

const videosWrapper = ref()
const FeaturedFideosWrapper = ref()
const getCategory = (index) => {
    const catagories = props.parentCategories;
    if (index < catagories.length) {
        return catagories[index]
    }
    return {}
}



const goToMusicCategory = () => {
    const catagories = props.parentCategories;
    let founded = find(catagories, item => {
        return String(item.slug).toLowerCase() == 'music'
    })
    if(founded) {
        Inertia.get(route('category.items', get(founded, 'slug')));
    }
}

const getTalent = (index) => {
    const talents = props.homeTalents;
    if (talents.length) {
        return find(talents, item => item.box_index == index)
    }
    return null
}

const user = computed(() => {
    if(usePage().props.value.auth.user){
        const { role, name } = get(usePage().props.value, 'auth.user')
        return { role, name }
    }

    return null
})
const toggleModalOfProfileVideoConfig = ref(false)
</script>