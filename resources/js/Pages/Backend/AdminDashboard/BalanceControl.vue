<template>
    <Head title="Balance Control" />
    <DashboardLayout :header="false" :footer="false" title="Talent Detail">
        <template v-slot:leftSidebar>
            <LeftSide />
        </template>
        <template v-slot:content>
            <div class="grid h-full">
                <div class="flex gap-2 md:justify-end justify-center pr-5">
                    <div class="min-w-[200px]">
                        <CSelect :options="filterOptions" v-model="targetFilter" @change="handleFilter" />
                    </div>
                    <!-- <button
                        @click="handleTab('pending')"
                        class="px-4 py-1 rounded border  font-medium"
                        :class="activeTab == 'pending' ? 'bg-sky-500 text-white' : ''"
                    >
                        {{ Helper.translate('Pending') }}
                    </button> -->
                    <!-- <button 
                        @click="handleTab('transferred')"
                        class="px-4 py-1 rounded border"
                        :class="activeTab == 'transferred' ? 'bg-sky-500 text-white' : ''"
                    >
                        {{ Helper.translate('Transferred') }}
                    </button> -->
                </div>
                <div class="grid mt-4">
                    <component :is="components['PageSize']" />
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="border-b whitespace-nowrap">
                                <tr>
                                    <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                        #
                                    </th>
                                    <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                        {{ Helper.translate("Date&Time") }}
                                    </th>
                                    <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                        {{ Helper.translate("Countdown") }}
                                    </th>
                                    <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                        <!-- {{ Helper.translate("Requester name") }} -->
                                        {{ Helper.translate("Username") }}
                                    </th>
                                    <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                        {{ Helper.translate("Talent Name") }}
                                    </th>
                                    <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                        {{ Helper.translate("View details") }}
                                    </th>
                                    <!-- <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                        {{ Helper.translate("Total Amount") }}
                                    </th>
                                    <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                        {{ Helper.translate("Maintenance Charge") }}
                                    </th>
                                    <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                        {{ Helper.translate("Commission") }}
                                    </th> -->
                                    <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                        {{ Helper.translate("Amount") }}
                                    </th>
                                    <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                        {{ Helper.translate("Status") }}
                                    </th>
                                    <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-right">
                                        {{ Helper.translate("App/Dec") }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in resultPerPage" :key="item.id" class="border-b"
                                    :class="item.download_status == 1 ? 'bg-green-50' : ''">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ Number(index) + 1 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ Helper.dateFormate(item.created_at) }} at
                                        {{ moment(item.created_at).format("hh:mm A") }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        <span v-if="get(item, 'settings.time')" class="text-green-600">
                                            {{ get(item, 'settings.time') }}
                                        </span>
                                        <Timer v-else :expirationDate="item.expire_date" :dateDifferenceInMillisecond="get(item, 'mail[0].duration_millisecond')" class="text-red-500" />
                                    </td>
                                    <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                        <div class="font-bold">
                                            {{ Helper.translate(item.user?.name, true) }}
                                        </div>
                                        <div>
                                            {{ Helper.translate(item.user?.email, true) }}
                                        </div>
                                    </td>
                                    <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                        <div class="font-bold">
                                            {{ Helper.translate(item.talent?.username, true) }}
                                        </div>
                                        <div>
                                            {{ Helper.translate(item.talent?.email, true) }}
                                        </div>
                                    </td>
                                    <!-- <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        <span v-if="item.download_status == 1">{{ Helper.translate('Downloaded') }}</span>
                                        <span v-else>{{ Helper.translate('Pending') }}</span>
                                    </td> -->
                                    <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                        <button @click="loadConversation(item)" v-if="item.mail"
                                            class="py-1 px-3 rounded-md bg-green-500 text-white">
                                            <EyeIcon />
                                        </button>
                                    </td>
                                    <!-- <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                        {{ Helper.priceFormate(item.amount + item.maintenance_charge_amount) }}
                                    </td>
                                    <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                        {{ Helper.priceFormate(item.maintenance_charge_amount) }}
                                    </td>
                                    <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                        {{ Helper.priceFormate(item.commission_amount) }}
                                    </td> -->
                                    <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                        {{ Helper.priceFormate(item.amount - item.commission_amount) }}
                                    </td>
                                    <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                        <template v-if="item.deleted_at">
                                            <span class="text-red-500 font-semibold">{{ Helper.translate('Cancelled')
                                            }}</span>
                                        </template>
                                        <template v-else>
                                            <span v-if="!item.is_expire && item.download_status == 0"
                                                class="text-orange-500 font-semibold">{{ Helper.translate('Pending')
                                                }}</span>
                                            <span v-if="item.download_status == 1" class="text-green-500 font-semibold">{{
                                                Helper.translate('Delivered') }}</span>
                                            <span v-if="item.download_status == 0 && item.is_expire"
                                                class="text-red-500 font-semibold">{{ Helper.translate('Expired') }}</span>
                                        </template>
                                    </td>
                                    <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap flex justify-end gap-2">
                                        <button v-if="+item.download_status == 1 && !+item.balance_status"
                                            @click="handleTransfer(item)"
                                            class="bg-green-500 px-2 py-1 rounded text-white text-sm font-bold block">
                                            {{ Helper.translate('Balance transfer') }}
                                        </button>
                                        <!-- v-if="item.is_expire" -->
                                        <button @click="handleApprove(item)"
                                            v-if="+item.download_status == 0 && +item.is_expire"
                                            class="px-1 py-1 rounded-full text-white bg-green-500 text-sm font-bold block">
                                            <CheckIcon />
                                        </button>
                                        <button @click="handleCancel(item)" v-if="!+item.balance_status && !item.deleted_at"
                                            class="px-1 py-1 rounded-full text-white bg-red-500 text-sm font-bold block">
                                            <CloseIcon class="w-5 h-5" />
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <component :is="components['Pagination']" />
                </div>
            </div>
        </template>

    </DashboardLayout>

    <Modal v-model="showModal" :isFull="true">
        <div class="max-w-[800px] flex-shrink-0 !w-full p-5 bg-white rounded-xl mx-auto relative">
            <div class="flex justify-end sticky top-5">
                <button @click="showModal = false"
                    class="absolute bg-white shadow-xl border border-red-300 w-11 h-11 rounded-full right-0 top-0 text-red-500 flex items-center justify-center">
                    <CloseIcon />
                </button>
            </div>
            <div v-for="(mail, index) in selectedEarning.mail" :key="index" class="border-b py-3 grid gap-3"
                :class="mail.role == 'user' ? 'text-red-600' : 'text-blue-600'">
                <div class="font-bold">
                    <span class="underline">
                        {{ mail.role == 'user' ? Helper.translate('User') : Helper.translate('Talent') }}
                    </span>
                </div>
                <div>
                    <div class="flex gap-1 flex-col" v-if="mail.role == 'user'">
                        <div v-if="mail.name">
                            <span class="font-bold">{{ Helper.translate('Name') }}:</span>
                            {{ mail.name }}
                        </div>
                        <div v-else>
                            <span class="font-bold">{{ Helper.translate('For') }}:</span>
                            <div>{{ mail.for }}</div>
                            <span class="font-bold">{{ Helper.translate('From') }}:</span>
                            <div>{{ mail.from }}</div>
                        </div>
                        <div>
                            <p>
                                <span class="font-bold">{{ Helper.translate('Gender') }}:</span> {{ mail.genders }}
                            </p>
                            <p class="mt-1">
                                <span class="font-bold">{{ Helper.translate('Occasion') }}:</span> {{ mail.occasion }}
                            </p>
                        </div>
                        <h1 class="font-bold">{{ Helper.translate('Instructions') }}:</h1>
                        <div v-html="mail.instructions"></div>
                    </div>
                    <div class="gap-1" v-if="mail.attachment">
                        <div>
                            <div class="max-w-[300px] relative mb-2">
                                <FeatureVideoItem
                                    v-if="mail.attachment"
                                    :path="mail.attachment"
                                    :showCover="false"
                                />
                                <a v-if="mail.attachment" :href="`${$page.props.asset}${mail.attachment}`" download class="w-10 h-10 rounded-full bg-green-500 text-white my-2 absolute top-2 right-2 flex items-center justify-center">
                                    <DownloadIcon class="w-5 h-5" />
                                </a>
                            </div>
                        </div>
                        <template v-if="mail.instructions">
                            <h1 class="font-bold">{{ Helper.translate('Message') }}:</h1>
                            <div v-html="mail.instructions"></div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </Modal>
</template>

<script setup>
import DashboardLayout from "./DashboardLayout.vue";
import LeftSide from "@/Components/Backend/AdminDashboard/LeftSide.vue";
import useTalents from "@/Pages/Backend/AdminDashboard/useTalents.js";
import AngleLeftIcon from "@/Icons/AngleLeftIcon.vue";
import Timer from '@/Components/Backend/Mail/LeftSideComponents/Timer.vue'
import Helper from "@/Helper";
import useTable from '@/Components/Backend/Global/Table/useTable.js'
import moment from "moment";
import { computed, onMounted, ref } from "vue";
import CloseIcon from "@/Icons/CloseIcon.vue";
import EyeIcon from "@/Icons/EyeIcon.vue";
import { Inertia } from "@inertiajs/inertia";
import { usePage } from "@inertiajs/inertia-vue3";
import CheckIcon from "@/Icons/CheckIcon.vue";
import CSelect from '@/Components/Global/CSelect.vue'
import { filter, get } from "lodash";
import Modal from "@/Components/Library/Modal.vue";
import Video from "@/services/Video.vue";
import FeatureVideoItem from "@/Components/Frontend/FeatureVideoItem.vue";
import DownloadIcon from "@/Icons/DownloadIcon.vue";
const { components, data, resultPerPage, search } = useTable()

const props = defineProps({
    pending_earnings: {
        type: Array,
        default: [],
    },
    transferred_earnings: {
        type: Array,
        default: [],
    },
})

const targetFilter = ref('All')
const showModal = ref(false)
const selectedEarning = ref({})

const filterOptions = [
    {
        key: 'All',
        value: 'All'
    },
    {
        key: 'Pending',
        value: 'Pending'
    },
    {
        key: 'Delivered',
        value: 'Delivered'
    },
    {
        key: 'Expired',
        value: 'Expired'
    },
    {
        key: 'Cancelled',
        value: 'Cancelled'
    },
]

onMounted(() => {
    // data.value = usePage().props.value.pending_earnings
    handleFilter()
})
const handleFilter = () => {
    let earnings = usePage().props.value.pending_earnings;
    switch (targetFilter.value) {
        case 'All':
            data.value = earnings;
            break;
        case 'Pending':
            data.value = filter(earnings, item => {
                return item.download_status == 0 && !item.deleted_at
            })
            break;
        case 'Delivered':
            data.value = filter(earnings, item => !item.deleted_at && item.download_status == 1)
            break;
        case 'Expired':
            data.value = filter(earnings, item => !item.deleted_at && item.download_status == 2)
            break;
        case 'Cancelled':
            data.value = filter(earnings, item => item.deleted_at)
            break;

        default:
            data.value = earnings;
            break;
    }
}

const loadConversation = (item) => {
    showModal.value = true
    // console.log(item);
    selectedEarning.value = item
}

const activeTab = ref('pending')

const handleTransfer = (item) => {
    Helper.confirm(Helper.translate('Are you sure to transfer balance?'), () => {
        Inertia.post(route('admin.talent.transfer_balance', {
            user: item.talent.id,
            id: item.id
        }))
    })
}

const handleCancel = (item) => {
    Helper.confirm(Helper.translate('Are you sure to decline?'), () => {
        Inertia.post(route('admin.talent.cancel_balance', {
            user: item.talent.id,
            id: item.id
        }))
    })
}

const handleApprove = (item) => {
    Helper.confirm(Helper.translate('Are you sure to approve?'), () => {
        Inertia.post(route('admin.talent.payment_controls_approve', item.id), {
            onSuccess() {
                location.reload()
            }
        })
    })
}

const handleTab = (tab) => {
    activeTab.value = tab;
    if (tab == 'pending') {
        resultPerPage.value = props.pending_earnings
    } else {
        resultPerPage.value = props.transferred_earnings
    }
}


</script>

<style scoped></style>
