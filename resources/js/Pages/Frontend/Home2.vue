<template>
    <Head :title="Helper.translate('Home')" />
    <Master>
        <Slider />
        
        <div class="bg-black pb-6">
            <h3 id="exploreSection" class="text-xl font-bold text-blue-400 pb-4 text-center py-3">{{ Helper.translate('Explore') }}</h3>
            <TalentCarousel
                v-if="homeTalents"
                :homeTalents="homeTalents"
                @onClick="(index) => toggleModalOfProfileVideoConfig = index"
            />
        </div>

        <DiscoverCategory />
        <HowWorks class="pt-5" />
        
        <div class="bg-black p-10">
            <div class="max-w-[1200px] mx-auto">
                <h3 class="text-center font-bold text-3xl text-[#6260d3] mb-4">{{ Helper.translate('Featured') }}</h3>
                <div class="grid xl:grid-cols-4 lg:grid-cols-3 md:grid-cols-2 gap-5 mt-8">
                    <div 
                        v-for="index in 4" 
                        :key="index" 
                        ref="videosWrapper"
                    >
                        <ExploreSectionItem 
                            @onClick="toggleModalOfProfileVideoConfig = (index+16)"
                            :role="user?.role"
                            :talent="getTalent((index+16))"
                            @pauseControl="(status) => Helper.pauseControl(status, (index-1), videosWrapper)"
                        />
                    </div>
                </div>
            </div>
        </div>
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