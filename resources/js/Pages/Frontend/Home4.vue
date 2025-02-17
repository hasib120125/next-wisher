<template>
    <Head :title="Helper.translate('Home')" />
    <Master>

        <div class="max-md:mx-4">
            <label
                class="flex relative max-w-3xl pr-3 mx-auto mt-10 gap-3 items-center rounded border border-gray-700 focus:outline-none focus:border-black focus:ring-0"
            >
                <input
                    type="text"
                    v-model="searchTempQuery"
                    @input="()=> {
                        if(!searchTempQuery) {
                            searchQuery=searchTempQuery
                            handleFilter()
                        }
                    }"
                    @keyup.enter="()=>{
                        searchQuery=searchTempQuery
                        selectedKey='all'
                        handleFilter()
                    }"
                    class="w-full peer px-4 py-3 bg-transparent flex-1 border-0 outline-0 focus:ring-0"
                />
                <span
                    class="absolute bg-white px-1 peer-focus:-top-[14px] transition-all duration-200 top-2 text-xl left-6 block"
                    :class="{
                        '!-top-[14px]': searchTempQuery
                    }"
                >
                    {{ Helper.translate('Search') }}
                </span>
                <button
                    @click="() => {
                        searchQuery=searchTempQuery
                        selectedKey='all'
                        handleFilter()
                    }"
                    class="w-8 h-8 rounded-full grid place-content-center bg-[#000547] text-white"
                >
                    <Preloader class="w-5 h-5" v-if="loadingSearch" />
                    <SearchIcon v-else class="w-4 h-4" />
                </button>
                <button
                    v-if="showFilterItems"
                    @click="() => {
                        selectedKey = 'all'
                        searchQuery=''
                        searchTempQuery=''
                        handleFilter()
                    }"
                    class="py-1 px-2 bg-[#000547] text-white rounded"
                >
                    {{ Helper.translate('Clear') }}
                </button>
            </label>
        </div>

        <DiscoverCategory />

        <div class="bg-white pb-6">
            <div id="exploreSection" class="mb-10">
                <p class="text-center text-xl font-semibold px-2.5">
                    {{ Helper.translate('Special videos for special occasions') }}
                </p>
            </div>
            <template v-if="showFilterItems">
                <TalentCarousel
                    :homeTalents="filterTalentList"
                    title="Search Results"
                    :indexStart="0"
                    :blueName="true"
                    :followIndex="false"
                    @onClick="(index) => toggleModalOfProfileVideoConfig = index"
                />
                <div 
                    v-if="filterTalentList?.length==0"
                    class="relative max-w-[1200px] sm:px-10 px-5 w-full mx-auto font-semibold"
                >
                    {{ Helper.translate('No result found') }}
                </div>
            </template>
            <TalentCarousel
                v-else
                :homeTalents="homeTalents"
                title="Featured Celebrities"
                :indexStart="0"
                :blueName="true"
                @onClick="(index) => toggleModalOfProfileVideoConfig = index"
            />
            
        </div>

        <div class="py-6 max-sm:px-4 max-w-[1120px] mx-auto">
            <h2 class="text-xl font-semibold mb-4">
                {{ Helper.translate('How Nextwisher works') }}
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
                <div class="bg-slate-700 text-white py-2 px-4 rounded-md">
                    <div class="flex gap-2 mb-2">
                        <span 
                            class="w-8 h-8 bg-[#278aff] text-white rounded-full text-xl font-semibold grid place-content-center"
                        >
                            {{ Helper.translate('1') }}
                        </span>
                        <span class="font-semibold">
                            {{ Helper.translate('Find a celebrity') }}
                        </span>
                    </div>
                    <div class="text-[18px]">{{ Helper.translate('Search and find a celebrity.') }}</div>
                </div>
                <div class="bg-slate-700 text-white py-2 px-4 rounded-md">
                    <div class="flex gap-2 mb-2">
                        <span 
                            class="w-8 h-8 bg-[#278aff] text-white rounded-full text-xl font-semibold grid place-content-center"
                        >
                            {{ Helper.translate('2') }}
                        </span>
                        <span class="font-semibold">{{ Helper.translate('Submit your request') }}</span>
                    </div>
                    <div class="text-[18px]">{{ Helper.translate('Tell them what you want to include in the video.') }}</div>
                </div>
                <div class="bg-slate-700 text-white py-2 px-4 rounded-md">
                    <div class="flex gap-2 mb-2">
                        <span 
                            class="w-8 h-8 bg-[#278aff] text-white rounded-full text-xl font-semibold grid place-content-center"
                        >
                            {{ Helper.translate('3') }}
                        </span>
                        <span class="font-semibold">{{ Helper.translate('Get your video') }}</span>
                    </div>
                    <div class="text-[18px]">
                       {{ Helper.translate('It takes up to 5 days to complete your request. When ready, you will get it in your inbox.') }}
                    </div>
                </div>
                <div class="bg-slate-700 text-white py-2 px-4 rounded-md">
                    <div class="flex gap-2 mb-2">
                        <span 
                            class="w-8 h-8 bg-[#278aff] text-white rounded-full text-xl font-semibold grid place-content-center"
                        >
                            {{ Helper.translate('4') }}
                        </span>
                        <span class="font-semibold">{{ Helper.translate('Enjoy') }}</span>
                    </div>
                    <div class="text-[18px]">
                        {{ Helper.translate('Enjoy your video and share it with friends and family.') }}
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white p-5" v-if="$page.props.featured_videos">
            <div class="max-w-[1000px] mx-auto">
                <h3 class="text-center font-semibold text-xl mb-0">
                    {{ Helper.translate('Featured videos') }}
                </h3>
                <div class="flex md:flex-wrap md:justify-center gap-5 mt-5 max-md:overflow-x-auto">
                    <div 
                        v-for="(video, index) in $page.props.featured_videos"
                        :key="index" 
                        ref="videosWrapper"
                        class="flex-shrink-0 w-[200px]"
                    >
                        <FeatureVideoItem
                            :path="video.path"
                            :cover="video.thumbnail"
                            @pauseControl="(status) => Helper.pauseControl(status, index, videosWrapper)"
                        />
                    </div>
                </div>
            </div>
        </div>

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
import HomeIcon from '@/Icons/HomeIcon.vue'
import SearchIcon from '@/Icons/SearchIcon.vue'
import { ref, computed } from 'vue'
import { find, get, indexOf, isEmpty } from 'lodash'
import { usePage } from '@inertiajs/inertia-vue3'
import TalentsList from '@/Pages/Frontend/TalentsList.vue'
import Helper from '@/Helper'
import { Inertia } from '@inertiajs/inertia'
import Preloader from '@/Components/Global/Preloader.vue'
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

const filterKey = ['all', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k','l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't','u', 'v', 'w', 'x', 'y', 'z']
const selectedKey = ref('all')
const searchQuery = ref('')
const searchTempQuery = ref('')
const filterTalentList = ref([])
const showFilterItems = ref(false)
const loadingSearch = ref(false)

const handleFilter = async () => {
    if(selectedKey.value == 'all' && !searchQuery.value) {
        showFilterItems.value = false
        return
    }
    loadingSearch.value = true
    const response = await axios.post(route('search'), {
        q: searchQuery.value,
        startWith: selectedKey.value,
    });
    loadingSearch.value = false
    showFilterItems.value = true
    filterTalentList.value = response.data
}

// const talentList = computed(() => {
//     let homeTalents = props.homeTalents;
//     if (selectedKey.value != 'all') {
//         homeTalents = homeTalents.filter(item => {
//             return String(item?.talent.username||'')
//                         .toLowerCase()
//                         .startsWith(selectedKey.value.toLowerCase())
//         });
//     }

//     if (searchQuery.value.trim() != '') {
//         homeTalents = homeTalents.filter(talent => {
//             return String(talent?.talent?.username||'')
//                         .toLowerCase()
//                         .includes(searchQuery.value.toLowerCase())
//         });
//     }

//     return homeTalents;
// })

// const 
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