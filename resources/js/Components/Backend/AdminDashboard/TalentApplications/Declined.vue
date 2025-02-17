<template>
    <div>
        <button @click="activeComponent = 'Applications'" class="flex items-center gap-2 mb-4 bg-gray-500 p-1 px-2 pr-3 font-semibold rounded text-white">
            <AngleLeftIcon class="w-4 h-4" />
            {{ Helper.translate('Back') }}
        </button> 
        <div class="flex gap-4 justify-between">
            <div class="flex gap-2 items-center">
                <component :is="components['PageSize']" />
            </div>
            <div class="flex gap-6 items-center w-full">
                <component :is="components['Search']" />
                <TabChanger :activeItems="declinedTelent.length" />
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
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                {{ Helper.translate('Verification') }}
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                {{ Helper.translate('Restore') }} /
                                {{ Helper.translate('Delete') }}
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
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                <span class="font-bold text-red-600 px-3 text-sm">
                                    {{ Helper.translate('Declined') }}
                                </span>
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap flex gap-4">
                                <button v-if="item?.note_email" @click="handleRestore(item.id)" class="bg-green-400 px-2 py-2 rounded-full text-white text-sm font-bold ml-auto block">
                                    <RestoreIcon class="w-4 h-4" />
                                </button>
                                <button @click="handleDelete(item.id)" class="bg-red-400 px-2 py-2 rounded-full text-white text-sm font-bold ml-auto block">
                                    <CloseIcon class="w-4 h-4" />
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
import CloseIcon from '@/Icons/CloseIcon.vue'
import AngleLeftIcon from '@/Icons/AngleLeftIcon.vue'
import AngleRightIcon from '@/Icons/AngleRightIcon.vue'
import useTable from '@/Components/Backend/Global/Table/useTable.js'
import useTalentApplications from '@/Pages/Backend/AdminDashboard/useTalentApplications.js'
import RestoreIcon from '@/Icons/RestoreIcon.vue'
import Helper from '@/Helper'
import { get } from 'lodash'
import { computed } from 'vue'
import { usePage } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'

const { components, data, resultPerPage, search } = useTable()
const { activeComponent } = useTalentApplications()

const declinedTelent = computed(() => {
    data.value = usePage().props.value.declinedTelent
    console.log(data.value[0]);
    return data.value
})

const handleDelete = (id) => {
    Helper.confirm(Helper.translate('Are you sure?'), () => {
        Inertia.delete(route('admin.delete_talent', id))
    })
}
const handleRestore = (id) => {
    Helper.confirm(Helper.translate('Are you sure?'), () => {
        Inertia.delete(route('admin.restore_declined_talent', id))
    })
}

</script>

<style scoped>
</style>