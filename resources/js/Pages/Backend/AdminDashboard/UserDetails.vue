<template>
    <Head title="User's details" />
    <DashboardLayout :header="false" :footer="false" title="User Detail">
        <template v-slot:leftSidebar>
            <LeftSide />
        </template>
        <template v-slot:content>
            <Link :href="route('admin.users')" class="p-1 px-3 pr-4 rounded bg-gray-500 text-white inline-flex items-center w-fit gap-2">
                <AngleLeftIcon class="w-4 h-4" />
                {{ Helper.translate('Back') }}
            </Link>
            <div class="grid user_detail_wrapper h-full">
                <div class="border p-4 h-full overflow-y-auto">
                    <h1 class="font-semibold">{{ Helper.translate('User') }}: {{ Helper.translate(user.name, true) }}</h1>
                    <div class="flex flex-col justify-between min-h-[460px] mt-4">
                        <div class="grid gap-2 text-sm">
                            <button 
                                @click="()=>{
                                    detailsActiveComponent = 'WishRequest'
                                    activeTab = 'WishRequest'
                                }"
                                class="border px-2 py-1 rounded w-full text-left"
                                :class="activeTab == 'WishRequest' && 'bg-gray-100'"
                            >
                                {{ Helper.translate('Wish Requested') }} ({{ size(get($page.props, 'wish_requests')) }})
                            </button>

                            <button
                                @click="()=>{
                                    detailsActiveComponent = 'WishRequest'
                                    activeTab = 'TipsSent'
                                }"
                                :class="activeTab == 'TipsSent' && 'bg-gray-100'"
                                class="border px-2 py-1 rounded w-full hover:bg-gray-100 text-left"
                            >
                                {{ Helper.translate('Tips Sent') }} ({{ size(get($page.props, 'tips')) }})
                            </button>

                            <button
                                @click="()=>{
                                    detailsActiveComponent = 'WishRequest'
                                    activeTab = 'CalendarPurchased'
                                }"
                                :class="activeTab == 'CalendarPurchased' && 'bg-gray-100'"
                                class="border px-2 py-1 rounded w-full hover:bg-gray-100 text-left"
                            >
                                {{ Helper.translate('Calendar Purchased') }} ({{ size(get($page.props, 'calender')) }})
                            </button>

                            <button
                                @click="()=>{
                                    detailsActiveComponent = 'WishRequest'
                                    activeTab = 'Subscriptions'
                                }"
                                :class="activeTab == 'Tips Sent' && 'bg-gray-100'"
                                class="border px-2 py-1 rounded w-full hover:bg-gray-100 text-left"
                            >
                                {{ Helper.translate('Subscriptions') }} ({{ size(get($page.props, 'mylife')) }})
                            </button>

                            <button 
                                @click="detailsActiveComponent = 'ChangePassword'"
                                class="border px-2 py-1 rounded w-full text-red-500 text-left mt-5"
                                :class="detailsActiveComponent == 'ChangePassword' && 'bg-red-500 text-gray-50 font-medium'"
                            >
                                {{ Helper.translate('Change Password') }}
                            </button>
                        </div>
                        <div class="font-medium text-sm grid gap-1">
                            <h2 class="">{{ Helper.translate('Total Amount Paid') }} ({{ Helper.priceFormate(get($page.props, 'total_paid')) }})</h2>
                            <h2 class="">{{ Helper.translate('Total Fees Paid') }} ({{ Helper.priceFormate(get($page.props, 'total_fee')) }})</h2>
                        </div>
                    </div>
                </div>
                <div class="border border-l-0 p-4">
                    <component 
                        :is="detailsComponents[detailsActiveComponent]" 
                        :activeTab="activeTab"
                        :user="user"
                    />
                </div>
            </div>
        </template>
    </DashboardLayout>
</template>

<script setup>
import DashboardLayout from './DashboardLayout.vue'
import LeftSide from '@/Components/Backend/AdminDashboard/LeftSide.vue'
import useUser from '@/Pages/Backend/AdminDashboard/useUser.js'
import AngleLeftIcon from '@/Icons/AngleLeftIcon.vue'
import { Link, Head } from '@inertiajs/inertia-vue3';
import Helper from '@/Helper';
import { ref } from 'vue'
import { get, size } from 'lodash';

const { detailsComponents, detailsActiveComponent } = useUser()

const activeTab = ref('WishRequest')

const props = defineProps({
    user: {
        type: Object,
        default: {}
    }
})

</script>


<style scoped>
    .user_detail_wrapper{
        grid-template-columns: 250px calc(100% - 250px);
    }
</style>