<template>
    <div>
        <div class="flex md:flex-nowrap flex-wrap gap-4 md:justify-between justify-center">
            <div class="flex gap-2 items-center">
                <component :is="components['PageSize']" />
            </div>
            <div class="flex md:flex-nowrap flex-wrap gap-6 items-center md:justify-start justify-center w-full">
                <component :is="components['Search']" />
                <select 
                    class="border-gray-100 py-1 "
                    v-model="selectedCategory"
                >
                    <option value="">{{ Helper.translate('-Filter by category-') }}</option>
                    <option 
                        :value="category" 
                        v-for="(category, index) in categories"
                        :key="index"
                    >
                        {{ Helper.translate(category, true) }}
                    </option>
                </select>
                <TabChanger :activeItems="talents.length" :deleteItem="deletedTalents.length" />
            </div>  
        </div>

        <div class="grid mt-4">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="border-b whitespace-nowrap">
                        <tr>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                #
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                {{ Helper.translate('Date & Time') }}
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                {{ Helper.translate('Name') }}
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                {{ Helper.translate('Email') }}
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                {{ Helper.translate('Country') }}
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                {{ Helper.translate('Social Link') }}
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                {{ Helper.translate('Category') }}
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                {{ Helper.translate('Verification') }}
                            </th>
                            <!-- <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4">
                                {{ Helper.translate('Files') }}
                            </th>  -->
                            <!-- <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4">
                                {{ Helper.translate('Show in frontend') }}
                            </th>  -->
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-right">
                                {{ Helper.translate('Action') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in resultPerPage" :key="index" class="border-b">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ Helper.translate(index+1, true) }}
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                {{ Helper.dateFormate(item.created_at) }} {{ Helper.translate('at') }} 
                                {{ moment(item.created_at).format("hh:mm A") }}
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                <Link :href="route('admin.talent.detail', item.id)" class="text-sky-500 font-medium">
                                    {{ Helper.translate(item.username, true) }}
                                </Link>
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                {{ Helper.translate(item.email, true) }}
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                {{ Helper.translate(get(item, 'country.name'), true) }}
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                {{ Helper.translate(item.link, true) }}
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                {{ Helper.translate(get(item, 'category.name'), true) }}
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                <template v-if="item.status">
                                    <button @click="videoPath=item.verification_video"  class="mx-auto block btn_ripple" title="See Talent Video.">
                                        <PlayIcon class="" />
                                    </button>
                                </template>
                            </td>
                            <!-- <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                <span v-if="file_access" class="text-green-500">Access</span>
                            </td> -->
                            <!-- <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                <button @click="handleFeatured(item.id)" v-if="item.is_featured" class="bg-red-500 text-white font-semibold text-xs rounded py-1 px-2">Hide</button>
                                <button @click="handleFeatured(item.id)" v-else class="bg-green-500 text-white font-semibold text-xs rounded py-1 px-2">Show</button>
                            </td> -->
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap font-semibold">
                                <div class="flex gap-2 justify-end">
                                    <Link :href="route('admin.talent.detail', item.id)" class="bg-green-500 px-2 py-1 rounded text-white text-sm font-bold block">
                                        <EyeIcon class="w-4 h-4" />
                                    </Link>
                                    <button @click="handleDeleteUser(item.id)" class="bg-red-500 px-2 py-1 rounded text-white text-sm font-bold block">
                                        <BlockIcon class="w-4 h-4" />
                                    </button>
                                    <!-- <button @click="handleDeleteUser(item.id, true)" class="bg-red-500 px-2 py-1 rounded text-white text-sm font-bold block">
                                        <DeleteIcon class="w-4 h-4" />
                                    </button> -->
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <Modal v-model="videoPath">
            <div class="relative p-2 pt-8 bg-white w-[450px]">
                <button @click="videoPath = false" class="absolute top-2 right-2 text-red-500">
                    <CloseIcon class="w-4 h-4" />
                </button>
                <div class="">
                    <video class="w-full" v-if="videoPath" controls>
                        <source :src="`${$page.props.ziggy.url}/${videoPath}`" type="video/mp4">
                        <source :src="`${$page.props.ziggy.url}/${videoPath}`" type="video/ogg">
                    </video>

                </div>
            </div>
        </Modal>
        <component :is="components['Pagination']" />
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/inertia-vue3'
import EyeIcon from '@/Icons/EyeIcon.vue'
import useTable from '@/Components/Backend/Global/Table/useTable.js'
import TabChanger from '@/Components/Backend/AdminDashboard/Talents/TabChanger.vue'

import Modal from '@/Components/Library/Modal.vue'
import { usePage } from '@inertiajs/inertia-vue3'
import CloseIcon from '@/Icons/CloseIcon.vue'
import Helper from '@/Helper'
import { computed, ref } from 'vue'
import { cloneDeep, get } from 'lodash'
import { Inertia } from '@inertiajs/inertia'
import VideoIcon from '@/Icons/VideoIcon.vue'
import PlayIcon from '@/Icons/PlayIcon.vue'
import moment from 'moment'
import BlockIcon from '@/Icons/BlockIcon.vue'
import DeleteIcon from '@/Icons/DeleteIcon.vue'

const { components, data, resultPerPage, search } = useTable()

const talents = computed(() => {
    data.value = usePage().props.value.talents
    if(selectedCategory.value){
        data.value = cloneDeep(data.value).filter(talent => get(talent, 'category.name') === selectedCategory.value)
    }
    return data.value
})

const deletedTalents = computed(() => { 
    return usePage().props.value.deletedTalents 
})

const videoPath = ref(false)

const handleDeleteUser = (id, fource=false) => {
    if (fource) {
        Helper.confirm(Helper.translate("Do you really want to delete parmanently?"), ()=>{
            Inertia.delete(route('admin.fource_delete_user', id))
        })
    } else {
        Helper.confirm(Helper.translate("Do you really want to block?"), ()=>{
            Inertia.delete(route('admin.delete_user', id))
        })
    }
}

const handleFeatured = (id) => {
    Helper.confirm(Helper.translate("Are you sure change this?"), ()=>{
        Inertia.post(route('admin.visibility', id))
    })
}

const selectedCategory = ref('')
const categories = computed(() => {
    let categories = []
    usePage().props.value.talents.forEach(talent => {
        if(!categories.includes(talent.category?.name)){
            categories.push(talent.category?.name)
        }
    })

    return categories
})
</script>


<style scoped>
.btn_ripple {
    border: none;
    outline: none;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: block;
    background-color: #e73212;
    color: #fff;
    display: grid;
    place-items: center;
    font-size: 18px;
    cursor: pointer;

    animation-name: ripple;
    animation-duration: 1.5s;
    animation-iteration-count: infinite;
}


@keyframes ripple {
    0% {
        box-shadow: 0 0 0 0 #0002, 0 0 0 0 #0002;
    }

    80% {
        box-shadow: 0 0 0 10px #0000, 0 0 0 20px #0000;
    }

    100% {
        box-shadow: 0 0 0 0 #0000, 0 0 0 0 #0000;
    }
}
</style>