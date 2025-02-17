<template>
    <div class="flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b">
                    <tr>
                        <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            #
                        </th>
                        <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            {{ Helper.translate('Talent name') }}
                        </th>
                        <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            {{ Helper.translate('Amount') }}
                        </th>
                        <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            {{ Helper.translate('Date & Time') }}
                        </th>
                        <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-right">
                            {{ Helper.translate('Status') }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in payments" :key="index" class="border-b">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ Helper.translate(index+1, true) }}
                        </td>
                        <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                            {{ Helper.translate(get(item, 'talent.name'), true) }}
                        </td>
                        <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                            {{ Helper.priceFormate(item.amount + item.maintenance_charge_amount) }}
                        </td>
                        <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                            {{ Helper.dateFormate(item.created_at) }}
                        </td>
                        <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap text-right">
                            <button class="bg-green-100 px-3 text-sm text-green-600">
                                {{ Helper.translate('Paid') }}
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <slot name="pagination" />
        </div>
    </div>
</template>

<script setup>
import Helper from "@/Helper"
import { get } from "lodash";

const props = defineProps({
    payments: {
        type: Object,
        default: []
    }
})

</script>

<style lang="scss" scoped>

</style>