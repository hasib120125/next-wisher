<template>
    <div>
        <div class="flex md:flex-nowrap flex-wrap gap-4 md:justify-between justify-center items-center">
            <div class="flex gap-2 items-center">
                <component :is="components['PageSize']" />
            </div>
            <button @click="activeComponent = 'Add'" class="px-4 py-1 rounded bg-sky-500 text-white font-bold">
                {{ Helper.translate('Add Country') }}
            </button>
        </div>

        <div v-if="countries.length" class="grid mt-4">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="border-b whitespace-nowrap">
                        <tr>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                #
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                {{ Helper.translate('Country Name') }}
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                {{ Helper.translate('Status') }}
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-right">
                                {{ Helper.translate('Action') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(country, index) in resultPerPage" :key="index" class="border-b">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ Helper.translate(index+1, true) }}
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                {{ Helper.translate(country.name, true) }}
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                                <span class="font-semibold" :class="country.status ? 'text-green-500' : 'text-red-500'">
                                    {{ country.status ? Helper.translate('Enabled') : Helper.translate('Disabled') }}
                                </span>
                            </td>
                            <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap flex justify-end gap-2">
                                <button @click="handleEdit(country)" class="bg-gray-800 px-2 py-1 rounded text-white text-sm font-bold block">
                                    <EditIcon class="w-4 h-4" />
                                </button>
                                <!-- <button @click="deleteCountry(country.id)" class="bg-red-500 px-2 py-1 rounded text-white text-sm font-bold block">
                                    <CloseIcon class="w-4 h-4" />
                                </button> -->
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <component :is="components['Pagination']" />
        </div>
        <Alert v-else title="No result found!" />
    </div>
</template>

<script setup>
import { computed, onMounted, watch } from 'vue'
import EditIcon from '@/Icons/EditIcon.vue'
import useCountry from '@/Pages/Backend/AdminDashboard/useCountry.js'
import useTable from '@/Components/Backend/Global/Table/useTable.js'
import CloseIcon from '@/Icons/CloseIcon.vue'
import { usePage } from '@inertiajs/inertia-vue3'
import Helper from '@/Helper.js'
import { Inertia } from '@inertiajs/inertia'
import Alert from '@/Components/Global/Alert.vue'

const { components, data, resultPerPage } = useTable()
const { activeComponent, selectedCountry } = useCountry()

const countries = computed(() => {
    return usePage().props.value.countries
})

watch(()=>{
    data.value = countries.value;
});

const handleEdit = (country) => {
    activeComponent.value = 'Edit'
    selectedCountry.value = country
}

const deleteCountry = (id) => { 
    Helper.confirm(Helper.translate("Do you really want to delete?"), ()=>{
        Inertia.delete(route('admin.country.delete', id))
    })
}
</script>