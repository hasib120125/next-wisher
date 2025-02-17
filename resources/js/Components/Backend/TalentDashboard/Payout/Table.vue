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
                            <template v-if="get(item, 'settings.sim')">
                                    / {{ Helper.translate(get(item, 'settings.sim')) }}
                            </template>
                        </td>
                        <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                            <template v-if="item.bank_type=='bank'">
                                <button @click="showBankDetails=item">
                                    <EyeIcon />
                                </button>
                            </template>
                            <template v-else>
                                {{ item.stripe_email }}
                            </template>
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

    <Modal v-model="showBankDetails">
        <div class="bg-white w-[300px] sm:w-[500px] relative">
            <div class="p-5 border-b font-semibold text-xl flex justify-between items-center">
                {{ Helper.translate('Bank info') }}
                <button @click="showBankDetails=false" class="w-8 h-8 rounded-full bg-red-500 text-white grid place-content-center">
                    &times;
                </button>
            </div>
            <div class="p-5">
                <div v-if="showBankDetails?.area" class="py-1 border-b mb-1 flex justify-between hover:bg-slate-100">
                    <span class="font-semibold">
                        {{ Helper.translate('Area') }}: 
                    </span>
                    <span v-if="showBankDetails?.area=='canada'" class="capitalize">
                        {{ Helper.translate(showBankDetails?.area, true) }}
                    </span>
                    <span v-if="showBankDetails?.area=='europe'" class="capitalize">
                        {{ Helper.translate('Europe & UK') }}
                    </span>
                    <span v-if="showBankDetails?.area=='outside'" class="capitalize">
                        {{ Helper.translate('Outside Europe') }}
                    </span>
                </div>
                <div v-if="showBankDetails?.full_name" class="py-1 border-b mb-1 flex justify-between hover:bg-slate-100">
                    <span class="font-semibold">
                        {{ Helper.translate('Account holder name') }}: 
                    </span>
                    <span>
                        {{ showBankDetails?.full_name }}
                    </span>
                </div>
                <div v-if="showBankDetails?.email" class="py-1 border-b mb-1 flex justify-between hover:bg-slate-100">
                    <span class="font-semibold">
                        {{ Helper.translate('Email') }}: 
                    </span>
                    <span>
                        {{ showBankDetails?.email }}
                    </span>
                </div>
                <div v-if="showBankDetails?.iban" class="py-1 border-b mb-1 flex justify-between hover:bg-slate-100">
                    <span class="font-semibold">
                        {{ Helper.translate('IBAN') }}: 
                    </span>
                    <span>
                        {{ showBankDetails?.iban }}
                    </span>
                </div>
                <div v-if="showBankDetails?.swift" class="py-1 border-b mb-1 flex justify-between hover:bg-slate-100">
                    <span class="font-semibold">
                        {{ Helper.translate('SWIFT / BIC CODE') }}: 
                    </span>
                    <span>
                        {{ showBankDetails?.swift }}
                    </span>
                </div>
                <div v-if="showBankDetails?.area=='outside' && showBankDetails?.account_number" class="py-1 border-b mb-1 flex justify-between hover:bg-slate-100">
                    <span class="font-semibold">
                        {{ Helper.translate('Account number') }}: 
                    </span>
                    <span>
                        {{ showBankDetails?.account_number }}
                    </span>
                </div>
            </div>
        </div>
    </Modal>
</template>

<script setup>
import { usePage } from '@inertiajs/inertia-vue3';
import useTable from '@/Components/Backend/Global/Table/useTable.js'
import { ref, onUpdated, watchEffect } from 'vue';
import Modal from '@/Components/Library/Modal.vue'
import Helper from '@/Helper';
import Alert from '@/Components/Global/Alert.vue';
import { isEmpty, get } from 'lodash'
import moment from 'moment'
import EyeIcon from '@/Icons/EyeIcon.vue';

const { components, data, resultPerPage } = useTable()

const showBankDetails = ref(false)

watchEffect(() => {
    data.value = usePage().props.value.requests
})

</script>

<style lang="scss" scoped>

</style>