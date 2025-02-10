<template>
    <div class="flex lg:justify-between justify-center lg:flex-nowrap flex-wrap items-center w-full">
        <component :is="components['PageSize']" class="w-full" />
        <div>
            <component :is="components['Search']" class="w-full" />
        </div>
    </div>
    <div class="grid w-full">
        <div class="overflow-x-auto w-full flex-shrink-0">
            <table class="w-full">
                <thead class="border-b">
                    <tr class="truncate">
                        <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            {{ Helper.translate('Invoice Id') }}
                        </th>
                        <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-center">
                            {{ Helper.translate('Request Date') }}<br>
                            <small>({{ Helper.translate('dd/mm/yyyy') }})</small>
                        </th>
                        <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            {{ Helper.translate('Payment Method') }}
                        </th>
                        <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            {{ Helper.translate('Payment Info') }}
                        </th>
                        <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            {{ Helper.translate('Amount') }}
                        </th>
                        <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-center">
                            {{ Helper.translate('Payment Date') }}<br>
                            <small>({{ Helper.translate('dd/mm/yyyy') }})</small>
                        </th>
                        <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-right">
                            {{ Helper.translate('Status') }}
                        </th>
                    </tr>
                </thead>
                <tbody v-if="!isEmpty(resultPerPage)">
                    <tr v-for="(item, index) in resultPerPage" :key="index" class="border-b">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ Helper.translate(item.invoice, true) }}
                        </td>
                        <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap text-center">
                            {{ moment(item.created_at).format('DD/MM/YYYY') }}
                        </td>
                        <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                            {{ Helper.translate(item.bank_type, true) }}
                        </td>
                        <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                            {{ item.stripe_email }}
                        </td>
                        <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                            {{ Helper.priceFormate(item.amount) }}
                        </td>
                        <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap text-center">
                            <template v-if="item.paid_at">
                                {{ moment(item.paid_at).format('DD/MM/YYYY') }}
                            </template>
                            <template v-else-if="item.deleted_at">
                                {{ Helper.translate('Declined') }}
                            </template>
                            <template v-else>
                                {{ Helper.translate('N/A') }}
                            </template>
                        </td>
                        <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap text-right">
                            <button v-if="item.status" class="text-green-600 px-3 text-sm">
                                {{ Helper.translate('Paid') }}
                            </button>
                            <button v-else-if="item.deleted_at" class="text-red-600 px-3 text-sm">
                                {{ Helper.translate('Declined') }}
                            </button>
                            <button v-else class="text-orange-400 px-3 text-sm">
                                {{ Helper.translate('Pending') }}
                            </button>
                        </td>
                    </tr>
                </tbody>
                <Alert v-else />
            </table>
            <component :is="components['Pagination']" />
        </div>
    </div>
</template>

<script setup>
import { usePage } from '@inertiajs/inertia-vue3';
import useTable from '@/Components/Backend/Global/Table/useTable.js'
import { onMounted, onUpdated, watchEffect } from 'vue';
import Helper from '@/Helper';
import Alert from '@/Components/Global/Alert.vue';
import { isEmpty } from 'lodash'
import moment from 'moment'

const { components, data, resultPerPage } = useTable()

watchEffect(() => {
    data.value = usePage().props.value.requests
})

</script>

<style lang="scss" scoped>

</style>