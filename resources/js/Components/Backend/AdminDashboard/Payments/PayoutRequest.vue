<template>
    <div>
        <div class="flex gap-4 justify-between">
            <component :is="components['PageSize']" />
            <div>
                <component :is="components['Search']" />
            </div>
        </div>

        <div class="grid mt-4">
            <div class="overflow-x-auto w-full">
                <table v-if="!isEmpty(resultPerPage)" class="w-full">
                    <thead class="border-b whitespace-nowrap">
                        <tr>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                #
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                {{ Helper.translate('Invoice ID') }}
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                {{ Helper.translate('Request Date') }}
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                {{ Helper.translate('Name') }}
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                {{ Helper.translate('Payment Method') }}
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                {{ Helper.translate('Payment Info') }}
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                {{ Helper.translate('Balance') }}
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                {{ Helper.translate('Amount') }}
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                {{ Helper.translate('Payment Date') }}
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                {{ Helper.translate('Proceed') }}
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                {{ Helper.translate('Cancel') }}
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
                                {{ Helper.translate(get(item.talent, 'name'), true) }}
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
                                {{ Helper.priceFormate(get(item, 'talent.balance')) }}
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                {{ Helper.priceFormate(item.amount) }}
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                <template v-if="item.paid_at">
                                    {{ Helper.dateFormate(item.paid_at) }}
                                </template>
                                <template v-else>
                                    {{ Helper.translate('N/A') }}
                                </template>
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                <button @click="handlePay(item.id)" class="bg-sky-500 px-4 py-1 rounded text-white text-sm font-bold">
                                    {{ Helper.translate('Pay now') }}
                                </button>
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                <button @click="handleCancel(item.id)" class="bg-red-400 px-2 py-2 rounded-full text-white text-sm font-bold ml-auto block">
                                    <CloseIcon class="w-4 h-4" />
                                </button>
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
import CloseIcon from '@/Icons/CloseIcon.vue'
import useTable from '@/Components/Backend/Global/Table/useTable.js'
import { onMounted, onUpdated, ref } from 'vue'
import { usePage } from '@inertiajs/inertia-vue3'
import Helper from '@/Helper'
import { get, isEmpty, orderBy } from 'lodash'
import Alert from '@/Components/Global/Alert.vue'
import { Inertia } from '@inertiajs/inertia'
import EyeIcon from '@/Icons/EyeIcon.vue'
import Modal from '@/Components/Library/Modal.vue'

const { components, data, resultPerPage } = useTable()

const showBankDetails = ref(false)

onMounted(() => {
    data.value = usePage().props.value.payout_requests;
})

onUpdated(() => {
    data.value = usePage().props.value.payout_requests
})

const handlePay = (id) => {
    Helper.confirm(Helper.translate('Are you sure to pay'), () => {
        Inertia.post(route('admin.talentPay', id))
    })
}
const handleCancel = (id) => {
    Helper.confirm(Helper.translate('Are you sure to cancel'), () => {
        Inertia.post(route('admin.TalentPayCancel', id))
    })
}

</script>

<style lang="scss" scoped>

</style>