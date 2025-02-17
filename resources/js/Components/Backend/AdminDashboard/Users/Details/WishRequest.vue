<template>
    <h2 class="font-semibold text-right mb-2">
        {{ Helper.translate('Wish total amount (fees)') }}: 
        <span class="text-sky-500">
            {{ Helper.priceFormate(sumBy(wish_requests, item => item.amount)) }} 
            ({{ Helper.priceFormate(
                sumBy(wish_requests, item => item.commission_amount) +
                sumBy(wish_requests, item => item.maintenance_charge_amount)
            ) }})
        </span>
    </h2>
    <div class="overflow-x-auto">
        <div class="flex justify-between">
            <component :is="components['PageSize']" />
            <div>
                <component :is="components['Search']" />
            </div>
        </div>
        <table class="w-full" v-if="!isEmpty(resultPerPage)">
            <thead class="border-b whitespace-nowrap">
                <tr>
                    <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                        #
                    </th>
                    <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                        {{ Helper.translate('Date & Time') }}
                    </th>
                    <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                        {{ Helper.translate('Talent Name') }}
                    </th>
                    <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                        {{ Helper.translate('Amount Paid') }}
                    </th>
                    <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-right">
                        {{ Helper.translate('Fees') }}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in resultPerPage" :key="index" class="border-b">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ Helper.translate(index+1, true) }}
                    </td>
                    <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                        {{ Helper.dateFormate(item.created_at) }}
                    </td>
                    <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                        {{ Helper.translate(get(item, 'talent.name')) }}
                    </td>
                    <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                        {{ Helper.priceFormate(item.amount) }}
                    </td>
                    <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap flex justify-end gap-2">
                        {{ Helper.priceFormate(+item.commission_amount + +item.maintenance_charge_amount) }}
                    </td>
                </tr>
            </tbody>
        </table>
        <Alert v-else />
    </div>
    <div class="flex items-center justify-between mt-5 text-sm">
        <component :is="components['Pagination']" />
    </div>
</template>

<script setup>
import AngleLeftIcon from '@/Icons/AngleLeftIcon.vue';
import AngleRightIcon from '@/Icons/AngleRightIcon.vue';
import useTable from '@/Components/Backend/Global/Table/useTable.js'
import { onMounted, ref, watch } from 'vue'
import { usePage } from '@inertiajs/inertia-vue3'
import Helper from '@/Helper'
import { get, isEmpty, sumBy } from 'lodash'
import Alert from '@/Components/Global/Alert.vue'

const props = defineProps({
    activeTab: String,
})

const { components, data, resultPerPage } = useTable()

const wish_requests = ref([])

onMounted(() => {
    data.value = usePage().props.value.wish_requests
    wish_requests.value = usePage().props.value.wish_requests
})

watch(props, ()=>{
    if (props.activeTab == 'WishRequest') {
        data.value = usePage().props.value.wish_requests
        wish_requests.value = usePage().props.value.wish_requests
    }

    if (props.activeTab == 'TipsSent') {
        data.value = usePage().props.value.tips
        wish_requests.value = usePage().props.value.tips
    }

    if (props.activeTab == 'CalendarPurchased') {
        data.value = usePage().props.value.calender
        wish_requests.value = usePage().props.value.calender
    }

    if (props.activeTab == 'Subscriptions') {
        data.value = usePage().props.value.mylife
        wish_requests.value = usePage().props.value.mylife
    }
}, {
    deep: true
})



</script>

<style scoped>

</style>