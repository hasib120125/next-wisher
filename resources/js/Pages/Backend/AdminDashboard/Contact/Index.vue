<template>
    <Head title="Contact Management" />
    <DashboardLayout :header="false" :footer="false" title="Contact List">
        <template v-slot:leftSidebar>
            <LeftSide />
        </template>
        <template v-slot:content>
            <div>
                <div class="flex md:flex-nowrap flex-wrap gap-4 md:justify-between justify-center items-center">
                    <component :is="components['PageSize']" />
                    <component :is="components['Search']" class="ml-auto mr-0" />
                </div>
        
                <div class="grid mt-4">
                    <div class="overflow-x-auto">
                        <table class="w-full" v-if="!isEmpty(data)">
                            <thead class="border-b whitespace-nowrap">
                                <tr>
                                    <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                        #
                                    </th>
                                    <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                        {{ Helper.translate('Date') }}
                                    </th>
                                    <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                        {{ Helper.translate('Name') }}
                                    </th>
                                    <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                        {{ Helper.translate('Reason') }}
                                    </th>
                                    <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                        {{ Helper.translate('Subject') }}
                                    </th>
                                    <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                        {{ Helper.translate('Email') }}
                                    </th>
                                    <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-right">
                                        {{ Helper.translate('Action') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <Row v-for="(item, index) in resultPerPage" :key="item.id" :item="item" :index="index" />
                            </tbody>
                        </table>
                        <Alert v-if="isEmpty(data)" />
                    </div>
                    <component :is="components['Pagination']" />
                </div>
            </div>
        </template>
    </DashboardLayout>
</template>

<script setup>
import DashboardLayout from '../DashboardLayout.vue'
import LeftSide from '@/Components/Backend/AdminDashboard/LeftSide.vue'
import useTable from '@/Components/Backend/Global/Table/useTable.js'
import Alert from '@/Components/Global/Alert.vue'
import { Head } from '@inertiajs/inertia-vue3'
import Helper from '@/Helper.js'
import { isEmpty } from 'lodash'
import { onMounted, onUpdated, watchEffect } from 'vue'
import Row from '@/Components/Backend/AdminDashboard/Contact/Row.vue' 

const props = defineProps({
    contacts: {
        type: Array,
        default: []
    }
})

const { components, data, resultPerPage } = useTable()

watchEffect(() => {
    data.value = props.contacts
})
// onUpdated(() => {
//     data.value = props.contacts
// })
// onMounted(() => {
//     data.value = props.contacts
// })



</script>


<style scoped>
</style>