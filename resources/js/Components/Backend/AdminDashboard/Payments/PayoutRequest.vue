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
</template>

<script setup>
import CloseIcon from '@/Icons/CloseIcon.vue'
import useTable from '@/Components/Backend/Global/Table/useTable.js'
import { onMounted, onUpdated } from 'vue'
import { usePage } from '@inertiajs/inertia-vue3'
import Helper from '@/Helper'
import { get, isEmpty, orderBy } from 'lodash'
import Alert from '@/Components/Global/Alert.vue'
import { Inertia } from '@inertiajs/inertia'

const { components, data, resultPerPage } = useTable()

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