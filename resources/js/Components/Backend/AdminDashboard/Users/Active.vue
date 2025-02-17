<template>
    <div>
        <div class="flex md:flex-nowrap flex-wrap gap-4 md:justify-between justify-center">
            <div class="flex gap-2 items-center">
                <component :is="components['PageSize']" />
            </div>
            <div class="flex md:flex-nowrap flex-wrap gap-6 items-center md:justify-start justify-center w-full">
                <component :is="components['Search']" />
                <TabChanger :activeItems="activeUsers.length" :deleteItems="deleteUsers.length" />
            </div>
        </div>
        <div class="grid mt-4">
            <div class="overflow-x-auto w-full">
                <table class="w-full">
                    <thead class="border-b whitespace-nowrap">
                        <tr>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                #
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                {{ Helper.translate("Date & Time") }}
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                {{ Helper.translate("Name") }}
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                {{ Helper.translate("Email") }}
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-right">
                                {{ Helper.translate("Action") }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(user, index) in resultPerPage" :key="index" class="border-b">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ Helper.translate(index + 1, true) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ Helper.dateFormate(user.created_at) }} {{ Helper.translate('at') }} 
                                {{ moment(user.created_at).format("hh:mm A") }}
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                <Link :href="route('admin.user.detail', user.id)" class="text-sky-500 font-medium">
                                    {{ Helper.translate(user.name, true) }}
                                </Link>
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                {{ Helper.translate(user.email, true) }}
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap flex justify-end gap-2">
                                <Link :href="route('admin.user.detail', user.id)" class="bg-green-500 px-2 py-1 rounded text-white text-sm font-bold block">
                                    <EyeIcon class="w-4 h-4" />
                                </Link>
                                <button @click="handleDeleteUser(user.id)" class="bg-red-400 px-2 py-1 rounded text-white text-sm font-bold block">
                                    <BlockIcon class="w-4 h-4" />
                                    <!-- <CloseIcon class="w-4 h-4" /> -->
                                </button>
                                <button @click="handleDeleteUser(user.id, true)" class="bg-red-400 px-2 py-1 rounded text-white text-sm font-bold block">
                                    <DeleteIcon class="w-4 h-4" />
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
import TabChanger from '@/Components/Backend/AdminDashboard/Users/TabChanger.vue'
import useTable from '@/Components/Backend/Global/Table/useTable.js'
import { usePage } from '@inertiajs/inertia-vue3'
import { Link } from '@inertiajs/inertia-vue3'
import CloseIcon from '@/Icons/CloseIcon.vue'
import { Inertia } from '@inertiajs/inertia'
import EyeIcon from '@/Icons/EyeIcon.vue'
import { computed } from 'vue'
import Helper from '@/Helper'
import moment from 'moment'
import Alert from '@/Components/Global/Alert.vue'
import BlockIcon from '@/Icons/BlockIcon.vue'
import DeleteIcon from '@/Icons/DeleteIcon.vue'

const { components, data, resultPerPage, search } = useTable()

const deleteUsers = computed(() => {
    return usePage().props.value.deletedUsers
})

const activeUsers = computed(() => {
    data.value = usePage().props.value.users
    return data.value
})

const handleDeleteUser = (id, fource=false) => {
    if (fource) {
        Helper.confirm(Helper.translate("Do you really want to delete parmanently?"), ()=>{
            Inertia.delete(route('admin.fource_delete_user', id))
        })
    } else {
        Helper.confirm(Helper.translate("Do you really want to block?"), ()=>{
            Inertia.delete(route('admin.delete_user', id))
        })
    }
}
</script>