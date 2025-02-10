<template>
    <div class="">
        <div class="flex gap-4 justify-between w-full">
            <div class="flex justify-between gap-3 flex-1">
                <component :is="components['PageSize']" />
                <component :is="components['Search']" />
                <!-- <CInput type="search" v-model="searchString" @input="handleSearch(data, searchString)" placeholder="Search" class="mx-auto max-w-[400px] w-full" /> -->
            </div>
            <!-- <div class="flex gap-4 items-center">
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
            </div>-->
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
                                scope="col" 
                                class="text-sm select-none cursor-pointer font-medium text-gray-900 px-6 py-4 text-left whitespace-nowrap"
                            >
                                <div class="flex gap-1">
                                    <!-- <SortIcon class="w-5 h-5 text-gray-400 ml-auto" /> -->
                                    {{ Helper.translate('Option') }}
                                </div>
                            </th>
                            <th
                                scope="col"
                                class="text-sm select-none cursor-pointer font-medium text-gray-900 px-6 py-4 text-left whitespace-nowrap"
                            >
                                <div class="flex gap-1">
                                    <!-- <SortIcon class="w-5 h-5 text-gray-400 ml-auto" /> -->
                                    {{ Helper.translate('Amount') }}
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
                                <template v-if="item.type=='wish'">
                                    {{ item.fulfilled_at ? Helper.dateFormate(item.fulfilled_at) : Helper.translate('N/A') }}
                                </template>
                                <template v-else>
                                    {{ Helper.dateFormate(item.created_at) }}
                                </template>
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                {{ get(item, 'talent.username') }}
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                               {{ get(item, 'talent.email') }}
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap capitalize">
                                {{ Helper.translate(item.type) }}
                            </td>
                            <td 
                                class="text-sm px-6 py-4 whitespace-nowrap text-gray-900"
                                :class="{'text-orange-600': item.type == 'wish' && item.fulfilled_at == null}"
                            >
                                {{ Helper.priceFormate(item.amount) }}
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
import { isEmpty, sortBy, orderBy, get } from 'lodash'
import Alert from '@/Components/Global/Alert.vue'
import moment from 'moment'
import CInput from '@/Components/Global/CInput.vue'

const { components, data, resultPerPage, cbFunc } = useTable()

const filterOption = ref({
    start: null,
    end: null,
    order: false // true = 'ASC'; false = 'DESC'
})

const showCustom = ref(false)
const searchString = ref('')

cbFunc.value = (item, string) => {
    let talent = item.talent
    if (
        talent.name.toLowerCase().includes(string.toLowerCase()) ||
        talent.username.toLowerCase().includes(string.toLowerCase()) ||
        String(item.amount).toLowerCase().includes(string.toLowerCase()) ||
        item.type.toLowerCase().includes(string.toLowerCase()) ||
        talent.email.toLowerCase().includes(string.toLowerCase())
    ) {
        return item
    }
}


const filterData = (shortBy=null) => {
    let earnings = usePage().props.value.earnings;
    data.value = orderBy(earnings, ['created_at'], ['desc']);

    // if (filterOption.value.start && filterOption.value.end) {
    //     data.value = data.value.filter(item => {
    //         return new Date(item.created_at) >= new Date(filterOption.value.start) 
    //                     && new Date(item.created_at) <= new Date(filterOption.value.end)
    //     })
    // }
    // showCustom.value = false;
}

onMounted(() => {
    // data.value = usePage().props.value.talent_earnings
    filterData();
})



</script>

<style lang="scss" scoped>

</style>