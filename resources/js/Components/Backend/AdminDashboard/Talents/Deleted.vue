<template>
    <div>
        <div class="flex md:flex-nowrap flex-wrap gap-4 md:justify-between justify-center">
            <div class="flex gap-2 items-center">
                <component :is="components['PageSize']" />
            </div>
            <div class="flex md:flex-nowrap flex-wrap gap-6 items-center md:justify-start justify-center w-full">
                <component :is="components['Search']" />
                <TabChanger :activeItems="talents.length" :deleteItem="deletedTalents.length" class="white-space-nowrap" />
            </div>  
        </div>

        <div class="grid mt-4">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="border-b whitespace-nowrap">
                        <tr>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                #
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                {{ Helper.translate('Name') }}
                            </th>
                            <!-- <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                {{ Helper.translate('First Name') }}
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                {{ Helper.translate('Last Name') }}
                            </th> -->
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                {{ Helper.translate('Email') }}
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                {{ Helper.translate('Country') }}
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                {{ Helper.translate('Social Link') }}
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                {{ Helper.translate('Category') }}
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4">
                                {{ Helper.translate('Deleted By') }}
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-right">
                                {{ Helper.translate('Date') }}
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-right">
                                {{ Helper.translate('Action') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in resultPerPage" :key="index" class="border-b">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ Helper.translate(index+1, true) }}
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                {{ Helper.translate(item.username, true) }}
                            </td>
                            <!-- <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                {{ Helper.translate(item.first_name, true) }}
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                {{ Helper.translate(item.last_name, true) }}
                            </td> -->
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                {{ Helper.translate(item.email, true) }}
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                {{ Helper.translate(get(item, 'country.name'), true) }}
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                {{ Helper.translate(item.link, true) }}
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                {{ Helper.translate(get(item, 'category.name'), true) }}
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap flex gap-2">
                                <template v-if="get(item, 'deleted_by.role')=='admin'">
                                    {{ Helper.translate('Admin') }}
                                </template>
                                <template v-else>
                                    {{ Helper.translate('User') }}
                                </template>
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap font-semibold">
                                {{ Helper.dateFormate(item.deleted_at) }}
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap flex justify-end gap-2">
                                <button @click="handleRestoreUser(item.id)" class="bg-red-400 px-2 py-1 rounded text-white text-sm font-bold block">
                                    <RestoreIcon class="w-4 h-4" />
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <component :is="components['Pagination']" />
    </div>
</template>

<script setup>
import CInput from '@/Components/Global/CInput.vue'
import AngleLeftIcon from '@/Icons/AngleLeftIcon.vue'
import AngleRightIcon from '@/Icons/AngleRightIcon.vue'
import EyeIcon from '@/Icons/EyeIcon.vue'
import useTable from '@/Components/Backend/Global/Table/useTable.js'
import TabChanger from '@/Components/Backend/AdminDashboard/Talents/TabChanger.vue'
import Helper from '@/Helper'
import { computed } from 'vue'
import { get } from 'lodash'
import { usePage } from '@inertiajs/inertia-vue3'
import RestoreIcon from '@/Icons/RestoreIcon.vue'
import { Inertia } from '@inertiajs/inertia'


const { components, data, resultPerPage, search } = useTable()

const talents = computed(() => {
    return usePage().props.value.talents
})
const deletedTalents = computed(() => {
    data.value = usePage().props.value.deletedTalents
    return data.value
})

const handleRestoreUser = (id) => {
    Helper.confirm(Helper.translate("Are you sure to restore?"), ()=>{
        Inertia.delete(route('admin.restore_user', id))
    })
}

</script>

<style scoped>

</style>