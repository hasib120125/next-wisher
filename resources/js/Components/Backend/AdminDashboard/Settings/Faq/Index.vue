<template>
    <Create 
        v-if="isEmpty(selectedItem)" 
    />
    <Edit 
        v-if="!isEmpty(selectedItem)" 
        @canceledit="selectedItem=null" 
        :selectedItem="selectedItem" 
    />

    <div class="grid mt-4">
        <h1 class="text-lg my-2 mb-4 flex items-center gap-6">{{ Helper.translate('Faq Lists') }}</h1>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b whitespace-nowrap">
                    <tr>
                        <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            #
                        </th>
                        <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            {{ Helper.translate('Questions') }}
                        </th>
                        <th scope="col" class="text-sm w-[300px] font-medium text-gray-900 px-6 py-4 text-left">
                            {{ Helper.translate('Answers') }}
                        </th>
                        <th scope="col" class="text-sm w-[300px] font-medium text-gray-900 px-6 py-4 text-left">
                            {{ Helper.translate('Type') }}
                        </th>
                        <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-right">
                            {{ Helper.translate('Action') }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in $page.props.faqs" :key="item.id" class="border-b">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ Helper.translate(index+1) }}
                        </td>
                        <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
                            {{ Helper.translate(item.question, true) }}
                        </td>
                        <td class="text-sm text-gray-900 px-6 py-4 truncate">
                            {{ String(item.answer).substring(0, 50) }}....
                        </td>
                        <td class="text-sm text-gray-900 px-6 py-4 font-bold capitalize">
                            {{ Helper.translate(item.type, true) }}
                        </td>
                        
                        <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap flex justify-end gap-2">
                            <button @click="selectedItem = item" class="bg-gray-800 px-2 py-1 rounded text-white text-sm font-bold block">
                                <EditIcon class="w-4 h-4" />
                            </button>
                            <button @click="handleDelete(item.id)" class="bg-red-500 px-2 py-1 rounded text-white text-sm font-bold block">
                                <CloseIcon class="w-4 h-4" />
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import Helper from '@/Helper'
import EditIcon from '@/Icons/EditIcon.vue'
import CloseIcon from '@/Icons/CloseIcon.vue'
import Create from '@/Components/Backend/AdminDashboard/Settings/Faq/Create.vue'
import Edit from '@/Components/Backend/AdminDashboard/Settings/Faq/Edit.vue'
import { isEmpty, size } from 'lodash'
import { Inertia } from '@inertiajs/inertia'

const selectedItem = ref();

const handleDelete = (id) => {
    Helper.confirm('Do you really want to delete?', () => {
        Inertia.delete(route('admin.deleteFaq', id))
    })
}

</script>
