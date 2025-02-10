<template>
    <div class="">
        <div class="flex gap-4 justify-between">
            <component :is="components['PageSize']" />
            <div>
                <div class="w-full">
                    <component :is="components['Search']" />
                </div>
            </div>
            <div class="flex gap-4 items-center">
                <div class="flex gap-4 items-center">
                    <template v-for="(item, index) in ['Paid', 'Canceled']" :key="index">
                        <button
                            @click="activeTab=item"
                            class="font-semibold border rounded px-4 py-1"
                            :class="activeTab == item ? 'bg-blue-500 text-white' : ''"
                        >
                            {{ Helper.translate(item) }}
                        </button>
                    </template>
                </div>
                <button @click="order=!order" class="w-6">
                    <SortIcon class="w-5 h-5 ml-auto" />
                </button>
            </div>
        </div>

        <div class="grid mt-4">
            <div class="overflow-x-auto w-full">
                <table class="w-full" v-if="!isEmpty(resultPerPage)">
                    <thead class="border-b">
                        <tr>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                #
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left whitespace-nowrap">
                                {{ Helper.translate('Invoice ID') }}
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left whitespace-nowrap">
                                {{ Helper.translate('Request Date') }}
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left whitespace-nowrap">
                                {{ Helper.translate('Payment Date') }}
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left whitespace-nowrap">
                                {{ Helper.translate('Name') }}
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left whitespace-nowrap">
                                {{ Helper.translate('Payment Method') }}
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left whitespace-nowrap">
                                {{ Helper.translate('Payment Info') }}
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left whitespace-nowrap">
                                {{ Helper.translate('Balance') }}
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left whitespace-nowrap">
                                {{ Helper.translate('Amount') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in resultPerPage" :key="index" class="border-b">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ Helper.translate(index+1, true) }}
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                {{ Helper.translate(item.invoice, true) }}
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                {{ Helper.dateFormate(item.created_at) }}
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                <template v-if="item.paid_at">
                                    {{ Helper.dateFormate(item.paid_at) }}
                                </template>
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                {{ Helper.translate(get(item, 'talent.name'), true) }}
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                {{ Helper.translate(item.bank_type, true) }}
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                <strong class="font-bold">{{ item.stripe_email }}</strong>
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                {{ Helper.priceFormate(get(item, 'talent.balance')) }}
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
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
import { onMounted, ref, watch } from 'vue'
import { usePage } from '@inertiajs/inertia-vue3'
import Helper from '@/Helper'
import { get, isEmpty, orderBy } from 'lodash'
import Alert from '@/Components/Global/Alert.vue'

const { components, data, resultPerPage } = useTable()

const activeTab = ref('Paid')
const order = ref(false)

onMounted(() => {
    data.value = orderBy(usePage().props.value.payout_paid, ['created_at'], [order.value ? 'asc' : 'desc'])
})

watch([activeTab, order], ()=> {
    let records = activeTab.value=='Paid' ? usePage().props.value.payout_paid : usePage().props.value.payout_canceled
    data.value = orderBy(records, ['created_at'], [order.value ? 'asc' : 'desc'])
})

</script>

<style lang="scss" scoped>

</style>