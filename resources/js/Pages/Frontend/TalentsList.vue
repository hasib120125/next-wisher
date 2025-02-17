<template>
    <div v-if="!loading" class="bg-white w-[95vw] h-[90vh] p-4 rounded overflow-y-auto">
        <div class="flex justify-between">
            <CInput type="search" v-model="searchString" @input="handleSearchInput" placeholder="Search talent" />
            <div class="flex gap-4 w-[200px]">
                <CSelect class="w-full" @input="handleSearchInput" :options="categoryList" v-model="category" />
            </div>
        </div>
        <div class="w-full h-full mb-4">
            <div
                class="pt-5"
            >
                <div v-if="!isEmpty(talentLists)" class="grid xl:grid-cols-4 md:grid-cols-2 grid-cols-1 items-end gap-6">
                    <template v-for="(talent, index) in talentLists" :key="index">
                        <div v-if="selectedIndex > 29">
                            <div ref="videoWrapper" class="video customRatio height-[402px] overflow-hidden relative">
                                <Video 
                                    v-if="get(talent, 'video_path')"
                                    :src="`${$page.props.asset}${get(talent, 'video_path')}`" 
                                    class="w-full absolute bottom-0"
                                    @pauseControl="(status) => Helper.pauseControl(status, (index), videoWrapper)"
                                />
                                <a 
                                    class="absolute top-4 right-4 z-10 bg-sky-500 text-white p-2 rounded"
                                    :href="`${$page.props.asset}${get(talent, 'video_path')}`"
                                    download
                                >
                                    <DownloadIcon class="w-4 h-4" />
                                </a>
                                <button 
                                    @click="saveHomeTalents(talent)" 
                                    class="absolute bottom-4 right-4 z-10 bg-green-500 text-white px-4 rounded shadow font-bold"
                                >
                                    {{ Helper.translate('Select') }}
                                </button>
                            </div>
                            <h2 class="font-bold bg-red-500 text-center text-white py-1 truncate">{{ talent.username }}</h2>
                        </div>
                        <Home2TalentItem
                            v-else
                            @click="(e) => {
                                if(!e.target.classList.contains('downloadImage')){
                                    saveHomeTalents(talent)
                                }
                            }"
                            :talent="talent"
                            :downloadable="true"
                        />
                    </template>
                </div>
                <div v-else class="text-center py-4">
                    {{ Helper.translate('No talent found') }}
                </div>
            </div>
        </div>
    </div>
    <div v-if="loading" class="flex items-center gap-4 text-white font-bold text-lg">
        <span class="w-8 h-8 absolute -ml-4 -mt-4 top-1/2 left-1/2 z-20 animate-spin">
            <svg class="w-8 h-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </span>
        {{ Helper.translate('Loading') }} ...
    </div>
</template>

<script setup>
import CInput from '@/Components/Global/CInput.vue'
import Preloader from '@/Components/Global/Preloader.vue';
import Helper from '@/Helper';
import axios from 'axios';
import { onMounted, ref } from 'vue';
import { find, isEmpty, map, get } from 'lodash';
import { Link, useForm, usePage } from '@inertiajs/inertia-vue3';
import CSelect from '@/Components/Global/CSelect.vue'
import Video from '@/services/Video.vue'
import Home2TalentItem from '@/Components/Frontend/Home2TalentItem.vue'
import DownloadIcon from '@/Icons/DownloadIcon.vue'

const props = defineProps({
    selectedIndex: Number,
    homeTalents: {
        type: Array,
        default: []
    }
})
const videoWrapper = ref()
const talentLists = ref([])
const loading = ref(false)

const selectedIds = ref([])

const category = ref('d')
const searchString = ref('')

const form = useForm({
    box_index: props.selectedIndex,
    user_id: null,
})

const categoryList = ref([])

onMounted(()=> {
    getTalents()
    const categories = usePage().props.value.parentCategories
    categoryList.value = map(categories, item => {
        return {
            key: item.id,
            value: item.name
        };
    })
    categoryList.value.unshift({
        key: 'd',
        value: Helper.translate('Select Category')
    });
})

const emit = defineEmits(['close'])

const saveHomeTalents = (talent) => {
    form.user_id = talent.id;
    Helper.confirm(Helper.translate('Are you sure to save'), () => {
        form.post(route('saveHomeTalents'), {
            onFinish() {
                emit('close')
            },
            preserveScroll: true,
        });
    })
}

const isSelected = (talent) => {
    return find(props.homeTalents, item => {
        return item.box_index == props.selectedIndex && talent.id == item.user_id
    })
}

let timeOut;
const handleSearchInput = () => {
    clearTimeout(timeOut)
    timeOut = setTimeout(() => {
        getTalents()
    }, 300)
}

const getTalents = () => {
    loading.value = true;
    axios.post(route('getFilteredTalents'), {
        search: searchString.value,
        category_id: category.value
    })
        .then(res => {
            talentLists.value = res.data
            loading.value = false
        })
        .catch(err => {
            loading.value = false
        })
}
    
</script>

<style lang="scss" scoped>

</style>