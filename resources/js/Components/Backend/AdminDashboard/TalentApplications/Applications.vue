<template>
    <div>
        <div class="flex md:flex-nowrap flex-wrap w-full gap-4 md:justify-between justify-center">
            <div class="flex gap-2 items-center">
                <component :is="components['PageSize']" />
            </div>
            <div class="flex md:flex-nowrap flex-wrap gap-6 items-center">
                <component class="md:w-[200px] w-full" :is="components['Search']" />
                <TabChanger :activeItems="talents.length" />
            </div>
            <button @click="activeComponent = 'Declined'" class="px-4 py-1 rounded bg-red-400 text-white font-bold whitespace-nowrap">
                {{ Helper.translate('Application declined') }}
            </button>
        </div>

        <div class="grid mt-4">
            <div class="overflow-x-auto w-full">
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
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                {{ Helper.translate('Approve/Decline') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in resultPerPage" :key="index" class="border-b">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ Helper.translate(index + 1) }}
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                {{ Helper.dateFormate(item.created_at) }} at 
                                {{ moment(item.created_at).format("hh:mm A") }}
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                {{ Helper.translate(item.username, true) }}
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
                                {{ get(item, 'category.name') }}
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                <button v-if="!item.status" @click="() => {
                                    showModal = true;
                                    selectedTalent = item.id;
                                    videoPath = item.verification_video
                                }" class="mx-auto block btn_ripple" title="See Talent Video.">
                                    <PlayIcon class="" />
                                </button>
                                <!-- <button v-if="!item.status" @click="handleAction(item, true)" class="bg-red-500 text-white rounded py-1 px-2">
                                    {{ Helper.translate('Approve') }}
                                </button>
                                <span v-else class="font-bold text-green-600 px-3 text-sm">{{ Helper.translate('Verified') }}</span> -->
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap flex gap-0">
                                <button 
                                    @click="()=> {
                                        selectedTalent = item.id;
                                        handleAction(true);
                                    }"
                                    class="bg-green-500 px-2 py-2 rounded-full text-white text-sm font-bold ml-auto block">
                                    <CheckIcon class="w-4 h-4" />
                                </button>
                                <button :disabled="loading" v-if="!item.status" @click="() => {
                                    selectedTalent = item.id;
                                    handleAction(false);
                                }" class="bg-red-400 px-2 py-2 rounded-full text-white text-sm font-bold ml-auto block">
                                    <CloseIcon class="w-4 h-4" />
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <component :is="components['Pagination']" />

        <Modal v-model="showModal">
            <div class="relative p-2 pt-8 bg-white w-[450px]">
                <button @click="showModal = false" class="absolute top-2 right-2 text-red-500">
                    <CloseIcon class="w-4 h-4" />
                </button>
                <div class="">
                    <!-- <img src="https://images.unsplash.com/photo-1675277456530-ffdfd0ff8548?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHwzfHx8ZW58MHx8fHw%3D&auto=format&fit=crop&w=500&q=60" alt=""> -->
                    <video class="w-full" v-if="videoPath" controls>
                        <source :src="`${$page.props.ziggy.url}/${videoPath}`" type="video/mp4">
                        <source :src="`${$page.props.ziggy.url}/${videoPath}`" type="video/ogg">
                    </video>

                </div>
                <!-- <div class="flex gap-4 justify-center mt-2 font-semibold">
                    <button :disabled="loading" @click="handleAction(true)" class="px-4 py-1 bg-green-600 text-white rounded shadow flex items-center">
                        <svg v-if="loading && !isDecline" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ Helper.translate('Approve') }}
                    </button>
                    <button :disabled="loading" @click="handleAction(false)" class="px-4 py-1 bg-red-400 text-white rounded shadow flex items-center">
                        <svg v-if="loading && isDecline" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ Helper.translate('Decline') }}
                    </button>
                </div> -->
            </div>
        </Modal>
    </div>
</template>

<script setup>
import { computed, onMounted, ref, watch } from 'vue'
import CloseIcon from '@/Icons/CloseIcon.vue'
import CheckIcon from '@/Icons/CheckIcon.vue'
import PlayIcon from '@/Icons/PlayIcon.vue'
import Modal from '@/Components/Library/Modal.vue'
import useTable from '@/Components/Backend/Global/Table/useTable.js'
import useTalentApplications from '@/Pages/Backend/AdminDashboard/useTalentApplications.js'
import Helper from '@/Helper'
import { usePage } from '@inertiajs/inertia-vue3'
import { get, isEmpty } from 'lodash'
import { Inertia } from '@inertiajs/inertia'
import moment from 'moment'

const { components, data, resultPerPage, search } = useTable()
const { activeComponent } = useTalentApplications()
const showModal = ref(false)

const selectedTalent = ref(null)
const videoPath = ref(null)
const loading = ref(false)
const isDecline = ref(false)

watch(showModal, () => {
    if (showModal.value == false) {
        selectedTalent.value = null;
        videoPath.value = null;
    }
})

const talents = computed(() => {
    data.value = usePage().props.value.talents
    return data.value
})

const handleAction = (data) => {
    if (selectedTalent.value) {
        Helper.confirm(data ? Helper.translate('Are you sure to approve this talent') : Helper.translate('Are you sure to reject this talent'), ()=>{
            loading.value = true;
            if (!data) {
                isDecline.value = true;
            }
            Inertia.post(route('talent.approve', selectedTalent.value), {
                status: data
            }, {
                onSuccess(e) {
                    loading.value = false;
                    isDecline.value = false;
                    if (!isEmpty(e.props.flash.message)) {
                        showModal.value = false;
                    }
                },
                onError(){
                    loading.value = false;
                    isDecline.value = false;
                }
            });
        })
    }
}
 
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