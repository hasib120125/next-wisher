<template>
    <div class="">
        <div class="flex gap-4 justify-between w-full">
            <div class="flex justify-between gap-3 flex-1">
                <component :is="components['PageSize']" />
                <component :is="components['Search']" />
            </div>
            <div class="flex gap-4 items-center">
                <div class="flex gap-4 items-center relative">
                    <button @click="showCustom=!showCustom" class="font-semibold border bg-blue-500 text-white rounded px-4 py-1">
                        {{ Helper.translate('Custom') }}
                    </button>
                    <div v-if="showCustom" class="absolute bg-white border top-full right-0 flex flex-col">
                        <input type="date" v-model="filterOption.start">
                        <input type="date" v-model="filterOption.end">
                        <button @click="filterData" class="bg-blue-500 text-white py-1 px-3 text-center">{{ Helper.translate('Apply') }}</button>
                    </div>
                </div>
                <button 
                    @click="()=> {
                        filterOption.order = !filterOption.order;
                        filterData()
                    }" 
                    class="w-6"
                    :class="filterOption.order ? 'opacity-100' : 'opacity-50'"
                >
                    <SortIcon class="w-5 h-5 ml-auto" />
                </button>
            </div>
        </div>

        <div class="grid mt-4">
            <div class="overflow-x-auto w-full">
                <table class="w-full" v-if="!isEmpty(resultPerPage)">
                    <thead class="border-b">
                        <tr>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left whitespace-nowrap">
                                #
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left whitespace-nowrap">
                                {{ Helper.translate('Date') }}
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left whitespace-nowrap">
                                {{ Helper.translate('Talents') }}
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left whitespace-nowrap">
                                {{ Helper.translate('Email') }}
                            </th>
                            <th 
                                @click="()=> {
                                    filterOption.order = !filterOption.order;
                                    filterData('revenue')
                                }"
                                scope="col" 
                                class="text-sm select-none cursor-pointer font-medium text-gray-900 px-6 py-4 text-left whitespace-nowrap"
                            >
                                <div class="flex gap-1">
                                    <div class="flex items-end gap-0.5">
                                        <SortIcon class="w-5 h-5 text-gray-400 ml-auto" />
                                        <div class="flex flex-col items-center text-center">
                                            <span class="font-semibold">
                                                {{ Helper.priceFormate(sumBy(data, item => +item.revenue)) }}
                                            </span>
                                            {{ Helper.translate('Revenue') }}
                                        </div>
                                    </div>
                                </div>
                            </th>

                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left whitespace-nowrap">
                                <div class="flex flex-col items-center text-center">
                                    <span class="font-semibold">
                                        {{ Helper.priceFormate(sumBy(data, item => +item.pending)) }}
                                    </span>
                                    {{ Helper.translate('Pending') }}
                                </div>
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left whitespace-nowrap">
                                <div class="flex flex-col items-center text-center">
                                    <span class="font-semibold">
                                        {{ Helper.priceFormate(sumBy(data, item => +item.paid)) }}
                                    </span>
                                    {{ Helper.translate('Paid') }}
                                </div>
                            </th>
                            <th
                                @click="()=> {
                                    filterOption.order = !filterOption.order;
                                    filterData()
                                }"
                                scope="col"
                                class="text-sm select-none cursor-pointer font-medium text-gray-900 px-6 py-4 text-left whitespace-nowrap"
                            >
                                <div class="flex gap-1">
                                    <div class="flex items-end gap-0.5">
                                        <SortIcon class="w-5 h-5 ml-auto" />
                                        <div class="flex flex-col items-center text-center">
                                            <span class="font-semibold">
                                                {{ Helper.priceFormate(sumBy(data, item => +item.balance)) }}
                                            </span>
                                            {{ Helper.translate('Balance') }}
                                        </div>
                                    </div>
                                </div>
                            </th>
                            <!-- <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                {{ Helper.translate('Total Revenue Gross') }} <span class="text-green-500">({{ Helper.translate('net') }})</span>
                            </th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in resultPerPage" :key="index" class="border-b">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ Helper.translate(index+1, true) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ Helper.dateFormate(item.created_at) }}
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                {{ Helper.translate(item.name, true) }}
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                {{ Helper.translate(item.email, true) }}
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                {{ Helper.priceFormate(item.revenue) }}
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                {{ Helper.priceFormate(item.pending) }}
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                {{ Helper.priceFormate(item.paid) }}
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                {{ Helper.priceFormate(item.balance) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <Alert v-else />
            </div>
        </div>

        <div class="flex items-center justify-between mt-5 text-sm">
            <component :is="components['Pagination']" />
        </div>
    </div>
</template>

<script setup>
import SortIcon from '@/Icons/SortIcon.vue'
import useTable from '@/Components/Backend/Global/Table/useTable.js'
import { computed, onMounted, ref } from 'vue'
import { usePage } from '@inertiajs/inertia-vue3'
import Helper from '@/Helper'
import { isEmpty, sortBy, orderBy, sumBy } from 'lodash'
import Alert from '@/Components/Global/Alert.vue'
import moment from 'moment'

const { components, data, resultPerPage } = useTable()

const filterOption = ref({
    start: null,
    end: null,
    order: false // true = 'ASC'; false = 'DESC'
})

const showCustom = ref(false)
const filterData = (shortBy=null) => {
    let earnings = usePage().props.value.talent_earnings;
    data.value = orderBy(earnings, [shortBy ? shortBy : 'total_payment_received'], [filterOption.value.order ? 'asc' : 'desc']);

    if (filterOption.value.start && filterOption.value.end) {
        data.value = data.value.filter(item => {
            return new Date(item.created_at) >= new Date(filterOption.value.start) 
                        && new Date(item.created_at) <= new Date(filterOption.value.end)
        })
    }
    showCustom.value = false;
}

onMounted(() => {
    filterData('created_at');
})



</script>

<style lang="scss" scoped>

</style>