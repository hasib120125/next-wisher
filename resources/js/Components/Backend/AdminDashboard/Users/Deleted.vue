<template>
    <div>
        <div class="flex md:flex-nowrap flex-wrap gap-4 md:justify-between justify-center">
            <div class="flex gap-2 items-center">
                <component :is="components['PageSize']" />
            </div>
            <div class="flex md:flex-nowrap flex-wrap gap-6 items-center md:justify-start justify-center w-full">
                <CInput type="search" :placeholder="Helper.translate('Search')" class="mx-auto max-w-[400px] w-full" />
                <TabChanger :activeItems="activeUsers.length" :deleteItems="deletedUser.length" />
            </div>
        </div>

        <div class="grid mt-4">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="border-b whitespace-nowrap">
                        <tr v-if="!isEmpty(resultPerPage)">
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                #
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                {{ Helper.translate('Name') }}
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                {{ Helper.translate('Email') }}
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                {{ Helper.translate('Deleted By') }}
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                {{ Helper.translate('Date') }}
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-right">
                                {{ Helper.translate('Action') }}
                            </th>
                        </tr>
                        <tr v-else>
                            <Alert title="No result found!" />
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(user, index) in resultPerPage" :key="index" class="border-b">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ Helper.translate(index + 1, true) }}
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                <Link :href="route('admin.user.detail', user.id)" class="text-sky-500 font-medium">
                                    {{ Helper.translate(user.name, true) }}
                                </Link>
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                {{ Helper.translate(user.email, true) }}
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                <Link :href="route('admin.user.detail', user.id)" class="text-sky-500 font-medium">
                                    {{ Helper.translate(get(user, 'deleted_by.name'), true) }} 
                                </Link>
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                {{ Helper.dateFormate(get(user, 'deleted_at')) }} 
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap flex justify-end gap-2">
                                <button @click="handleRestoreUser(user.id)" class="bg-red-400 px-2 py-1 rounded text-white text-sm font-bold block">
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
import { computed } from 'vue'
import { isEmpty } from 'lodash'
import CInput from '@/Components/Global/CInput.vue'
import TabChanger from '@/Components/Backend/AdminDashboard/Users/TabChanger.vue'
import useTable from '@/Components/Backend/Global/Table/useTable.js'
import { Link, usePage } from '@inertiajs/inertia-vue3'
import { get } from 'lodash'
import Helper from '@/Helper'
import RestoreIcon from '@/Icons/RestoreIcon.vue'
import { Inertia } from '@inertiajs/inertia'
import Alert from '@/Components/Global/Alert.vue'

const { components, data, resultPerPage } = useTable()
const deletedUser = computed(() => {
    data.value = usePage().props.value.deletedUsers
    return data.value
})

const activeUsers = computed(() => {
    return usePage().props.value.users
})

const handleRestoreUser = (id) => {
    Helper.confirm(Helper.translate("Are you sure to restore?"), ()=>{
        Inertia.delete(route('admin.restore_user', id))
    })
}
</script>